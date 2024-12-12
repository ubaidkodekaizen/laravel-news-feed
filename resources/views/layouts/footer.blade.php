<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/2.11.8/umd/popper.min.js"></script> 
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.3/js/bootstrap.min.js"></script>
<script src="https://ajax.aspnetcdn.com/ajax/jquery.validate/1.14.0/jquery.validate.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.inputmask/5.0.8/jquery.inputmask.min.js"></script>
<script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCJmm5iuEx2gVM3qj9a1zAWI_Y_C4Judnc&libraries=places&callback=initAutocomplete"></script>
<script src="https://cdn.jsdelivr.net/npm/swiper/swiper-bundle.min.js"></script>
<script src="{{asset('assets/js/custom.js')}}"></script>
@yield('scripts')
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


        // Search Bar
  

        $('#header_search').on('keyup', function() {
            var searchTerm = $(this).val();
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
                    if (response.product_services.length || response.company_sub_categories.length || response.company_industries.length || response.first_name.length || response.last_name.length) {
                        suggestionBox.show();
                        response.product_services.forEach(function(item) {
                            suggestionBox.append('<div class="suggestion-item" data-type="product_service" data-value="' + item.product_service_name + '">' + item.product_service_name + '</div>');
                        });
                        response.company_sub_categories.forEach(function(item) {
                            suggestionBox.append('<div class="suggestion-item" data-type="company_sub_category" data-value="' + item + '">' + item + '</div>');
                        });
                        response.company_industries.forEach(function(item) {
                            suggestionBox.append('<div class="suggestion-item" data-type="company_industry" data-value="' + item + '">' + item + '</div>');
                        });
                        // response.first_name.forEach(function(item) {
                        //     suggestionBox.append('<div class="suggestion-item" data-type="first_name" data-value="' + item + '">' + item + '</div>');
                        // });
                        // response.last_name.forEach(function(item) {
                        //     suggestionBox.append('<div class="suggestion-item" data-type="last_name" data-value="' + item + '">' + item + '</div>');
                        // });
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
                var currentValue = $('#product_service_name1').val();
                $('#product_service_name1').val(currentValue ? currentValue + ', ' + selectedValue : selectedValue);
            } else if (dataType === 'company_industry') {
                var currentValue = $('#company_industry1').val();
                $('#company_industry1').val(currentValue ? currentValue + ', ' + selectedValue : selectedValue);
            } else if (dataType === 'company_sub_category') {
                var currentValue = $('#company_sub_category1').val();
                $('#company_sub_category1').val(currentValue ? currentValue + ', ' + selectedValue : selectedValue);
            }

            var currentSearch = $('#header_search').val();
            $('#header_search').val(selectedValue);

            // Hide the suggestion box
            $('#suggestion_box').hide();
        });


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