@yield('scripts')
<script>
    // var userTokenRoute = "{{ route('user.token') }}"; // Route removed

    // Auth user data for feed
    @auth
    window.authUserId = {{ Auth::id() }};
    window.authUserAvatar = @json($authUserData['photo'] ?? '');
    window.authUserInitials = @json($authUserData['user_initials'] ?? 'U');
    window.authUserHasPhoto = {{ $authUserData['user_has_photo'] ?? false ? 'true' : 'false' }};
    @endauth
</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/2.11.8/umd/popper.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.3/js/bootstrap.min.js"></script>
<script src="https://ajax.aspnetcdn.com/ajax/jquery.validate/1.14.0/jquery.validate.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.inputmask/5.0.8/jquery.inputmask.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>

@if(config('services.google_maps.api_key'))
<script async defer
    src="https://maps.googleapis.com/maps/api/js?key={{ config('services.google_maps.api_key') }}&libraries=places&callback=initCityAutocomplete">
</script>
@endif
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



        // Search functionality removed - newsfeed boilerplate doesn't include product/service search



        function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    $('#imagePreview img').attr('src', e.target.result);
                    $('#imagePreview img').hide();
                    $('#imagePreview img').fadeIn(650);
                }
                reader.readAsDataURL(input.files[0]);
            }
        }

        $("#imageUpload").change(function() {
            readURL(this);
        });
        // Company profile pic upload removed - newsfeed boilerplate doesn't include company features



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
