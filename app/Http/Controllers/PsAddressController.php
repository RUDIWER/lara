<?php

namespace App\Http\Controllers;

use App\Models\PsAddress;
use App\Http\Controllers\Controller;
use Dhtmlx\Connector\GridConnector;
use Illuminate\Http\Request;

class PsAddressController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function addressEdit($id_address)
    {
        $address = PsAddress::find($id_address);
        return compact('address');
    }

    public function addressUpdate(Request $request,$id_address)
    {
        $address = PsAddress::find($id_address);
        $address->alias = $request->alias;
        $address->lastname = $request->lastname;
        $address->firstname = $request->firstname;
        $address->address1 = $request->address1;
        $address->address2 = $request->address2;
        $address->vat_number = $request->vat_number;
        $address->postcode = $request->postcode;
        $address->city = $request->city;
        $address->id_country = $request->id_country;
        $address->phone = $request->phone;
        $address->phone_mobile = $request->phone_mobile;
        $address->other = $request->other;
        $address->save();
        return response()->json($address);
    }
    // If change Address create see also PsCustomerController create Customer !! there we save also the address !!!!
    // MAke it later with a treat
    public function addressCreate(Request $request)
    {
        $address = new PsAddress;
        $address->id_supplier = $request->id_supplier;
        $address->id_customer = $request->id_customer;
        $address->lastname = $request->lastname;
        $address->firstname = $request->firstname;
        $address->id_country = $request->id_country;
        $address->id_state = 0;
        $address->id_manufacturer = 0;
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
        return response()->json($address);
    }

    public function addressDelete(Request $request,$id_address)
    {
        $address = PsAddress::find($id_address);
        $address->delete();
//        return response()->json($address);
    }

};
