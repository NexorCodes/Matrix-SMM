@extends(template().'layouts.error')
@section('title','403 Forbidden')
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
                        <div class="error-title">403</div>
                        <div class="error-info">@lang("You don't have permission to access ‘/’ on")</div>
                        <div class="btn-area">
                            <a href="{{ url('/') }}" class="cmn-btn">@lang("Back to Homepage")</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
