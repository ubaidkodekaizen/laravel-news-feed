<!-- resources/views/user-Services.blade.php -->

@extends('layouts.dashboard-layout')

@section('dashboard-content')
    <div class="row">
        <div class="col-12">
            <div class="section_heading_flex">
                <h2>Qualifications</h2>
                <a href="{{ Route('user.add.qualifications') }}" class="btn btn-primary">Add Qualifications</a>
            </div>
            <div class="table-resposive data_table_user">
                <table id="qualifications-table" class="display" style="width:100%">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name of College/University Attended</th>
                            <th>Degree/Diploma</th>
                            <th>Year Graduated</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($educations as $education)
                            <tr>
                                <td>{{ $education->id }}</td>
                                <td>{{ $education->college_university }}</td>
                                <td>{{ $education->degree_diploma }}</td>
                                <td>{{ $education->year }}</td>
                                <td class="btn_flex">
                                    <a href="{{ route('user.edit.qualifications', $education->id) }}"
                                        class="btn btn-primary">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </a>
                                    <form action="{{ route('user.delete.qualifications', $education->id) }}" method="POST" class="mb-0">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger"
                                            onclick="return confirm('Are you sure?');">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center">No education records found.</td>
                            </tr>
                        @endforelse

                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script>
        jQuery(document).ready(function($) {
            $('#qualifications-table').DataTable();
        });
    </script>
@endsection
