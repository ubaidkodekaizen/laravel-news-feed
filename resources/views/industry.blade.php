@extends('layouts.main')
@section('content')
    <section class="industry_specialist">
        <div class="container">
            <div class="industry-heading text-center mb-5">
                <span class="subHeading">Technology</span>
                <h1>Industry <span>Experts</span></h1>
                <p>Neque porro quisquam est qui dolorem ipsum quia dolor sit amet, consectetur, adipisci velit.</p>
            </div>
        </div>

        <div class="container" id="industryContainer">
            <div class="row g-4">

                @if ($users->isEmpty())
                    <p>No experts found for {{ $industry }}.</p>
                @else
                    @foreach ($users as $user)
                        <div class="col-md-3">
                            <div class="industryProfileCard">
                                <!-- Profile Picture -->

                                <div class="industryProfileMainImg text-center">
                                    <img src="{{ $user->photo ? asset('storage/' . $user->photo) : 'https://placehold.co/150' }}"
                                        alt="{{ $user->first_name }}'s Profile Picture" class="industryProfileImg">
                                    <div class="industryProfileMainImgGradientBg"></div>
                                </div>

                                <!-- Profile Details -->
                                <div class="industryProfileDetails">
                                    <h4 class="industryProfileName">{{ $user->first_name }} {{ $user->last_name }}</h4>
                                    <p class="industryProfileRole">{{ $user->company->company_position ?? 'N/A' }}</p>
                                    <p class="industryProfileCountry"><i class="fas fa-map-marker-alt"></i>
                                        {{ $user->country ?? 'N/A' }}</p>
                                    <div class="d-flex gap-1">
                                        <p class="industryProfileCategory">{{ $user->company->company_industry ?? 'N/A' }}
                                        </p>


                                        <!-- Action Buttons -->
                                        <div class="industryProfileCta">
                                            <a href="{{ route('user.profile', ['slug' => $user->slug]) }}"
                                                class="btn btn-outline-primary btn-sm" title="View Profile">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            {{-- <a href="javascript:void(0)" class="btn btn-outline-success btn-sm" title="Message">
                                        <i class="fas fa-envelope"></i>
                                    </a> --}}
                                            <a href="https://www.linkedin.com/in/{{ $user->linkedin_url ?? '' }}"
                                                class="btn btn-outline-info btn-sm" title="LinkedIn" target="_blank">
                                                <i class="fab fa-linkedin"></i>
                                            </a>
                                        </div>
                                    </div>
                                    <p class="industryProfileMemberSince">Member since:
                                        {{ $user->created_at->format('M Y') }}
                                    </p>
                                </div>


                            </div>
                        </div>
                    @endforeach
                @endif
            </div>
            <div class="RecordAndPagination">
                <div class="top-controls">
                    <label for="recordsPerPage">Records per page</label>
                    <div class="select-wrapper">
                        <select id="recordsPerPage">
                            <option value="8" selected>8</option>
                            <option value="12">12</option>
                            <option value="24">24</option>
                        </select>
                        <svg class="dropdown-icon" width="14" height="14" viewBox="0 0 24 24" fill="none">
                            <path d="M7 10l5 5 5-5" stroke="#555" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" />
                        </svg>
                    </div>
                    <span class="record-info" id="recordInfo"></span>
                </div>

                <div class="pagination" id="pagination"></div>
            </div>
        </div>

        {{-- dynamic section  --}}



        {{-- <div class="container">
            <div class="row g-4">

                @if ($users->isEmpty())
                    <p>No experts found for {{ $industry }}.</p>
                @else
                    @foreach ($users as $user)
                        <div class="col-md-3">
                            <div class="industry-profile-card">
                                <!-- Profile Picture -->

                                <div class="profile-pic text-center">
                                    <img src="{{ $user->photo ? asset('storage/' . $user->photo) : 'https://placehold.co/150' }}"
                                        alt="{{ $user->first_name }}'s Profile Picture" class="img-fluid rounded-circle">
                                </div>

                                <!-- Profile Details -->
                                <div class="profile_details text-center mt-3">
                                    <h4 class="mb-1">{{ $user->first_name }} {{ $user->last_name }}</h4>
                                    <p class="text-muted mb-1">{{ $user->company->company_position ?? 'N/A' }}</p>
                                    <p class="text-muted mb-1"><i class="fas fa-map-marker-alt"></i>
                                        {{ $user->country ?? 'N/A' }}</p>
                                    <p class="text-muted mb-1">{{ $user->company->company_industry ?? 'N/A' }}</p>
                                    <p class="text-muted mb-3">Member since: {{ $user->created_at->format('M Y') }}
                                    </p>
                                </div>

                                <!-- Action Buttons -->
                                <div class="action-buttons d-flex justify-content-center gap-3">
                                    <a href="{{ route('user.profile', ['slug' => $user->slug]) }}"
                                        class="btn btn-outline-primary btn-sm" title="View Profile">
                                        <i class="fas fa-eye"></i>
                                    </a> --}}
        {{-- <a href="javascript:void(0)" class="btn btn-outline-success btn-sm" title="Message">
                                        <i class="fas fa-envelope"></i>
                                    </a> --}}
        {{-- <a href="https://www.linkedin.com/in/{{ $user->linkedin_url ?? '' }}"
                                        class="btn btn-outline-info btn-sm" title="LinkedIn" target="_blank">
                                        <i class="fab fa-linkedin"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>
        </div> --}}
    </section>

    <div id="footer">
             <p>© 2025 – Powered By AMCOB LLC. All Rights Reserved.</p>
         </div>

    <!-- <section class="lp_footer">
        <div class="container">
            <div class="row">
                <div class="col">
                    <h3>STAYS</h3>
                    <ul class="footer_list">
                        <li>
                            <a href="javascript:void(0);">
                                Hotels
                            </a>
                        </li>
                        <li>
                            <a href="javascript:void(0);">
                                Resorts
                            </a>
                        </li>
                        <li>
                            <a href="javascript:void(0);">
                                Villas
                            </a>
                        </li>
                        <li>
                            <a href="javascript:void(0);">
                                Farm Stays
                            </a>
                        </li>
                        <li>
                            <a href="javascript:void(0);">
                                Appartments
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="col">
                    <h3>ABOUT US</h3>
                    <ul class="footer_list">
                        <li>
                            <a href="javascript:void(0);">
                                Our team
                            </a>
                        </li>
                        <li>
                            <a href="javascript:void(0);">
                                Our branches
                            </a>
                        </li>
                        <li>
                            <a href="javascript:void(0);">
                                Join us
                            </a>
                        </li>
                        <li>
                            <a href="javascript:void(0);">
                                For a sustainable world
                            </a>
                        </li>
                        <li>
                            <a href="javascript:void(0);">
                                Campaigns
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="col">
                    <h3>SERVICES</h3>
                    <ul class="footer_list">
                        <li>
                            <a href="javascript:void(0);">
                                Holidays stays
                            </a>
                        </li>
                        <li>
                            <a href="javascript:void(0);">
                                Conferences
                            </a>
                        </li>
                        <li>
                            <a href="javascript:void(0);">
                                Conventions
                            </a>
                        </li>
                        <li>
                            <a href="javascript:void(0);">
                                Presentations
                            </a>
                        </li>
                        <li>
                            <a href="javascript:void(0);">
                                Team building
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="col">
                    <h3>POLICY</h3>
                    <ul class="footer_list">
                        <li>
                            <a href="javascript:void(0);">
                                Terms and conditions
                            </a>
                        </li>
                        <li>
                            <a href="javascript:void(0);">
                                Privacy
                            </a>
                        </li>
                        <li>
                            <a href="javascript:void(0);">
                                Cookies
                            </a>
                        </li>
                        <li>
                            <a href="javascript:void(0);">
                                Legal information
                            </a>
                        </li>
                        <li>
                            <a href="javascript:void(0);">
                                Sustainablility
                            </a>
                        </li>
                        <li>
                            <a href="javascript:void(0);">
                                Safety Resources Center
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
            <p class="powered_by">
                Powered By <a href="https://amcob.org/" target="_blank" rel="noopener noreferrer">AMCOB</a>
            </p>
        </div>
    </section> -->




    <script>
        // ==== CONFIG ====
        const recordsPerPageSelect = document.getElementById("recordsPerPage");
        const pagination = document.getElementById("pagination");
        const recordInfo = document.getElementById("recordInfo");
        const allCards = Array.from(document.querySelectorAll(".industryProfileCard"));
        const cardsContainer = document.querySelector(".row.g-4.flex");

        let currentPage = 1;
        let recordsPerPage = parseInt(recordsPerPageSelect.value);

        // ==== INITIAL SETUP ====
        function renderCards() {
            const start = (currentPage - 1) * recordsPerPage;
            const end = start + recordsPerPage;

            // Hide all cards
            allCards.forEach(card => card.parentElement.style.display = "none");

            // Show current range
            const visibleCards = allCards.slice(start, end);
            visibleCards.forEach(card => card.parentElement.style.display = "block");

            // ✅ Updated Record Info Format (e.g., "8 of 140 records")
            const shownCount = Math.min(end, allCards.length);
            recordInfo.textContent = `${shownCount} of ${allCards.length} records`;

            // Render pagination
            renderPagination();
        }

        // ==== PAGINATION ====
        function renderPagination() {
            pagination.innerHTML = "";
            const totalPages = Math.ceil(allCards.length / recordsPerPage);

            const createButton = (page, text = page, disabled = false, active = false) => {
                const btn = document.createElement("button");
                btn.textContent = text;
                btn.disabled = disabled;
                btn.className = "page-btn";
                if (active) btn.classList.add("active");
                btn.addEventListener("click", () => {
                    currentPage = page;
                    renderCards();
                });
                return btn;
            };

            const addEllipsis = () => {
                const span = document.createElement("span");
                span.textContent = "...";
                span.className = "ellipsis";
                pagination.appendChild(span);
            };

            // Prev Button
            pagination.appendChild(createButton(currentPage - 1, "Prev", currentPage === 1));

            // Page Buttons with Ellipsis
            const maxVisible = 5;
            let startPage = Math.max(1, currentPage - 2);
            let endPage = Math.min(totalPages, startPage + maxVisible - 1);

            if (endPage - startPage < maxVisible - 1) {
                startPage = Math.max(1, endPage - maxVisible + 1);
            }

            if (startPage > 1) {
                pagination.appendChild(createButton(1));
                if (startPage > 2) addEllipsis();
            }

            for (let i = startPage; i <= endPage; i++) {
                pagination.appendChild(createButton(i, i, false, i === currentPage));
            }

            if (endPage < totalPages) {
                if (endPage < totalPages - 1) addEllipsis();
                pagination.appendChild(createButton(totalPages));
            }

            // Next Button
            pagination.appendChild(createButton(currentPage + 1, "Next", currentPage === totalPages));
        }

        // ==== EVENT LISTENERS ====
        recordsPerPageSelect.addEventListener("change", () => {
            recordsPerPage = parseInt(recordsPerPageSelect.value);
            currentPage = 1;
            renderCards();
        });

        // ==== INITIAL CALL ====
        renderCards();
    </script>
@endsection
