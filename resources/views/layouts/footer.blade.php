@yield('scripts')
<script>
    var userTokenRoute = "{{ route('user.token') }}";
    var getConversations = "{{ route('get.conversations') }}";

    function getMessageRoute(conversationId) {
        return "{{ route('get.message', ['conversation' => '__ID__']) }}".replace('__ID__', conversationId);
    }
    var sendMsg = "{{ route('sendMessage') }}";
    var createConversation = "{{ route('create.conversation') }}";

    function getUserConversationRoute(conversationId) {
        return "{{ route('get.user.conversation', ['conversation' => '__ID__']) }}".replace('__ID__', conversationId);
    }
    var userIsTyping = "{{ route('user.is.typing') }}";

    function addReactionRoute(messageId) {
        return "{{ route('add.reaction', ['message' => '__ID__']) }}".replace('__ID__', messageId);
    }

    function removeReactionRoute(messageId) {
        return "{{ route('add.reaction', ['message' => '__ID__']) }}".replace('__ID__', messageId);
    }
    var userPing = "{{ route('user.ping') }}";
    var userOffline = "{{ route('user.offline') }}";

    window.updateMessageRoute = function(messageId) {
        return "{{ url('api/messages') }}/" + messageId;
    };
    window.deleteMessageRoute = function(messageId) {
        return "{{ url('api/messages') }}/" + messageId;
    };
</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/2.11.8/umd/popper.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.3/js/bootstrap.min.js"></script>
<script src="https://ajax.aspnetcdn.com/ajax/jquery.validate/1.14.0/jquery.validate.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.inputmask/5.0.8/jquery.inputmask.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>

<script async defer
    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCJmm5iuEx2gVM3qj9a1zAWI_Y_C4Judnc&libraries=places&callback=initCityAutocomplete">
</script>
<script src="https://cdn.jsdelivr.net/npm/swiper/swiper-bundle.min.js"></script>
<script src="{{ asset('assets/js/custom.js?v1') }}"></script>
@auth
    <script>
        window.userId = {{ auth()->id() ?? 'null' }};

        @auth
        window.userFirstName = "{{ auth()->user()->first_name }}";
        window.userLastName = "{{ auth()->user()->last_name }}";
        window.userEmail = "{{ auth()->user()->email }}";

        @php
            $userPhoto = auth()->user()->photo;
            // Generate full URL for photo
            // Use helper function that handles both S3 URLs and local storage
            $photoUrl = getImageUrl($userPhoto) ?? '';
        @endphp

        window.userPhoto = "{{ $photoUrl }}";
        window.userSlug = "{{ auth()->user()->slug }}";
        window.userInitials =
            "{{ strtoupper(substr(auth()->user()->first_name, 0, 1) . substr(auth()->user()->last_name, 0, 1)) }}";
        @endauth
    </script>
    <div id="chat-container"></div>
@endauth

<script>
    jQuery(document).ready(function($) {



        // Google Map Autocomplete for City
        function initCityAutocomplete() {
            const cityMappings = [{
                    inputId: 'city', // Input field for city
                    fields: {
                        city: 'city',
                        state: 'state',
                        country: 'country',
                        county: 'county'
                    }
                },
                {
                    inputId: 'company_city', // Input field for company city
                    fields: {
                        city: 'company_city',
                        state: 'company_state',
                        country: 'company_country',
                        county: 'company_county'
                    }
                }
            ];

            cityMappings.forEach(mapping => {
                const input = document.getElementById(mapping.inputId);
                const autocomplete = new google.maps.places.Autocomplete(input, {
                    types: ['(cities)'], // Restrict autocomplete to cities
                    fields: ['address_components', 'geometry'] // Fetch only required fields
                });

                autocomplete.addListener('place_changed', () => {
                    const place = autocomplete.getPlace();
                    if (place.address_components) {
                        const addressComponents = parseCityComponents(place.address_components);
                        populateCityFields(mapping.fields, addressComponents);
                    }
                });
            });
        }

        // Parse City-specific Address Components
        function parseCityComponents(components) {
            const cityComponents = {
                city: '',
                state: '',
                country: '',
                county: ''
            };

            components.forEach(component => {
                const types = component.types;

                if (types.includes('locality')) {
                    cityComponents.city = component.long_name; // City Name
                } else if (types.includes('administrative_area_level_1')) {
                    cityComponents.state = component.long_name; // State Name
                } else if (types.includes('administrative_area_level_2')) {
                    cityComponents.county = component.long_name; // County Name
                } else if (types.includes('country')) {
                    cityComponents.country = component.long_name; // Country Name
                }
            });

            return cityComponents;
        }

        // Populate Fields with City Data
        function populateCityFields(fieldMapping, cityComponents) {
            for (const [key, elementId] of Object.entries(fieldMapping)) {
                if (cityComponents[key] && document.getElementById(elementId)) {
                    document.getElementById(elementId).value = cityComponents[key];
                }
            }
        }

        // Initialize Autocomplete on Window Load
        window.onload = initCityAutocomplete;



        // Search Bar
        $('#search_form').on('submit', function() {
            // Disable empty input fields
            $(this).find('input').each(function() {
                if (!$(this).val().trim()) {
                    $(this).prop('disabled', true);
                }
            });

            // Disable unselected select fields
            $(this).find('select').each(function() {
                if (!$(this).val()) { // Check if no value is selected
                    $(this).prop('disabled', true);
                }
            });
        });



        $('#header_search').on('keyup', function(e) {
            var searchTerm = $(this).val();

            // Handle Enter key
            if (e.key === 'Enter') {
                e.preventDefault();
                if (searchTerm) {
                    $('#product1').val(searchTerm);
                    $('#search_form').submit();
                }
                return;
            }

            // Avoid suggestions for empty or short search terms
            if (searchTerm.length < 2) {
                $('#suggestion_box').hide();
                return;
            }

            $.ajax({
                url: "{{ route('search.suggestion') }}",
                method: 'GET',
                data: {
                    term: searchTerm,
                },
                success: function(response) {
                    var suggestionBox = $('#suggestion_box');
                    suggestionBox.empty();

                    // Only show suggestions if there's valid data
                    if (response.products.length || response.services.length || response
                        .company_industries
                        .length || response.first_name
                        .length) {
                        suggestionBox.show();
                        response.products.forEach(function(item) {
                            suggestionBox.append(
                                '<div class="suggestion-item" data-type="product" data-value="' +
                                item.title + '">' + item
                                .title + '</div>');
                        });
                        response.services.forEach(function(item) {
                            suggestionBox.append(
                                '<div class="suggestion-item" data-type="service" data-value="' +
                                item.title + '">' + item
                                .title + '</div>');
                        });
                        response.first_name.forEach(function(item) {
                            suggestionBox.append(
                                '<div class="suggestion-item" data-type="name" data-value="' +
                                item.first_name + '">' +
                                item.first_name + ' ' + item.last_name +
                                '</div>');
                        });
                        response.company_industries.forEach(function(item) {
                            suggestionBox.append(
                                '<div class="suggestion-item" data-type="company_industry" data-value="' +
                                item + '">' + item + '</div>');
                        });
                    } else {
                        suggestionBox.hide();
                    }
                }
            });
        });

        $('#suggestion_box').on('click', '.suggestion-item', function() {
            var selectedValue = $(this).data('value');
            var dataType = $(this).data('type');

            if (dataType === 'product') {
                $('#product1').val(selectedValue);
            } else if (dataType === 'service') {
                $('#service1').val(selectedValue);
            } else if (dataType === 'company_industry') {
                $('#company_industry1').val(selectedValue);
            } else if (dataType === 'name') {
                $('#first_name1').val(selectedValue);
            }

            $('#header_search').val(selectedValue);

            // Hide the suggestion box
            $('#suggestion_box').hide();
        });

        // Close suggestion box when clicking outside
        $(document).on('click', function(e) {
            if (!$(e.target).closest('.search_area').length) {
                $('#suggestion_box').hide();
            }
        });



        function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    $('#imagePreview img').attr('src', e.target.result);
                    $('#imagePreviewCompany img').attr('src', e.target.result);
                    $('#imagePreview img').hide();
                    $('#imagePreviewCompany img').hide();
                    $('#imagePreview img').fadeIn(650);
                    $('#imagePreviewCompany img').fadeIn(650);
                }
                reader.readAsDataURL(input.files[0]);
            }
        }

        $("#imageUpload").change(function() {
            readURL(this);
        });
        $("#imageUploadCompany").change(function() {
            readURL(this);
        });

        $('.personalProfilePicBtn').on('click', function(e) {
            e.preventDefault();
            $('#imageUpload').trigger('click');
        });

        $('.companyProfilePicBtn').on('click', function(e) {
            e.preventDefault();
            $('#imageUploadCompany').trigger('click');
        });



    });
</script>
<script>
    document.querySelector(".logoutBtn").addEventListener("click", function() {
        localStorage.setItem("sanctum-token", "");
    });
</script>


{{-- @yield('scripts') --}}

</body>

</html>
