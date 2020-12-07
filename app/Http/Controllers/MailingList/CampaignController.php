<?php

namespace App\Http\Controllers\MailingList;

use App\Permission;
use Facades\App\Helpers\NewListingHelper;
use App\Jobs\SendCampaignToSubscriberJob;
use App\MailingListModel\Campaign;
use App\MailingListModel\Group;
use App\MailingListModel\Subscriber;
use App\MailingListModel\SentCampaign;
use App\MailingListModel\SentCampaignSubscriber;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Validation\Rule;

class CampaignController extends Controller
{

    public function __construct()
    {
        Permission::module_init($this, 'campaign');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $searchFields = ['name'];

        $campaigns = NewListingHelper::simple_search(Campaign::class, $searchFields);

        // Simple search init data
        $filter = NewListingHelper::get_filter($searchFields);

        $searchType = 'simple_search';

        return view('admin.mailing-list.campaigns.index', compact('campaigns','filter', 'searchType'));
    }

    public function sent_campaigns(Request $request)
    {
        $searchFields = ['name'];

        $sentCampaigns = NewListingHelper::simple_search(SentCampaign::class, $searchFields);

        // Simple search init data
        $filter = NewListingHelper::get_filter($searchFields);

        $searchType = 'simple_search';

        return view('admin.mailing-list.campaigns.sent-campaigns', compact('sentCampaigns', 'filter', 'searchType'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $groups = Group::all();
        $subscribers = Subscriber::all();

        return view('admin.mailing-list.campaigns.create', compact('groups', 'subscribers'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $newCampaign = $this->validate_data($request);

        $campaign = Campaign::create($newCampaign);

        if ($request->submit == 'save and send') {

            $newCampaign['sender_id'] = auth()->id();
            $newCampaign['campaign_id'] = $campaign->id;

            $sentCampaign = SentCampaign::create($newCampaign);

            $recipients = $request->has('recipients') ? $request->recipients : [];
            foreach ($recipients as $recipientId) {
                $subscriber = Subscriber::find($recipientId);
//                Mail::to($subscriber)->send(new CampaignMail(Setting::info(), $campaign, $subscriber));
                $this->send_campaign_to_subscriber($campaign, $subscriber, $sentCampaign);
            }

            $recipientGroups = $request->has('recipient_groups') ? $request->recipient_groups : [];
            foreach ($recipientGroups as $groupId) {
                $group = Group::find($groupId);
                foreach ($group->subscribers as $subscriber) {
                    $this->send_campaign_to_subscriber($campaign, $subscriber, $sentCampaign, $groupId);
                }
            }
        }

        if ($campaign) {
            return redirect()->route('mailing-list.campaigns.index')->with(['success' => 'The campaign has been added and send to the recipient/s.']);
        } else {
            return redirect()->route('mailing-list.campaigns.create')->with(['error' => 'Failed to add campaign. Please try again.']);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Request $request
     * @param Campaign $campaign
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, Campaign $campaign)
    {
        $groups = Group::all();
        $subscribers = Subscriber::all();

        return view('admin.mailing-list.campaigns.edit', compact('campaign', 'groups', 'subscribers'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Campaign $campaign)
    {
        $campaignData = $this->validate_data($request);

        $campaign->update($campaignData);

        if ($request->submit == 'save and send') {

            $campaignData['sender_id'] = auth()->id();
            $campaignData['campaign_id'] = $campaign->id;

            $sentCampaign = SentCampaign::create($campaignData);

            $recipients = $request->has('recipients') ? $request->recipients : [];
            foreach ($recipients as $recipientId) {
                $subscriber = Subscriber::find($recipientId);
                $this->send_campaign_to_subscriber($campaign, $subscriber, $sentCampaign);
            }

            $recipientGroups = $request->has('recipient_groups') ? $request->recipient_groups : [];
            foreach ($recipientGroups as $groupId) {
                $group = Group::find($groupId);
                foreach ($group->subscribers as $subscriber) {
                    $this->send_campaign_to_subscriber($campaign, $subscriber, $sentCampaign, $groupId);
                }
            }
        }

        if ($campaign) {
            return redirect()->route('mailing-list.campaigns.index')->with(['success' => 'The campaign has been added and send to the recipient/s.']);
        } else {
            return redirect()->route('mailing-list.campaigns.create')->with(['error' => 'Failed to add campaign. Please try again.']);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Campaign $campaign
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function destroy(Campaign $campaign)
    {
        if ($campaign->delete()) {
            return back()->with('success', 'The campaign has been deleted');
        } else {
            return back()->with('error', 'Failed to delete a campaign. Please try again.');
        }
    }

    public function destroy_many()
    {
        $deleteIds = explode(',', request('ids'));
        if (sizeof($deleteIds) > 0 ) {
            $delete = Campaign::whereIn('id', $deleteIds)->delete();
            if ($delete) {
                return back()->with('success', 'The download manager category\s has been deleted');
            }
        }

        return back()->with('error', 'Failed to delete download manager category\s.');
    }

    public function restore($id)
    {
        Campaign::withTrashed()->findOrFail($id)->restore();

        return back()->with('success', 'The download manager category has been restored');
    }

    /**
     * @param Campaign $campaign
     * @param Subscriber $subscriber
     * @param SentCampaign $sentCampaign
     * @param null $groupId
     */
    public function send_campaign_to_subscriber(Campaign $campaign, Subscriber $subscriber, SentCampaign $sentCampaign, $groupId = null): void
    {
        $campaignHistory = new SentCampaignSubscriber();
        $campaignHistory->sent_campaign_id = $sentCampaign->id;
        $campaignHistory->group_id = $groupId;
        $campaignHistory->subscriber_id = $subscriber->id;
        $campaignHistory->mailing_type = $groupId ? "group" : "individual";
        $campaignHistory->save();

        dispatch(new SendCampaignToSubscriberJob($subscriber, $campaign, $campaignHistory));
    }

    public function validate_data($request)
    {
        return $request->validate([
            'name' => 'max:150|required',
            'subject' => 'max:150|required',
            'content' => 'required',
            'submit' => 'required',
            'recipients' => Rule::requiredIf(function() use ($request) {
                return $request->submit == 'save and send' && empty($request->recipient_groups);
            }),
            'recipients.*' => 'exists:subscribers,id',
            'recipient_groups' => Rule::requiredIf(function() use ($request) {
                return $request->submit == 'save and send' && empty($request->recipients);
            }),
            'recipient_groups.*' => 'exists:groups,id'
        ]);
    }
}
