<?php

namespace App\Http\Controllers;

use App\DirectMessage;
use App\Office;
use App\Payment;
use App\Post;
use App\PostAttachment;
use App\PostResponse;
use App\Sms;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        if (Auth::user()->iduser_role <= 2) {
            return view('index', [
                'title' => __('Dashboard'),
                'officeCount' => $this->numberOfOffices(),
                'usersCount' => $this->numberOfUsers(),
                'totalStorage' => $this->totalStorage(),
                'offices' => $this->getOffices(),
            ]);
        }
        else if (Auth::user()->iduser_role == 3) {

            return view('index', [
                'title' => __('Dashboard'),
                'currentStorage' => $this->currentStorage(),
                'nextPayment' => $this->getNextPaymentDate(),
                'agentsCount' => $this->getAgentCount(),
                'memberCount' => $this->getMemberCount(),
                'postCount' => $this->getPostCount(),
                'comments' => $this->latestComments(),
                'posts' => $this->latestPost(),
                'responseChart' => $this->getResponseCategories(),
                'referralCode' => $this->getReferral(),
                'smsLimit' => $this->getSmsLimit(),
//                'bars' => $this->createBarChart()
            ]);
        } else if (Auth::user()->iduser_role == 4) {
            return view('index', [
                'title' => __('Dashboard'),
                'comments' => $this->latestComments(),
                'posts' => $this->latestPost(),
                'agentsCount' => $this->getAgentCount(),
                'memberCount' => $this->getMemberCount(),
                'postCount' => $this->getPostCount(),
//                'responseChart' => $this->getResponseCategories(),
                'bars' => $this->createBarChart(),

            ]);
        }  else if (Auth::user()->iduser_role == 5) {
            return view('index', [
                'title' => __('Dashboard'),
                'responseChart' => $this->getResponseCategories()
            ]);
        }else if (Auth::user()->iduser_role == 8) {
            return view('index', [
                'title' => __('Dashboard'),
                'pendingPostCount' => $this->getPendingPostCount(),
                'comments' => $this->latestComments(),
                'posts' => $this->latestPost(),
                'pendingSmsCount' => $this->pendingSmsCount(),
            ]);
        }
        else if (Auth::user()->iduser_role == 9) {
            return view('index', [
                'title' => __('Dashboard'),
                'pendingPostCount' => $this->getPendingPostCount(),
                'comments' => $this->latestComments(),
                'posts' => $this->latestPost(),
                'pendingSmsCount' => $this->pendingSmsCount(),

            ]);
        }
        else if (Auth::user()->iduser_role == 10) {
            return view('index', [
                'title' => __('Dashboard'),
                'agentsCount' => $this->getAgentCount(),
                'memberCount' => $this->getMemberCount(),
                'postCount' => $this->getPostCount(),
                'posts' => $this->latestPost(),
                'comments' => $this->latestComments(),
                'responseChart' => $this->getResponseCategories(),

            ]);
        }

        return view('index', ['title' => __('Dashboard :')]);

    }

    //Office storage category
    public function getStorage()
    {
        $array = [];

        $responses = PostResponse::where('status', 1)->whereIn('response_type', [2, 3, 4])->whereHas('post', function ($q) {
            $q->where('idoffice', Auth::user()->idoffice)->where('status', 1);
        })->latest()->get()->groupBy('response_type');

        if ($responses != null) {
            foreach ($responses as $key => $value) {
                $size = ($value->sum('size') / 1000000);
                $array += [$key => $size];
            }
        }

        $attachments = PostAttachment::where('status', 1)->whereHas('post', function ($q) {
            $q->where('idoffice', Auth::user()->idoffice)->where('status', 1);
        })->latest()->get()->groupBy('file_type');

        if ($attachments != null) {
            foreach ($attachments as $key => $value) {
                $type = $key + 1;
                $size = ($value->sum('size') / 1000000);
                if(isset($array[$type])){
                    $array[$type] += $size;

                }
                else{
                    $array += [$type => $size];
                }
            }
        }

        if ($array != null) {
            foreach ($array as $key => $value) {
                $array[$key] = round($value, 2);
            }
        }

        return $array;
    }
    //Office storage category end

    //Get barchart data
    public function createBarChart()
    {
        $bars = collect();

        $bars->push(['y' => 'Jan', 'a' => Post::whereYear('created_at', '=', date('Y'))->whereMonth('created_at', '=', 1)->count()]);
        $bars->push(['y' => 'Feb', 'a' => Post::whereYear('created_at', '=', date('Y'))->whereMonth('created_at', '=', 2)->count()]);
        $bars->push(['y' => 'Mar', 'a' => Post::whereYear('created_at', '=', date('Y'))->whereMonth('created_at', '=', 3)->count()]);
        $bars->push(['y' => 'Apr', 'a' => Post::whereYear('created_at', '=', date('Y'))->whereMonth('created_at', '=', 4)->count()]);
        $bars->push(['y' => 'May', 'a' => Post::whereYear('created_at', '=', date('Y'))->whereMonth('created_at', '=', 5)->count()]);
        $bars->push(['y' => 'Jun', 'a' => Post::whereYear('created_at', '=', date('Y'))->whereMonth('created_at', '=', 6)->count()]);
        $bars->push(['y' => 'Jul', 'a' => Post::whereYear('created_at', '=', date('Y'))->whereMonth('created_at', '=', 7)->count()]);
        $bars->push(['y' => 'Aug', 'a' => Post::whereYear('created_at', '=', date('Y'))->whereMonth('created_at', '=', 8)->count()]);
        $bars->push(['y' => 'Sep', 'a' => Post::whereYear('created_at', '=', date('Y'))->whereMonth('created_at', '=', 9)->count()]);
        $bars->push(['y' => 'Oct', 'a' => Post::whereYear('created_at', '=', date('Y'))->whereMonth('created_at', '=', 10)->count()]);
        $bars->push(['y' => 'Nov', 'a' => Post::whereYear('created_at', '=', date('Y'))->whereMonth('created_at', '=', 11)->count()]);
        $bars->push(['y' => 'Dec', 'a' => Post::whereYear('created_at', '=', date('Y'))->whereMonth('created_at', '=', 12)->count()]);

        return $bars;
    }

    //Get barchart data end

    public function getNextPaymentDate()
    {
        //Get new payment date
        $office = Office::find(intval(Auth::user()->idoffice));
        $officePayments = Payment::where('idoffice', $office->idoffice)->select('for_month')->get();
        if ($officePayments != null) {
            $lastPayment = $officePayments->max('for_month');
        } else {
            $lastPayment = null;
        }

        if ($lastPayment != null) {
            $nextPayment = date('Y-m-d', strtotime($lastPayment . '+1 month'));
        } else {
            $nextPayment = $office->payment_date;
        }
        return $nextPayment;
        //Get new payment date end
    }

    public function latestComments()
    {
        //last comments
        $comments = PostResponse::where('status', 1)->whereHas('post', function ($q) {
            $q->where('idoffice', Auth::user()->idoffice)->where('status', 1);
        })->latest()->limit(10)->get();
        return $comments;
        //last comments end
    }

    public function latestPost()
    {
        //last posts
        $posts = Post::where('idoffice', Auth::user()->idoffice)->where('status', 1)->latest()->limit(10)->get();
        return $posts;
        //last posts end
    }

    public function currentStorage()
    {
        //Office storage sum
        $currentResponse = PostResponse::where('status', 1)->whereHas('post', function ($q) {
            $q->where('idoffice', Auth::user()->idoffice)->where('status', 1);
        })->latest()->sum('size');

        $currentAttachments = PostAttachment::where('status', 1)->whereHas('post', function ($q) {
            $q->where('idoffice', Auth::user()->idoffice)->where('status', 1);
        })->latest()->sum('size');

        return round($currentAttachments + $currentResponse, 2);
        //Office storage sum end
    }

    public function getAgentCount()
    {
        //Get agents on office
        $agentsCount = User::where('idoffice', Auth::user()->idoffice)->where('iduser_role', 6)->whereIn('status', [1, 2])->count();
        return $agentsCount;
        //Get agents on office end
    }

    public function getMemberCount()
    {
        //Get members on office
        return User::whereHas('member', function ($q) {
            $q->whereHas('memberAgents', function ($q) {
                $q->where('idoffice', Auth::user()->idoffice)->whereIn('status', [1, 2]);
            });
        })->where('iduser_role', 7)->count();
        //Get members on office end
    }

    public function getPostCount()
    {
        //Post count
        return Post::where('idoffice', Auth::user()->idoffice)->count();
        //Post count end
    }

    public function getPendingPostCount()
    {
        //Post count
        return Post::where('idoffice', Auth::user()->idoffice)->where('status',2)->count();
        //Post count end
    }

    public function pendingSmsCount()
    {
        //Post count
        return Sms::where('idoffice', Auth::user()->idoffice)->where('status',2)->count();
        //Post count end
    }

    public function getResponseCategories()
    {
        $responses = collect();
        $cats = ['a' => 1, 'b' => 2, 'c' => 3, 'd' => 4];
        $months = [
            'Jan' => 1,
            'Feb' => 2,
            'Mar' => 3,
            'Apr' => 4,
            'May' => 5,
            'Jun' => 6,
            'Jul' => 7,
            'Aug' => 8,
            'Sep' => 9,
            'Oct' => 10,
            'Nov' => 11,
            'Dec' => 12
        ];
        foreach($months as $monthName=>$month) {
            $array = ['y' => $monthName];

            foreach ($cats as $key => $value) {
                $array[$key] = PostResponse::where('status', 1)->whereHas('post', function ($q) {
                    $q->where('idoffice', Auth::user()->idoffice)->where('status', 1);
                })->whereHas('analysis', function ($q) use ($value) {
                    $q->where('idsub_category', $value)->where('status', 1)->where('base', 1);
                })->whereYear('created_at', '=', date('Y'))->whereMonth('created_at', '=', $month)->count();
            }
            $responses->push($array);
        }
        return $responses;
    }

    public function numberOfOffices(){
        return Office::count();
    }

    public function numberOfUsers(){
        return User::count();
    }

    public function totalStorage(){
        $attachments = floatval(PostAttachment::sum('size'));
        $response = floatval(PostResponse::sum('size'));
        $directMessages = floatval(DirectMessage::sum('size'));

        return round(($attachments + $response + $directMessages)/1000000,2);
    }

    public function getOffices(){
        return Office::all();
    }

    public function getReferral(){
        //get referral code
        if(Auth::user()->iduser_role == 3 ){
            return Auth::user()->officeAdmin->referral_code;
        }
        else if(Auth::user()->iduser_role == 6 ){
            return Auth::user()->agent->referral_code;
        }
        else{
            return '';
        }
        //get referral code end
    }

    public function getSmsLimit(){
        if(Auth::user()->office->sms_module != 1){
            return null;
        }
        $limit = Auth::user()->office->smaLimit != null ? Auth::user()->office->smaLimit->limit : 0;
        $used = Auth::user()->office->smaLimit != null ? Auth::user()->office->smaLimit->current : 0;

        return $used.'/'.$limit;
    }
}
