<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Muslim Lynk | Founders and CEO Database</title>
    <link rel="icon" href="{{ asset('assets/images/logo_bg.png') }}" type="image/x-icon">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.3/css/bootstrap.min.css" />
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="{{ asset('assets/css/auth-style.css') }}">
    <style>
        .img_side_width {
            min-width: 530px;
            width: 100%;
        }
    </style>
</head>

<body>
    <div class="form_container">
        <div class="form_flex">
            <div class="row">
                <div class="col-lg-6 mobile_hide">
                    <div class="img_side_width">
                        <img src="{{ asset('assets/images/logo.png') }}" alt="" class="img-fluid">
                    </div>

                </div>
                <div class="col-lg-6">
                    <div class="form-section">
                        <h2 class="heading mb-4">Sign Up</h2>

                        @if (session('success'))
                            <div class="alert alert-success" role="alert">
                                {{ session('success') }}
                            </div>
                        @endif

                        @if (session('error'))
                            <div class="alert alert-danger" role="alert">
                                {{ session('error') }}
                            </div>
                        @endif
                        <form method="POST" action="{{ route('authorize.payment') }}" id="user_register"
                            autocomplete="off" class="form">
                            @csrf
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="input-box">
                                        <span class="icon"><i class='bx bx-user'></i></span>
                                        <input id="first_name" type="text"
                                            class="@error('first_name') is-invalid @enderror" name="first_name"
                                            value="{{ old('first_name') }}" required autocomplete="off" maxlength="50">
                                        @error('first_name')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                        <label>{{ __('First Name') }}</label>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="input-box">
                                        <span class="icon"><i class='bx bx-user'></i></span>
                                        <input id="last_name" type="text"
                                            class="@error('last_name') is-invalid @enderror" name="last_name"
                                            value="{{ old('last_name') }}" required autocomplete="off" maxlength="50">
                                        @error('last_name')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                        <label>{{ __('Last Name') }}</label>
                                    </div>
                                </div>
                            </div>

                            <div class="input-box">
                                <span class="icon"><i class='bx bx-envelope'></i></span>
                                <input id="email" type="email" class="@error('email') is-invalid @enderror"
                                    name="email" value="{{ old('email') }}" required autocomplete="off"
                                    maxlength="100">
                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                                <label>{{ __('Email Address') }}</label>
                            </div>

                            <div class="input-box">
                                <span class="icon"><i class='bx bx-phone'></i></span>
                                <input id="phone" type="text" class="@error('phone') is-invalid @enderror"
                                    name="phone" value="{{ old('phone') }}" required autocomplete="off"
                                    maxlength="100">
                                @error('phone')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                                <label>{{ __('Phone Number') }}</label>
                            </div>

                            <div class="input-box">
                                <span class="icon"><i class='bx bx-map'></i></span>
                                <input id="address" type="text" name="billing_address"
                                    value="{{ old('billing_address') }}" required>
                                <label>Card Billing Address</label>
                            </div>

                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="input-box">
                                        <span class="icon"><i class='bx bx-map'></i></span>
                                        <input id="country" type="text" name="country"
                                            value="{{ old('country') }}" required>
                                        <label>Country</label>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="input-box">
                                        <span class="icon"><i class='bx bx-map'></i></span>
                                        <input id="state" type="text" name="state"
                                            value="{{ old('state') }}" required>
                                        <label>State</label>
                                    </div>
                                </div>
                                <!--<div class="col-lg-6">-->
                                <!--    <div class="input-box">-->
                                <!--        <input id="country" type="text" name="country" value="{{ old('country') }}" required>-->
                                <!--<div class="select">-->

                                <!--    <select id="country" name="country" required>-->
                                <!--        <option value="">Select Country</option>-->
                                <!--        <option value="{{ old('country') }}" selected>{{ old('country') }}</option>-->
                                <!--    </select>-->
                                <!--</div>-->
                                <!--    </div>-->
                                <!--</div>-->
                                <!--<div class="col-lg-6">-->
                                <!--    <div class="input-box">-->
                                <!--         <input id="state" type="text" name="state" value="{{ old('state') }}" required>-->
                                <!--<div class="select">-->
                                <!--    <select id="state" name="state" required>-->
                                <!--        <option value="">Select State/Region</option>-->
                                <!--        <option value="{{ old('state') }}" selected>{{ old('state') }}</option>-->
                                <!--    </select>-->
                                <!--</div>-->
                                <!--    </div>-->
                                <!--</div>-->
                            </div>

                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="input-box">
                                        <span class="icon"><i class='bx bx-map'></i></span>
                                        <input id="city" type="text" name="city"
                                            value="{{ old('city') }}" required>
                                        <label>City</label>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="input-box">
                                        <span class="icon"><i class='bx bx-map-pin'></i></span>
                                        <input id="zipcode" type="text" name="zip_code"
                                            value="{{ old('zip_code') }}" required>
                                        <label>Zip Code</label>
                                    </div>
                                </div>
                            </div>

                            <div class="input-box">
                                <div class="select">
                                    <select id="plan_id" name="plan_id" required>
                                        {!! \App\Helpers\DropdownHelper::getPlanDropdown() !!}
                                    </select>
                                </div>
                                <input type="hidden" name="amount" id="amount" value="{{ old('amount') }}">
                                <input type="hidden" name="type" id="type" value="{{ old('type') }}">
                            </div>

                            <div class="input-box">
                                <span class="icon"><i class='bx bx-credit-card'></i></span>
                                <input id="card_number" type="number"
                                    class="@error('card_number') is-invalid @enderror" name="card_number"
                                    value="{{ old('card_number') }}" required autocomplete="off" maxlength="16">
                                @error('card_number')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                                <label>{{ __('Card Number (16 digits)') }}</label>
                            </div>

                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="input-box">
                                        <span class="icon"><i class='bx bx-calendar'></i></span>
                                        <input id="expiration_date" type="text"
                                            class="@error('expiration_date') is-invalid @enderror"
                                            name="expiration_date" value="{{ old('expiration_date') }}" required
                                            autocomplete="off" maxlength="5">
                                        @error('expiration_date')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                        <label>{{ __('Expiration Date (MM/YY)') }}</label>
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="input-box">
                                        <span class="icon"><i class='bx bx-shield-alt-2'></i></span>
                                        <input id="cvv" type="text"
                                            class="@error('cvv') is-invalid @enderror" name="cvv"
                                            value="{{ old('cvv') }}" required autocomplete="off" maxlength="4">
                                        @error('cvv')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                        <label>{{ __('CVV') }}</label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-check mt-3 account_signup">
                                <input class="form-check-input" type="checkbox" name="agree_terms" id="agree_terms"
                                    required>

                                <label class="form-check-label" for="agree_terms">
                                    I have read and agree to the <a href="{{ route('terms.of.service') }}"
                                        target="_blank"> <span class="theme-color">Terms of service</span> </a>
                                </label>
                            </div>

                            <button type="submit" class="custom-btn btn-14">{{ __('Sign Up') }}</button>
                        </form>

                        <div class="account_signup">
                            <a href="{{ route('login.form') }}">Already have an account? <span
                                    class="theme-color">Sign
                                    In</span> </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://ajax.aspnetcdn.com/ajax/jquery.validate/1.14.0/jquery.validate.js"></script>
    <script async defer
        src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCJmm5iuEx2gVM3qj9a1zAWI_Y_C4Judnc&libraries=places&callback=initBillingAddressAutocomplete">
    </script>
    <script>
        // Google Maps Autocomplete for Billing Address
        function initBillingAddressAutocomplete() {
            const billingMapping = {
                inputId: 'address', // Billing Address Input Field
                fields: {
                    country: 'country',
                    state: 'state',
                    city: 'city',
                    zip_code: 'zipcode'
                }
            };

            const input = document.getElementById(billingMapping.inputId);
            if (!input) {
                console.error('Billing address input field not found!');
                return;
            }

            const autocomplete = new google.maps.places.Autocomplete(input, {
                types: ['geocode'], // Focus on geographic data
                fields: ['address_components', 'geometry']
            });

            autocomplete.addListener('place_changed', () => {
                const place = autocomplete.getPlace();

                if (place.address_components) {
                    const addressComponents = parseBillingAddressComponents(place.address_components);
                    populateBillingFields(billingMapping.fields, addressComponents);
                }
            });
        }

        // Parse Address Components for Billing Address
        function parseBillingAddressComponents(components) {
            const addressComponents = {
                country: '',
                state: '',
                city: '',
                zip_code: ''
            };

            components.forEach(component => {
                const types = component.types;

                if (types.includes('country')) {
                    addressComponents.country = component.long_name; // Full Country Name
                } else if (types.includes('administrative_area_level_1')) {
                    addressComponents.state = component.long_name; // State/Province
                } else if (types.includes('locality')) {
                    addressComponents.city = component.long_name; // City
                } else if (types.includes('postal_code')) {
                    addressComponents.zip_code = component.long_name; // Zip Code
                }
            });

            return addressComponents;
        }

        // Populate Fields with Extracted Address Data
        function populateBillingFields(fieldMapping, addressComponents) {
            for (const [key, elementId] of Object.entries(fieldMapping)) {
                const field = document.getElementById(elementId);
                if (addressComponents[key] && field) {
                    field.value = addressComponents[key];
                }
            }
        }

        // Initialize Autocomplete on Window Load
        window.onload = initBillingAddressAutocomplete;

        $(document).ready(function() {

            $('#user_register').validate({
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
                    agree_terms: {
                        required: true
                    },
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
                    agree_terms: {
                        required: "You must agree to the terms of service"
                    }
                },
                errorPlacement: function(error, element) {
                    // Append the error message after the closest `.input-box` container
                    if (element.closest('.input-box').length) {
                        error.insertAfter(element.closest('.input-box'));
                    } else if (element.closest('.account_signup').length) {
                        error.insertAfter(element.closest('.account_signup'));
                    } else {
                        // Default placement if `.input-box` is not found
                        error.insertAfter(element);
                    }
                }
            });


        });
    </script>

    <script>
        const countriesData = {


            "AE": {
                "name": "United Arab Emirates",
                "states": [
                    "Abu Dhabi", "Ajman", "Dubai", "Fujairah", "Ras Al Khaimah", "Sharjah", "Umm Al-Quwain"
                ]
            },
            "AU": {
                "name": "Australia",
                "states": [
                    "New South Wales", "Queensland", "South Australia", "Tasmania", "Victoria",
                    "Western Australia"
                ]
            },
            "CA": {
                "name": "Canada",
                "states": [
                    "Alberta", "British Columbia", "Manitoba", "New Brunswick", "Newfoundland and Labrador",
                    "Nova Scotia", "Ontario", "Prince Edward Island", "Quebec", "Saskatchewan"
                ]
            },
            "DE": {
                "name": "Germany",
                "states": [
                    "Baden-Württemberg", "Bavaria", "Berlin", "Brandenburg", "Bremen", "Hamburg", "Hesse",
                    "Lower Saxony", "Mecklenburg-Vorpommern", "North Rhine-Westphalia",
                    "Rhineland-Palatinate",
                    "Saarland", "Saxony", "Saxony-Anhalt", "Schleswig-Holstein", "Thuringia"
                ]
            },
            "FR": {
                "name": "France",
                "states": [
                    "Île-de-France", "Provence-Alpes-Côte d'Azur", "Auvergne-Rhône-Alpes",
                    "Nouvelle-Aquitaine",
                    "Occitanie", "Hauts-de-France", "Bretagne", "Normandie", "Grand Est",
                    "Centre-Val de Loire",
                    "Pays de la Loire", "Bourgogne-Franche-Comté", "Corse"
                ]
            },
            "GB": {
                "name": "United Kingdom",
                "states": [
                    "England", "Scotland", "Wales", "Northern Ireland"
                ]
            },
            "IN": {
                "name": "India",
                "states": [
                    "Andhra Pradesh", "Arunachal Pradesh", "Assam", "Bihar", "Chhattisgarh", "Goa",
                    "Gujarat",
                    "Haryana", "Himachal Pradesh", "Jharkhand", "Karnataka", "Kerala", "Madhya Pradesh",
                    "Maharashtra", "Manipur", "Meghalaya", "Mizoram", "Nagaland", "Odisha", "Punjab",
                    "Rajasthan", "Sikkim", "Tamil Nadu", "Telangana", "Tripura", "Uttar Pradesh",
                    "Uttarakhand",
                    "West Bengal"
                ]
            },
            "PK": {
                "name": "Pakistan",
                "states": [
                    "Balochistan", "Khyber Pakhtunkhwa", "Punjab", "Sindh", "Azad Jammu and Kashmir",
                    "Gilgit-Baltistan", "Islamabad Capital Territory"
                ]
            },
            "SA": {
                "name": "Saudi Arabia",
                "states": [
                    "Al Bahah", "Al Jawf", "Al Madinah", "Al-Qassim", "Asir", "Eastern Province", "Ha'il",
                    "Mecca", "Najran", "Northern Borders", "Riyadh", "Tabuk", "Jizan", "Saudi Capital"
                ]
            },
            "TR": {
                "name": "Turkey",
                "states": [
                    "Adana", "Adiyaman", "Afyonkarahisar", "Agri", "Aksaray", "Amasya", "Ankara", "Antalya",
                    "Ardahan", "Artvin", "Aydin", "Balikesir", "Bilecik", "Bingol", "Bitlis", "Bolu",
                    "Burdur", "Bursa",
                    "Canakkale", "Cankiri", "Corum", "Denizli", "Diyarbakir", "Edirne", "Elazig",
                    "Erzincan", "Erzurum",
                    "Eskisehir", "Gaziantep", "Giresun", "Gumushane", "Hakkari", "Hatay", "Igdir",
                    "Isparta", "Istanbul",
                    "Izmir", "Kahramanmaras", "Karabuk", "Karaman", "Kastamonu", "Kayseri", "Kirikkale",
                    "Kirklareli",
                    "Kirsehir", "Kocaeli", "Konya", "Kuyucak", "Malatya", "Manisa", "Mardin", "Mugla",
                    "Mus", "Nevsehir",
                    "Nigde", "Ordu", "Osmaniye", "Rize", "Sakarya", "Samsun", "Sanliurfa", "Siirt", "Sinop",
                    "Sirnak",
                    "Sivas", "Tekirdag", "Tokat", "Trabzon", "Tunceli", "Usak", "Van", "Yalova", "Yozgat"
                ]
            },
            "US": {
                "name": "United States",
                "states": [
                    "Alabama", "Alaska", "Arizona", "Arkansas", "California", "Colorado", "Connecticut",
                    "Delaware",
                    "Florida", "Georgia", "Hawaii", "Idaho", "Illinois", "Indiana", "Iowa", "Kansas",
                    "Kentucky",
                    "Louisiana", "Maine", "Maryland", "Massachusetts", "Michigan", "Minnesota",
                    "Mississippi",
                    "Missouri", "Montana", "Nebraska", "Nevada", "New Hampshire", "New Jersey",
                    "New Mexico",
                    "New York", "North Carolina", "North Dakota", "Ohio", "Oklahoma", "Oregon",
                    "Pennsylvania",
                    "Rhode Island", "South Carolina", "South Dakota", "Tennessee", "Texas", "Utah",
                    "Vermont",
                    "Virginia", "Washington", "West Virginia", "Wisconsin", "Wyoming"
                ]
            },




        };

        function populateCountryState() {
            const countrySelect = document.getElementById("country");
            const stateSelect = document.getElementById("state");

            Object.keys(countriesData).forEach(function(countryCode) {
                let option = document.createElement("option");
                option.value = countryCode;
                option.textContent = countriesData[countryCode].name;
                countrySelect.appendChild(option);
            });

            countrySelect.addEventListener("change", function() {
                const selectedCountry = countrySelect.value;
                const states = countriesData[selectedCountry]?.states || [];
                stateSelect.innerHTML = "<option value=''>Select State/Region</option>";
                states.forEach(function(state) {
                    let option = document.createElement("option");
                    option.value = state;
                    option.textContent = state;
                    stateSelect.appendChild(option);
                });
            });
        }

        window.onload = populateCountryState;
    </script>



    <script>
        document.getElementById('expiration_date').addEventListener('input', function(e) {
            let value = e.target.value;
            value = value.replace(/[^0-9/]/g, '');
            if (value.length === 2 && e.inputType !== 'deleteContentBackward' && !value.includes('/')) {
                value = value + '/';
            }
            if (value.length > 5) {
                value = value.slice(0, 5);
            }

            e.target.value = value;
        });
    </script>
    <script>
        $(document).ready(function() {
            $('#plan_id').on('change', function() {
                let selectedText = $(this).find('option:selected').text();
                let [amount, type] = selectedText.split(' /').map(part => part.trim());
                $('#amount').val(amount);
                $('#type').val(type);
            });
        });
    </script>
    {{-- <script>
        $(document).ready(function() {
            $('#togglePassword').on('click', function() {
                const passwordField = $('#password');
                const icon = $(this);

                if (passwordField.attr('type') === 'password') {
                    passwordField.attr('type', 'text');
                    icon.removeClass('bx-show').addClass('bx-hide');
                } else {
                    passwordField.attr('type', 'password');
                    icon.removeClass('bx-hide').addClass('bx-show');
                }
            });

            $('#togglePasswordConfirmation').on('click', function() {
                const passwordConfirmationField = $('#password_confirmation');
                const icon = $(this);

                if (passwordConfirmationField.attr('type') === 'password') {
                    passwordConfirmationField.attr('type', 'text');
                    icon.removeClass('bx-show').addClass('bx-hide');
                } else {
                    passwordConfirmationField.attr('type', 'password');
                    icon.removeClass('bx-hide').addClass('bx-show');
                }
            });
        });
    </script> --}}


</body>

</html>
