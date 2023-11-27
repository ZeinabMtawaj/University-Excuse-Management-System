@extends('layout')

@section('content')

<div class="container mt-5 mb-3">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-lg">
                <div class="card-header" style="color:#4a4a4a">
                    <h3 class="mb-0">@lang('messages.edit_profile')</h3>
                </div>
                <div class="card-body">
                    <form class="mt-3" method="post" action="{{ route('user.profile.update') }}">
                        @csrf
                        @method('PUT') <!-- Assuming you're using RESTful controllers -->

                        <!-- Name -->
                        <div class="form-group">
                            <label for="profileName">@lang('messages.name')</label>
                            <input type="text" class="form-control" id="profileName" name="name" required value="{{ old('name', $user->name) }}">
                            @error('name')
                                <p class="text-danger small mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Username -->
                        <div class="form-group">
                            <label for="profileUsername">@lang('messages.username')</label>
                            <input type="text" class="form-control" id="profileUsername" name="username" required value="{{ old('username', $user->username) }}">
                            @error('username')
                                <p class="text-danger small mt-1">{{ $message }}</p>
                            @enderror                
                        </div>

                        <!-- Academic Number (if this is editable) -->
                        <div class="form-group">
                            <label for="profileAcademicNumber">@lang('messages.academic_number')</label>
                            <input type="number" class="form-control" id="profileAcademicNumber" name="academic_number" value="{{ old('academic_number', $user->academic_number) }}">
                            @error('academic_number')
                                <p class="text-danger small mt-1">{{ $message }}</p>
                            @enderror 
                        </div>

                        <!-- Faculty (if this is editable) -->
                        <div class="form-group">
                            <label for="profileFaculty">@lang('messages.faculty')</label>
                            <select class="form-control" id="profileFaculty" name="faculty_id">
                                <option value="">@lang('messages.select_faculty')</option>
                                @foreach($faculties as $faculty)
                                    <option value="{{ $faculty->id }}" {{ (old('faculty_id', $user->faculty_id) == $faculty->id) ? 'selected' : '' }}>{{ $faculty->name }}</option>
                                @endforeach
                            </select>
                            @error('faculty_id')
                                <p class="text-danger small mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Password -->
                        <div class="form-group">
                            <label for="profilePassword">@lang('messages.new_password')</label>
                            <input type="password" class="form-control" id="profilePassword" name="password" minlength="8">
                            @error('password')
                                <p class="text-danger small mt-1">{{ $message }}</p>
                            @enderror 
                        </div>

                        <!-- Confirm Password -->
                        <div class="form-group">
                            <label for="profilePasswordConfirmation">@lang('messages.confirm_password')</label>
                            <input type="password" class="form-control" id="profilePasswordConfirmation" name="password_confirmation" minlength="8">
                        </div>

                        <!-- Update Button -->
                        <button type="submit" class="btn btn-primary mb-5">@lang('messages.update_profile')</button>
                    </form>
                </div>
            </div>
        </div>

    </div>
</div>

@endsection
