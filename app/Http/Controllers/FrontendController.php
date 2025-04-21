<?php

namespace App\Http\Controllers;

use App\Http\Requests\Frontend\ContactSendRequest;
use App\Http\Requests\Frontend\SubscribeStoreRequest;
use App\Mail\SendMail;
use App\Models\Currency;
use App\Models\PageDetail;
use App\Models\Subscribe;
use App\Traits\Frontend;
use App\Traits\Notify;
use App\Traits\PageSeo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class FrontendController extends Controller
{
    use Frontend, PageSeo, Notify;

    public function page($slug = '/')
    {

        try {
            $connection = DB::connection()->getPdo();

            $selectedTheme = getTheme();
            if (!$connection) {
                throw new \Exception("Unable to establish a connection to the database. Please check your connection settings and try again later");
            }
            $existingSlugs = collect([]);
            DB::table('pages')->select('slug')->get()->map(function ($item) use ($existingSlugs) {
                $existingSlugs->push($item->slug);
            });

            if (!in_array($slug, $existingSlugs->toArray())) {
                abort(404);
            }

            $pageDetails = PageDetail::with('page')
                ->whereHas('page', function ($query) use ($slug, $selectedTheme) {
                    $query->where(['slug' => $slug, 'template_name' => $selectedTheme]);
                })
                ->firstOrFail();

            $footer = footerData();
            $pageSeo = $this->pageSeoData($slug, $selectedTheme);
            $sectionsData = $this->getSectionsData($pageDetails->sections, $pageDetails->content, $selectedTheme);

            return view("themes.{$selectedTheme}.page", compact('sectionsData', 'pageSeo', 'footer'));
        } catch (\Exception $exception) {
            \Cache::forget('ConfigureSetting');

            if ($exception->getCode() == 404) {
                abort(404);
            }
            if ($exception->getCode() == 403) {
                abort(403);
            }
            if ($exception->getCode() == 401) {
                abort(401);
            }
            if ($exception->getCode() == 503) {
                return redirect()->route('maintenance');
            }
            if ($exception->getCode() == "42S02") {
                die($exception->getMessage());
            }
            if ($exception->getCode() == 1045) {
                die("Access denied. Please check your username and password.");
            }
            if ($exception->getCode() == 1044) {
                die("Access denied to the database. Ensure your user has the necessary permissions.");
            }
            if ($exception->getCode() == 1049) {
                die("Unknown database. Please verify the database name exists and is spelled correctly.");
            }
            if ($exception->getCode() == 2002) {
                die("Unable to connect to the MySQL server. Check the database host and ensure the server is running.");
            }
            if ($exception->getCode() == 0) {
                die("Unknown connection issue. Verify your connection parameters and server status.");
            }
            return redirect()->route('instructionPage');
        }
    }

    public function subscribe(SubscribeStoreRequest $request)
    {
        try {
            $subscribe = Subscribe::create([
                'email' => $request->email
            ]);

            throw_if(!$subscribe, 'Something went wrong, while storing data.');

            return back()->with('success', 'Subscribed successfully.');
        } catch (\Exception $exception) {
            return back()->with('error', $exception->getMessage());
        }
    }

    public function contactSend(ContactSendRequest $request): \Illuminate\Http\RedirectResponse
    {
        $requestData = $request->except('_token', '_method');

        $name = $requestData['name'];
        $email_from = $requestData['email'];
        $subject = $requestData['subject'];
        $message = $requestData['message'] . "<br>Regards<br>" . $name;
        $from = $email_from;

        Mail::to(basicControl()->sender_email)->queue(new SendMail($from, $subject, $message));
        return back()->with('success', 'Mail has been sent successfully.');
    }

    public function setCurrency(Request $request)
    {
        $currency = Currency::where('code', $request->currency)->first();
        $cookie = cookie('currency', json_encode($currency));
        return response([
            'success' => true
        ])->cookie($cookie);
    }
}
