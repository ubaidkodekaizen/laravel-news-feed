@extends('layouts.main')


@section('content')
    <section class="industry_specialist">
        <div class="container">
            <div class="industry-heading text-center mb-5">
                <span class="subHeading">MuslimLynk</span>
                <h1>Smart <span>Suggestion</span></h1>
                <p>Neque porro quisquam est qui dolorem ipsum quia dolor sit amet, consectetur, adipisci velit.</p>
            </div>
            <div class="row g-3">

                @forelse($suggestions as $suggestion)
                    @php
                        $user = $suggestion['user'];
                        $company = $suggestion['company'];
                        $education = $user->userEducations->first(); // first education if exists
                    @endphp

                    <div class="col-md-4">
                        <div class="industry-profile-card">
                            <div class="profile-pic text-center">
                                <img id="UserProfileImg" src="{{ $user->photo ? asset('storage/' . $user->photo) : 'https://via.placeholder.com/150' }}"
                                    alt="{{ $user->first_name }} {{ $user->last_name }}'s Profile Picture">
                                {{-- <img id="UserProfileImg" src="assets/images/user/Rectangle 240648925.png" class="img-fluid"> --}}
                            </div>

                            <div class="profile_details mt-3">
                                <h4 class="mb-1 userProfileName">{{ $user->first_name }} {{ $user->last_name }}</h4>
                                <p class="mb-1 userProfileRole">{{ $company->company_position ?? 'N/A' }}</p>
                                <p class="mb-1 userProfileCountry">
                                    <i class="fas fa-map-marker-alt"></i>
                                    {{ $user->country ?? 'N/A' }}
                                </p>
                                <p class="mb-1 userProfileCategory">
                                    {{ $company->company_industry ?? 'N/A' }}
                                </p>

                                <div class="userInfo">
                                    <div>


                                        {{-- Match Score with hover tooltip --}}
                                        <p class="userProfileScore">
                                            Match Score: {{ $suggestion['score'] }}
                                        </p>

                                        <p class="mb-3 userProfileMemberSince">Member since:
                                            {{ $user->created_at->format('M Y') }}</p>
                                    </div>


                                    <div class="action-buttons d-flex justify-content-center gap-3">
                                        <a href="{{ url('user/profile/' . $user->slug) }}"
                                            class="btn btn-outline-primary btn-sm" title="View Profile">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        @if ($user->linkedin_url)
                                            <a href="{{ $user->linkedin_url }}" class="btn btn-outline-info btn-sm"
                                                title="LinkedIn" target="_blank">
                                                <i class="fab fa-linkedin"></i>
                                            </a>
                                        @endif
                                    </div>
                                </div>

                            </div>


                        </div>
                    </div>
                @empty
                    <div class="col-12 text-center">
                        <p>No smart suggestions available right now.</p>
                    </div>
                @endforelse

            </div>
            <div class="RecordAndPagination">
                <div class="top-controls">
                    <label for="recordsPerPage">Records per page</label>
                    <div class="select-wrapper">
                        <select id="recordsPerPage">
                            <option value="6" selected>6</option>
                            <option value="8">8</option>
                            <option value="10">10</option>
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
    </section>

    <!-- <section class="lp_footer">
        <div class="container">
            <p class="powered_by">
                Powered By <a href="https://amcob.org/" target="_blank" rel="noopener noreferrer">AMCOB</a>
            </p>
        </div>
    </section> -->
    <div id="footer">
             <p>© 2025 – Powered By AMCOB LLC. All Rights Reserved.</p>
         </div>


    <script>
        // ==== CONFIG ====
        const recordsPerPageSelect = document.getElementById("recordsPerPage");
        const pagination = document.getElementById("pagination");
        const recordInfo = document.getElementById("recordInfo");
        const allCards = Array.from(document.querySelectorAll(".industry-profile-card"));
        const cardsContainer = document.querySelector(".row.g-3");

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

    {{-- Enable Bootstrap tooltips --}}
    @push('scripts')
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
                var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
                    return new bootstrap.Tooltip(tooltipTriggerEl)
                })
            });
        </script>
    @endpush
@endsection
