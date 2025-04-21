<?php

namespace App\Console\Commands;

use App\Models\Currency;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AutoCurrencyUpdate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:auto-currency-update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
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
        foreach ($responseData['quotes'] as $key => $quote){
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
            'auto_currency_update' => 1
        ]);

        $this->info("Currency rate updated successfully.");
    }
}
