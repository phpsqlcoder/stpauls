<?php

namespace App\Http\Controllers\MailingList;

use App\Helpers\Webfocus\Setting;
use App\Mail\MailingList\UnsubscribedMail;
use App\Mail\MailingList\WelcomeMail;
use App\MailingListModel\Group;
use App\MailingListModel\Subscriber;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SubscriberFrontController extends Controller
{
    public function subscribe(Request $request)
    {
        $newSubscriber = $request->validate([
            'email' => 'required|email',
            'first_name' => '',
            'last_name' => '',
            'alert_types' => ''
        ]);

        $subscriber = Subscriber::withTrashed()->where('email', $request->email)->first();
        if ($subscriber) {
            if ($subscriber->trashed()) {

                foreach($newSubscriber['alert_types'] as $typeId) {
                    $group = Group::find($typeId);
                    if ($group && ! $group->subscribers->containts($subscriber->id)) {
                        $group->subscribers()->attach($subscriber->id);
                    }
                }

                $subscriber->restore();
                return response()->json(['success' => true, 'message' => 'Thank you for subscribing again.']);
            } else {
                if (is_array($newSubscriber['alert_types']) && !empty($newSubscriber['alert_types'])) {
                    $subscribeToNewAlert = false;
                    foreach($newSubscriber['alert_types'] as $typeId) {
                        $group = Group::find($typeId);
                        if ($group && ! $group->subscribers->contains($subscriber->id)) {
                            $group->subscribers()->attach($subscriber->id);
                            $subscribeToNewAlert = true;
                        }
                    }

                    if ($subscribeToNewAlert)
                        return response()->json(['success' => true, 'message' => 'Thank you for subscribing again.']);
                    else
                        return response()->json(['failed' => true, 'message' => 'Your email is already in our list.']);

                } else {
                    return response()->json(['failed' => true, 'message' => 'Your email is already in our list.']);
                }
            }
        }

        $newSubscriber['code'] = Subscriber::generate_unique_code();

        $subscriber = Subscriber::create($newSubscriber);

        if (!empty($subscriber)) {

            if (is_array($newSubscriber['alert_types']) && !empty($newSubscriber['alert_types'])) {
                foreach($newSubscriber['alert_types'] as $typeId) {
                    $group = Group::find($typeId);
                    if ($group && ! $group->subscribers->contains($subscriber->id)) {
                        $group->subscribers()->attach($subscriber->id);
                    }
                }
            }

            \Mail::to($request->email)->send(new WelcomeMail(Setting::info(), $subscriber));
            return response()->json(['success' => true, 'message' => 'Thank you for subscribing.']);
        } else {
            return response()->json(['failed' => true, 'message' => 'Failed to subscribe. Please try again later.']);
        }

    }

    public function unsubscribe(Request $request, Subscriber $subscriber, $code)
    {
        if ($subscriber->code == $code) {

            \Mail::to($subscriber->email)->send(new UnsubscribedMail(Setting::info()));

            $subscriber->delete();

            return view('components.unsubscribed');

//            return "You’ve been successfully removed from our mailing list. <script>window.setTimeout(function(){window.location.href = '".url('/')."'}, 3000);";
        }

        abort(404);

    }
}

