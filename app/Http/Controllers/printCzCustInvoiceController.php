<?php


namespace App\Http\Controllers;

use App\Models\CzCustInvoice;
use App\Models\CzParameter;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests;
use PDF;

class printCzCustInvoiceController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function getInvoicePdf($id_cust_invoice)
    {
        $invoice = CzCustInvoice::where('id_cust_invoice', $id_cust_invoice)->first();
        $param = CzParameter::find(1);
        $invoiceRows = $invoice->invoiceDetails;
        $pdf=PDF::loadView('pdfs.CzCustInvoice',  compact('invoice','invoiceRows','param'));
        return $pdf->stream('factuur.pdf');
    }    

}
