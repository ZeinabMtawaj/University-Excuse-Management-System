@extends('users.dashboard')

@section('crud')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    @lang('messages.add_new_course_apology_excuse')
                    <a href="{{ url()->previous() }}" class="btn btn-secondary">@lang('messages.back')</a>
                </div>
                <div class="card-body">

<form method="POST" action="{{ route('courseApologyExcuses.store') }}" enctype="multipart/form-data">
    @csrf <!-- CSRF token for security -->

    <!-- Course Selection Dropdown -->
    <div class="form-group row">
        <label for="course_id" class="col-md-2 col-form-label text-md-right">@lang('messages.course')</label>
        <div class="col-md-10">
            <select class="form-control @error('course_id') is-invalid @enderror" id="course_id" name="course_id" required>
                <option value="">@lang('messages.select_course')</option>
                @foreach($courses as $course)
                    <option value="{{ $course->id }}">{{ $course->name }}</option>
                @endforeach
            </select>
            @error('course_id')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
    </div>

    <div class="form-group row">
        <label for="file" class="col-md-2 col-form-label text-md-right">@lang('messages.file')</label>
        <div class="col-md-10">
            <div class="custom-file">
                <input type="file" class="custom-file-input @error('file') is-invalid @enderror" id="file" name="file" required>
                <label class="custom-file-label" for="file">@lang('messages.choose_file')</label>
                @error('file')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
        </div>
    </div>

    <div class="form-group row">
        <label for="date" class="col-md-2 col-form-label text-md-right">@lang('messages.date')</label>
        <div class="col-md-10">
            <input type="date" class="form-control @error('date') is-invalid @enderror" id="date" name="date" required>
            @error('date')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
    </div>

    <!-- Submit Button -->
    <div class="form-group row">
        <div class="col-md-10 offset-md-2">
            <button type="submit" class="btn btn-primary">@lang('messages.submit')</button>
        </div>
    </div>
</form>

</div>
</div>
</div>
</div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        var fileInputs = document.querySelectorAll('.custom-file-input');
    
        Array.prototype.forEach.call(fileInputs, function (input) {
            input.addEventListener('change', function (event) {
                var input = event.target;
                var label = input.nextElementSibling;
                var fileName = input.files[0].name;
                label.textContent = fileName;
            });
        });
    });
</script>

@endsection
