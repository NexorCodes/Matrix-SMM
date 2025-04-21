<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Currency;
use App\Models\SocialMedia;
use App\Traits\PageSeo;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ServiceController extends Controller
{
    use PageSeo;

    public function services()
    {
        $data['footer'] = footerData();
        $data['socialMedia'] = SocialMedia::has('category')->with('category')->orderBy('id', 'asc')->get();
        $data['pageSeo'] = $this->pageSeoData('services', basicControl()->theme);
        return view(template() . 'service', $data);
    }

    public function getServices(Request $request)
    {
        $socialMedia = $request->social_media_id;
        $categoryId = $request->category;
        $searchTerm = $request->search;

        $currency = Currency::where('code', $request->currency)->first();
        $categories = Category::with('socialMedia')
            ->select('id', 'category_title', 'social_media_id')
            ->with(['service' => function ($query) {
                $query->select('id', 'category_id', 'service_title', 'price', 'min_amount', 'max_amount', 'description', 'service_status')
                    ->where('service_status', 1)->userRate();
            }])
            ->whereHas('service', function ($query) {
                $query->whereHas('provider', function ($q) {
                    $q->where('status', 1);
                })->orWhereDoesntHave('provider');
            })
            ->when(isset($socialMedia) && $socialMedia != 0, function ($query) use ($socialMedia) {
                return $query->where('social_media_id', $socialMedia);
            })
            ->when(isset($categoryId), function ($query) use ($categoryId) {
                return $query->where('id', $categoryId);
            })
            ->when($searchTerm, function ($query) use ($searchTerm) {
                return $query->where(function ($query) use ($searchTerm) {
                    $query->whereRaw("category_title REGEXP '\\\\b{$searchTerm}\\\\b'")
                        ->orWhereHas('service', function ($query) use ($searchTerm) {
                            $query->whereRaw("service_title REGEXP '\\\\b{$searchTerm}\\\\b'");
                        });
                });
            })
            ->where('status', 1)
            ->paginate(basicControl()->paginate);

        $categories->getCollection()->transform(function ($category) use ($currency) {
            foreach ($category->service as $service) {
                $service->service_title = Str::limit($service->service_title, 50);
                $service->price = currencyPosition(doubleval($service->price));
                $service->min_amount = conveter($service->min_amount);
                $service->max_amount = conveter($service->max_amount);
                if ($currency) {
                    if ($service->user_rate) {
                        $service->priceSelectedCurrency = currencyPositionBySelectedCurrency(doubleval($service->user_rate) * doubleval($currency->conversion_rate), $currency->code);
                    } else {
                        $service->priceSelectedCurrency = currencyPositionBySelectedCurrency(doubleval($service->price) * doubleval($currency->conversion_rate), $currency->code);
                    }
                }
            }
            return $category;
        });

        return response()->json($categories);
    }

    public function getCategory(Request $request)
    {
        $socialMedia = $request->social_media_id;
        $categories = Category::select('id', 'category_title', 'social_media_id')
            ->when(isset($socialMedia), function ($query) use ($socialMedia) {
                return $query->where('social_media_id', $socialMedia);
            })
            ->where('status', 1)
            ->get();
        return response()->json($categories);
    }
}
