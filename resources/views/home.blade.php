@extends('layout')

@section('content') 
<div class="container-fluid">
    <div class="row">
        <!-- Text Content -->
        <div class="col-lg-6 d-flex align-items-center justify-content-center">
            <div class="text-content">
                <div class="h2">{{ __('messages.guide_text') }}</div>
                {{ __('messages.problem_contact') }}
            </div>
        </div>
        <!-- Image -->
        <div class="col-lg-6">
            <img src="images/hero-img.PNG" class="main-image" alt="{{ __('messages.guide_text') }}">
        </div>
    </div>
</div>

<!-- About Section -->
<section class="about-section py-5" id="about" >
    <div class="container">
        <!-- About Title -->
        <div class="row">
            <div class="col-12 text-center mb-4">
                <h2 class="about-title">{{ __('messages.about_us') }}</h2>
            </div>
        </div>

       <!-- Boxes -->
<div class="row">
    <div class="col-md-3 mb-4">
        <div class="about-box">
            <div class="box-content">
                {{ __('messages.welcome_portal') }}
            </div>
            <div class="color-corner"></div>
        </div>
    </div>
    <div class="col-md-3 mb-4">
        <div class="about-box">
            <div class="box-content">
                {{ __('messages.life_happens') }}
            </div>
            <div class="color-corner"></div>
        </div>
    </div>
    <div class="col-md-3 mb-4">
        <div class="about-box">
            <div class="box-content">
                {{ __('messages.hassle_free_process') }}
            </div>
            <div class="color-corner"></div>
        </div>
    </div>
    <div class="col-md-3 mb-4">
        <div class="about-box">
            <div class="box-content">
                {{ __('messages.top_priorities') }}
            </div>
            <div  class="color-corner"></div>
        </div>
    </div>
</div>

    </div>
</section>

@endsection
