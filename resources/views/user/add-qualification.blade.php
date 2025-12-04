@extends('layouts.dashboard-layout')

@section('dashboard-content')
    <div class="qualifications">
        <div class="section_heading_flex">
            <h2>Add Qualifications</h2>
        </div>
        <div class="add_form">
            <form action="{{ route('user.store.qualifications', $education->id ?? '') }}" method="POST"
                enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="col-lg-12 mb-3">
                        <label for="college_name" class="form-label">Name of College/University Attended:</label>
                        <input type="text" name="college_name" id="college_name" class="form-control"
                            value="{{ old('college_name', $education->college_university ?? '') }}" required>
                    </div>

                    <div class="col-lg-12 mb-3">
                        <label for="degree" class="form-label">Degree/Diploma:</label>
                        <input type="text" name="degree" id="degree" class="form-control"
                            value="{{ old('degree', $education->degree_diploma ?? '') }}" required>
                    </div>

                    <div class="col-lg-12 mb-3">
                        <label for="year_graduated" class="form-label">Year Graduated:</label>
                        <input type="text" name="year_graduated" id="year_graduated" class="form-control"
                            value="{{ old('year_graduated', $education->year ?? '') }}" required>
                    </div>
                    <div class="col-12">
                        <button class="btn btn-primary" type="submit">Submit</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
