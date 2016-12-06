<?php

namespace App\Http\Controllers;

use App\Models\PsSupplier;
use App\Models\PsAddress;
use App\Http\Controllers\Controller;
use Dhtmlx\Connector\GridConnector;
use Illuminate\Http\Request;

class PsSupplierController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function suppliers()
    {
        return view('leveranciers.leveranciers');
    }

    public function supplierData() {
        $connector = new GridConnector(null, "PHPLaravel");
        $connector->configure(
            new PsSupplier(),
            "id",
            "id_supplier,name,email_1,tel_1"
        );
        $connector->render();
    }

    public function addressData($id_supplier) {
        $connector = new GridConnector(null, "PHPLaravel");
        $connector->configure(
            PsAddress::where('id_supplier', '=', $id_supplier)->get(),
            "id",
            "id_address,alias,company,vat_number,address1,postcode,city,id_country,phone,phone_mobile"
        );
        $connector->render();
    }

    public function create() {
        $supplier = new PsSupplier();
        $isNew = 1;
        return view('leveranciers.edit', compact('supplier','isNew'));
    }

    public function edit($id_supplier) {
        $supplier = PsSupplier::find($id_supplier);
        $isNew = 0;
        return view('leveranciers.edit', compact('supplier','isNew'));
    }

    public function save(Request $request, $id_supplier) {
        $data = $request->all();;
        $supplier = PsSupplier::findornew($id_supplier);
        $latest_id_supplier = $lev->id_supplier;
        $supplier->fill($data);
        $supplier->date_upd = date('Y-m-d H:i:s');
        if(!$id_supplier){
            // Hier stand waarden toekennnen
            $supplier->date_add = date('Y-m-d H:i:s');
        }
    //    $supplier->save();
        // if new record create also address !
        // If changing this see also PsAddressController create address and change also ther !
        // make this later with a treat
        if($latest_id_supplier !== $lev->id_supplier) {
            $address = new PsAddress;
            $address->id_supplier = $lev->id_supplier;
            $address->lastname = $lev->lastname;
            $address->firstname = $lev->firstname;
            $address->id_country = $request->id_country;
            $address->id_state = 0;
            $address->id_manufacturer = 0;
            $address->id_customer= 0;
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
	        'message' => 'Leverancier succesvol Opgeslagen !',
            'alert-type' => 'success'
        );
        return redirect('/leveranciers')->with($notification);
    }
}
