<?php

namespace App\Http\Controllers;

use App\Models\ApiProvider;
use App\Models\Order;
use App\Models\Service;
use App\Models\Transaction;
use App\Models\User;
use App\Traits\GetChildPanel;
use App\Traits\Notify;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use Modules\ChildPanel\App\Models\ChildPanel;

class ApiController extends Controller
{
    use Notify, GetChildPanel;

    public function apiV1(Request $request)
    {
        $req = $request->all();
        $validator = Validator::make($req, [
            'key' => 'required',
            'action' => 'required|in:balance,services,add,create_order,status,orders,refill,refill_status',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()->first()], 422);
        }

        $user = User::where('api_token', $req['key'])->first(['id', 'api_token', 'status', 'balance']);
        if (!$user || $user->status == 0) {
            return response()->json(['error' => "The selected key is invalid."], 422);
        }

        $basic = basicControl();
        if (strtolower($req['action']) == 'balance') {

            $result['status'] = 'success';
            $result['balance'] = $user->balance;
            $result['currency'] = $basic->base_currency;
            return response()->json($result, 200);

        } elseif (strtolower($req['action']) == 'services') {

            $result = $this->getServices();
            return response()->json($result, 200);

        } elseif (strtolower($req['action']) == 'add') {
            $validator = Validator::make($req, [
                'service' => 'required',
                'link' => 'required',
                'quantity' => 'required|integer'
            ]);
            if ($validator->fails()) {
                return response()->json(['error' => $validator->messages()->first()], 422);
            }
            $service = Service::where('id', $req['service'])->where('service_status', 1)->first();
            if (!$service) {
                return response()->json(['error' => "The selected service is invalid."], 422);
            }
            $quantity = $req['quantity'];
            if ($service->drip_feed == 1) {
                $rules['runs'] = 'required|integer|not_in:0';
                $rules['interval'] = 'required|integer|not_in:0';
                $validator = Validator::make($req, $rules);
                if ($validator->fails()) {
                    return response()->json(['error' => $validator->messages()->first()], 422);
                }
                $quantity = $req['quantity'] * $req['runs'];
            }
            if ($service->min_amount <= $quantity && $service->max_amount >= $quantity) {
                $price = round(($quantity * $service->price) / 1000, 2);

                if ($user->balance < $price) {
                    return response()->json(['error' => "You don't have sufficient balance."], 400);
                }

                $order = new Order();
                $order->user_id = $user->id;
                $order->category_id = $service->category_id;
                $order->service_id = $service->id;
                $order->link = $req['link'];
                $order->quantity = $req['quantity'];
                $order->status = 'processing';
                $order->price = $price;
                $order->runs = isset($req['runs']) ? $req['runs'] : null;
                $order->interval = isset($req['interval']) ? $req['interval'] : null;
                if (isset($service->api_provider_id)) {
                    $apiproviderdata = ApiProvider::find($service->api_provider_id);
                    $postData = [
                        'key' => $apiproviderdata['api_key'],
                        'action' => 'add',
                        'service' => $service->api_service_id,
                        'link' => $req['link'],
                        'quantity' => $req['quantity']
                    ];
                    if (isset($req['runs']))
                        $postData['runs'] = $req['runs'];

                    if (isset($req['interval']))
                        $postData['interval'] = $req['interval'];

                    $response = Http::post($apiproviderdata['url'], $postData);

                    $apidata = $response->json();
                    if (isset($apidata->order)) {
                        $order->status_description = "order: {$apidata['order']}";
                        $order->api_order_id = $apidata['order'];
                    } else {
                        $order->status_description = "error: {$apidata['error']}";
                    }
                }
                $order->save();

                $user->balance -= $price;
                $user->save();

                $transaction = new Transaction();
                $transaction->user_id = $user->id;
                $transaction->trx_type = '-';
                $transaction->amount = $price;
                $transaction->remarks = 'Place order';
                $transaction->charge = 0;
                $transaction->save();

                $this->sendMailSms($user, 'ORDER_CONFIRM', [
                    'order_id' => $order->id,
                    'order_at' => $order->created_at,
                    'service' => optional($order->service)->service_title,
                    'status' => $order->status,
                    'paid_amount' => $price,
                    'remaining_balance' => $user->balance,
                    'currency' => $basic->currency,
                    'transaction' => $transaction->trx_id,
                ]);

                $result['status'] = 'success';
                $result['order'] = $order->id;
                return response()->json($result, 200);

            } else {
                return response()->json(['error' => "Order quantity should be minimum {$service->min_amount} and maximum {$service->max_amount}."], 400);
            }

        } elseif (strtolower($req['action']) == 'create_order') {

            $result = $this->createOrder($req, $user);
            return response()->json($result, 200);

        } elseif (strtolower($req['action']) == 'status') {
            $validator = Validator::make($req, [
                'order' => 'required'
            ]);
            if ($validator->fails()) {
                return response()->json(['error' => $validator->messages()->first()], 422);
            }

            $order = Order::where('id', $req['order'])->where('user_id', $user->id)->first();
            if (!$order) {
                return response()->json(['error' => "The selected order id is invalid."], 422);
            }

            $result['status'] = strtoupper($order->status);
            $result['charge'] = $order->service['api_provider_price'];
            $result['start_count'] = (int)$order->start_count;
            $result['remains'] = (int)$order->remains;
            $result['currency'] = $basic->currency;
            return response()->json($result, 200);

        } elseif (strtolower($req['action']) == 'orders') {
            $validator = Validator::make($req, [
                'orders' => 'required'
            ]);
            if ($validator->fails()) {
                return response()->json(['error' => $validator->messages()->first()], 422);
            }
            $orders = explode(",", $req['orders']);

            $result = Order::whereIn('id', $orders)->where('user_id', $user->id)->get()->map(function ($order) {
                return [
                    'order' => $order->id,
                    'status' => strtoupper($order->status),
                    'charge' => $order->service['api_provider_price'],
                    'start_count' => (int)$order->start_count,
                    'remains' => (int)$order->remains
                ];
            });
            return response()->json($result, 200);

        } elseif (strtolower($req['action']) == 'refill') {
            $validator = Validator::make($req, [
                'order' => 'required'
            ]);
            if ($validator->fails()) {
                return response()->json(['error' => $validator->messages()->first()], 422);
            }

            $order = Order::with('service', 'service.provider')->where('id', $req['order'])->where('user_id', $user->id)->first();
            if (!$order) {
                return response()->json(['error' => "The selected order id is invalid."], 422);
            }

            if ($order->status == 'completed' && $order->remains > 0 && optional($order->service)->refill == 1 && $order->refilled_at->lt(Carbon::now()->subHours(24)) && (!isset($order->refill_status) || $order->refill_status == 'completed' || $order->refill_status == 'partial' || $order->refill_status == 'canceled' || $order->refill_status == 'refunded')) {
                if (optional($order->service)->is_refill_automatic == 1) {
                    if (optional(optional($order->service)->provider)->status != 1) {
                        return response()->json(['error' => "You are not eligible to send refill request."], 400);
                    }

                    $providerUrl = optional(optional($order->service)->provider)->url;
                    $apiKey = optional(optional($order->service)->provider)->api_key;
                    $apiOrderId = $order->api_order_id;

                    $refillResponse = Http::post($providerUrl, [
                        'key' => $apiKey,
                        'action' => 'refill',
                        'order' => $apiOrderId,
                    ]);

                    $response = $refillResponse->json();

                    if (isset($response['refill'])) {
                        $order->api_refill_id = $response['refill'];
                        $order->refill_status = 'awaiting';
                        $order->refilled_at = now();
                        $order->save();
                    } else {
                        return response()->json(['error' => "You are not eligible to send refill request."], 400);
                    }
                } else {
                    $order->refill_status = 'awaiting';
                    $order->refilled_at = now();
                    $order->save();
                }
            } else {
                return response()->json(['error' => "You are not eligible to send refill request."], 400);
            }
            return response()->json(['refill' => $order->id], 200);

        } elseif (strtolower($req['action']) == 'refill_status') {
            $validator = Validator::make($req, [
                'refill' => 'required'
            ]);
            if ($validator->fails()) {
                return response()->json(['error' => $validator->messages()->first()], 422);
            }

            $order = Order::where('id', $req['order'])->where('user_id', $user->id)->whereNotNull('refill_status')->first();
            if (!$order) {
                return response()->json(['error' => "The selected refill id is invalid."], 422);
            }

            $result['status'] = strtoupper($order->refill_status);
        }
    }

    public function getServices()
    {
        $services = Service::where('service_status', 1)
            ->get()
            ->map(function ($service) {
                return [
                    'service' => $service->id,
                    'name' => $service->service_title,
                    'category' => optional($service->category)->category_title,
                    'rate' => $service->price,
                    'min' => $service->min_amount,
                    'max' => $service->max_amount,
                    'drip_feed' => $service->drip_feed,
                    'refill' => $service->refill,
                    'type' => $service->service_type,
                    'currency' => basicControl()->base_currency,
                    'api_provider_id' => $service->api_provider_id,
                ];
            });

        return $services;
    }

    public function createOrder($req, $user): \Illuminate\Http\JsonResponse|array
    {
        $validator = Validator::make($req, [
            'service' => 'required|exists:services,id,service_status,1',
            'link' => 'required',
            'quantity' => 'required|integer|not_in:0'
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()->first()], 422);
        }


        $service = Service::find($req['service']);
        if ($service->drip_feed == 1 && (!empty($req['type']) && $req['type'] != 'Mass')) {
            $validator = Validator::make($req, [
                'runs' => 'required|integer|not_in:0',
                'interval' => 'required|integer|not_in:0'
            ]);

            if ($validator->fails()) {
                return response()->json(['error' => $validator->messages()->first()], 422);
            }
            $quantity = $req['quantity'] * $req['runs'];
        } else {
            $quantity = $req['quantity'];
        }

        $price = round(($quantity * $service->price) / 1000, 2);

        $child_panel = ChildPanel::where('id', $req['child_panel_id'])->first();

        if (optional($child_panel->user)->balance < $price) {
            $result['reason'] = "You don't have sufficient balance.";
            return $result;
        }

        if ($service->min_amount <= $quantity && $service->max_amount >= $quantity) {
            if ($user->balance < $price) {
                return response()->json(['error' => "You don't have sufficient balance."], 400);
            }

            if (isset($service->api_provider_id)) {
                $providerData = ApiProvider::where('id', $service->api_provider_id)->first();
                $postData = [
                    'key' => $providerData['api_key'],
                    'action' => 'add',
                    'service' => $service->api_service_id,
                    'link' => $req['link'],
                    'quantity' => $req['quantity']
                ];
                if (isset($req['runs']))
                    $postData['runs'] = $req['runs'];

                if (isset($req['interval']))
                    $postData['interval'] = $req['interval'];

                $response = Http::post($providerData['url'], $postData);
                $apiData = $response->json();
            }

            $user->balance -= $price;
            $user->save();

            $transaction = new Transaction();
            $transaction->user_id = $user->id;
            $transaction->child_panel_id = $child_panel->id;
            $transaction->trx_type = '-';
            $transaction->amount = $price;
            $transaction->remarks = 'Place order from child panel';
            $transaction->charge = 0;
            $transaction->save();

            $result['status'] = 'success';
            $result['order'] = $apiData['order'] ?? null;
            $result['error'] = $apiData['error'] ?? null;

            return $result;
        } else {
            return response()->json(['error' => "Order quantity should be minimum {$service->min_amount} and maximum {$service->max_amount}."], 400);
        }
    }
}
