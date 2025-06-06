<!-- api-docs -->
<section class="api-docs">
    <!--API DETAILS-->
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="card api-details shadow">
                    <div class="card-header">
                        <h5 class="card-title">@lang('API DETAILS')</h5>
                    </div>
                    <div class="card-body content">
                        <div class="row">
                            <div class="col-sm-12">
                                <h6>@lang('API URL')</h6>
                                <p>{{ route('userApiKey') }}</p>
                            </div>
                            <div class="col-sm-12">
                                <h6>@lang('API KEY')</h6>
                                <p>@lang('Your API Key')</p>
                            </div>
                            <div class="col-sm-6 col-md-4">
                                <h6>@lang('HTTP METHOD')</h6>
                                <p>@lang('POST')</p>
                            </div>
                            <div class="col-sm-6 col-md-4">
                                <h6>@lang('RESPONSE FORMAT')</h6>
                                <p>@lang('JSON')</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!--PLACE NEW ORDER-->
    <div class="container mt-5">
        <div class="row">
            <div class="col-md-12">
                <div class="card api-details mb-0">
                    <div class="card-header">
                        <h5 class="card-title">@lang('PLACE NEW ORDER')</h5>
                    </div>
                    <div class="card-body content">
                        <div class="row">
                            <div class="col-sm-4">
                                <h6 class="text-lowercase">@lang('key')</h6>
                                <p>@lang('Your API key')</p>
                            </div>
                            <div class="col-sm-4">
                                <h6 class="text-lowercase">@lang('action')</h6>
                                <p>@lang('add')</p>
                            </div>
                            <div class="col-sm-4">
                                <h6 class="text-lowercase">@lang('service')</h6>
                                <p>@lang('Service ID')</p>
                            </div>
                            <div class="col-sm-4">
                                <h6 class="text-lowercase">@lang('link')</h6>
                                <p>@lang('Link to page')</p>
                            </div>

                            <div class="col-sm-4">
                                <h6 class="text-lowercase">@lang('quantity')</h6>
                                <p>@lang('Needed quantity')</p>
                            </div>

                            <div class="col-sm-4">
                                <h6 class="text-lowercase">
                                    @lang('runs')
                                    <small class="text-muted">@lang('(optional)')</small>
                                </h6>
                                <p>@lang('Runs to deliver')</p>
                            </div>

                            <div class="col-sm-4">
                                <h6 class="text-lowercase">
                                    @lang('interval')
                                    <small class="text-muted">@lang('(optional)')</small>
                                </h6>
                                <p>@lang('Interval in minutes')</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="api-code my-5">
                    <p class="text-success">//Example response</p>
                    <pre class="text-white">
{
    "status": "success",
    "order": 116
}
</pre
>
                </div>
            </div>
        </div>
    </div>

    <!--STATUS ORDER-->
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="card api-details mb-0">
                    <div class="card-header">
                        <h5 class="card-title">@lang('STATUS ORDER')</h5>
                    </div>
                    <div class="card-body content">
                        <div class="row">
                            <div class="col-sm-4">
                                <h6 class="text-lowercase">@lang('key')</h6>
                                <p>@lang('Your API key')</p>
                            </div>
                            <div class="col-sm-4">
                                <h6 class="text-lowercase">@lang('action')</h6>
                                <p>@lang('status')</p>
                            </div>
                            <div class="col-sm-4">
                                <h6 class="text-lowercase">@lang('order')</h6>
                                <p>@lang('Order ID')</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="api-code my-5">
                    <p class="text-success">//Example response</p>
                    <pre class="text-white">
{
    "status": "Processing",
    "charge": "3.60",
    "start_count": 0,
    "remains": 0,
    "currency": "BDT"
}
</pre>
                </div>
            </div>
        </div>
    </div>

    <!--MULTIPLE STATUS ORDER-->
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="card api-details mb-0">
                    <div class="card-header">
                        <h5 class="card-title">@lang('MULTIPLE STATUS ORDER')</h5>
                    </div>
                    <div class="card-body content">
                        <div class="row">
                            <div class="col-sm-4">
                                <h6 class="text-lowercase">@lang('key')</h6>
                                <p>@lang('Your API key')</p>
                            </div>
                            <div class="col-sm-4">
                                <h6 class="text-lowercase">@lang('action')</h6>
                                <p>@lang('orders')</p>
                            </div>
                            <div class="col-sm-4">
                                <h6 class="text-lowercase">@lang('orders')</h6>
                                <p>@lang('Order IDs separated by comma (array data)')</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="api-code my-5">
                    <p class="text-success">//Example response</p>
                    <pre class="text-white">
[
    {
        "order": 116,
        "status": "Processing",
        "charge": "3.60",
        "start_count": 10,
        "remains": 0
    },
    {
        "order": 117,
        "status": "Completed",
        "charge": null,
        "start_count": 0,
        "remains": 0
    }
]</pre>
                </div>
            </div>
        </div>
    </div>

    <!--CREATE REFILL-->
    <div class="container ">
        <div class="row">
            <div class="col-md-12">
                <div class="card api-details mb-0">
                    <h5 class="card-title text-white">@lang('PLACE REFILL')</h5>
                    <div class="card-body content">
                        <div class="row">
                            <div class="col-sm-3">
                                <h6 class="text-lowercase">@lang('key')</h6>
                                <p>@lang('Your API key')</p>
                            </div>
                            <div class="col-sm-3">
                                <h6 class="text-lowercase">@lang('action')</h6>
                                <p>
                                    @lang('refill')
                                </p>
                            </div>
                            <div class="col-sm-3">
                                <h6 class="text-lowercase">@lang('order')</h6>
                                <p>@lang('Order ID')</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="api-code mt-2 mb-5">
                    <p class="text-success">//Example response</p>
                    <pre class="text-white">
{
    "refill": "1"
}
</pre>
                </div>
            </div>
        </div>
    </div>

    <!--STATUS REFILL-->
    <div class="container ">
        <div class="row">
            <div class="col-md-12">
                <div class="card api-details mb-0">
                    <h5 class="card-title text-white">@lang('STATUS REFILL')</h5>
                    <div class="card-body content">
                        <div class="row">
                            <div class="col-sm-4">
                                <h6 class="text-lowercase">@lang('key')</h6>
                                <p>@lang('Your API key')</p>
                            </div>
                            <div class="col-sm-4">
                                <h6 class="text-lowercase">@lang('action')</h6>
                                <p>
                                    @lang('refill_status')
                                </p>
                            </div>
                            <div class="col-sm-4">
                                <h6 class="text-lowercase">@lang('refill')</h6>
                                <p>@lang('Refill ID')</p>
                            </div>


                        </div>
                    </div>
                </div>

                <div class="api-code mt-2 mb-5">
                    <p class="text-success">//Example response</p>
                    <pre class="text-white">
{
    "status": "Completed"
}
</pre>
                </div>

            </div>
        </div>
    </div>

    <!--SERVICE LIST-->
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="card api-details mb-0">
                    <div class="card-header">
                        <h5 class="card-title">@lang('SERVICE LIST')</h5>
                    </div>
                    <div class="card-body content">
                        <div class="row">
                            <div class="col-sm-4">
                                <h6 class="text-lowercase">@lang('key')</h6>
                                <p>@lang('Your API key')</p>
                            </div>
                            <div class="col-sm-4">
                                <h6 class="text-lowercase">@lang('action')</h6>
                                <p>@lang('services')</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="api-code my-5">
                    <p class="text-success">//Example response</p>
                    <pre class="text-white">
[
    {
        "service": 1,
        "name": "🙋&zwj;♂️ Followers [Ultra-High Quality Profiles]",
        "category": "🥇 [VIP]\r\n",
        "rate": "4.80",
        "min": 100,
        "max": 10000
    },
    {
        "service": 11,
        "name": "🧨 Instagram Power Comments (100k+ Accounts) ➡️ [3 Comments]",
        "category": "💬 Instagram - Verified / Power Comments [ Own Service ]",
        "rate": "0.60",
        "min": 500,
        "max": 5000
    },
    {
        "service": 52,
        "name": "🎙️ Facebook Live Stream Views ➡️ [ 120 Min ]",
        "category": "🔵 Facebook - Live Stream Views\r\n",
        "rate": "57.60",
        "min": 50,
        "max": 2000
    }
]</pre
                    >
                </div>
            </div>
        </div>
    </div>

    <!--USER BALANCE-->
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="card api-details mb-0">
                    <div class="card-header">
                        <h5 class="card-title">@lang('USER BALANCE')</h5>
                    </div>
                    <div class="card-body content">
                        <div class="row">
                            <div class="col-sm-4">
                                <h6 class="text-lowercase">@lang('key')</h6>
                                <p>@lang('Your API key')</p>
                            </div>
                            <div class="col-sm-4">
                                <h6 class="text-lowercase">@lang('action')</h6>
                                <p>@lang('balance')</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="api-code my-5">
                    <p class="text-success">//Example response</p>
                    <pre class="text-white">
{
  "status": "success",
  "balance": "0.03",
  "currency": "USD"
}
</pre
>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- /api-docs -->
