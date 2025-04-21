@extends(template().'layouts.error')
@section('title','500')
@section('content')
    <section class="error-section">
        <div class="container">
            <div class="row g-5 align-items-center">
                <div class="col-sm-6">
                    <div class="error-thum">
                        <img src="{{ asset(template(true). 'img/error/error.png') }}" alt="">
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="error-content">
                        <div class="error-title">500</div>
                        <div class="error-info">@lang("The server encountered an internal error misconfiguration and was unable to complete your request. Please contact the server administrator.")</div>
                        <div class="btn-area">
                            <a href="{{ url('/') }}" class="cmn-btn">@lang("Back to Homepage")</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
