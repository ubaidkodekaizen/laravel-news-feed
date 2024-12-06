</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/2.11.8/umd/popper.min.js"></script> 
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.3/js/bootstrap.min.js"></script>
<script src="https://ajax.aspnetcdn.com/ajax/jquery.validate/1.14.0/jquery.validate.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script src="{{asset('admin-assets/js/custom.js')}}"></script>
<script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCJmm5iuEx2gVM3qj9a1zAWI_Y_C4Judnc&libraries=places&callback=initAutocomplete"></script>

<script>


    $(document).ready(function() {

        // Google map autocomplete
        function initAutocomplete() {
            const addressMappings = [
                {
                    inputId: 'address',
                    fields: {
                        country: 'country',
                        state: 'state',
                        city: 'city',
                        county: 'county',
                        zip_code: 'zip_code',
                    }
                },
                {
                    inputId: 'company_address',
                    fields: {
                        country: 'company_country',
                        state: 'company_state',
                        city: 'company_city',
                        county: 'company_county',
                        zip_code: 'company_zip_code',
                    }
                }
            ];

            addressMappings.forEach(mapping => {
                const input = document.getElementById(mapping.inputId);
                const autocomplete = new google.maps.places.Autocomplete(input, {
                    types: ['geocode'],
                    componentRestrictions: { country: ["us"] }
                });

                autocomplete.addListener('place_changed', () => {
                    const place = autocomplete.getPlace();
                    if (place.address_components) {
                        const addressComponents = parseAddressComponents(place.address_components);
                        populateFields(mapping.fields, addressComponents);
                    }
                });
            });
        }

        function parseAddressComponents(components) {
            const addressComponents = {
                country: '',
                state: '',
                city: '',
                county: '',
                zip_code: ''
            };

            components.forEach(component => {
                const types = component.types;

                if (types.includes('country')) {
                    addressComponents.country = component.long_name;
                } else if (types.includes('administrative_area_level_1')) {
                    addressComponents.state = component.long_name;
                } else if (types.includes('locality')) {
                    addressComponents.city = component.long_name;
                } else if (types.includes('administrative_area_level_2')) {
                    addressComponents.county = component.long_name;
                } else if (types.includes('postal_code')) {
                    addressComponents.zip_code = component.long_name;
                }
            });

            return addressComponents;
        }

        function populateFields(fieldMapping, addressComponents) {
            for (const [key, elementId] of Object.entries(fieldMapping)) {
                if (addressComponents[key]) {
                    document.getElementById(elementId).value = addressComponents[key];
                }
            }
        }

        window.onload = initAutocomplete;



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





    });


    
   
</script>

</body>
</html>