<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Currency\CurrencyStoreRequest;
use App\Models\Currency;
use Facades\App\Services\CurrencyLayerService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class CurrencyController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->all();
        $data['currencies'] = Currency::orderBy('id', 'desc')
            ->when(!empty($search['search']), function ($query) use ($search) {
                $query->where('name', 'like', '%' . $search['search'] . '%')
                    ->orWhere('code', 'like', '%' . $search['search'] . '%');
            })
            ->when(isset($search['status']), function ($query) use ($search) {
                return $query->where('status', $search['status']);
            })
            ->paginate(basicControl()->paginate);
        return view('admin.currency.index', $data);
    }

    public function create()
    {
        return view('admin.currency.create');
    }

    public function store(CurrencyStoreRequest $request)
    {
        $data = $request->validated();
        try {
            $currencyRes = Currency::create([
                'name' => $data['name'],
                'code' => $data['code'],
                'symbol' => $data['symbol'],
                'conversion_rate' => $data['conversion_rate'],
                'status' => $data['status']
            ]);

            throw_if(!$currencyRes, 'Something went wrong, Please try again');

            return back()->with('success', 'Currency added successfully.');
        } catch (\Exception $exception) {
            return back()->with('error', $exception->getMessage());
        }
    }

    public function edit(string $id)
    {
        try {
            $data['currency'] = Currency::where('id', $id)->firstOr(function () {
                throw new \Exception('Currency is not available.');
            });
            return view('admin.currency.edit', $data);
        } catch (\Exception $exception) {
            return back()->with('error', $exception->getMessage());
        }
    }

    public function update(CurrencyStoreRequest $request, string $id)
    {
        $data = $request->validated();
        try {
            $currency = Currency::where('id', $id)->firstOr(function () {
                throw new \Exception('Currency is not available.');
            });

            $currencyRes = $currency->update([
                'name' => $data['name'],
                'code' => $data['code'],
                'symbol' => $data['symbol'],
                'conversion_rate' => $data['conversion_rate'],
                'status' => $data['status']
            ]);

            throw_if(!$currencyRes, 'Something went wrong, Please try again');

            return back()->with('success', 'Currency updated successfully.');
        } catch (\Exception $exception) {
            return back()->with('error', $exception->getMessage());
        }
    }

    public function destroy(string $id)
    {
        try {
            $currency = Currency::where('id', $id)->firstOr(function () {
                throw new \Exception('Currency is not available.');
            });
            $currency->delete();
            return back()->with('success', 'Currency deleted successfully.');
        } catch (\Exception $exception) {
            return back()->with('error', $exception->getMessage());
        }
    }

    public function statusChange(Request $request, $id)
    {
        try {
            $currency = Currency::where('id', $id)->firstOr(function () {
                throw new \Exception('Currency is not available.');
            });

            $currency->update([
                'status' => $request->status == 0 ? 1 : 0
            ]);

            throw_if(!$currency, 'Something went wrong, Please try again.');

            return back()->with('success', 'Currency status changed successfully.');
        } catch (\Exception $exception) {
            return back()->with('error', $exception->getMessage());
        }
    }

    public function autoUpdateCurrency(Request $request)
    {
        $currencyData = Currency::where('status', 1)->get();

        $endpoint = 'live';
        $source = basicControl()->base_currency;
        $currency_layer_url = "http://api.currencylayer.com";
        $currency_layer_access_key = basicControl()->currency_layer_access_key;

        $currencyCode = [];
        foreach ($currencyData as $currency) {
            $currencyCode[] = $currency->code;
        }

        $currencyCode = array_unique($currencyCode);
        $currencies = implode(',', $currencyCode);

        $CurrencyAPIUrl = "$currency_layer_url/$endpoint?access_key=$currency_layer_access_key&source=$source&currencies=$currencies";

        $response = Http::acceptJson()
            ->get($CurrencyAPIUrl);

        $responseData = $response->json();

        $currencyUpdateRate = [];
        foreach ($responseData['quotes'] as $key => $quote) {
            $strReplace = str_replace($responseData['source'], '', $key);
            $currencyUpdateRate[$strReplace] = $quote;
        }

        foreach ($currencyUpdateRate as $currencyCode => $conversionRate) {
            $currency = Currency::where('code', $currencyCode)->first();
            if ($currency) {
                $currency->update([
                    'conversion_rate' => $conversionRate
                ]);
            }
        }

        basicControl()->update([
            'auto_currency_update' => $request->auto_update_currency == 0 ? 1 : 0
        ]);

        return back()->with('success', 'Currency rate updated successfully.');
    }
}
