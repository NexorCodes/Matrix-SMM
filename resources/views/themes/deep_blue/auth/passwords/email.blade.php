@extends(template().'layouts.app')
@section('title', trans($pageSeo['page_title'] ?? 'Forgot Password'))


@section('content')
    <!-- Reset -->
    <section class="login-section">
        <div class="container">
            <div class="row g-lg-0 gy-5 align-items-center">

                <div class="col-lg-6">
                    <div class="text-box">
                        <h4>@lang(@$forgotPasswordContent->description->title)</h4>
                        <p>{!! __(@$forgotPasswordContent->description->description) !!}</p>
                    </div>
                </div>


                <div class="col-lg-6">
                    @if (session('status'))
                        <div class="alert alert-success alert-dismissible fade show w-100" role="alert">
                            {{ session('status') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('user.password.email') }}" class="form-content w-100">
                        @csrf
                        <div class="row g-3">
                            <div class="col-12">
                                <h4>@lang('Reset Password')</h4>
                            </div>

                            <div class="input-box col-12">
                                <input class="form-control" type="email" name="email" value="{{old('email')}}"
                                       placeholder="@lang('Enter Your Email Address')">
                                @error('email')
                                <p class="text-danger mt-1">@lang($message)</p>
                                @enderror
                            </div>
                        </div>

                        <button type="submit" class="btn-smm mt-4">@lang('Send Password Reset Link')</button>
                        <div class="bottom">
                            @lang('Don\'t have account?') <br/>
                            <a href="{{ route('register') }}">@lang('Register here')</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
    <!-- /Reset -->

    @include(template(). 'sections.footer')
@endsection
