@extends('layouts.main')
@section('content')
    <style>
        .user_company_profile .profile_pic img {
            border-radius: 15px;
            height: 300px;
            width: 300px;
            margin: 0 auto;
            object-fit: cover;
            max-width: 100%;
            display: block;
            border: 2px solid var(--primary);
        }
    </style>

    <section class="user_profile_view">
        <div class="container">
            <!-- LinkedIn Profile View -->
            <div class="profile-container">
                <!-- Cover Image -->
                <div class="cover-image"></div>

                <!-- Profile Image -->
                <div class="position-relative">
                    <div class="profile-image">
                        <img src="{{ $user->photo ? asset('storage/' . $user->photo) : 'https://cdn.pixabay.com/photo/2015/10/05/22/37/blank-profile-picture-973460_640.png' }}"
                            alt="Profile Image">
                    </div>
                    <!-- Profile Details -->
                    <div class="profile-details">
                        <h1>
                            {{ $user->first_name ?? '' }} {{ $user->last_name ?? '' }}
                        </h1>
                        <p> {{ $user->user_position ?? 'Not Provided' }} </p>
                        <p class="location"> {{ $user->city ?? '' }}, {{ $user->county ?? '' }}, {{ $user->state ?? '' }},
                            {{ $user->country ?? '' }}</p>
                        <a class="contact-info" href="javascript:void(0);" data-bs-toggle="modal"
                            data-bs-target="#moreDetailsModal">More Details</a>
                        <div class="mt-4">
                            <a href="javascript:void(0)" class="btn btn-secondary direct-message-btn"
                                data-receiver-id="{{ $user->id }}">
                                Direct Message
                            </a>
                        </div>
                    </div>
    
                    <div class="contact_social_flex">
                        <div class="contact_email">
                            @if ($user->phone_public == 'Yes')
                                <div class="contact_info_flex">
                                    <i class="fa-solid fa-phone"></i>
                                    <div class="contact_name_info">
                                        <a href="tel:{{ $user->phone ?? '' }}">{{ $user->phone ?? '' }}</a>
                                    </div>
                                </div>
                            @endif
                            @if ($user->email_public == 'Yes')
                                <div class="contact_info_flex">
                                    <i class="fa-solid fa-envelope"></i>
                                    <div class="contact_name_info">
                                        <a href="mailto:{{ $user->email ?? '' }}">{{ $user->email ?? '' }}</a>
                                    </div>
                                </div>
                            @endif
                        </div>
                        <ul class="list_check_flex">
                            @if ($user->linkedin_url)
                                <li>
                                    <a href="https://www.linkedin.com/in/{{ $user->linkedin_url }}" target="_blank"
                                        title="Facebook">
                                        <img src="{{ asset('assets/images/social-icons/linkedin.png') }}" alt="">
                                    </a>
                                </li>
                            @endif
                            @if ($user->facebook_url)
                                <li>
                                    <a href="{{ $user->facebook_url }}" target="_blank" title="Facebook">
                                        <img src="{{ asset('assets/images/social-icons/facebook.png') }}" alt="">
                                    </a>
                                </li>
                            @endif
    
                            @if ($user->x_url)
                                <li>
                                    <a href="{{ $user->x_url }}" target="_blank" title="X (Formerly Twitter)">
                                        <img src="{{ asset('assets/images/social-icons/twitter.png') }}" alt="">
                                    </a>
                                </li>
                            @endif
    
                            @if ($user->instagram_url)
                                <li>
                                    <a href="{{ $user->instagram_url }}" target="_blank" title="Instagram">
                                        <img src="{{ asset('assets/images/social-icons/instagram.png') }}" alt="">
                                    </a>
                                </li>
                            @endif
    
                            @if ($user->tiktok_url)
                                <li>
                                    <a href="{{ $user->tiktok_url }}" target="_blank" title="TikTok">
                                        <img src="{{ asset('assets/images/social-icons/tiktok.png') }}" alt="">
                                    </a>
                                </li>
                            @endif
    
                            @if ($user->youtube_url)
                                <li>
                                    <a href="{{ $user->youtube_url }}" target="_blank" title="YouTube">
                                        <img src="{{ asset('assets/images/social-icons/youtube.png') }}" alt="">
                                    </a>
                                </li>
                            @endif
                        </ul>
                    </div>
                    <div class="company_profile_section">
                        <div class="row">
                            <div class="col-lg-3">
                                <div class="company_card">
                                    <div class="company_logo">
                                        <img src="{{ isset($user->company) && $user->company->company_logo ? asset('storage/' . $user->company->company_logo) : 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTcd5J_YDIyLfeZCHcsBpcuN8irwbIJ_VDl0Q&s' }}"
                                            alt="Company Logo" class="logo_img">
                                    </div>
                                    <div class="company_card_details">
                                        <div class="company_name_icon_flex">
                                            @if (!empty($user->company->company_name))
                                                <h2 class="company_name">
                                                    <span><i class="fa-solid fa-building"></i></span>
                                                    {{ $user->company->company_name }}
                                                </h2>
                                            @endif
    
                                            <div class="icons_flex">
                                                @if (!empty($user->company->company_web_url))
                                                    <a href="{{ $user->company->company_web_url }}" class="company_link"
                                                        target="_blank">
                                                        <i class="fa-solid fa-link"></i>
                                                    </a>
                                                @endif
    
                                                @if (!empty($user->company->company_linkedin_url))
                                                    <a href="{{ $user->company->company_linkedin_url }}" target="_blank"
                                                        class="company_link" rel="noopener noreferrer">
                                                        <i class="fa-brands fa-linkedin"></i>
                                                    </a>
                                                @endif
                                            </div>
                                        </div>
    
                                        @if (!empty($user->company->company_business_type))
                                            <p class="company_experience">
                                                <span><i class="fa-solid fa-landmark"></i></span>
                                                {{ $user->company->company_business_type }}
                                            </p>
                                        @endif
    
                                        @if (!empty($user->company->company_position))
                                            <p class="company_position">
                                                <span><i class="fa-solid fa-user-tie"></i></span>
                                                {{ $user->company->company_position }}
                                            </p>
                                        @endif
    
                                        @if (!empty($user->company->company_experience))
                                            <p class="company_experience">
                                                <span><i class="fa-solid fa-business-time"></i></span>
                                                {{ $user->company->company_experience }}
                                            </p>
                                        @endif
    
                                        @if (!empty($user->company->company_phone))
                                            <a href="tel:{{ $user->company->company_phone }}" class="company_contact">
                                                <span><i class="fa-solid fa-phone"></i></span>
                                                {{ $user->company->company_phone }}
                                            </a>
                                        @endif
    
                                        @if (!empty($user->company->company_revenue))
                                            <p class="company_experience">
                                                <span><i class="fa-solid fa-money-bill-trend-up"></i></span>
                                                ${{ $user->company->company_revenue }}
                                            </p>
                                        @endif
    
                                        @if (!empty($user->company->company_no_of_employee))
                                            <p class="company_experience">
                                                <span><i class="fa-solid fa-people-group"></i></span>
                                                {{ $user->company->company_no_of_employee }}
                                            </p>
                                        @endif
    
                                        @if (!empty($user->company->company_industry))
                                            <p class="company_experience">
                                                <span><i class="fa-solid fa-industry"></i></span>
                                                {{ $user->company->company_industry }}
                                            </p>
                                        @endif
                                    </div>
    
                                    <div class="profile_qualification_sec">
                                        <h1 class="profile_data profile_heading">
                                            Qualifications
                                        </h1>
                                        <div class="accordion" id="userEducation">
                                            @forelse ($user->userEducations as $education)
                                                <div class="accordion-item">
                                                    <h2 class="accordion-header">
                                                        <button class="accordion-button" type="button"
                                                            data-bs-toggle="collapse"
                                                            data-bs-target="#collapse_edu{{ $loop->index }}"
                                                            aria-expanded="{{ $loop->first ? 'true' : 'false' }}"
                                                            aria-controls="collapse_edu{{ $loop->index }}">
                                                            {{ $education->degree_diploma ?? '' }}
                                                        </button>
                                                    </h2>
                                                    <div id="collapse_edu{{ $loop->index }}"
                                                        class="accordion-collapse collapse {{ $loop->first ? 'show' : '' }}"
                                                        data-bs-parent="#userEducation">
                                                        <div class="accordion-body">
                                                            {{ $education->college_university ?? '' }} -
                                                            {{ $education->year ?? '' }}
                                                        </div>
                                                    </div>
                                                </div>
                                            @empty
                                                <p>No Education is added to show.</p>
                                            @endforelse
                                        </div>
                                    </div>
                                </div>
    
                            </div>
                            <div class="col-lg-9">
                                <div class="event_slider">
                                    <div class="container">
                                        <h2 class="mb-3">Products</h2>
                                        <div class="swiper">
                                            <div class="swiper-wrapper">
                                                @forelse($user->products as $product)
                                                    <div class="swiper-slide">
                                                        <div class="card">
                                                            <img src="{{ $product->product_image ? asset('storage/' . $product->product_image) : asset('https://placehold.co/420x250') }}"
                                                                alt="{{ $product->title }}">
    
                                                            <div class="card-content">
                                                                <div class="service_price_duration my-0 event_price_label">
                                                                    <p class="service_price">
                                                                        <span>
                                                                            @if ($product->original_price)
                                                                                <s>${{ number_format($product->original_price, 2) }}</s>
                                                                            @endif
                                                                            ${{ number_format($product->discounted_price ?? $product->original_price, 2) }}
                                                                            / {{ $product->unit_of_quantity ?? '' }}
                                                                        </span>
                                                                    </p>
                                                                </div>
    
                                                                <!-- Product Title -->
                                                                <div class="details">
                                                                    <h3>{{ $product->title }}</h3>
                                                                    <p>{{ Str::limit($product->short_description, 100, '...') }}</p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @empty
                                                    <p class="text-center text-muted">No products available.</p>
                                                @endforelse
    
                                            </div>
    
                                            <!-- Add Pagination and Navigation -->
                                            <div class="swiper-button-prev"></div>
                                            <div class="swiper-button-next"></div>
                                        </div>
    
    
                                    </div>
    
                                </div>
                                <div class="services_profile_border">
                                    <div class="container">
                                        <h2 class="mb-3">Services</h2>
                                        <div class="services_slider services_profile_slider articles overflow-hidden pt-0">
                                            <div class="swiper-wrapper">
                                                @forelse($user->services as $service)
                                                <div class="swiper-slide">
                                                    <div class="card">
                                                        <img src="{{ $service->service_image ? asset('storage/' . $service->service_image) : asset('https://placehold.co/420x250') }}"
                                                                alt="{{ $service->title }}">
    
                                                        <div class="card-body">
                                                            <h3 class="service_heading">{{ $service->title }}</h3>
                                                            <p>{{ Str::limit($service->short_description, 100, '...') }}</p>
                                                            <div class="service_price_duration">
                                                                <div class="service_price">
                                                                    <p>
                                                                        <span>
                                                                            @if ($service->discounted_price && $service->discounted_price < $service->original_price)
                                                                                <s>${{ $service->original_price }}</s>
                                                                                ${{ $service->discounted_price }}
                                                                            @else
                                                                                ${{ $service->original_price }}
                                                                            @endif
                                                                            / {{ $service->duration }}
                                                                        </span>
    
    
                                                                    </p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                @empty
                                                    <p class="text-center text-muted">No services available.</p>
                                                @endforelse
                                            </div>
    
                                            <div class="swiper-button-prev"></div>
                                            <div class="swiper-button-next"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


            </div>

        </div>
    </section>

    <!-- Modal -->
    <div class="modal fade" id="moreDetailsModal" tabindex="-1" aria-labelledby="moreDetailsModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="moreDetailsModalLabel">More Details</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">

                    @if(!empty($user->gender))
                        <div class="contact_info_flex">
                            <i class="fa-solid fa-venus-mars"></i>
                            <div class="contact_name_info">
                                <label for="gender" class="contact_heading">Gender</label>
                                <p>{{ $user->gender }}</p>
                            </div>
                        </div>
                    @endif
                
                    @if(!empty($user->age_group))
                        <div class="contact_info_flex">
                            <i class="fa-regular fa-calendar-days"></i>
                            <div class="contact_name_info">
                                <label for="age_group" class="contact_heading">Age Group</label>
                                <p>{{ $user->age_group }}</p>
                            </div>
                        </div>
                    @endif
                
                    @if(!empty($user->ethnicity))
                        <div class="contact_info_flex">
                            <i class="fa-solid fa-users-between-lines"></i>
                            <div class="contact_name_info">
                                <label for="ethnicity" class="contact_heading">Ethnicity</label>
                                <p>{{ $user->ethnicity }}</p>
                            </div>
                        </div>
                    @endif
                
                    @if(!empty($user->nationality))
                        <div class="contact_info_flex">
                            <i class="fa-solid fa-passport"></i>
                            <div class="contact_name_info">
                                <label for="nationality" class="contact_heading">Nationality</label>
                                <p>{{ $user->nationality }}</p>
                            </div>
                        </div>
                    @endif
                
                    @if(!empty($user->languages))
                        <div class="contact_info_flex">
                            <i class="fa-solid fa-language"></i>
                            <div class="contact_name_info">
                                <label for="languages" class="contact_heading">Languages</label>
                                <p>{{ $user->languages }}</p>
                            </div>
                        </div>
                    @endif
                
                    @if(!empty($user->marital_status))
                        <div class="contact_info_flex">
                            <i class="fa-solid fa-person-circle-question"></i>
                            <div class="contact_name_info">
                                <label for="marital_status" class="contact_heading">Marital Status</label>
                                <p>{{ $user->marital_status }}</p>
                            </div>
                        </div>
                    @endif
                
                </div>
                
            </div>
        </div>
    </div>

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
                        <input type="hidden" name="receiver_id" id="receiver_id" value="{{ $user->id }}">
                        <!-- Receiver ID will be set dynamically -->

                        <div class="mb-3">
                            <label for="messageContent" class="form-label">Your Message</label>
                            <textarea class="form-control" id="messageContent" name="content" rows="4" required>Hi {{ $user->first_name ?? '' }} {{ $user->last_name ?? '' }}, 
I came across your profile and was really impressed by your work. I’d love to connect and exchange ideas.

Looking forward to connecting! 

Best Regards,
{{ Auth::user()->first_name }} {{ Auth::user()->last_name }}</textarea>
                        </div>

                        <button type="submit" class="btn btn-primary w-100 ">Send Message</button>
                    </form>
                    <div id="messageStatus" class="mt-3 text-center"></div> <!-- Status Message -->
                </div>
            </div>
        </div>
    </div>
@endsection


@section('scripts')
    <script>
        $(document).ready(function() {
            // Check if conversation exists before opening modal
            $('.direct-message-btn').on('click', function() {
                const receiverId = $(this).data('receiver-id');

                // Check if conversation exists
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
                            $('#receiver_id').val(receiverId);
                            $('#mainModal').modal('show');
                        }
                    },
                    error: function(xhr) {
                        console.error('Error checking conversation:', xhr);
                    }
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
@endsection
