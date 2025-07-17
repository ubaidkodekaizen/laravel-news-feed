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

        .read-more-btn:hover,
        .read-more-btn:focus,
        .read-more-btn:active {
            background: transparent !important;
            color: var(--secondary) !important;
            border: none !important;
            border-top: 1px solid var(--secondary) !important;
        }

        #productModal .modal-dialog.modal-lg {
            max-width: 600px;
        }

        .productModalImageBox {
            height: 300px;
            position: relative;
        }

        #productModalImage {
            height: 100%;
            width: 100%;
            object-fit: cover;
            object-position: top center;
        }

        .productModalPriceBox {
            position: absolute;
            bottom: 5px;
            right: 5px;
            background: var(--secondary);
            color: #fff;
            margin: 0;
            border-radius: 25px;
            padding: 3px 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        .productModalContent {
            padding: 10px;
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

        #productModal .modal-content {
            overflow: hidden;
        }

        #productModalLabel {
            font-size: 18px;
            line-height: 1.3em;
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

        .articles .card {
            min-height: 610px;
        }

        .articles .card .card-body {
            display: flex;
            flex-direction: column;
        }

        .articles .card .direct-message-btn {
            padding: 10px 10px;
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

        .customHeading::before {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 100%;
            background: linear-gradient(to right, transparent, var(--secondary));
            height: 5px;
            border-radius: 10px;
        }

        .articles {
            overflow: hidden;
            position: relative;
        }


        .service_search_area form{
            position: relative;
            top: 50%;
            left: 50%;
            transform: translate(-50%,-50%);
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

        .service_search_area input{
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;;
            height: 42.5px;
            line-height: 30px;
            outline: 0;
            font-size: 1em;
            border-radius: 20px;
            padding: 0 45px 0px 20px;
            border: 1px solid var(--primary);
        }

        .service_search_area .fa{
            box-sizing: border-box;
            padding: 10px;
            width: 42.5px;
            height: 42.5px;
            position: absolute;
            top: 0;
            right: 0;
            border-radius: 50%;
            color: var(--primary);
            text-align: center;
            font-size: 1.2em;
            transition: all 1s;
        }

        .service_search_area form:hover .fa{
            background: var(--primary);
            color: white;
        }
        .min_h_400{
            min-height: calc(100vh - 400px);
        }
    </style>

    <section class="feed_lp">
        <div class="container">
            <h1 class="main_heading">
                Services Offered by Muslim Lynk Members
            </h1>
            <div class="service_search_area">
                <div class="row justify-content-center">
                    <div class="col-lg-6">
                        <form action="">
                            <input type="search" placeholder="Search here ...">
                            <button type="submit">
                                <i class="fa fa-search"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="articles min_h_400">
        <div class="container">
            <div class="overflow-hidden">
                    <div class="row g-4">
                        @include('partial.service_cards', ['services' => $services])
                    </div>
            </div>
        </div>
    </section>
    <section class="lp_footer">
        <div class="container">

            <p class="powered_by">
                Powered By <a href="https://amcob.org/" target="_blank" rel="noopener noreferrer">AMCOB</a>
            </p>
        </div>
    </section>
    <!-- Main Modal -->
    <div class="modal fade" id="mainModal" tabindex="-1" aria-labelledby="mainModalLabel" aria-hidden="true">
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
    <div class="modal fade" id="productModal" tabindex="-1" aria-labelledby="productModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">

                <div class="modal-body p-0">
                    <div class="productModalImageBox">
                        <img id="productModalImage" src="" class="img-fluid mb-3" alt="Product image" />
                        <p class="productModalPriceBox"> <span id="productModalPrice"></span><span
                                id="productModalQuantity"></span></p>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="productModalContent">


                        <h5 class="modal-title customHeading" id="productModalLabel">Product Title</h5>


                        <p class="mt-2"><span id="productModalDescription"></span></p>


                    </div>

                </div>
                <div class="modal-footer">
                    <div class="productModalUserProfileBox">
                        <img id="productModalUserPhoto" src="" alt="User photo" class="rounded-circle me-2"
                            width="50" height="50">
                        <div>
                            <h6 id="productModalUserName" class="mb-0"></h6>
                            <small id="productModalDate"></small>
                        </div>
                    </div>
                    <a href="javascript:void(0)" class="view-more direct-message-btn" data-receiver-id=""
                        data-bs-dismiss="modal">Message Now</a>
                </div>
            </div>
        </div>
    </div>
@endsection


@section('scripts')
<script>
        jQuery(document).ready(function () {
            const searchInput = jQuery('input[type="search"]');
            const resultsContainer = jQuery('.articles .row');

            jQuery('form').on('submit', function (e) {
                e.preventDefault();
                fetchServices(searchInput.val());
            });

            searchInput.on('input', function () {
                const value = jQuery(this).val();
                clearTimeout(jQuery.data(this, 'timer'));
                const wait = setTimeout(() => fetchServices(value), 500);
                jQuery(this).data('timer', wait);
            });

            function fetchServices(searchTerm) {
                jQuery.ajax({
                    url: "{{ route('services') }}", 
                    method: "GET",
                    data: { search: searchTerm },
                    success: function (response) {
                        resultsContainer.html(response.html);
                    },
                    error: function () {
                        resultsContainer.html('<div class="col-12"><p>Error loading services.</p></div>');
                    }
                });
            }
        });
    </script>
    <script>
        jQuery(document).ready(function($) {

            let directMessageBtn = document.querySelectorAll('.direct-message-btn');
            console.log("directMessageBtn", directMessageBtn);
            directMessageBtn.forEach(element => {
                console.log("element", element);
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
                    url: '{{ route('sendMessage') }}',
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
                modal.querySelector('#productModalUserPhoto').src = wrapper.dataset.userPhoto;
                modal.querySelector('#productModalUserName').textContent = wrapper.dataset.userName;
                modal.querySelector('#productModalDate').textContent = "Posted on " + wrapper.dataset.date;
                modal.querySelector('.direct-message-btn').setAttribute('data-receiver-id', wrapper.dataset
                    .id);

                bsModal.show();
            });
        });
    </script>
@endsection
