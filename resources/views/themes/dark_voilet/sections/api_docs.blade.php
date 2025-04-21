<section class="api-docs-section">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="card mb-50">
                    <div class="card-header">
                        <h5 class="mb-0">@lang("API Documentation")</h5>
                    </div>
                    <div class="card-body">
                        <small>@lang("Note: Please read the API intructions carefully. Its your solo responsability what
                            you add by
                            our API.")</small>
                        <div class="cmn-table mt-20">
                            <div class="table-responsive">
                                <table class="table table-striped align-middle min-width-table">
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
                            <div class="table-responsive">
                                <table class="table table-striped align-middle min-width-table">
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
                            <div class="table-responsive">
                                <table class="table table-striped align-middle min-width-table">
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
                            <div class="table-responsive">
                                <table class="table table-striped align-middle min-width-table">
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
                            <div class="table-responsive">
                                <table class="table table-striped align-middle min-width-table">
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
                            <div class="table-responsive">
                                <table class="table table-striped align-middle min-width-table">
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
                            <div class="table-responsive">
                                <table class="table table-striped align-middle min-width-table">
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
    </div>
</section>
