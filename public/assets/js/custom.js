


$(document).ready(function () {  
    $("#user_company").validate({
        rules: {
            company_logo: {
                required: true,
                accept: "image/*"
            },
            company_name: {
                required: true,
                minlength: 3
            },
            company_email: {
                required: true,
                email: true
            },
            company_web_url: {
                required: true,
                url: true
            },
            company_linkedin_url: {
                required: true,
                url: true
            },
            company_position: {
                required: true
            },
            company_about: {
                required: true,
                minlength: 10
            },
            company_revenue: {
                required: true,
                // digits: true
            },
            company_address: {
                required: true
            },
            company_country: {
                required: true
            },
            company_state: {
                required: true
            },
            company_city: {
                required: true
            },
            company_county: {
                required: true
            },
            company_zip_code: {
                required: true,
                digits: true,
                minlength: 5,
                maxlength: 10
            },
            company_no_of_employee: {
                required: true
            },
            company_business_type: {
                required: true
            },
            company_industry: {
                required: true
            },
            company_sub_category: {
                required: true
            },
            company_community_service: {
                required: true
            },
            company_contribute_to_muslim_community: {
                required: true
            },
            company_affiliation_to_muslim_org: {
                required: true
            },
            "product_service_name[]": {
                required: true
            },
            "product_service_description[]": {
                required: true
            },
            "product_service_area[]": {
                required: true
            },
            "accreditation[]": {
                required: true
            }
        },
        messages: {
            company_logo: {
                required: "Please upload a company logo",
                accept: "Only image files are allowed"
            },
            company_name: {
                required: "Please enter your company name",
                minlength: "Company name must be at least 3 characters long"
            },
            company_email: {
                required: "Please enter a valid company email",
                email: "Please enter a valid email address"
            },
            company_web_url: {
                required: "Please enter the company web URL",
                url: "Please enter a valid URL"
            },
            company_linkedin_url: {
                required: "Please enter the company LinkedIn URL",
                url: "Please enter a valid URL"
            },
            company_position: {
                required: "Please enter your position or designation"
            },
            company_about: {
                required: "Please provide a company description",
                minlength: "Description must be at least 10 characters long"
            },
            company_revenue: {
                required: "Please enter the company revenue",
                // digits: "Revenue must be a numeric value"
            },
            company_address: {
                required: "Please enter the company address"
            },
            company_country: {
                required: "Please select the company country"
            },
            company_state: {
                required: "Please select the company state"
            },
            company_city: {
                required: "Please enter the company city"
            },
            company_county: {
                required: "Please enter the company county"
            },
            company_zip_code: {
                required: "Please enter the company zip code",
                digits: "Zip code must be numeric",
                minlength: "Zip code must be at least 5 digits",
                maxlength: "Zip code cannot be more than 10 digits"
            },
            company_no_of_employee: {
                required: "Please select the number of employees"
            },
            company_business_type: {
                required: "Please select the business type"
            },
            company_industry: {
                required: "Please select the company industry"
            },
            company_sub_category: {
                required: "Please select a sub category"
            },
            company_community_service: {
                required: "Please select if the company engages in community service"
            },
            company_contribute_to_muslim_community: {
                required: "Please select a contribution level"
            },
            company_affiliation_to_muslim_org: {
                required: "Please select an affiliation status"
            },
            "product_service_name[]": {
                required: "Please enter the product/service name"
            },
            "product_service_description[]": {
                required: "Please enter the product/service description"
            },
            "product_service_area[]": {
                required: "Please enter the Product Service Area"
            },
            "accreditation[]": {
                required: "Please enter the accreditation"
            }
        },
        errorElement: "span",
        errorPlacement: function (error, element) {
            error.addClass("text-danger");
            if (element.closest(".col-lg-6").length) {
                element.closest(".col-lg-6").append(error);
            } else if (element.closest(".col-lg-4").length) {
                element.closest(".col-lg-4").append(error);
            }
        }

    });
    $('#phone').inputmask('(999) 999-9999'); 
    $("#user_details").validate({
        rules: {
            first_name: {
                required: true,
                minlength: 2
            },
            last_name: {
                required: true,
                minlength: 2
            },
            email: {
                required: true,
                email: true
            },
            phone: {
                required: true,
            },
            linkedin_url: {
                required: true,
                // url: true
            },
            x_url: {
                url: true
            },
            instagram_url: {
                url: true
            },
            facebook_url: {
                url: true
            },
            address: {
                required: true
            },
            city: {
                required: true
            },
            state: {
                required: true
            },
            country: {
                required: true
            },
            county: {
                required: true
            },
            zip_code: {
                required: true,
                digits: true
            },
            industry_to_connect: {
                required: true
            },
            sub_category_to_connect: {
                required: true
            },
            community_interest: {
                required: true
            }
        },
        messages: {
            first_name: {
                required: "Please enter your first name",
                minlength: "First name must be at least 2 characters"
            },
            last_name: {
                required: "Please enter your last name",
                minlength: "Last name must be at least 2 characters"
            },
            email: {
                required: "Please enter your email",
                email: "Please enter a valid email address"
            },
            phone: {
                required: "Please enter your phone number",
                minlength: "Phone number must be at least 10 digits",
                digits: "Please enter only numbers"
            },
            linkedin_url: "Please enter a valid URL",
            x_url: "Please enter a valid URL",
            instagram_url: "Please enter a valid URL",
            facebook_url: "Please enter a valid URL",
            address: "Please enter your address",
            city: "Please enter your city",
            county: "Please enter your county",
            country: "Please enter your country",
            state: "Please enter your state",
            zip_code: {
                required: "Please enter your zip code",
                digits: "Zip code must be a number"
            },
            industry_to_connect: "Please select an industry",
            sub_category_to_connect: "Please select a sub-category",
            community_interest: "Please select your community interest"
        },
        errorElement: "span",
        errorPlacement: function (error, element) {
            error.addClass("text-danger");
            if (element.closest(".col-lg-6").length) {
                element.closest(".col-lg-6").append(error);
            } else if (element.closest(".col-lg-4").length) {
                element.closest(".col-lg-4").append(error);
            } else {
                element.closest(".col-12").append(error);
            }
        }
    });
});
