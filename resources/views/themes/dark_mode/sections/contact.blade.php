<!-- CONTACT -->
<div class="contact-section">
    <div class="container">
        <div class="row gy-5 g-lg-5 align-items-center">
            <div class="col-lg-6">
                <div class="text-box">
                    <div class="header-text">
                        <h5>@lang(@$contact['single']['title'])</h5>
                        <h3>@lang(@$contact['single']['sub_heading'])</h3>
                        <p>  @lang(@$contact['single']['sub_title'])</p>
                    </div>
                    <div class="info-box">
                        <div class="icon-box">
                            <img src="{{ asset(template(true).'img/location.png') }}" alt="@lang('location image')"/>
                        </div>
                        <div class="text">
                            <h5>@lang('Address')</h5>
                            <p>{{@$contact['single']['address']}}</p>
                        </div>
                    </div>
                    <div class="info-box">
                        <div class="icon-box">
                            <img src="{{ asset(template(true).'img/phone.png') }}" alt="@lang('phone image')"/>
                        </div>
                        <div class="text">
                            <h5>@lang('Email')</h5>
                            <p>{{@$contact['single']['email']}}</p>
                        </div>
                    </div>
                    <div class="info-box">
                        <div class="icon-box">
                            <img src="{{ asset(template(true).'img/email.png') }}" alt="@lang('email image')"/>
                        </div>
                        <div class="text">
                            <h5>@lang('Phone')</h5>
                            <p>{{@$contact['single']['phone']}}</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <form action="{{route('contact.send')}}" method="post">
                    @csrf
                    <h4>@lang(@$contact['single']['heading'])</h4>
                    <div class="row g-3">
                        <div class="input-box col-md-6">
                            <input
                                class="form-control"
                                type="text"
                                name="name"
                                value="{{old('name')}}"
                                placeholder="@lang('Full Name')"
                            />
                            @error('name')
                            <span class="text-danger">@lang($message)</span>
                            @enderror
                        </div>
                        <div class="input-box col-md-6">
                            <input
                                class="form-control"
                                type="email"
                                name="email" value="{{old('email')}}"
                                placeholder="@lang('Email Address')"
                            />
                            @error('email')
                            <span class="text-danger">@lang($message)</span>
                            @enderror
                        </div>
                        <div class="input-box col-md-12">
                            <input
                                class="form-control"
                                type="text"
                                name="subject" value="{{old('subject')}}"
                                placeholder="@lang('Subject')"
                            />
                            @error('subject')
                            <span class="text-danger">@lang($message)</span>
                            @enderror
                        </div>
                        <div class="input-box col-12">
                                <textarea
                                    class="form-control"
                                    name="message"
                                    cols="30"
                                    rows="3"
                                    placeholder="@lang('Your message')"
                                >{{old('message')}}</textarea>
                            @error('message')
                            <span class="text-danger">@lang($message)</span>
                            @enderror
                        </div>
                        <div class="input-box col-12">
                            <button type="submit" class="btn-smm">{{trans('Send Message')}}</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- /CONTACT -->
