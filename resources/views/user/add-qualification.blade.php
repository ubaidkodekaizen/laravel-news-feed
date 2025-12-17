@extends('layouts.dashboard-layout')

<style>
    .form-label,
    .col-lg-12 label {
        margin-bottom: .5rem;
        font-family: "inter";
        font-weight: 400;
        font-size: 18px;
    }

    .add_form .form-control,
    .add_form .form-select {
        font-family: "inter";
        font-weight: 400 !important;
        font-size: 18px !important;
        padding: 14.5px .75rem !important;
        background-color: #F6F7FC !important;
        border: 2px solid #E9EBF0 !important;
        border-radius: 9.77px !important;
    }

    .add_form button.btn.btn-primary {
        font-family: "poppins";
        font-weight: 500;
        font-size: 18px;
        padding: 15px 66px;
        border-radius: 9.77px;
    }
    
</style>

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
