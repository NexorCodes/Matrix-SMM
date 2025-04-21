@extends('admin.layouts.app')
@section('page_title', __('Order Details'))
@section('content')
    <div class="content container-fluid">
        <div class="page-header">
            <div class="row align-items-end">
                <div class="col-sm mb-2 mb-sm-0">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb breadcrumb-no-gutter">
                            <li class="breadcrumb-item">
                                <a class="breadcrumb-link" href="javascript:void(0)">
                                    @lang('Dashboard')
                                </a>
                            </li>
                            <li class="breadcrumb-item">
                                <a class="breadcrumb-link" href="{{ route('admin.order', 'list') }}">
                                    @lang('Manage Orders')
                                </a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">@lang('Order Details')</li>
                        </ol>
                    </nav>
                    <div class="d-sm-flex align-items-sm-center">
                        <h1 class="page-header-title">@lang("Order") #{{ $order->id }}</h1>
                        <span class="badge bg-soft-success text-success ms-sm-3">
                            <span class="legend-indicator bg-success"></span>@lang("Paid")
                          </span>
                        <span class="badge bg-soft-{{$order->getStatusClass($order->status)}} text-{{$order->getStatusClass($order->status)}} ms-2 ms-sm-3">
                            <span class="legend-indicator bg-{{$order->getStatusClass($order->status)}}"></span>{{ ucfirst($order->status) }}
                          </span>
                        <span class="ms-2 ms-sm-3">
                            <i class="bi-calendar-week"></i> {{ dateTime($order->created_at) }}
                          </span>
                    </div>
                </div>
            </div>
        </div>

        <div class="row d-flex justify-content-between">
            <div class="col-lg-7">
                <div class="d-grid gap-3 gap-lg-5">
                    <div class="card pb-3">
                        <div class="card-header card-header-content-between">
                            <h4 class="card-header-title">@lang("Order details")</h4>
                        </div>
                        <div class="card-body mt-2">
                            <form action="{{ route('admin.order.update', $order->id) }}" method="post"
                                  enctype="multipart/form-data">
                                @csrf
                                @method('put')
                                <div class="row mb-4 d-flex align-items-center">
                                    <div class="col-md-6">
                                        <label for="categoryLabel" class="form-label">@lang('Category')</label>
                                        <input type="text" class="form-control  @error('category') is-invalid @enderror"
                                               name="category" id="categoryLabel" placeholder="@lang("Category")"
                                               aria-label="@lang("Category")"
                                               autocomplete="off"
                                               value="{{ old('category',  optional(optional($order->service)->category)->category_title) }}"
                                               disabled>
                                        @error('category')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label for="serviceLabel" class="form-label">@lang('Service')</label>
                                        <input type="text" class="form-control  @error('service') is-invalid @enderror"
                                               name="service" id="serviceLabel" placeholder="@lang("Service")"
                                               aria-label="@lang("Service")"
                                               autocomplete="off"
                                               value="{{ old('service', optional($order->service)->service_title ) }}"
                                               disabled>
                                        @error('service')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row mb-4 d-flex align-items-center">
                                    <div class="col-md-6">
                                        <label for="quantityLabel" class="form-label">@lang('Quantity')</label>
                                        <input type="number"
                                               class="form-control @error('quantity') is-invalid @enderror"
                                               name="quantity" id="quantityLabel" placeholder="@lang('Quantity')"
                                               aria-label="@lang('Quantity')"
                                               autocomplete="off"
                                               value="{{ old('quantity', $order->quantity) }}" disabled>
                                        @error('quantity')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label for="startCounter" class="form-label">@lang('Start Counter')</label>
                                        <input type="number"
                                               class="form-control  @error('start_counter') is-invalid @enderror"
                                               name="start_counter" id="startCounterLabel"
                                               placeholder="@lang("Start Counter")"
                                               aria-label="@lang("Start Counter")"
                                               autocomplete="off"
                                               value="{{ old('start_counter', $order->start_counter) }}" disabled>
                                        @error('start_counter')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="row align-items-center mb-3">
                                    <div class="col-md-6">
                                        <label for="intervalLabel" class="form-label">@lang('Interval')</label>
                                        <input type="text" class="form-control  @error('interval') is-invalid @enderror"
                                               name="interval" id="intervalLabel" placeholder="@lang("Interval")"
                                               aria-label="@lang("interval")"
                                               autocomplete="off"
                                               value="{{ old('interval', $order->interval) }}" disabled>
                                        @error('interval')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label for="runsLabel" class="form-label">@lang('Runs')</label>
                                        <input type="text" class="form-control  @error('runs') is-invalid @enderror"
                                               name="runs" id="intervalLabel" placeholder="@lang("Runs")"
                                               aria-label="@lang("Runs")"
                                               autocomplete="off"
                                               value="{{ old('runs', $order->runs) }}" disabled>
                                        @error('runs')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="row align-items-center">
                                    <div class="col-md-6">
                                        <label for="apiProviderLabel" class="form-label">@lang('API Provider')</label>
                                        <input type="text" class="form-control  @error('interval') is-invalid @enderror"
                                               name="api_provider" id="apiProviderLabel"
                                               placeholder="@lang("Api Provider")"
                                               aria-label="@lang("Api Provider")"
                                               autocomplete="off"
                                               value="{{ old('api_provider', optional(optional($order->service)->provider)->api_name) }}"
                                               disabled>
                                        @error('api_provider')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label for="ApiOrderIdLabel" class="form-label">@lang('API Order ID')</label>
                                        <input type="text" class="form-control  @error('runs') is-invalid @enderror"
                                               name="api_order_id" id="ApiOrderIdLabel"
                                               placeholder="@lang("Api Order Id")"
                                               aria-label="@lang("Api Order Id")"
                                               autocomplete="off"
                                               value="{{ old('api_order_id', $order->api_order_id) }}" disabled>
                                        @error('api_order_id')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="mt-3">
                                            <label class="form-label" for="api_response">@lang("API Response")</label>
                                            <textarea id="api_response" class="form-control" name="api_response"
                                                      placeholder="@lang("API Response")"
                                                      rows="3"
                                                      disabled>{{ old('api_response', $order->status_description) }}</textarea>
                                            @error('api_response')
                                            <span class="invalid-feedback d-block">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-5">
                <div class="d-grid gap-3 gap-lg-5">
                    <div class="card pb-3">
                        <div class="card-header card-header-content-between">
                            <h4 class="card-header-title">@lang("Edit Order")</h4>
                            <a class="link" href="javascript:void(0)"></a>
                            <span class="badge bg-soft-{{$order->getStatusClass($order->status)}} text-{{$order->getStatusClass($order->status)}} ms-2 ms-sm-3">
                            <span class="legend-indicator bg-{{$order->getStatusClass($order->status)}}"></span>{{ ucfirst($order->status) }}
                          </span>
                        </div>
                        <div class="card-body mt-2">
                            <form action="{{ route('admin.order.update', $order->id) }}" method="post"
                                  enctype="multipart/form-data">
                                @csrf
                                @method('put')
                                <div class="row mb-4 d-flex align-items-center">


                                    <div class="col-md-12">
                                        <label for="linkLabel" class="form-label">@lang('Link')</label>
                                        <input type="text" class="form-control  @error('link') is-invalid @enderror"
                                               name="link" id="linkLabel"
                                               placeholder="@lang("www.example.com/your_profile_identity")"
                                               aria-label="@lang("Link")"
                                               autocomplete="off"
                                               value="{{ old('link', $order->link)}}">
                                        @error('link')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row mb-4 d-flex align-items-center">
                                    <div class="col-md-6">
                                        <label for="remainsLabel" class="form-label">@lang('Remains')</label>
                                        <input type="number"
                                               class="form-control @error('remains') is-invalid @enderror"
                                               name="remains" id="remainsLabel" placeholder="@lang('Remains')"
                                               aria-label="@lang('Remains')"
                                               autocomplete="off"
                                               value="{{ old('remains', $order->remains) }}">
                                        @error('remains')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label for="startCounter" class="form-label">@lang('Start Counter')</label>
                                        <input type="number"
                                               class="form-control  @error('start_counter') is-invalid @enderror"
                                               name="start_counter" id="startCounterLabel"
                                               placeholder="@lang("Start Counter")"
                                               aria-label="@lang("Start Counter")"
                                               autocomplete="off"
                                               value="{{ old('start_counter', $order->start_counter) }}">
                                        @error('start_counter')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row align-items-center">
                                    <div class="{{ isset($order->refilled_at) ? 'col-md-6' : 'col-md-12' }}">
                                        <label for="apiProviderLabel" class="form-label">@lang('Change Status')</label>
                                        <div class="tom-select-custom">
                                            <select class="js-select form-select" name="status" autocomplete="off"
                                                    data-hs-tom-select-options='{
                                                  "placeholder": "Change Status",
                                                  "hideSearch": true
                                                }'>
                                                <option value="" selected disabled>@lang("Change Status")</option>
                                                <option
                                                    value="awaiting" {{ $order->status == 'awaiting' ? 'selected' : '' }}>@lang('Awaiting')</option>
                                                <option
                                                    value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>@lang('Pending')</option>
                                                <option
                                                    value="processing" {{ $order->status == 'processing' ? 'selected' : '' }}>@lang('Processing')</option>
                                                <option
                                                    value="progress" {{ $order->status == 'progress' ? 'selected' : '' }}>@lang('In Progress')</option>
                                                <option
                                                    value="completed" {{ $order->status == 'completed' ? 'selected' : '' }}>@lang('Completed')</option>
                                                <option
                                                    value="partial" {{ $order->status == 'partial' ? 'selected' : '' }}>@lang('Partial')</option>
                                                <option
                                                    value="canceled" {{ $order->status == 'canceled' ? 'selected' : '' }}>@lang('Canceled')</option>
                                                <option
                                                    value="refunded" {{ $order->status == 'refunded' ? 'selected' : '' }}>@lang('Refunded')</option>
                                            </select>
                                        </div>
                                        @error('status')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    @if(isset($order->refilled_at))
                                        <div class="col-md-6">
                                            <label for="apiProviderLabel"
                                                   class="form-label">@lang('Change Status')</label>
                                            <div class="tom-select-custom">
                                                <select class="js-select form-select" name="status" autocomplete="off"
                                                        data-hs-tom-select-options='{
                                                          "placeholder": "Change Status",
                                                          "hideSearch": true
                                                        }'>
                                                    <option value="" selected disabled>@lang("Change Status")</option>
                                                    <option
                                                        value="awaiting" {{ $order->status == 'awaiting' ? 'selected' : '' }}>@lang('Awaiting')</option>
                                                    <option
                                                        value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>@lang('Pending')</option>
                                                    <option
                                                        value="processing" {{ $order->status == 'processing' ? 'selected' : '' }}>@lang('Processing')</option>
                                                    <option
                                                        value="progress" {{ $order->status == 'progress' ? 'selected' : '' }}>@lang('In Progress')</option>
                                                    <option
                                                        value="completed" {{ $order->status == 'completed' ? 'selected' : '' }}>@lang('Completed')</option>
                                                    <option
                                                        value="partial" {{ $order->status == 'partial' ? 'selected' : '' }}>@lang('Partial')</option>
                                                    <option
                                                        value="canceled" {{ $order->status == 'canceled' ? 'selected' : '' }}>@lang('Canceled')</option>
                                                    <option
                                                        value="refunded" {{ $order->status == 'refunded' ? 'selected' : '' }}>@lang('Refunded')</option>
                                                </select>
                                            </div>
                                            @error('status')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    @endif
                                </div>


                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="mt-3">
                                            <label class="form-label" for="note">@lang("Note")</label>
                                            <textarea id="note" class="form-control" name="reason"
                                                      placeholder="@lang("Note")"
                                                      rows="3">{{ old('reason', $order->reason) }}</textarea>
                                            @error('reason')
                                            <span class="invalid-feedback d-block">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="d-flex justify-content-start mt-5">
                                    <button type="submit"
                                            class="btn btn-primary submit_btn">@lang('Save changes')</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('css-lib')
    <link rel="stylesheet" href="{{ asset('assets/admin/css/tom-select.bootstrap5.css') }}">
@endpush

@push('js-lib')
    <script src="{{ asset("assets/admin/js/tom-select.complete.min.js") }}"></script>
@endpush

@push('script')
    <script>
        'use strict';
        $(document).ready(function () {
            HSCore.components.HSTomSelect.init('.js-select')
        });
    </script>
@endpush
