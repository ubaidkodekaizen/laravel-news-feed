@extends('layouts.main')
@section('content')
    <style>
        .read-more-btn {
            width: 100%;
            background: transparent;
            border: none;
            border-top: 1px solid #ddd;
            border-radius: 0px;
            color: #686868;
            font-weight: 500;
            text-align: left;
        }

        button.btn.btn-sm.btn-primary.mt-2.read-more-btn.trigger-element {
            border: none;
            font-size: 13.65px;
            font-family: "Inter";
            font-weight: 500;
            padding: 0;
            color: #273572;
            background-color: transparent;
        }

        .avatar-initials {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background: #394a93;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
            font-size: 18px;
            letter-spacing: 1px;
        }

        .read-more-btn:hover {
            color: #b8c034 !important;
            background-color: transparent;
        }

        .event_slider .view-more {
            display: inline-block;
            padding: 16px 10px;
            background: #273572;
            color: var(--white);
            text-decoration: none;
            font-family: "Inter";
            margin-top: 10px;
            border-radius: 10px;
            font-weight: 400;
            transition: 0.3s;
            border: 1px solid #273572;
            text-align: center;
        }

        .event_slider .view-more:hover {
            color: #273572 !important;
            background: #B8C034;
            border: 1px solid #B8C034;
        }

        #productModal .modal-dialog.modal-lg {
            max-width: 1139px;
            width: 100%;
        }

        .productModalImageBox {
            height: 100%;
            border-radius: 14.47px;
            border: 3px solid #B8C034;
            position: relative;
            overflow: hidden;
        }

        #productModalImage {
            height: 100%;
            width: 100%;
            object-fit: cover;
            object-position: top center;
        }

        .productModalPriceBox {
            background: var(--secondary);
            margin: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            width: fit-content;
            gap: 10px;
            color: #000;
            font-family: "Inter";
            font-size: 16px;
            font-weight: 500;
            padding: 6px 12px;
            border-radius: 50px;
        }

        .productModalContent {
            padding: 10px;
            width: 100%;
        }

        small#productModalDate {
            font-size: 12px;
            color: #4f4f4f;
        }

        #productModal .modal-footer {
            display: flex;
            align-items: stretch;
            justify-content: space-between;
        }

        .productModalUserProfileBox {
            display: flex;
            align-items: center;
            justify-content: start;
        }

        #productModal .direct-message-btn {
            flex: 1;
            border-radius: 10px;
            background: var(--secondary);
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            max-width: 200px;
            transition: .3s;
        }

        #productModal .direct-message-btn:hover {
            background: var(--primary);
            transition: .3s;
        }

        .productModalImageBox .btn-close {
            position: absolute;
            top: 5px;
            right: 5px;
            background-color: #fff;
            color: #000;
            --bs-btn-close-opacity: 1;
            padding: 5px;
        }

        .trigger-element {
            cursor: pointer;
        }

        .service_slider_img_box {
            height: 250px;
            position: relative;
        }

        .customHeading {
            color: var(--primary);
            position: relative;
            display: inline-block;
            padding-bottom: 5px;
            font-weight: 700;
        }

        .service_search_area form {
            position: relative;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            transition: all 1s;
            width: 100%;
            height: 50px;
            background: white;
            box-sizing: border-box;
            border-radius: 25px;
            border: 4px solid white;
            padding: 5px;
            cursor: pointer;
        }

        .service_search_area input {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 63.5px;
            line-height: 30px;
            outline: 0;
            border-radius: 16px;
            padding: 4px 30px;
            background: var(--primary);
            color: #fff;
            border: 1px solid var(--primary);
            font-family: "Inter", sans-serif;
            font-optical-sizing: auto;
            font-style: normal;
            font-weight: 400;
            font-size: 16px;
        }

        .service_search_area input::placeholder {
            color: #fff;
        }

        .service_search_area .fa {
            box-sizing: border-box;
            padding: 10px;
            width: 78.5px;
            height: 59.5px;
            position: absolute;
            align-content: center;
            top: 2px;
            right: 1.7px;
            border-radius: 0px 14px 14px 0px;
            color: var(--primary);
            background: #B8C034;
            text-align: center;
            font-size: 1.2em;
            transition: all 1s;
        }

        .min_h_400 {
            min-height: calc(100vh - 400px);
        }

        .col-lg-7 {
            padding: 0;
        }

        .event_slider .card {
            width: 100%;
        }

        .event_slider .card {
            width: 100%;
            box-shadow: none;
            border: none;
            border-radius: 0;
            min-height: unset;
            background: #F2F2F2;
        }

        .event_slider_img_box {
            height: 414px;
        }

        .event_price_label {
            bottom: 20px;
            left: 20px;
        }

        .event_price_label .service_price {
            font-family: "Inter";
            font-size: 16px;
            padding: 1px 14px;
            color: #000000;
            font-weight: 500;
            margin-bottom: 0px !important;
        }

        .articles .card .card-body {
            display: flex;
            flex-direction: column;
            padding: 10px 28px 28px;
        }

        .event_slider .card-content {
            padding: 10px 28px 28px;
        }

        .event_slider .card-content h3 {
            margin: 15px 0px;
            margin-bottom: 14px;
            font-size: 21px;
            font-family: "Inter";
            font-weight: 500;
            color: #000000;
        }

        .event_slider .card-content p {
            font-size: 16px;
            font-family: "Inter";
            font-weight: 400;
            color: #555 !important;
            margin-bottom: 0;
        }

        button.btn.btn-sm.btn-primary.mt-2.read-more-btn.trigger-element {
            border: none;
            font-size: 13.65px;
            font-family: "Inter";
            font-weight: 500;
            padding: 0;
            color: #273572;
        }

        .read-more-btn:hover {
            color: #b8c034 !important;
            background-color: transparent;
        }

        .event_slider .posted_name_date {
            flex-direction: column;
            justify-content: center;
            align-items: start;
        }

        .service_posted_by .posted_name_date h6 {
            margin-bottom: 1px;
            color: var(--black);
            font-size: 17.71px;
            font-family: "Inter";
            font-weight: 400;
            line-height: 26.73px;
        }

        .event_slider .card-content .details {
            margin-bottom: 0;
        }

        .service_posted_by .posted_name_date p {
            font-size: 16px;
            font-family: "Inter";
            font-weight: 400;
            color: #555 !important;
            margin-bottom: 0;
        }

        .service_posted_by {
            margin: 20px 0 !important;
        }

        .articles .card .direct-message-btn {
            padding: 16px 10px;
            font-family: "Inter";
            margin-top: 10px;
            border-radius: 10px;
            font-weight: 400;
        }

        #productModal .modal-footer {
            padding: 0 0 0 34px;
            border: none;
            width: 100%;
            max-width: 60%;
            display: flex;
            align-items: stretch;
            justify-content: space-between;
        }

        .productModalUserProfileBox {
            display: flex;
            align-items: center;
            justify-content: start;
            width: 100%;
            max-width: 48%;
        }

        .productModalUserProfileBox img {
            object-fit: cover;
        }

        h6#productModalUserName {
            font-size: 18.71px;
            font-family: Inter;
            font-weight: 400;
            line-height: 26.73px;
            color: #fff;
        }


        small#productModalDate {
            font-size: 16.04px;
            font-family: Inter;
            font-weight: 400;
            line-height: 18.71px;
            color: #fff;
        }

        #productModal .direct-message-btn {
            height: 60px !important;
            font-size: 18px;
            font-weight: 500;
            font-family: "Poppins";
            flex: 1;
            border-radius: 10px;
            background: var(--secondary);
            color: #273572;
            display: flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            max-width: 326px;
            height: 100%;
            transition: .3s;
        }

        .modal-dialog-centered {
            /* min-height: 0 !important; */
        }

        #productModal .direct-message-btn:hover {
            background: var(--secondary);
            color: #ffffffff;
            transition: .3s;
        }



        #productModal .modal-content {
            margin-top: 150px;
            height: fit-content;
            overflow: hidden;
            flex-direction: row;
            background: linear-gradient(45deg, #1F2C77 50%, #2C3FB3 110%);
            padding: 26px;
            border: 3px solid #fff;
            border-radius: 17px !important;
            overflow: hidden;
        }

        h5.customHeading {
            color: #fff !important;
            width: fit-content;
            text-align: left;
            font-family: "Bebas Neue", sans-serif;
            font-weight: 400;
            font-style: normal;
            font-size: 64px;
            line-height: 106%;
            margin: 18px 0;
            position: relative;
        }

        h5.customHeading::after {
            content: "";
            position: absolute;
            bottom: 0;
            left: 0;
            width: 50px;
            height: 6px;
            background-color: #b8c034;
        }

        #productModalDescription {

            font-size: 22px;
            font-family: "inter";
            font-optical-sizing: auto;
            font-weight: 400;
            font-style: normal;
            line-height: 140%;
            width: 100%;
            color: #fff;
        }

        .modal-footer .btn-close {
            --bs-btn-close-bg: url("{{ asset('assets/images/modalCloseVector.svg') }}") !important;
            position: absolute;
            top: 5px;
            right: 5px;
            background-color: #434F9C;
            color: #ffffff !important;
            --bs-btn-close-opacity: 1;
            padding: 7px 12px 18px 14px;
            border-radius: 50%;
        }

        .modal-footer .btn-close img {
            width: 15px;

        }
    </style>

    <link rel="stylesheet" href="{{ asset('assets/css/footer.css') }}">

    <section class="feed_lp">
        <div class="container">
            <h1 class="main_heading">
                Products Offered by <span class="feedLpPri">Muslim<span class="feedLpSec">Lynk Members</span></span>
            </h1>
            <p class="feedLpPara">MuslimLynk members offer a diverse range of products designed to support businesses at every stage. From e-commerce goods to specialized manufacturing outputs, the community delivers reliable, high-quality products that empower growth, efficiency, and scalability.</p>
            {{-- <div class="service_search_area">
                <div class="row justify-content-center">
                    <div class="col-lg-7">
                        <form action="">
                            <input type="search" placeholder="Search by name...">
                            <button type="submit">
                                <i class="fa fa-search"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div> --}}
        </div>

    </section>

    <!-- Categories Section -->
    <section class="product-categories-section" style="padding: 40px 0;">
        <div class="container">
            <h2 class="text-center mb-4" style="font-weight: bold; font-family: 'Inter', sans-serif; color: #B8C034; font-size: 24px;">
                Explore Products by Category
            </h2>
            <div id="categoriesContainer">
                <!-- Categories will be loaded via JavaScript -->
            </div>
        </div>
    </section>

    <section class="event_slider min_h_400">
        <div class="container">
            <div class="row g-4" id="productsContainer">
                @include('partial.product_cards', ['products' => $products])
            </div>
        </div>
    </section>

    <style>
        .category-card {
            background: #273572;
            border: 2px solid #273572;
            border-radius: 12px;
            padding: 12px 20px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Inter', sans-serif;
            font-weight: 500;
            font-size: 14px;
            color: #fff;
            text-decoration: none;
        }

        .category-card:hover {
            background: #B8C034;
            border-color: #B8C034;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(184, 192, 52, 0.3);
            color: #fff;
        }

        .category-card.active {
            background: #B8C034;
            border-color: #B8C034;
            color: #fff;
        }

        .category-card.active:hover {
            background: #a0a92d;
            border-color: #a0a92d;
            color: #fff;
        }

        #productsContainer.loading {
            opacity: 0.5;
            pointer-events: none;
        }

        .loading-spinner {
            text-align: center;
            padding: 40px;
        }

        #categoriesContainer {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 12px;
        }

        #categoriesContainer .category-card {
            flex: 0 0 auto;
            min-width: 140px;
            max-width: 180px;
        }
    </style>

    @include('layouts.home-footer')

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Display categories from server
            const categories = @json($categories ?? []);
            displayCategories(categories);

            function displayCategories(categories) {
                const container = document.getElementById('categoriesContainer');
                container.innerHTML = '';

                categories.forEach(category => {
                    const card = document.createElement('div');
                    card.className = 'category-card';
                    card.textContent = category;
                    card.dataset.category = category;

                    card.addEventListener('click', function() {
                        // Remove active class from all cards
                        document.querySelectorAll('.category-card').forEach(c => c.classList.remove('active'));
                        // Add active class to clicked card
                        card.classList.add('active');
                        // Load products for this category
                        loadProductsByCategory(category);
                    });

                    container.appendChild(card);
                });
            }

            function loadProductsByCategory(category) {
                const productsContainer = document.getElementById('productsContainer');
                productsContainer.classList.add('loading');
                productsContainer.innerHTML = '<div class="col-12"><div class="loading-spinner"><p>Loading products...</p></div></div>';

                // Use jQuery AJAX to match existing code style
                jQuery.ajax({
                    url: "{{ route('products') }}",
                    method: "GET",
                    data: {
                        category: category
                    },
                    success: function(response) {
                        productsContainer.classList.remove('loading');
                        if (response.html) {
                            productsContainer.innerHTML = response.html;
                            // Re-attach event listeners for product modals
                            attachEventListeners();
                        } else {
                            productsContainer.innerHTML = '<div class="col-12"><div class="text-center"><p>No products found in this category.</p></div></div>';
                        }
                    },
                    error: function() {
                        productsContainer.classList.remove('loading');
                        productsContainer.innerHTML = '<div class="col-12"><div class="text-center"><p>Error loading products. Please try again.</p></div></div>';
                    }
                });
            }

            function attachEventListeners() {
                // Event listeners are already attached via existing scripts
                // This is a placeholder if we need to reattach any specific listeners
            }
        });
    </script>

    <!-- Main Modal -->
    <div class="modal fade" id="mainModal" tabindex="-1" aria-labelledby="mainModalLabel">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header" style="background-color: var(--primary); color: #fff;">
                    <h5 class="modal-title" id="mainModalLabel">Send Direct Message</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="directMessageForm">
                        <input type="hidden" name="receiver_id" id="receiver_id" value="">
                        <!-- Receiver ID will be set dynamically -->

                        <div class="mb-3">
                            <label for="messageContent" class="form-label">Your Message</label>
                            <textarea class="form-control" id="messageContent" name="content" rows="4" required></textarea>
                        </div>

                        <button type="submit" class="btn btn-primary w-100 ">Send Message</button>
                    </form>
                    <div id="messageStatus" class="mt-3 text-center"></div> <!-- Status Message -->
                </div>
            </div>
        </div>
    </div>
    <!-- Modal -->
    <div class="modal fade" id="productModal" tabindex="-1" aria-labelledby="productModalLabel">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">

                <div class="modal-body p-0">
                    <div class="productModalImageBox">
                        <img id="productModalImage" src="" class="img-fluid mb-3" alt="Product image" />

                    </div>


                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><img
                            src="{{ asset('assets/images/closeIcon.webp') }}" alt=""></button>

                    <div class="productModalContent">
                        <p class="productModalPriceBox"> <span id="productModalPrice"></span><span
                                id="productModalQuantity"></span></p>


                        <h5 class="modal-title customHeading" id="productModalLabel">Product Title</h5>


                        <p class="mt-2"><span id="productModalDescription"></span></p>


                    </div>
                    <div class="productModalUserProfileBox">

                        <a href="javascript:void(0)" class="view-more direct-message-btn" data-receiver-id=""
                            data-bs-dismiss="modal">Message Now</a>
                    </div>
                    <div class="productModalUserProfileBox">
                        <img id="productModalUserPhoto" src="" alt="User photo" class="rounded-circle me-2"
                            width="50" height="50">
                        <div>
                            <h6 id="productModalUserName" class="mb-0"></h6>
                            <small id="productModalDate"></small>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection


@section('scripts')
    <script>
        jQuery(document).ready(function() {
            const searchInput = jQuery('input[type="search"]');
            const resultsContainer = jQuery('.event_slider .row');

            jQuery('form').on('submit', function(e) {
                e.preventDefault();
                fetchProducts(searchInput.val());
            });

            searchInput.on('input', function() {
                const value = jQuery(this).val();
                clearTimeout(jQuery.data(this, 'timer'));
                const wait = setTimeout(() => fetchProducts(value), 500);
                jQuery(this).data('timer', wait);
            });

            function fetchProducts(searchTerm) {
                // Get selected category
                const activeCategory = document.querySelector('.category-card.active');
                const category = activeCategory ? activeCategory.dataset.category : '';
                
                jQuery.ajax({
                    url: "{{ route('products') }}",
                    method: "GET",
                    data: {
                        search: searchTerm,
                        category: category
                    },
                    success: function(response) {
                        resultsContainer.html(response.html);
                        attachEventListeners();
                    },
                    error: function() {
                        resultsContainer.html(
                            '<div class="col-12"><p>Error loading products.</p></div>');
                    }
                });
            }
        });
    </script>

    <script>
        jQuery(document).ready(function($) {

            let directMessageBtn = document.querySelectorAll('.direct-message-btn');
            // console.log("directMessageBtn", directMessageBtn);
            directMessageBtn.forEach(element => {
                // console.log("element", element);
                element.addEventListener("click", function() {
                    let receiverId = $(this).data('receiver-id');
                    $('#receiver_id').val(receiverId);
                    console.log("receiverId", receiverId);
                    $.ajax({
                        url: '/api/check-conversation',
                        method: 'GET',
                        data: {
                            receiver_id: receiverId
                        },
                        headers: {
                            "Authorization": localStorage.getItem("sanctum-token")
                        },
                        success: function(response) {
                            if (response.conversation_exists) {
                                // If conversation exists, open chat directly
                                if (window.openChatWithUser) {
                                    window.openChatWithUser(receiverId);
                                }
                            } else {
                                // If no conversation, open the modal
                                console.log(response.receiver);
                                $('#receiver_id').val(receiverId);
                                $("#messageContent").val(`Hi ${response.receiver.first_name ?? ''} ${response.receiver.last_name ?? ''},
I came across your profile and was really impressed by your work. I’d love to connect and exchange ideas.
Looking forward to connecting!
Best Regards,
{{ Auth::user()->first_name }} {{ Auth::user()->last_name }}`);
                                // $('#mainModal').modal('show');
                                var myModal = new bootstrap.Modal(document
                                    .getElementById('mainModal'));
                                myModal.show();
                            }
                        },
                        error: function(xhr) {
                            console.error('Error checking conversation:', xhr);
                        }
                    });
                });


            });

            $('#directMessageForm').on('submit', function(e) {
                e.preventDefault();

                const formData = {
                    receiver_id: $('#receiver_id').val(),
                    content: $('#messageContent').val(),
                    _token: '{{ csrf_token() }}'
                };

                $.ajax({
                    url: "{{ route('sendMessage') }}",
                    method: 'POST',
                    data: formData,
                    headers: {
                        "Authorization": localStorage.getItem("sanctum-token")
                    },
                    success: function(response) {
                        // Close the modal
                        $('#mainModal').modal('hide');

                        // Trigger opening the chat box and specific conversation
                        if (window.openChatWithUser) {
                            window.openChatWithUser(formData.receiver_id);
                        }
                    },
                    error: function(xhr) {
                        const errorMsg = xhr.responseJSON?.error ||
                            'An error occurred. Please try again.';
                        $('#messageStatus').html(
                            `<div class="alert alert-danger">${errorMsg}</div>`);
                    }
                });
            });
        });
    </script>
    <script>
        window.AUTH_USER_ID = {{ Auth::id() ?? 'null' }};
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const modal = document.getElementById('productModal');
            const bsModal = new bootstrap.Modal(modal);

            document.body.addEventListener('click', function(e) {
                const trigger = e.target.closest('.trigger-element');

                if (!trigger) return;

                const wrapper = trigger.closest('.product-trigger-wrapper, .service-trigger-wrapper');
                if (!wrapper) return;

                modal.querySelector('.modal-title').textContent = wrapper.dataset.title;
                modal.querySelector('#productModalDescription').textContent = wrapper.dataset.description;
                modal.querySelector('#productModalImage').src = wrapper.dataset.image;
                modal.querySelector('#productModalPrice').textContent = wrapper.dataset.price;
                modal.querySelector('#productModalQuantity').textContent = wrapper.dataset.quantity;


                modal.querySelector('#productModalUserName').textContent = wrapper.dataset.userName;
                modal.querySelector('#productModalDate').textContent = "Posted on " + wrapper.dataset.date;
                modal.querySelector('.direct-message-btn').setAttribute('data-receiver-id', wrapper.dataset
                    .id);
                const receiverId = wrapper.dataset.id;
                // hide button if it's the same user
                if (window.AUTH_USER_ID && parseInt(window.AUTH_USER_ID) === parseInt(receiverId)) {
                    modal.querySelector('.direct-message-btn').closest('.productModalUserProfileBox').style
                        .display = 'none';
                } else {
                    modal.querySelector('.direct-message-btn').closest('.productModalUserProfileBox').style
                        .display = 'inline-flex'; // or 'block' based on your CSS
                }
                // Handle user photo or initials
                const userPhotoElement = modal.querySelector('#productModalUserPhoto');
                const userPhoto = wrapper.dataset.userPhoto;

                if (userPhoto) {
                    // Show photo - replace with img if initials div exists
                    if (userPhotoElement.tagName !== 'IMG') {
                        const img = document.createElement('img');
                        img.id = 'productModalUserPhoto';
                        img.className = 'rounded-circle me-2';
                        img.width = 50;
                        img.height = 50;
                        img.src = userPhoto;
                        img.alt = wrapper.dataset.userName;
                        userPhotoElement.replaceWith(img);
                    } else {
                        userPhotoElement.src = userPhoto;
                        userPhotoElement.alt = wrapper.dataset.userName;
                    }
                } else {
                    // Show initials - replace img with div if needed
                    if (userPhotoElement.tagName === 'IMG') {
                        const initialsDiv = document.createElement('div');
                        initialsDiv.id = 'productModalUserPhoto';
                        initialsDiv.className = 'avatar-initials me-2';
                        initialsDiv.textContent = wrapper.dataset.userInitials;
                        userPhotoElement.replaceWith(initialsDiv);
                    } else {
                        userPhotoElement.textContent = wrapper.dataset.userInitials;
                    }
                }

                bsModal.show();
            });
        });
    </script>
@endsection
