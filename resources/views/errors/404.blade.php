@extends('frontend.master')
@section('content')
<!-- start wpo-page-title -->
<section class="wpo-page-title">
    <h2 class="d-none">Hide</h2>
    <div class="container">
        <div class="row">
            <div class="col col-xs-12">
                <div class="wpo-breadcumb-wrap">
                    <ol class="wpo-breadcumb-wrap">
                        <li><a href="{{route('index')}}">Home</a></li>
                        <li>Error</li>
                    </ol>
                </div>
            </div>
        </div> <!-- end row -->
    </div> <!-- end container -->
</section>
<!-- end page-title -->

<!-- start error-404-section -->
<section class="error-404-section section-padding">
    <div class="container">
        <div class="row">
            <div class="col col-xs-12">
                <div class="content clearfix">
                    <div class="error">
                        <img src="{{asset('frontend/images')}}/error-404.png" alt>
                    </div>
                    <div class="error-message">
                        <h3>Oops! Page Not Found!</h3>
                        <p>We’re sorry but we can’t seem to find the page you requested. This might be because
                            the link is no longer valid.</p>
                        <a href="{{route('index')}}" class="theme-btn">Back to home</a>
                    </div>
                </div>
            </div>
        </div> <!-- end row -->
    </div> <!-- end container -->
</section>
<!-- end error-404-section -->
@endsection