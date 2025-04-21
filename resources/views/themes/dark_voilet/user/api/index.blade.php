@extends(template().'layouts.user')
@section('title',trans('API Docs'))
@section('content')
    <main id="main" class="main bg-color2">
        <div class="pagetitle">
            <h3 class="mb-1">@lang("api")</h3>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ url('/') }}">
                            @lang("Home")</a>
                    </li>
                    <li class="breadcrumb-item"><a href="{{ route('user.dashboard') }}">
                            @lang("Dashboard")</a>
                    </li>
                    <li class="breadcrumb-item active">@lang("api")</li>
                </ol>
            </nav>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card mb-50">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <h5 class="mb-0">@lang("API Key")</h5>
                        <button class="cmn-btn" data-bs-toggle="modal"
                                data-bs-target="#apiKeyGenerateModal">@lang("Generate Key")</button>
                    </div>
                    <div class="card-body p-sm-5">
                        <h6>@lang("API KEY")</h6>
                        <div class="input-group mb-3">
                            <input id="api_key" type="{{ session()->get('type') == 'text' ? 'text' : 'password' }}"
                                   class="form-control api_token"
                                   value="{{ $api_token ?? 'Generate Your API Key' }}"
                                   aria-label="@lang("Api Key")" aria-describedby="basic-addon2" readonly>
                            @if(session()->get('type'))
                                <div class="input-group-text" id="copyBtn">
                                    <i class="fa-light fa-copy"></i>@lang("Copy")</div>
                            @else
                                <div class="input-group-text" data-bs-toggle="modal"
                                     data-bs-target="#apiKeyViewModal">
                                    <i class="fa-light fa-eye"></i>@lang("View")</div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="card mb-50">
                    <div class="card-header">
                        <h5 class="mb-0">@lang("API Documentation")</h5>
                    </div>
                    <div class="card-body">
                        <small>@lang("Note: Please read the API intructions carefully. Its your solo responsability what
                            you add by
                            our API.")</small>
                        <div class="cmn-table mt-20">
                            <div class="table-responsive overflow-hidden">
                                <table class="table table-striped align-middle">
                                    <thead>
                                    <tr>
                                        <th scope="col">@lang("Parameter")</th>
                                        <th scope="col">@lang("Description")</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr>
                                        <td data-label="Parameter"><span>@lang("HTTP Method")</span></td>
                                        <td data-label="Description">
                                            <span>@lang("POST")</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td data-label="Parameter"><span>@lang("Response format")</span></td>
                                        <td data-label="Description">
                                            <span>@lang("Json")</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td data-label="Parameter"><span>@lang("API URL")</span></td>
                                        <td data-label="Description">
                                            <span class="text-break">{{ route('userApiKey') }}</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td data-label="Parameter"><span>@lang("API Key")</span></td>
                                        <td data-label="Description">
                                            <span class="text-break">fK6aGhCOXIxz0uRi7KbghLxAkP7z3DPJ</span>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card mb-50">
                    <div class="card-header">
                        <h5 class="mb-0">@lang("PLACE NEW ORDER")</h5>
                    </div>
                    <div class="card-body">
                        <div class="cmn-table">
                            <div class="table-responsive overflow-hidden">
                                <table class="table table-striped align-middle">
                                    <thead>
                                    <tr>
                                        <th scope="col">@lang("Parameter")</th>
                                        <th scope="col">@lang("Description")</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr>
                                        <td data-label="Parameter"><span>@lang("key")</span></td>
                                        <td data-label="Description">
                                            <span>@lang("Your API key")</span>
                                        </td>

                                    </tr>
                                    <tr>
                                        <td data-label="Parameter"><span>@lang("quantity")</span></td>
                                        <td data-label="Description">
                                            <span>@lang("Needed quantity")</span>
                                        </td>

                                    </tr>
                                    <tr>
                                        <td data-label="Parameter"><span>@lang("action")</span></td>
                                        <td data-label="Description">
                                            <span>@lang("add")</span>
                                        </td>

                                    </tr>
                                    <tr>
                                        <td data-label="Parameter"><span>@lang("runs") <small>(optional)</small></span>
                                        </td>
                                        <td data-label="Description">
                                            <span>@lang("Runs to deliver")</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td data-label="Parameter"><span>@lang("service")</span></td>
                                        <td data-label="Description">
                                            <span>@lang("Service ID")</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td data-label="Parameter"><span>@lang("interval")
                                                    <small>@lang("(optional)")</small></span></td>
                                        <td data-label="Description">
                                            <span>@lang("Interval in minutes")</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td data-label="Parameter"><span>@lang("link")</span></td>
                                        <td data-label="Description">
                                            <span>@lang("Link to page")</span>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="card-body">
                            <h5 class="mb-10">@lang("Example response:")</h5>
                            <div class="api-code-box">
                                <pre>
{
   "status": "success",
   "order": 116
}
                                </pre>
                                <div class="editor-dots">

                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card mb-50">
                    <div class="card-header">
                        <h5 class="mb-0">@lang("STATUS ORDER")</h5>
                    </div>
                    <div class="card-body">
                        <div class="cmn-table">
                            <div class="table-responsive overflow-hidden">
                                <table class="table table-striped align-middle">
                                    <thead>
                                    <tr>
                                        <th scope="col">@lang("Parameter")</th>
                                        <th scope="col">@lang("Description")</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr>
                                        <td data-label="Parameter"><span>@lang("key")</span></td>
                                        <td data-label="Description">
                                            <span>@lang("Your API key")</span>
                                        </td>

                                    </tr>

                                    <tr>
                                        <td data-label="Parameter"><span>@lang("action")</span></td>
                                        <td data-label="Description">
                                            <span>@lang("status")</span>
                                        </td>

                                    </tr>
                                    <tr>
                                        <td data-label="Parameter"><span>@lang("order")</span></td>
                                        <td data-label="Description">
                                            <span>@lang("Order ID")</span>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="card-body">
                            <h5 class="mb-10">@lang("Example response:")</h5>
                            <div class="api-code-box">
                                <pre>
{
   "status": "Processing",
   "charge": "3.60",
   "start_count": 0,
   "remains": 0,
   "currency": "BDT"
}
                                </pre>
                                <div class="editor-dots">

                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card mb-50">
                    <div class="card-header">
                        <h5 class="mb-0">@lang("MULTIPLE STATUS ORDER")</h5>
                    </div>
                    <div class="card-body">
                        <div class="cmn-table">
                            <div class="table-responsive overflow-hidden">
                                <table class="table table-striped align-middle">
                                    <thead>
                                    <tr>
                                        <th scope="col">@lang("Parameter")</th>
                                        <th scope="col">@lang("Description")</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr>
                                        <td data-label="Parameter"><span>@lang("key")</span></td>
                                        <td data-label="Description">
                                            <span>@lang("Your API key")</span>
                                        </td>

                                    </tr>

                                    <tr>
                                        <td data-label="Parameter"><span>@lang("action")</span></td>
                                        <td data-label="Description">
                                            <span>@lang("orders")</span>
                                        </td>

                                    </tr>
                                    <tr>
                                        <td data-label="Parameter"><span>@lang("orders")</span></td>
                                        <td data-label="Description">
                                            <span>@lang("Order IDs separated by comma (array data)")</span>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="card-body">
                            <h5 class="mb-10">@lang("Example response:")</h5>
                            <div class="api-code-box">
                                <pre>
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
]
                                </pre>
                                <div class="editor-dots">

                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card mb-50">
                    <div class="card-header">
                        <h5 class="mb-0">@lang("PLACE REFILL")</h5>
                    </div>
                    <div class="card-body">
                        <div class="cmn-table">
                            <div class="table-responsive overflow-hidden">
                                <table class="table table-striped align-middle">
                                    <thead>
                                    <tr>
                                        <th scope="col">@lang("Parameter")</th>
                                        <th scope="col">@lang("Description")</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr>
                                        <td data-label="Parameter"><span>@lang("key")</span></td>
                                        <td data-label="Description">
                                            <span>@lang("Your API key")</span>
                                        </td>

                                    </tr>

                                    <tr>
                                        <td data-label="Parameter"><span>@lang("action")</span></td>
                                        <td data-label="Description">
                                            <span>@lang("refill")</span>
                                        </td>

                                    </tr>
                                    <tr>
                                        <td data-label="Parameter"><span>@lang("order")</span></td>
                                        <td data-label="Description">
                                            <span>@lang("Order ID")</span>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="card-body">
                            <h5 class="mb-10">@lang("Example response:")</h5>
                            <div class="api-code-box">
                                <pre>
{
   "refill": "1"
}
                                </pre>
                                <div class="editor-dots">

                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card mb-50">
                    <div class="card-header">
                        <h5 class="mb-0">@lang("STATUS REFILL")</h5>
                    </div>
                    <div class="card-body">
                        <div class="cmn-table">
                            <div class="table-responsive overflow-hidden">
                                <table class="table table-striped align-middle">
                                    <thead>
                                    <tr>
                                        <th scope="col">@lang("Parameter")</th>
                                        <th scope="col">@lang("Description")</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr>
                                        <td data-label="Parameter"><span>@lang("key")</span></td>
                                        <td data-label="Description">
                                            <span>@lang("Your API key")</span>
                                        </td>

                                    </tr>

                                    <tr>
                                        <td data-label="Parameter"><span>@lang("action")</span></td>
                                        <td data-label="Description">
                                            <span>@lang("refill_status")</span>
                                        </td>

                                    </tr>
                                    <tr>
                                        <td data-label="Parameter"><span>@lang("refill")</span></td>
                                        <td data-label="Description">
                                            <span>@lang("Refill ID")</span>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="card-body">
                            <h5 class="mb-10">@lang("Example response:")</h5>
                            <div class="api-code-box">
                                <pre>
{
   "status": "Completed"
}
                                </pre>
                                <div class="editor-dots">

                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card mb-50">
                    <div class="card-header">
                        <h5 class="mb-0">@lang("SERVICE LIST")</h5>
                    </div>
                    <div class="card-body">
                        <div class="cmn-table">
                            <div class="table-responsive overflow-hidden">
                                <table class="table table-striped align-middle">
                                    <thead>
                                    <tr>
                                        <th scope="col">@lang("Parameter")</th>
                                        <th scope="col">@lang("Description")</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr>
                                        <td data-label="Parameter"><span>@lang("key")</span></td>
                                        <td data-label="Description">
                                            <span>@lang("Your API key")</span>
                                        </td>

                                    </tr>

                                    <tr>
                                        <td data-label="Parameter"><span>@lang("action")</span></td>
                                        <td data-label="Description">
                                            <span>@lang("services")</span>
                                        </td>

                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="card-body">
                            <h5 class="mb-10">@lang("Example response:")</h5>
                            <div class="api-code-box">
                                <pre>
[
   {
      "service": 1,
      "name": "🙋‍♂️ Followers [Ultra-High Quality Profiles]",
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
]
                                </pre>
                                <div class="editor-dots">

                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </main>

    @include(template(). 'user.api.components.api_key_generate_modal')
    @include(template(). 'user.api.components.api_key_view_modal')
@endsection


@push('script')
    <script>
        "use strict";
        document.getElementById("copyBtn").addEventListener("click", () => {
            let copyText = document.getElementById("api_key");
            copyText.select();
            copyText.setSelectionRange(0, 99999); // For mobile devices
            document.execCommand("copy");
            Notiflix.Notify.success(`Copied: ${copyText.value}`);
        })
    </script>
@endpush




