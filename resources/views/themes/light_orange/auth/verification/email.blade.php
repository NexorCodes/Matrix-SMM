@extends(template().'layouts.app')
@section('title', trans($pageSeo['page_title'] ?? 'Email Verify'))

@section('content')
    <style>
        .login_signup_page{
            background: none !important;
        }
    </style>

    <!-- email verification -->
    <section class="login_signup_page">
        <div class="container">
            <div class="row g-5">
                <div class="col-lg-6 offset-3">
                    <div class="login_signup_form p-4">
                        <form method="POST" action="{{route('user.mail.verify')}}">
                            @csrf
                            <div class="form_title pb-2">
                                <h4>{{ trans($pageSeo['page_title'] ?? 'Email Verify') }}</h4>
                            </div>
                            <div class="mb-4">
                                <input class="form-control" type="text" name="code" value="{{old('code')}}" placeholder="@lang('Code')" autocomplete="off">
                                @error('code')
                                <p class="text-danger mt-1">{{ trans($message) }}</p>
                                @enderror
                                @error('error')
                                <p class="text-danger mt-1">{{ trans($message) }}</p>
                                @enderror
                            </div>

                            <button type="submit" class="btn custom_btn mt-30 w-100">@lang('Submit')</button>
                            <div class="pt-20 text-center">
                                @lang("Didn't get Code?")
                                <p class="mb-0 highlight"><a href="{{route('user.resend.code')}}?type=email">@lang('Resend code')</a></p>
                                @error('resend')
                                <p class="text-danger mt-1">{{ trans($message) }}</p>
                                @enderror
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- email verification -->

    @include(template(). 'sections.footer')
@endsection
