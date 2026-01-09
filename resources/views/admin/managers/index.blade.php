@extends('admin.layouts.main')
<style>
@import url('https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap');
</style>
<style>
    body{
        background: #fafbff !important;
    }
    .card-header:first-child {
        background: #fafbff !important;
        border: none;
    }
    .card-body {
        background: #fafbff !important;
    }
    .card-title {
        border-radius: 0;
        color: #333 !important;
        margin: 0;
        font-size: 28px;
        font-family: "Inter";
        font-weight: 500;
    }
    .card-header.card_header_flex {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .card-header.card_header_flex a{
        background: var(--primary);
        border-color: var(--primary);
        font-family: "poppins";
        font-weight: 300;
        padding: 16px 30px;
        border-radius: 10.66px;
        color: white;
        text-decoration: none;
    }
</style>
@section('content')
<main class="main-content">
    @php
        $filter = $filter ?? 'all';
        $counts = $counts ?? [];
        $user = Auth::user();
        $isAdmin = $user && $user->role_id == 1;
        $canView = $isAdmin || ($user && $user->hasPermission('managers.view'));
        $canCreate = $isAdmin || ($user && $user->hasPermission('managers.create'));
        $canEdit = $isAdmin || ($user && $user->hasPermission('managers.edit'));
        $canDelete = $isAdmin || ($user && $user->hasPermission('managers.delete'));
        $canRestore = $isAdmin || ($user && $user->hasPermission('managers.restore'));
    @endphp
    @if(!$canView)
        @php
            abort(403, 'Unauthorized action.');
        @endphp
    @endif
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="card" style="border: none;">
                    <div class="card-header card_header_flex">
                        <h4 class="card-title">Managers/Editor</h4>
                        @if($canCreate && $filter !== 'deleted')
                            <a href="{{ route('admin.add.manager') }}" class="btn btn-primary">Add Manager/Editor</a>
                        @endif
                    </div>
                    <div class="card-body">
                        <!-- Tabs Navigation -->
                        <ul class="nav nav-tabs mb-4" id="managersTabs" role="tablist" style="border-bottom: 2px solid #E1E0E0;">
                            <li class="nav-item" role="presentation">
                                <a class="nav-link {{ $filter === 'all' ? 'active' : '' }}" 
                                   href="{{ route('admin.managers', ['filter' => 'all']) }}"
                                   style="color: #333; font-family: 'Inter'; font-weight: 500; padding: 12px 20px; border: none;">
                                    All <span class="badge bg-secondary">{{ $counts['all'] ?? 0 }}</span>
                                </a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a class="nav-link {{ $filter === 'manager' ? 'active' : '' }}" 
                                   href="{{ route('admin.managers', ['filter' => 'manager']) }}"
                                   style="color: #333; font-family: 'Inter'; font-weight: 500; padding: 12px 20px; border: none;">
                                    Manager <span class="badge bg-secondary">{{ $counts['manager'] ?? 0 }}</span>
                                </a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a class="nav-link {{ $filter === 'editor' ? 'active' : '' }}" 
                                   href="{{ route('admin.managers', ['filter' => 'editor']) }}"
                                   style="color: #333; font-family: 'Inter'; font-weight: 500; padding: 12px 20px; border: none;">
                                    Editor <span class="badge bg-secondary">{{ $counts['editor'] ?? 0 }}</span>
                                </a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a class="nav-link {{ $filter === 'deleted' ? 'active' : '' }}" 
                                   href="{{ route('admin.managers', ['filter' => 'deleted']) }}"
                                   style="color: #333; font-family: 'Inter'; font-weight: 500; padding: 12px 20px; border: none;">
                                    Deleted <span class="badge bg-danger">{{ $counts['deleted'] ?? 0 }}</span>
                                </a>
                            </li>
                        </ul>
                        <style>
                            .nav-tabs .nav-link {
                                border: none;
                                border-bottom: 3px solid transparent;
                                transition: all 0.3s ease;
                            }
                            .nav-tabs .nav-link:hover {
                                border-bottom-color: #37488E;
                                color: #37488E !important;
                            }
                            .nav-tabs .nav-link.active {
                                border-bottom-color: #37488E;
                                color: #37488E !important;
                                background-color: transparent;
                            }
                            .nav-tabs .badge {
                                margin-left: 5px;
                                font-size: 12px;
                                padding: 4px 8px;
                            }
                        </style>
                        @if(session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif
                        <table id="managersTable" class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Role</th>
                                    <th>First Name</th>
                                    <th>Last Name</th>
                                    <th>Email</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($managers as $manager)
                                <tr>
                                    <td>{{ $manager->id }}</td>
                                    <td>
                                        <span class="badge bg-{{ $manager->role_id == 2 ? 'primary' : 'info' }}">
                                            {{ $manager->role->name ?? 'N/A' }}
                                        </span>
                                    </td>
                                    <td>{{ $manager->first_name }}</td>
                                    <td>{{ $manager->last_name }}</td>
                                    <td>{{ $manager->email }}</td>
                                    <td>
                                        @if($filter === 'deleted')
                                            @if($canRestore)
                                            <form action="{{ route('admin.restore.manager', $manager->id) }}" method="POST"
                                                style="display:inline-block;" class="restore-manager-form">
                                                @csrf
                                                <button type="submit" class="btn btn-success btn-sm">Restore</button>
                                            </form>
                                            @endif
                                        @else
                                            @if($canEdit)
                                            <a href="{{ route('admin.edit.manager', $manager->id) }}" class="btn btn-warning btn-sm">Edit</a>
                                            @endif
                                            @if($canDelete)
                                            <form action="{{ route('admin.delete.manager', $manager->id) }}" method="POST"
                                                style="display:inline-block;" class="delete-manager-form">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                            </form>
                                            @endif
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center">No Managers/Editors found</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection

@section('scripts')
<script src="https://code.jquery.com/jquery-3.7.0.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script>
    $(document).ready(function() {
        $('#managersTable').DataTable();
        
        // Delete confirmation with SweetAlert
        $('.delete-manager-form').on('submit', function(e) {
            e.preventDefault();
            const form = $(this);
            
            Swal.fire({
                title: 'Are you sure?',
                text: 'You want to delete this Manager/Editor? This action cannot be undone!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.off('submit').submit();
                }
            });
        });

        // Restore confirmation with SweetAlert
        $('.restore-manager-form').on('submit', function(e) {
            e.preventDefault();
            const form = $(this);
            
            Swal.fire({
                title: 'Restore Manager/Editor?',
                text: 'This Manager/Editor will be restored and become active again.',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#28a745',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, restore it!',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.off('submit').submit();
                }
            });
        });

        // Show success message if exists
        @if(session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: '{{ session('success') }}',
                showConfirmButton: false,
                timer: 3000
            });
        @endif
    });
</script>
@endsection
