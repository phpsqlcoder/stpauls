<?php

namespace App\Http\Controllers\Settings;

use App\Permission;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Auth;

use App\Setting;
use App\MediaAccounts;

use App\EcommerceModel\PaymentList;
use App\EcommerceModel\PaymentOption;
use App\EcommerceModel\CheckoutOption;

class WebController extends Controller
{
    public function __construct()
    {
        Permission::module_init($this, 'website_settings');
    }

    public function edit(Request $request)
    {
        $web = Setting::first();
        $medias = MediaAccounts::get();

        $banks = PaymentOption::where('payment_id',2)->get();
        $remittances = PaymentOption::where('payment_id',3)->get();
        $cod = CheckoutOption::find(1); // cash on delivery data
        $stp = CheckoutOption::find(2); // store pick up data
        $sdd = CheckoutOption::find(4); // same day delivery data

        return view('admin.settings.website.index',compact('web','medias','banks','remittances','cod','stp','sdd'));
    }

    public function update(Request $request)
    {
        $this->validate($request,[
            'website_name' => 'required',
            'company_name' => 'required',
            'copyright'    => 'required',
            'web_favicon'  => 'mimes:ico|max:100',
            'company_logo' => 'image|mimes:jpeg,png,jpg,svg|max:1000',
        ]);


        $web = Setting::first();
        $web->website_name = $request->website_name;
        $web->company_name = $request->company_name;
        $web->copyright = $request->copyright;
        $web->google_map = $request->g_map;
        $web->user_id = Auth::id();
        $web->google_recaptcha_sitekey = $request->g_recaptcha_sitekey;
        $web->save();


        if($web){
            if($request->has('web_favicon')) {
                $this->upload_favicons($request->file('web_favicon'));
            }

            if($request->has('company_logo')) {
                $this->upload_logo($request->file('company_logo'));
            }
            return back()->with('success', __('standard.settings.website.update_success'));
        } else {
            return back()->with('error', __('standard.settings.website.update_failed'));
        }
    }

    public function upload_favicons($favicon)
    {
        $fileName = time().'_'.$favicon->getClientOriginalName();
        $web = Setting::first()->update([
            'website_favicon' => $fileName,
            'user_id' => Auth::id()
        ]);

        if($web){
            $image_url = Storage::putFileAs('/public/icons', $favicon, $fileName);
        }

    }

    public function upload_logo($logo)
    {
        $fileName = time().'_'.$logo->getClientOriginalName();
        $web = Setting::first()->update([
            'company_logo' => $fileName,
            'user_id' => Auth::id()
         ]);

        if($web){
            $image_url = Storage::putFileAs('/public/logos', $logo, $fileName);
        }

    }

    public function remove_logo(Request $request){

        $web = Setting::first();
        $web->company_logo = '';
        $web->user_id = Auth::id();
        $web->save();

        Storage::delete(Setting::select('company_logo')->where('id',$id)->get());

        return back()->with('success', __('standard.settings.website.remove_logo_success'));
    }

    public function remove_icon(Request $request){

        $web = Setting::first();
        $web->website_favicon = '';
        $web->user_id = Auth::id();
        $web->save();

        Storage::delete(Setting::select('website_favicon')->where('id',$id)->get());

        return back()->with('success', __('standard.settings.website.remove_favicon_success'));
    }


    public function update_contacts(Request $request)
    {
        $contacts = Setting::first();
        $contacts->company_address = $request->company_address;
        $contacts->mobile_no = $request->mobile_no;
        $contacts->fax_no = $request->fax_no;
        $contacts->tel_no = $request->tel_no;
        $contacts->email = $request->email;
        $contacts->viber_no = $request->viber_no;
        $contacts->user_id = Auth::id();
        $contacts->save();

        if($contacts){
            return back()->with('success', __('standard.settings.website.contact_update_success'));
        } else {
            return back()->with('error', __('standard.settings.website.contact_update_failed'));
        }
    }

    // public function update_ecommerce(Request $request)
    // {
    //     $ecommerce = Setting::first();
    //     $ecommerce->min_order = $request->min_order;
    //     $ecommerce->promo_is_displayed = (isset($_POST['promo_is_displayed']) == '1' ? '1' : '0');
    //     $ecommerce->review_is_allowed = (isset($_POST['review_is_allowed']) == '1' ? '1' : '0');
    //     $ecommerce->pickup_is_allowed = (isset($_POST['pickup_is_allowed']) == '1' ? '1' : '0');

    //     $ecommerce->min_order_is_allowed = (isset($_POST['min_order_is_allowed']) == '1' ? '1' : '0');
    //     $ecommerce->flatrate_is_allowed = (isset($_POST['flatrate_is_allowed']) == '1' ? '1' : '0');
    //     $ecommerce->delivery_collect_is_allowed = (isset($_POST['delivery_collect_is_allowed']) == '1' ? '1' : '0');

    //     $ecommerce->delivery_note = $request->delivery_note;
    //     $ecommerce->save();

    //     if($ecommerce){
    //         return back()->with([
    //             'tabname' => 'ecommerce',
    //             'success' =>  'Successfully updated the ecommerce settings.'
    //         ]);
    //     } else {
    //         return back()->with([
    //             'tabname' => 'ecommerce',
    //             'error' =>  'Error occur while updating Ecommerce Settings.'
    //         ]);
    //     }
    // }

    // public function update_paynamics(Request $request)
    // {
    //     $ecommerce = Setting::first();
    //     $accepted_payments = '';
    //     if( isset($request->accepted_payments) && is_array($request->accepted_payments) ) {
    //         $accepted_payments = implode(',', $request->accepted_payments);
    //     }
    //     $ecommerce->accepted_payments = $accepted_payments;

    //     $ecommerce->save();

    //     if($ecommerce){
    //         return back()->with('success','Successfully Updated Paynamics Settings');
    //     } else {
    //         return back()->with('error', __('standard.settings.website.contact_update_failed'));
    //     }
    // }

    public function update_media_accounts(Request $request)
    {
        $data   = $request->all();

        $mid   = $data['mid'];
        $urls   = $data['url'];
        $medias = $data['social_media'];

        foreach($medias as $key => $i){
            if($urls[$key] <> null){
                if($mid[$key] == null){
                    MediaAccounts::create([
                        'name' => $i,
                        'media_account' => $urls[$key],
                        'user_id' => Auth::id()
                    ]);
                } else {
                    MediaAccounts::where('id',$mid[$key])->update([
                        'name' => $i,
                        'media_account' => $urls[$key],
                        'user_id' => Auth::id()
                    ]);
                }
            }
        }

        return back()->with('success', __('standard.settings.website.social_updates_success'));
    }

    public function remove_media(Request $request)
    {
        $media = MediaAccounts::whereId($request->id);

        $media->update([ 'user_id' => Auth::id() ]);
        $media->delete();

        return back()->with('success', __('standard.settings.website.social_remove_success'));
    }

    public function update_data_privacy(Request $request)
    {
        $privacy = Setting::first();
        $privacy->data_privacy_title = $request->privacy_title;
        $privacy->data_privacy_popup_content = $request->pop_up_content;
        $privacy->data_privacy_content = $request->content;
        $privacy->user_id = Auth::id();
        $privacy->save();

        return back()->with('success', __('standard.settings.website.privacy_updates_success'));
    }

    public function add_bank(Request $request)
    {
        PaymentOption::create([
            'payment_id' => 2,
            'account_name' => $request->account_name,
            'name' => $request->name,
            'type' => 'bank',
            'account_no' => $request->account_no,
            'branch' => $request->branch,
            'is_default' => 0,
            'is_active' => 0,
            'user_id' => Auth::id()
        ]);

        return back()->with([
            'tabname' => 'ecommerce',
            'success' =>  'Bank has been added.'
        ]);
    }

    public function bank_update(Request $request)
    {
        $data = $request->all();
        if(isset($request->bank)){
            $selected_banks = $data['bank'];
            $bank_options = PaymentOption::where('payment_id',2)->get();

            // update banks into active
            foreach($selected_banks as $key => $bank){
                PaymentOption::find($bank)->update(['is_active' => 1]);
            }

            $arr_selectedbank = [];
            foreach($selected_banks as $key => $bank){
                array_push($arr_selectedbank,$bank);
            }

            foreach($bank_options as $bank){
                if(!in_array($bank->id,$arr_selectedbank)){
                    PaymentOption::find($bank->id)->update(['is_active' => 0]);
                }
            }
        } else {
            PaymentOption::where('payment_id',2)->where('is_default',0)->update(['is_active' => 0]);
        }
        
        return back()->with([
            'tabname' => 'ecommerce',
            'success' =>  'Banks has been updated.'
        ]);

    }

    public function remittance_update(Request $request)
    {
        $data = $request->all();
        if(isset($request->remittance)){
            $selected_remittances = $data['remittance'];
            $remittance_options = PaymentOption::where('payment_id',3)->get();

            // update banks into active
            foreach($selected_remittances as $key => $remittance){
                PaymentOption::find($remittance)->update(['is_active' => 1]);
            }

            $arr_selectedremittance = [];
            foreach($selected_remittances as $key => $remittance){
                array_push($arr_selectedremittance,$remittance);
            }

            foreach($remittance_options as $remittance){
                if(!in_array($remittance->id,$arr_selectedremittance)){
                    PaymentOption::find($remittance->id)->update(['is_active' => 0]);
                }
            }
        } else {
            PaymentOption::where('payment_id',3)->update(['is_active' => 0]);
        }
        
        return back()->with([
            'tabname' => 'ecommerce',
            'success' =>  'Remittance has been updated.'
        ]);

    }

    public function update_bank(Request $request)
    {
        PaymentOption::find($request->id)->update([
            'name' => $request->name,
            'account_name' => $request->account_name,
            'account_no' => $request->account_no,
            'branch' => $request->branch,
            'user_id' => Auth::id()
        ]);

        return back()->with([
            'tabname' => 'ecommerce',
            'success' =>  'Bank details has been updated.'
        ]);
    }

    public function delete_bank(Request $request)
    {
        PaymentOption::find($request->id)->delete();

        return back()->with([
            'tabname' => 'ecommerce',
            'success' =>  'Bank has been deleted.'
        ]);
    }

    public function add_remittance(Request $request)
    {
        $data = PaymentOption::create([
            'payment_id' => 3,
            'name' => $request->name,
            'recipient' => $request->recipient,
            'account_no' => $request->account_no,
            'type' => 'remittance',
            'qrcode' => isset($request->qrcode) ? $request->qrcode->getClientOriginalName() : '',
            'is_default' => 0,
            'is_active' => 0,
            'user_id' => Auth::id()
        ]);

        if(isset($request->qrcode)){
            $file = $request->qrcode;

            Storage::makeDirectory('/public/qrcodes/'.$data->id);
            Storage::putFileAs('/public/qrcodes/'.$data->id, $file, $file->getClientOriginalName());
        }

        return back()->with([
            'tabname' => 'ecommerce',
            'success' =>  'Remittance has been added.'
        ]);
    }

    public function update_remittance(Request $request)
    {
        $qry = PaymentOption::find($request->id);

        $qry->update([
            'name' => $request->name,
            'recipient' => $request->recipient,
            'account_no' => $request->account_no,
            'qrcode' => isset($request->qrcode) ? $request->qrcode->getClientOriginalName() : $qry->qrcode,
            'user_id' => Auth::id()
        ]);

        if(isset($request->qrcode)){
            $file = $request->qrcode;

            if($qry->qrcode == ''){
               Storage::makeDirectory('/public/qrcodes/'.$qry->id);
            }
            
            Storage::putFileAs('/public/qrcodes/'.$qry->id, $file, $file->getClientOriginalName());
        }

        return back()->with([
            'tabname' => 'ecommerce',
            'success' =>  'Remittance details has been updated.'
        ]);
    }

    public function delete_remittance(Request $request)
    {
        PaymentOption::find($request->id)->delete();

        return back()->with([
            'tabname' => 'ecommerce',
            'success' =>  'Remittance has been deleted.'
        ]);
    }

    public function cod_update(Request $request)
    {
        $data = $request->all();
        $days = $data['cod_days'];

        $allowed_days = "";
        foreach($days as $day){
            $allowed_days .= $day.'|';
        }

        $qry = CheckoutOption::find($request->id)->update([
            'service_fee' => $request->service_fee,
            'maximum_purchase' => $request->max_purchase,
            'allowed_days' => rtrim($allowed_days,'|'),
            'reminder' => $request->reminder,
            'within_metro_manila' => (isset($request->within_metro_manila) ? 1 : 0),
            'outside_metro_manila' => (isset($request->outside_metro_manila) ? 1 : 0),
            'user_id' => Auth::id()
        ]);

        return back()->with([
            'tabname' => 'ecommerce',
            'success' =>  'Cash on Delivery details has been updated.'
        ]);
    }

    public function stp_update(Request $request)
    {
        $data = $request->all();
        $days = $data['stp_days'];

        $allowed_days = "";
        foreach($days as $day){
            $allowed_days .= $day.'|';
        }

        $qry = CheckoutOption::find($request->id)->update([
            'allowed_days' => rtrim($allowed_days,'|'),
            'allowed_time_from' => date('H:i',strtotime($request->time_from)),
            'allowed_time_to' =>date('H:i',strtotime($request->time_to)),
            'reminder' => $request->reminder,
            'user_id' => Auth::id()
        ]);

        return back()->with([
            'tabname' => 'ecommerce',
            'success' =>  'Store Pick up details has been updated.'
        ]);
    }

    public function sdd_update(Request $request)
    {
        $data = $request->all();
        $days = $data['sdd_days'];

        $allowed_days = "";
        foreach($days as $day){
            $allowed_days .= $day.'|';
        }

        $qry = CheckoutOption::find($request->id)->update([
            'service_fee' => $request->service_fee,
            'maximum_purchase' => $request->max_purchase,
            'allowed_days' => rtrim($allowed_days,'|'),
            'allowed_time_from' => date('H:i',strtotime($request->time_from)),
            'allowed_time_to' =>date('H:i',strtotime($request->time_to)),
            'reminder' => $request->reminder,
            'user_id' => Auth::id()
        ]);

        return back()->with([
            'tabname' => 'ecommerce',
            'success' =>  'Same Day Delivery details has been updated.'
        ]);
    }

    public function deactivate_payment_opt(Request $request)
    {
        PaymentList::find($request->id)->update(['is_active' => 0, 'user_id' => Auth::id()]);

        return back()->with([
            'tabname' => 'ecommerce',
            'success' =>  'Payment option has been deactivated.'
        ]);
    }

    public function activate_payment_opt(Request $request)
    {
        PaymentList::find($request->id)->update(['is_active' => 1, 'user_id' => Auth::id()]);

        return back()->with([
            'tabname' => 'ecommerce',
            'success' =>  'Payment option has been activated.'
        ]);
    }
    
}
