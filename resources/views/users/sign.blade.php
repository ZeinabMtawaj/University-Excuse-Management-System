@extends('layout')

@section('content') 

<div class="container mt-5">
    <div class="row">
        <!-- Left Side - Tabs and Forms -->
        <div class="col-lg-6">
            <!-- Tabs Navigation -->
            <ul class="nav nav-tabs" id="authTabs" role="tablist">
                <li class="nav-item">
                    <a class="nav-link {{ $activeTab == 'signin' ? 'active' : '' }}" id="signin-tab" data-toggle="tab" href="#signin" role="tab" aria-controls="signin" aria-selected="true">{{ __('messages.sign_in') }}</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ $activeTab == 'signup' ? 'active' : '' }}" id="signup-tab" data-toggle="tab" href="#signup" role="tab" aria-controls="signup" aria-selected="false">{{ __('messages.sign_up') }}</a>
                </li>
            </ul>

            <!-- Tabs Content -->
            <div class="tab-content" id="authTabsContent">
                <div class="tab-pane fade {{ $activeTab == 'signin' ? 'show active' : '' }}" id="signin" role="tabpanel" aria-labelledby="signin-tab">
                    <form class="mt-3" method="post" action="/users/authenticate">
                        @csrf
                        <!-- Academic Number (for Sign In) -->
                        <div class="form-group">
                            <label for="signinAcademicNumber">{{ __('messages.academic_number') }}</label>
                            <input type="number" class="form-control" id="signinAcademicNumber" name="academic_number" value="{{ old('academic_number') }}">
                            @error('password')
                                <p class="text-danger small mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="signinPassword">{{ __('messages.password') }}</label>
                            <input type="password" class="form-control" id="signinPassword" name="password" value="{{ old('password') }}">
                            @error('password')
                                <p class="text-danger small mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            @error('invalidCred')
                                <p class="text-danger small mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-primary">{{ __('messages.sign_in') }}</button>
                    </form>
                </div>

                <!-- Sign Up Form -->
                <div class="tab-pane fade {{ $activeTab == 'signup' ? 'show active' : '' }}" id="signup" role="tabpanel" aria-labelledby="signup-tab">
                    <form class="mt-3" action="/users" method="post">
                        @csrf
                        <!-- Name -->
                        <div class="form-group">
                            <label for="signupName">{{ __('messages.name') }}</label>
                            <input type="text" class="form-control" id="signupName" name="name" required pattern="^[a-zA-Z\s]+$" title="{{ __('messages.only_letters') }}" value="{{ old('name') }}">
                            @error('name')
                                <p class="text-danger small mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="signupUserName">{{ __('messages.username') }}</label>
                            <input type="text" class="form-control" id="signupUserName" name="username" required pattern="^[a-zA-Z\s]+$" title="{{ __('messages.only_letters') }}" value="{{ old('username') }}">
                            @error('username')
                                <p class="text-danger small mt-1">{{ $message }}</p>
                            @enderror                
                        </div>

                        <!-- Academic Number -->
                        <div class="form-group">
                            <label for="signupAcademicNumber">{{ __('messages.academic_number') }}</label>
                            <input type="number" class="form-control" id="signupAcademicNumber" name="academic_number" required min="1" max="99999999" value="{{ old('academic_number') }}">
                            @error('academic_number')
                                <p class="text-danger small mt-1">{{ $message }}</p>
                            @enderror 
                        </div>

                        <!-- Password -->
                        <div class="form-group">
                            <label for="signupPassword">{{ __('messages.password') }}</label>
                            <input type="password" class="form-control" id="signupPassword" name="password" required minlength="8" value="{{ old('password') }}">
                            @error('password')
                                <p class="text-danger small mt-1">{{ $message }}</p>
                            @enderror 
                        </div>

                        <!-- Confirm Password -->
                        <div class="form-group">
                            <label for="signupPasswordConfirm">{{ __('messages.confirm_password') }}</label>
                            <input type="password" class="form-control" id="signupPasswordConfirm" name="password_confirmation" required minlength="8" value="{{ old('password_confirmation') }}">
                            @error('password_confirmation')
                                <p class="text-danger small mt-1">{{ $message }}</p>
                            @enderror                 
                        </div>

                        <!-- Faculty (Assuming dropdown) -->
                        <div class="form-group">
                            <label for="signupFaculty">{{ __('messages.faculty') }}</label>
                            <select class="form-control" id="signupFaculty" name="faculty_id" required>
                                <option value="">{{ __('messages.select_faculty') }}</option>
                                @foreach($faculties as $faculty)
                                    <option value="{{ $faculty->id }}">{{ $faculty->name }}</option>
                                @endforeach
                            </select>
                            @error('faculty_id')
                                <p class="text-danger small mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <button type="submit" class="btn btn-primary mb-5">{{ __('messages.sign_up') }}</button>
                    </form>
                </div>
            </div>
         </div>

        <!-- Right Side - Image -->
        <div class="col-lg-6">
            <img src="{{ asset('images/sign.jpg') }}" alt="{{ __('messages.sign_image_alt') }}" class="img-fluid">
        </div>
    </div>
</div>

@endsection
