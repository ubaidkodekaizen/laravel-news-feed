    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/2.11.8/umd/popper.min.js"></script> 
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.3/js/bootstrap.min.js"></script>
    <script src="https://ajax.aspnetcdn.com/ajax/jquery.validate/1.14.0/jquery.validate.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.inputmask/5.0.8/jquery.inputmask.min.js"></script>


    <script src="{{asset('assets/js/custom.js')}}"></script>
    <script>

        $(document).ready(function() {

            $('#header_search').on('keyup', function() {
                var searchTerm = $(this).val();
                if (searchTerm.length < 2) {
                    $('#suggestion_box').hide();
                    return;
                }
                $.ajax({
                    url: {{ route('search.suggestion') }}",
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
                $('#header_search').val(currentSearch ? currentSearch + ', ' + selectedValue : selectedValue);

                // Hide the suggestion box
                $('#suggestion_box').hide();
            });


            $(document).on('click', function(e) {
                if (!$(e.target).closest('.search_area').length) {
                    $('#suggestion_box').hide();
                }
            });
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

       
    </script>

    
</body>
</html>