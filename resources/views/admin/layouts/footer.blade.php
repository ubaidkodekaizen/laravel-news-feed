</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/2.11.8/umd/popper.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.3/js/bootstrap.min.js"></script>
<script src="https://ajax.aspnetcdn.com/ajax/jquery.validate/1.14.0/jquery.validate.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="{{ asset('admin-assets/js/custom.js?v1') }}"></script>
@if(config('services.google_maps.api_key'))
<script async defer
    src="https://maps.googleapis.com/maps/api/js?key={{ config('services.google_maps.api_key') }}&libraries=places&callback=initCityAutocomplete">
</script>
@endif
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
