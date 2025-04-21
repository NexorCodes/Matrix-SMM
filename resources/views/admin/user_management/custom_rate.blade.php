@extends('admin.layouts.app')
@section('page_title', __('Custom Rate'))
@section('content')
    <div class="content container-fluid" id="app">

        <div class="page-header">
            <div class="row align-items-end">
                <div class="col-sm mb-2 mb-sm-0">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb breadcrumb-no-gutter">
                            <li class="breadcrumb-item"><a class="breadcrumb-link"
                                                           href="javascript:void(0)">@lang("Dashboard")</a></li>
                            <li class="breadcrumb-item" aria-current="page">@lang("User")</li>
                            <li class="breadcrumb-item active" aria-current="page">@lang("Custom Rate")</li>
                        </ol>
                    </nav>
                    <h1 class="page-header-title">@lang("Custom Rate")</h1>
                </div>
                <div class="col-sm-auto">
                    <a href="{{ route('admin.currency.create') }}" class="btn btn-primary"
                       data-bs-toggle="modal" data-bs-target="#addEventToCalendarModal">
                        <i class="bi-plus me-1"></i> @lang("Add Custom Rate")
                    </a>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header card-header-content-md-between">
                <div class="mb-2 mb-sm-0">
                    <h4 class="card-header-title">@lang("Custom Rates")</h4>
                </div>
                <div class="d-flex gap-2">
                    <div class="dropdown">
                        <button type="button" class="btn btn-white btn-sm w-100"
                                id="dropdownMenuClickable" data-bs-auto-close="false"
                                id="usersFilterDropdown"
                                data-bs-toggle="dropdown"
                                aria-expanded="false">
                            <i class="bi-filter me-1"></i> @lang('Filter')
                        </button>
                        <div
                            class="dropdown-menu dropdown-menu-sm-end dropdown-card card-dropdown-filter-centered filter_dropdown"
                            aria-labelledby="dropdownMenuClickable">
                            <div class="card">
                                <div class="card-header card-header-content-between">
                                    <h5 class="card-header-title">@lang('Filter Category')</h5>
                                    <button type="button" class="btn btn-ghost-secondary btn-icon btn-sm ms-2"
                                            id="filter_close_btn">
                                        <i class="bi-x-lg"></i>
                                    </button>
                                </div>

                                <div class="card-body">
                                    <form action="" method="get">
                                        <div class="row">
                                            <div class="col-12 mb-4">
                                                <span class="text-cap text-body">@lang("Search")</span>
                                                <input type="text" class="form-control" name="search"
                                                       id="category_filter_input" value="{{ request()->search }}"
                                                       autocomplete="off">
                                            </div>

                                            <div class="col-sm-12 mb-5">
                                                <small class="text-cap text-body">@lang("Status")</small>
                                                <div class="tom-select-custom">
                                                    <select class="js-select form-select" name="status"
                                                            autocomplete="off"
                                                            data-hs-tom-select-options='{
                                                              "placeholder": "Any status",
                                                              "hideSearch": true
                                                            }'>
                                                        <option value="">@lang("Any status")</option>
                                                        <option value="1"
                                                                data-option-template='<span class="d-flex align-items-center"><span class="legend-indicator bg-success"></span>Active</span>'>
                                                            @lang("Active")
                                                        </option>
                                                        <option value="0"
                                                                data-option-template='<span class="d-flex align-items-center"><span class="legend-indicator bg-danger"></span>Inactive</span>'>
                                                            @lang("Inactive")
                                                        </option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="d-grid">
                                            <button type="submit" id="filter_button"
                                                    class="btn btn-primary">@lang('Apply')</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class=" table-responsive datatable-custom  ">
                <table id="datatable"
                       class="js-datatable table table-borderless table-nowrap table-align-middle card-table">
                    <thead class="thead-light">
                    <tr>
                        <th>@lang('Service Id')</th>
                        <th>@lang('Service Title')</th>
                        <th>@lang('Original Price')</th>
                        <th>@lang('Custom Price')</th>
                        <th>@lang('Action')</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="(serve, index) in userServices" :key="index">
                        <td data-label="@lang('Service Id')"
                            class="font-weight-bold text-uppercase">
                            @{{serve.service.id}}
                        </td>
                        <td data-label="@lang('Service')">@{{serve.service.service_title}}</td>
                        <td data-label="@lang('Orginal Price')">
                            @{{serve.service.price}} {{ basicControl()->base_currency }}</td>
                        <td data-label="@lang('Custom Price')">
                            <input type="text" class="form-control"
                                   :value="serve.price" @input="inputPrice(serve.id, $event)">
                        </td>
                        <th scope="col">
                            <button type="button" class="btn btn-white btn-sm delete-btn"
                                    :data-route="`{{ route('admin.user.deleteServiceRate', '') }}/${serve.service.id}`"
                                    data-bs-toggle="modal"
                                    data-bs-target="#deleteServiceModal">
                                <i class="bi-trash me-1"></i> @lang("Delete")
                            </button>
                        </th>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="modal fade" id="addEventToCalendarModal" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h3 class="modal-title" id="userDeleteMultipleModalLabel"><i
                                class="fa-light fa-square-check me-2"></i> @lang('Add Custom Rate')</h3>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row mb-4">
                            <div class="col-sm-3 mb-2 mb-sm-0">
                                <div class="d-flex align-items-center mt-2">
                                    <i class="bi-list-ul nav-icon"></i>
                                    <div class="flex-grow-1">@lang("Categories")</div>
                                </div>
                            </div>
                            <div class="col-sm">
                                <div class="tom-select-custom">
                                    <select class="js-select form-select category" autocomplete="off"
                                            v-model="category_id" @change="changeCategory"
                                            data-hs-tom-select-options='{
                                          "placeholder": "Select a category",
                                          "hideSearch": false
                                        }'>
                                        <option value="" disabled>@lang("Select Category")</option>
                                        <option :value="category.id" v-for="category in categories">
                                            @{{category.category_title}}
                                        </option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-sm-3 mb-2 mb-sm-0">
                                <div class="d-flex align-items-center mt-2">
                                    <i class="fal fa-sync nav-icon"></i>
                                    <div class="flex-grow-1">@lang("Services")</div>
                                </div>
                            </div>
                            <div class="col-sm">
                                <div class="tom-select-custom">
                                    <select class="abc-js-select form-select service" autocomplete="off"
                                            v-model="service_id" @change="selectService($event)"
                                            data-hs-tom-select-options='{
                                          "placeholder": "Select a service",
                                          "hideSearch": false
                                        }'>
                                        <option value="" v-if="0 < services.length" disabled>Select Service</option>
                                        <option :value="service.id" v-for="service in services">
                                            @{{service.service_title}}
                                        </option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-3 mb-2 mb-sm-0">
                                <div class="d-flex align-items-center mt-2">
                                    <i class="fal fa-money-bill nav-icon"></i>
                                    <div class="flex-grow-1">@lang("Price")</div>
                                </div>
                            </div>
                            <div class="col-sm">
                                <label for="priceLabel" class="visually-hidden form-label">@lang("Price")</label>
                                <div class="input-group mb-3">
                                    <input type="text" class="form-control" v-model="amount" aria-label="Amount">
                                    <span class="input-group-text">{{ basicControl()->base_currency }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer gap-3">
                        <button type="button" id="discardFormt" class="btn btn-white"
                                data-bs-dismiss="modal">@lang("Close")
                        </button>
                        <button type="button" @click="setUserRate" class="btn btn-primary"
                                :disabled="disabled"> @lang('Save') </button>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- Modal -->
    <div class="modal fade" id="deleteServiceModal" tabindex="-1" role="dialog"
         aria-labelledby="deleteServiceModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteServiceModalLabel"><i
                            class="fa-light fa-square-check"></i> @lang("Confirmation")</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="" method="post" class="setRoute">
                    @csrf
                    <div class="modal-body">
                        @lang("Do you want to delete this user service?")
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-white" data-bs-dismiss="modal">@lang("Close")</button>
                        <button type="submit" class="btn btn-primary">@lang("Confirm")</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- End Modal -->

@endsection

@push('css-lib')
    <link rel="stylesheet" href="{{ asset('assets/admin/css/tom-select.bootstrap5.css') }}">
@endpush

@push('js-lib')
    <script src="{{ asset('assets/admin/js/tom-select.complete.min.js') }}"></script>
@endpush


@push('script')
    <script>
        'use strict';
        $(document).on('click', '.delete-btn', function () {
            let route = $(this).attr('data-route');
            $('.setRoute').attr('action', route);
        });

        var app = new Vue({
            el: '#app',
            data: {
                deleteModal: false,
                user_id: "{{$user->id}}",
                category_id: '',
                service_id: '',
                priceHint: '',
                amount: '',
                userServices: [],
                categories: [],
                services: [],
                myService: {},
                deleteItem: {},
                disabled: true
            },
            beforeMount() {
                this.categories = @json($categories);

                this.serviceList();
                HSCore.components.HSTomSelect.init('.js-select', {
                    maxOptions: 250,
                })
            },
            mounted() {
                HSCore.components.HSTomSelect.init('.js-select', {
                    maxOptions: 250,
                })

            },
            watch: {
                category_id() {
                    this.checkField();
                },
                service_id() {
                    this.checkField();
                },
                amount(value) {
                    this.checkField();
                    console.log(value);
                }
            },
            methods: {
                serviceList(list = null) {
                    if (list) {
                        this.userServices = list;
                    } else {
                        this.userServices = @json($userServices);
                    }
                },

                changeCategory() {
                    var _this = this;
                    _this.service_id = '',
                        _this.priceHint = '',
                        _this.amount = '',

                        axios.get('{{route('admin.user.getService')}}', {
                            params: {
                                category_id: _this.category_id
                            }
                        })
                            .then(function (response) {


                                _this.services = response.data
                                // _this.$nextTick(() => {
                                //     HSCore.components.HSTomSelect.init('.abc-js-select', {
                                //         maxOptions: 250,
                                //     });
                                // });

                            })
                            .catch(function (error) {
                                console.log(error);
                            });
                },
                selectService(event) {
                    var serveId = event.target.value;
                    var _this = this;

                    var list = _this.services;

                    var result = list.filter((obj) => {
                        if (obj.id == serveId) {
                            _this.myService = obj
                            _this.amount = parseFloat(obj.price).toFixed(2)
                            _this.priceHint = (parseFloat(obj.price).toFixed(2)) + "{{config('basic.currency_symbol')}}"
                        }
                    });


                    axios.get('{{route('admin.user.getService')}}', {
                        params: {
                            service_id: serveId,
                            user_id: _this.user_id
                        }
                    })
                        .then(function (response) {
                            if (0 < response.data) {
                                _this.amount = parseFloat(response.data).toFixed(2)
                            }
                        })
                        .catch(function (error) {
                            console.log(error);
                        });

                },


                checkField() {
                    if (this.category_id && this.service_id && this.amount) {
                        this.disabled = false;
                    } else {
                        this.disabled = true;
                    }
                },

                setUserRate() {

                    var _this = this;

                    axios.post('{{route('admin.user.setServiceRate')}}', {
                        user_id: this.user_id,
                        category_id: this.category_id,
                        service_id: this.service_id,
                        amount: this.amount,
                    })
                        .then(function (response) {
                            if (response.data.errors) {
                                var getErrors = response.data.errors;
                                for (var err in getErrors) {
                                    Notiflix.Notify.failure("" + getErrors[err][0]);
                                }
                            }
                            if (response.data.success) {
                                $('#addEventToCalendarModal').modal('hide');
                                Notiflix.Notify.success("" + response.data.success);
                                _this.serviceList(response.data.userServices)
                            }

                        })
                        .catch(function (error) {
                        });
                },


                inputPrice(id, event) {
                    axios.post('{{ route('admin.user.updateServiceRate') }}', {
                        id: id,
                        amount: event.target.value
                    })
                        .then(function (response) {
                            this.services = response.data
                        })
                        .catch(function (error) {
                            console.log(error);
                        });
                },

            }
        });

    </script>
@endpush


