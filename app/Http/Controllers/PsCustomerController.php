<?php

namespace App\Http\Controllers;

use App\Models\PsCustomer;
use App\Models\PsAddress;
use App\Http\Controllers\Controller;
use Dhtmlx\Connector\GridConnector;
use Illuminate\Http\Request;

class PsCustomerController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function customers()
    {
        return view('klanten.klanten');
    }

    public function customerData() {
        $connector = new GridConnector(null, "PHPLaravel");
        $connector->configure(
            new PsCustomer(),
            "id",
            "id_customer,lastname,firstname,email"
        );
        $connector->render();
    }

    public function addressData($id_customer) {
        $connector = new GridConnector(null, "PHPLaravel");
        $connector->configure(
            PsAddress::where('id_customer', '=', $id_customer)->get(),
            "id",
            "id_address,alias,company,vat_number,address1,postcode,city,id_country,phone,phone_mobile"
        );
        $connector->render();
    }

    public function create() {
        $klant = new PsCustomer();
        $isNew = 1;
        return view('klanten.edit', compact('klant','isNew'));
    }

    public function edit($id_customer) {
        $klant = PsCustomer::find($id_customer);
        $isNew = 0;
        return view('klanten.edit', compact('klant','isNew'));
    }

    public function save(Request $request, $id_customer) {
        $data = $request->all();;
        $klant = PsCustomer::findornew($id_customer);
        $latest_id_customer = $klant->id_customer;
        $klant->fill($data);
        $klant->date_upd = date('Y-m-d H:i:s');
        if(!$id_customer){
            $klant->id_risk = 0;
            $klant->max_payment_days = 0;
            $klant->active = 1;
            $klant->is_guest = 1;
            $klant->date_add = date('Y-m-d H:i:s');
            $klant->passwd = 'ZWD1234567890';
            $klant->newsletter = 1;
            $klant->newsletter_date_add = date('Y-m-d H:i:s');
        }
        $klant->save();
        // if new record create also address !
        // If changing this see also PsAddressController create address and change also ther !
        // make this later with a treat
        if($latest_id_customer !== $klant->id_customer) {
            $address = new PsAddress;
            $address->id_customer = $klant->id_customer;
            $address->lastname = $klant->lastname;
            $address->firstname = $klant->firstname;
            $address->id_state = 0;
            $address->id_manufacturer = 0;
            $address->id_supplier= 0;
            $address->id_warehouse = 0;
            $address->active = 1;
            $address->deleted = 0;
            $address->alias = $request->alias;
            $address->address1 = $request->address1;
            $address->address2 = $request->address2;
            $address->vat_number = $request->vat_number;
            $address->postcode = $request->postcode;
            $address->city = $request->city;
            $address->id_country = $request->id_country;
            $address->phone = $request->phone;
            $address->phone_mobile = $request->phone_mobile;
            $address->other = $request->other;
            $address->date_add = date('Y-m-d H:i:s');
            $address->date_upd = date('Y-m-d H:i:s');
            $address->save();
        }

        $notification = array(
	        'message' => 'Klant succesvol Opgeslagen !',
            'alert-type' => 'success'
        );
        return redirect('/klanten')->with($notification);
    }
}
