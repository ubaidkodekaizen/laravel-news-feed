<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/2.11.8/umd/popper.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.3/js/bootstrap.min.js"></script>
<script src="https://ajax.aspnetcdn.com/ajax/jquery.validate/1.14.0/jquery.validate.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.inputmask/5.0.8/jquery.inputmask.min.js"></script>
<script async defer
    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCJmm5iuEx2gVM3qj9a1zAWI_Y_C4Judnc&libraries=places&callback=initCityAutocomplete">
</script>
<script src="https://cdn.jsdelivr.net/npm/swiper/swiper-bundle.min.js"></script>
<script src="{{ asset('assets/js/custom.js?v1') }}"></script>
@yield('scripts')
<script>
    $(document).ready(function() {



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
                e.preventDefault(); // Prevent form from being submitted prematurely
                if (searchTerm) {
                    $('#product_service_name1').val(searchTerm);
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
                    if (response.product_services.length || response.company_industries
                        .length) {
                        suggestionBox.show();
                        response.product_services.forEach(function(item) {
                            suggestionBox.append(
                                '<div class="suggestion-item" data-type="product_service" data-value="' +
                                item.product_service_name + '">' + item
                                .product_service_name + '</div>');
                        });
                        // response.company_sub_categories.forEach(function(item) {
                        //     suggestionBox.append(
                        //         '<div class="suggestion-item" data-type="company_sub_category" data-value="' +
                        //         item + '">' + item + '</div>');
                        // });
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

            if (dataType === 'product_service') {
                $('#product_service_name1').val(selectedValue);
            } else if (dataType === 'company_industry') {
                $('#company_industry1').val(selectedValue);
            }
            //else if (dataType === 'company_sub_category') {
            //     $('#company_sub_category1').val(selectedValue);
            // }

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





    });
</script>


</body>

</html>
