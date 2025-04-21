@extends(template().'layouts.user')
@section('title',trans('New Order'))
@section('content')
    <div class="dashboard-wrapper" id="new_order">
        <div class="container">
            <div class="breadcrumb-area">
                <h4 class="title">@lang("New Order")</h4>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ url('/') }}"><i
                                class="fa-light fa-house"></i>
                            @lang("Home")</a>
                    </li>
                    <li class="breadcrumb-item"><a href="{{ route('user.dashboard') }}">
                            @lang("Dashboard")</a>
                    </li>
                    <li class="breadcrumb-item" aria-current="page">@lang("Order")</li>
                    <li class="breadcrumb-item active" aria-current="page">@lang("New Order")</li>
                </ul>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="card mb-30">
                        <div class="card-header">
                            <h4>@lang("choose a social network")</h4>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                @forelse($socialMedia as $media)
                                    <div class="col-lg-3 col-md-4 col-sm-6">
                                        <button type="button" class="social-btn w-100"
                                                :class="{ activeSocialMedia: '{{ $media->id }}' == isActive }"
                                                @click="toggleActive('{{ $media->id   }}')"
                                                @click.prevent="getCategoryAndService('{{ $media->id   }}')">
                                            <img class="social-media-img"
                                                 src="{{ getFile($media->icon_driver, $media->icon) }}"
                                                 alt="{{ $media->name }}">
                                            {{ $media->name }}
                                        </button>
                                    </div>
                                @empty
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row g-4 pulse-loader">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <form @submit.prevent="placeOrder">
                                <div class="row g-3">
                                    <div class="col-12">
                                        <label class="form-label">@lang("Category")</label>
                                        <select class="cmn-select3" name="categories" v-model="selectedCategory"
                                                @change="handleCategoryChange()">
                                            <option value="" disabled selected>@lang("Select Category")</option>
                                            <option v-for="category in categories" :key="category.id"
                                                    :value="category.id">
                                                @{{ category.category_title }}
                                            </option>
                                        </select>
                                        <span class="text-danger category"></span>
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label">@lang("Service")</label>
                                        <select class="cmn-select4" name="service" v-model="selectedService"
                                                @change="handleServiceChange()">
                                            <option value="" disabled selected>@lang("Select Service")</option>
                                            <option v-for="service in services" :key="service.id" :value="service.id"
                                                    :value="service.id">
                                                @{{ service.service_title }}
                                            </option>
                                        </select>
                                        <span class="text-danger service"></span>
                                    </div>
                                    <div class="col-12">
                                        <label for="link" class="form-label">@lang("link")</label>
                                        <input type="text" class="form-control" id="link" v-model="link"
                                               placeholder="@lang("www.example.com/your_profile_identity")" autocomplete="off">
                                        <span class="text-danger link error-link"></span>
                                    </div>
                                    <div class="col-12">
                                        <label for="quantity" class="form-label">@lang("Quantity")</label>
                                        <input type="number" class="form-control" v-model.number="quantity"
                                               id="quantity"
                                               value="0">
                                        <span class="text-danger quantity"></span>
                                    </div>
                                    <div class="col-12 drip_feed" :class="display">
                                        <label class="form-label">@lang('Drip-Feed')</label>
                                        <div class="custom-switch-btn w-md-25">
                                            <input type="checkbox" name="drip_feed" @click="dripFeedChecked"
                                                   class="form-check-input"
                                                   id="status"
                                                   value="0" v-model="isChecked">
                                            <label class="custom-switch-checkbox-label" for="status">
                                                <span class="custom-switch-checkbox-inner"></span>
                                                <span class="custom-switch-checkbox-switch"></span>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="drip_feed_check" :class="[display, dripFeedCheck]">
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <div class="drip_feed">
                                                    <label class="form-label">@lang('Runs')</label>
                                                    <input type="number" id="runs" v-model.number="runs"
                                                           class="form-control"
                                                           value="{{ old('runs') }}">
                                                    <span class="text-danger runs"></span>
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="drip_feed">
                                                    <label class="form-label">@lang('Interval (in minutes)')</label>
                                                    <input type="number" v-model="interval" class="form-control"
                                                           value="{{ old('interval') }}">
                                                    <span class="text-danger interval"></span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="drip_feed mt-3">
                                            <label class="form-label">@lang('Total Quantity')</label>
                                            <input type="text" class="form-control total_quantity" name="total_quantity"
                                                   :value="total"
                                                   disabled>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <label for="price" class="form-label">@lang("price")</label>
                                        <div class="input-group">
                                            <input type="number" class="form-control" :value="totalPrice" id="price"
                                                   disabled>
                                            <div class="input-group-text">
                                                @if(auth()->user()->currency)
                                                    {{ auth()->user()->currency }}
                                                @else
                                                    {{ basicControl()->base_currency }}
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-12">
                                        <label for="Comments" class="form-label">@lang("Comments")
                                            <sub>(@lang("Optional"))</sub></label>
                                        <textarea class="form-control" v-model="comments" id="Comments"
                                                  rows="3"></textarea>
                                        <span class="text-danger comments"></span>
                                    </div>

                                    <div class="col-12">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" v-model="check"
                                                   id="Yes, i have confirmed the order!">
                                            <label class="form-check-label"
                                                   for="Yes, i have confirmed the order!">
                                                @lang("Yes, i have confirmed the order!")
                                            </label>
                                        </div>
                                        <span class="text-danger check"></span>
                                    </div>
                                    <button type="submit" class="cmn-btn mt-30">@lang("place order")</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h4>@lang("service description")</h4>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-12">
                                    <label for="Service-name" class="form-label">@lang("Service name")</label>
                                    <input type="text" class="form-control" id="Service-name"
                                           :value="serviceName" disabled>
                                </div>
                                <div class="col-12">
                                    <div class="row g-3">
                                        <div class="col-lg-4 col-md-6">
                                            <label for="Minimum Amount"
                                                   class="form-label">@lang("Minimum Amount")</label>
                                            <input type="text" class="form-control" v-model.number="minAmount"
                                                   id="Minimum Amount" disabled>
                                        </div>
                                        <div class="col-lg-4 col-md-6">
                                            <label for="Maximum Amount"
                                                   class="form-label">@lang("Maximum Amount")</label>
                                            <input type="text" class="form-control" v-model.number="maxAmount"
                                                   id="Maximum Amount" disabled>
                                        </div>
                                        <div class="col-lg-4 col-md-6">
                                            <label for="Price per 1k" class="form-label">@lang("Price per 1k")</label>
                                            <div class="input-group">
                                                <input type="text" v-model.number="pricePer" class="form-control"
                                                       disabled>
                                                <div class="input-group-text">
                                                    @if(auth()->user()->currency)
                                                        {{ auth()->user()->currency }}
                                                    @else
                                                        {{ basicControl()->base_currency }}
                                                    @endif
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 mb-2">
                                    <label for="Description" class="form-label">@lang("Description")</label>
                                    <textarea class="form-control" id="Description" rows="12" disabled>@{{ description }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js-lib')
    <script src="{{ asset('assets/global/js/vue.global.prod.min.js') }}"></script>
@endpush

@push('script')
    <script>
        const {createApp} = Vue;
        const newOrder = createApp({
            data() {
                return {
                    socialMedia: @json($socialMedia),
                    categories: [],
                    services: [],
                    selectedCategory: '0',
                    selectedService: '0',
                    selectedServiceFromOrder: @json($selectService),
                    display: 'd-none',
                    dripFeedCheck: 'd-none',
                    isChecked: false,
                    quantity: 0,
                    runs: 0,
                    interval: '',
                    serviceName: '',
                    minAmount: 0,
                    maxAmount: 0,
                    pricePer: 0,
                    description: '',
                    fractionNumber: '{{ basicControl()->fraction_number }}',
                    link: '',
                    check: '',
                    comments: '',
                    errors: [],
                    currency: @json($currency),
                    isActive: 0
                };
            },

            computed: {
                total() {
                    return this.quantity * this.runs;
                },
                totalPrice() {
                    let total = (this.quantity / 1000) * this.pricePer;
                    return total.toFixed(this.fractionNumber);
                }
            },

            mounted() {
                if (this.selectedServiceFromOrder) {
                    this.getCategoryAndService(this.selectedServiceFromOrder.category.social_media_id);
                } else {
                    this.getCategoryAndService(0);
                }

                $('.cmn-select3').select2();
                $('.cmn-select3').on('change', (event) => {
                    this.selectedCategory = event.target.value;
                    this.handleCategoryChange();
                });

                $('.cmn-select4').select2();
                $('.cmn-select4').on('change', (event) => {
                    this.selectedService = event.target.value;
                    this.handleServiceChange();
                });
            },

            methods: {
                toggleActive(id) {
                    let socialMedia = this.socialMedia.find(media => media.id == id);
                    this.isActive = socialMedia.id;
                },
                handleServiceChange() {
                    let selectedService = this.services.find(service => service.id == this.selectedService);
                    if (this.currency) {
                        let formattedCurrency = parseFloat(selectedService.price) * parseFloat(this.currency.conversion_rate);
                        this.pricePer = formattedCurrency.toFixed(2);
                    } else {
                        let formattedCurrency = parseFloat(selectedService.price);
                        this.pricePer = formattedCurrency.toFixed(2);
                    }
                    this.minAmount = selectedService.min_amount;
                    this.maxAmount = selectedService.max_amount;
                    this.serviceName = selectedService.service_title;
                    this.description = selectedService.description;

                    if (selectedService.drip_feed == 0) {
                        this.isChecked = false
                        this.display = 'd-none'
                    } else {
                        this.isChecked = true
                        this.display = 'd-block'
                        this.dripFeedCheck = 'd-block'
                    }
                },

                handleCategoryChange() {
                    let selectedCategory = this.categories.find(category => category.id == this.selectedCategory);
                    this.services = selectedCategory.service;

                    if (this.services.length > 0) {
                        this.selectedService = this.services[0].id;
                        this.serviceName = this.services[0].service_title;
                        if (this.currency) {
                            let formattedCurrency = parseFloat(this.services[0].price) * parseFloat(this.currency.conversion_rate);
                            this.pricePer = formattedCurrency;
                        } else {
                            let formattedCurrency = parseFloat(this.services[0].price);
                            this.pricePer = formattedCurrency.toFixed(2);
                        }
                        this.minAmount = this.services[0].min_amount;
                        this.maxAmount = this.services[0].max_amount;
                        this.description = this.services[0].description;
                    } else {
                        this.selectedService = '0';
                        this.serviceName = '';
                        this.pricePer = 0;
                        this.minAmount = 0;
                        this.maxAmount = 0;
                    }
                },

                getCategoryAndService(id) {
                    let url = "{{ route('user.get.category.service') }}";
                    axios.get(url, {
                        params: {
                            social_media_id: id
                        }
                    })
                        .then(response => {
                            this.categories = response.data;

                            if (this.selectedServiceFromOrder) {
                                this.selectedCategory = this.selectedServiceFromOrder.category_id;
                                this.services.push(this.selectedServiceFromOrder);
                                this.selectedService = this.selectedServiceFromOrder.id;
                                this.serviceName = this.selectedServiceFromOrder.service_title;
                                if (this.currency) {
                                    let formattedCurrency = parseFloat(this.selectedServiceFromOrder.price) * parseFloat(this.currency.conversion_rate);
                                    this.pricePer = formattedCurrency;
                                } else {
                                    let formattedCurrency = parseFloat(this.selectedServiceFromOrder.price);
                                    this.pricePer = formattedCurrency;
                                }
                                this.minAmount = this.selectedServiceFromOrder.min_amount;
                                this.maxAmount = this.selectedServiceFromOrder.max_amount;
                                this.description = this.selectedServiceFromOrder.description;
                            } else {
                                if (this.categories.length > 0) {
                                    this.selectedCategory = this.categories[0].id;
                                    this.services = this.categories[0].service;

                                    if (this.services.length > 0) {
                                        this.selectedService = this.services[0].id;
                                        this.serviceName = this.services[0].service_title;
                                        if (this.currency) {
                                            let formattedCurrency = parseFloat(this.services[0].price) * parseFloat(this.currency.conversion_rate);
                                            this.pricePer = formattedCurrency.toFixed(2);
                                        } else {
                                            let formattedCurrency = parseFloat(this.services[0].price);
                                            this.pricePer = formattedCurrency.toFixed(2);
                                        }
                                        this.minAmount = this.services[0].min_amount;
                                        this.maxAmount = this.services[0].max_amount;
                                        this.description = this.services[0].description;
                                        if (this.services[0].drip_feed == 0) {
                                            this.dripFeedCheck = 'd-none'
                                            this.display = 'd-none'
                                            this.isChecked = false
                                        } else {
                                            this.dripFeedCheck = 'd-block'
                                            this.display = 'd-block'
                                            this.isChecked = true
                                        }
                                    } else {
                                        this.selectedService = '0';
                                    }
                                } else {
                                    this.selectedCategory = '0';
                                    this.services = [];
                                    this.selectedService = '0';
                                    this.serviceName = '';
                                    this.pricePer = 0;
                                    this.minAmount = 0;
                                    this.maxAmount = 0;
                                    this.description = '';
                                }
                            }
                        })
                        .catch(error => {
                        });
                },

                dripFeedChecked() {
                    let checked = $('#status').is(":checked");
                    if (checked) {
                        this.dripFeedCheck = 'd-block'
                    } else {
                        this.dripFeedCheck = 'd-none'
                    }
                },

                placeOrder() {
                    let url = "{{ route('user.order.store') }}";
                    Notiflix.Block.standard('.pulse-loader', 'Please wait a few moments.', {
                        backgroundColor: 'rgb(191 255 221 / 20%)',
                        svgColor: '#08cf65',
                        messageColor: '#002138',
                        messageFontSize: '18px',
                        fontFamily: 'Roboto, sans-serif'
                    });

                    axios.post(url, {
                        category: this.selectedCategory,
                        service: this.selectedService,
                        link: this.link,
                        quantity: this.quantity,
                        check: this.check,
                        drip_feed: this.isChecked,
                        runs: this.runs,
                        interval: this.interval,
                        comments: this.comments,
                    })
                        .then(response => {

                            Notiflix.Block.remove('.pulse-loader');

                            if (response.data.status == 'success') {
                                Notiflix.Notify.success(`${response.data.message}`);
                                this.quantity = '';
                                this.check = '';
                                this.link = '';
                                this.runs = '';
                                this.drip_feed = '';
                                this.interval = '';
                                this.comments = '';
                            }
                            if (response.data.status == 'error') {
                                Notiflix.Block.remove('.pulse-loader');
                                Notiflix.Notify.failure(`${response.data.message}`);
                            }
                        })
                        .catch(error => {
                            Notiflix.Block.remove('.pulse-loader');
                            let _this = this;
                            this.makeErrorRemove();
                            let res = error.response.data
                            _this.errors = res.errors
                            for (let err in _this.errors) {
                                let selector = document.querySelector("." + err);
                                if (selector) {
                                    selector.innerText = `${_this.errors[err]}`;
                                }
                            }
                        });
                },

                makeErrorRemove() {
                    $('.link').text('');
                    $('.quantity').text('');
                    $('.check').text('');
                    $('.runs').text('');
                    $('.interval').text('');
                },
            }
        });
        newOrder.mount('#new_order');
    </script>
@endpush

