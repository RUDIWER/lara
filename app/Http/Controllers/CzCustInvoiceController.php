<?php

namespace App\Http\Controllers;

use App\Models\CzCustInvoice;
use App\Models\CzParameter;
use App\Http\Controllers\Controller;
use Dhtmlx\Connector\GridConnector;
use Illuminate\Http\Request;

class CzCustInvoiceController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function invoices()
    {
        return view('verkopen.facturen.invoices');
    }

     public function invoiceData() {
        $connector = new GridConnector(null, "PHPLaravel");
        $connector->configure(
            new CzCustInvoice(),
            "id",
            "id_cust_invoice,customer_name,customer_first_name,customer_email,total_invoice_incl_btw"
        );
        $connector->render();
    }

        public function edit($id_cust_invoice) {
        $invoice = CzCustInvoice::where('id_cust_invoice', $id_cust_invoice)->first();
        $param = CzParameter::find(1);
        $invoiceDetails = $invoice->invoiceDetails;
        $isNew = 0;
        return view('verkopen.facturen.edit', compact('invoice','invoiceDetails','param', 'isNew'));
    }




}