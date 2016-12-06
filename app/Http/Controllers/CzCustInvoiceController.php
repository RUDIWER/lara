<?php

namespace App\Http\Controllers;

use App\Models\CzCustInvoice;
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
        return view('verkopen.invoices');
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
}