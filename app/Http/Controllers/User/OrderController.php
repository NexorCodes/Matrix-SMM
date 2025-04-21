<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\OrderStoreRequest;
use App\Models\ApiProvider;
use App\Models\Category;
use App\Models\Currency;
use App\Models\Order;
use App\Models\Service;
use App\Models\SocialMedia;
use App\Models\Transaction;
use App\Traits\Notify;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\View\View;


class OrderController extends Controller
{
    use Notify;

    protected object $user;

    protected string $theme;

    public function __construct()
    {
        $this->middleware(['auth']);
        $this->middleware(function ($request, $next) {
            $this->user = auth()->user();
            return $next($request);
        });
    }

    public function create(Request $request)
    {
        try {
            $serviceId = $request->serviceId;
            if (isset($serviceId)) {
                $data['selectService'] = Service::where('service_status', 1)->userRate()->with('category', 'category.socialMedia')->find($serviceId);
            } else {
                $data['selectService'] = null;
            }
            $data['currency'] = Currency::where('code', auth()->user()->currency)->first();
            $data['socialMedia'] = SocialMedia::has('category')->with('category')->orderBy('id', 'asc')->get();
            return view(template() . 'user.order.add', $data);
        } catch (\Exception $e) {
            return back()->with('error', 'Something went wrong, Please try again.');
        }
    }

    public function getCategoryService(Request $request): \Illuminate\Http\JsonResponse
    {
        $categories = Category::with(['service' => function ($query) {
            return $query->where('service_status', 1)->userRate();
        }])
            ->whereHas('service', function ($query) {
                $query->where('service_status', 1)
                    ->whereHas('provider', function ($query) {
                        $query->where('status', 1);
                    })->orWhereDoesntHave('provider');;
            })
            ->where('status', 1)
            ->when($request->social_media_id != 0, function ($query) use ($request) {
                return $query->where('social_media_id', $request->social_media_id);
            })->get();
        return response()->json($categories);
    }


    public function store(OrderStoreRequest $request): \Illuminate\Foundation\Application|\Illuminate\Http\Response|\Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory
    {
        DB::beginTransaction();
        $data = $request->validated();
        $service = Service::userRate()->find($request->service);
        $quantity = $request->quantity;

        if ($service->drip_feed == 1) {
            if (!isset($request->drip_feed)) {
                $quantity = $request->quantity * $request->runs;
            }
        }

        if ($service->min_amount <= $quantity && $service->max_amount >= $quantity) {
            $userRate = ($service->user_rate) ?? $service->price;
            $price = round(($quantity * $userRate) / 1000, basicControl()->fraction_number);

            $user = Auth::user();
            if ($user->balance < $price) {
                return response([
                    'status' => 'error',
                    'message' => 'Insufficient balance in your wallet.'
                ]);
            }

            $order = Order::create([
                'user_id' => $user->id,
                'category_id' => $data['category'],
                'service_id' => $data['service'],
                'link' => $data['link'],
                'quantity' => $data['quantity'],
                'status' => 'processing',
                'price' => $price,
                'comments' => $data['comments'],
                'runs' => isset($data['runs']) && !empty($data['runs']) ? $data['runs'] : null,
                'interval' => isset($data['interval']) && !empty($data['interval']) ? $data['interval'] : null,
            ]);

            if (!$order) {
                DB::rollBack();
                return response([
                    'status' => 'error',
                    'message' => 'Something went wrong with the transaction balance. Please try again.'
                ]);
            }

            if (isset($service->api_provider_id)) {

                $apiProviderData = ApiProvider::find($service->api_provider_id);
                $postData = [
                    'key' => $apiProviderData['api_key'],
                    'action' => 'add',
                    'service' => $service->api_service_id,
                    'link' => $data['link'],
                    'quantity' => $data['quantity']
                ];

                if (isset($data['runs']))
                    $postData['runs'] = $data['runs'];

                if (isset($data['interval']))
                    $postData['interval'] = $data['interval'];

                $response = Http::post($apiProviderData['url'], $postData);
                $apiData = $response->json();

                if (isset($apiData['order'])) {
                    $order->update([
                        'status_description' => "order: {$apiData['order']}",
                        'api_order_id' => $apiData['order']
                    ]);
                } else {
                    $order->update([
                        'status_description' => "error: {$apiData['error']}",
                    ]);
                }
            }

            $user->decrement('balance', $price);

            $transaction = Transaction::create([
                'transactional_id' => $order->id,
                'transactional_type' => Order::class,
                'user_id' => $user->id,
                'trx_type' => '-',
                'amount' => $price,
                'remarks' => 'Place order',
                'charge' => 0
            ]);

            if (!$transaction) {
                DB::rollBack();
                return response([
                    'status' => 'error',
                    'message' => 'Something went wrong with the transaction balance. Please try again.'
                ]);
            }

            $msg = [
                'username' => $user->username,
                'price' => currencyPosition($price),
                'order_id' => $order->id,
            ];

            $action = [
                "link" => route('admin.order'),
                "image" => getFile($user->image_driver, $user->image),
                "icon" => "fas fa-cart-plus text-white"
            ];

            $this->adminPushNotification('ORDER_CREATE', $msg, $action);
            $this->adminFirebasePushNotification('ORDER_CREATE', $msg, route('admin.order'));

            $this->sendMailSms($user, 'ORDER_CONFIRM', [
                'order_id' => $order->id,
                'order_at' => $order->created_at,
                'service' => optional($order->service)->service_title,
                'status' => $order->status,
                'paid_amount' => $price,
                'remaining_balance' => $user->balance,
                'currency' => basicControl()->currency,
                'transaction' => $transaction->trx_id,
            ]);

            DB::commit();
            return response([
                'status' => 'success',
                'message' => 'Your order has been submitted'
            ]);

        } else {
            DB::rollBack();
            return response([
                'status' => 'error',
                'message' => "Order quantity should be minimum {$service->min_amount} and maximum {$service->max_amount}"
            ]);
        }
    }


    public function index(Request $request, $status = null)
    {
        $search = $request->all();
        $currency = Currency::where('code', auth()->user()->currency)->first();
        $orders = Order::with(['service:id,service_title,service_status,description,refill'])->orderBy('id', 'desc')
            ->where('user_id', auth()->user()->id)
            ->when(isset($status), function ($query) use ($status) {
                return $query->where('status', $status);
            })
            ->when(!empty($search['order_id']), function ($query) use ($search) {
                return $query->where('id', $search['order_id']);
            })
            ->when(!empty($search['service']), function ($query) use ($search) {
                $query->whereHas('service', function ($qry) use ($search) {
                    return $qry->where('service_title', 'like', '%' . $search['service'] . '%');
                });
            })
            ->when(isset($search['status']), function ($query) use ($search) {
                return $query->where('status', $search['status']);
            })
            ->when(isset($search['from_date']) && isset($search['to_date']), function ($query) use ($search) {
                return $query->whereBetween('created_at', [$search['from_date'], $search['to_date']]);
            })
            ->when(isset($search['from_date']) && !isset($search['to_date']), function ($query) use ($search) {
                return $query->whereDate('created_at', isset($search['from_date']));
            })
            ->when(!isset($search['from_date']) && isset($search['to_date']), function ($query) use ($search) {
                return $query->whereDate('created_at', $search['to_date']);
            })
            ->paginate(15);
        return view(template() . 'user.order.index', compact('orders', 'currency'));
    }

    public function showOrderRefill(Request $request, $status = null): view
    {
        $search = $request->all();
        $data['currency'] = Currency::where('code', auth()->user()->currency)->first();
        $data['orders'] = Order::with(['service:id,service_title,service_status,description,refill'])
            ->where('user_id', Auth::id())
            ->when(isset($status), function ($query) use ($status) {
                return $query->where('status', $status);
            })
            ->when(!empty($search['order_id']), function ($query) use ($search) {
                return $query->where('id', $search['order_id']);
            })
            ->when(!empty($search['service']), function ($query) use ($search) {
                $query->whereHas('service', function ($qry) use ($search) {
                    return $qry->where('service_title', 'like', '%' . $search['service'] . '%');
                });
            })
            ->when(isset($search['status']), function ($query) use ($search) {
                return $query->where('status', $search['status']);
            })
            ->when(isset($search['from_date']) && isset($search['to_date']), function ($query) use ($search) {
                return $query->whereBetween('created_at', [$search['from_date'], $search['to_date']]);
            })
            ->when(isset($search['from_date']) && !isset($search['to_date']), function ($query) use ($search) {
                return $query->whereDate('created_at', isset($search['from_date']));
            })
            ->when(!isset($search['from_date']) && isset($search['to_date']), function ($query) use ($search) {
                return $query->whereDate('created_at', $search['to_date']);
            })
            ->whereNotNull('refill_status')
            ->whereNotNull('refilled_at')
            ->orderBy('id', 'desc')->paginate(15);
        return view(template() . 'user.order.show_order_refill', $data);
    }

    public function orderRefill(Request $request, $id): \Illuminate\Http\RedirectResponse
    {
        $order = Order::with('service', 'service.provider')->findOrFail($id);
        try {
            if ($order->status == 'completed' && $order->remains > 0 && optional($order->service)->refill == 1 && ($order->refilled_at == null || Carbon::parse($order->refilled_at) < Carbon::now()->subHours(24)) && (!isset($order->refill_status) || $order->refill_status == 'completed' || $order->refill_status == 'partial' || $order->refill_status == 'canceled' || $order->refill_status == 'refunded')) {
                if (optional($order->service)->is_refill_automatic == 1) {
                    if (optional(optional($order->service)->provider)->status != 1) {
                        return back()->with('error', 'You are not eligible to send refill request.');
                    }

                    $response = Http::post(optional(optional($order->service)->provider)->url, [
                        'key' => optional(optional($order->service)->provider)->api_key,
                        'action' => 'refill',
                        'order' => $order->api_order_id,
                    ]);

                    if (isset($response->refill)) {
                        $order->update([
                            'api_refill_id' => $response['refill'],
                            'refill_status' => 'awaiting',
                            'refilled_at' => now(),
                        ]);
                    } else {
                        return back()->with('error', 'You are not eligible to send refill request.');
                    }
                } else {
                    $order->update([
                        'refill_status' => 'awaiting',
                        'refilled_at' => now(),
                    ]);
                }
            } else {
                return back()->with('error', 'You are not eligible to send refill request.');
            }
            return back()->with('success', 'Refill request has been submitted');
        } catch (\Exception $e) {
            return back()->with('error', 'Something went wrong, Please try again.');
        }
    }

    public function showDripFeed(Request $request, $status = null)
    {
        $search = $request->all();
        $data['currency'] = Currency::where('code', auth()->user()->currency)->first();
        $data['orders'] = Order::with(['service'])->where('drip_feed', '!=', NULL)
            ->orderBy('id', 'desc')->where('user_id', auth()->user()->id)
            ->when(isset($status), function ($query) use ($status) {
                return $query->where('status', $status);
            })
            ->when(!empty($search['order_id']), function ($query) use ($search) {
                return $query->where('id', $search['order_id']);
            })
            ->when(!empty($search['service']), function ($query) use ($search) {
                $query->whereHas('service', function ($qry) use ($search) {
                    return $qry->where('service_title', 'like', '%' . $search['service'] . '%');
                });
            })
            ->when(isset($search['status']), function ($query) use ($search) {
                return $query->where('status', $search['status']);
            })
            ->when(isset($search['from_date']) && isset($search['to_date']), function ($query) use ($search) {
                return $query->whereBetween('created_at', [$search['from_date'], $search['to_date']]);
            })
            ->when(isset($search['from_date']) && !isset($search['to_date']), function ($query) use ($search) {
                return $query->whereDate('created_at', isset($search['from_date']));
            })
            ->when(!isset($search['from_date']) && isset($search['to_date']), function ($query) use ($search) {
                return $query->whereDate('created_at', $search['to_date']);
            })
            ->paginate(15);
        return view(template() . 'user.order.show_drip_feed_order', $data);
    }
}
