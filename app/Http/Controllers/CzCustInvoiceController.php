<?php

namespace App\Http\Controllers;

use App\Models\CzCustInvoice;
use App\Models\CzCustInvoiceDetail;
use App\Models\CzParameter;
use App\Models\PsCustomer;
use App\Models\PsAddress;
use App\Models\CzProduct;
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

    public function invoiceData() 
    {
        $connector = new GridConnector(null, "PHPLaravel");
        $connector->configure(
            new CzCustInvoice(),
            "id",
            "id_cust_invoice,customer_name,customer_first_name,customer_email,total_invoice_incl_btw"
        );
        $connector->render();
    }

    public function edit($id_cust_invoice) 
    {
        $invoice = CzCustInvoice::where('id_cust_invoice', $id_cust_invoice)->first();
        $customers = PsCustomer::all();
        $products = CzProduct::all();
        $param = CzParameter::find(1);
        $invoiceDetails = $invoice->invoiceDetails;
        $isNew = 0;
        return view('verkopen.facturen.edit', compact('invoice','customers', 'products', 'invoiceDetails','param', 'isNew'));
    }

    public function create()
    {
        $invoice = new CzCustInvoice();
        $customers = PsCustomer::all();
        $products = CzProduct::all();
        $param = CzParameter::find(1);
        $invoiceDetails = 0;
        $isNew = 1;
        return view('verkopen.facturen.edit', compact('invoice', 'customers', 'products', 'invoiceDetails', 'param', 'isNew'));
    }

    public function save(Request $request, $id_cust_invoice)
    {
        $data = $request->all();
        $Invoice = CzCustInvoice::findornew($id_cust_invoice); 
        $Invoice->id_cust_order = $data['id_cust_order'];
        dd($data);
        // Fields to save for edit AND create
        $Invoice->total_shipping_btw_procent = $order->carrier_tax_rate;
        $Invoice->total_shipping_exl_btw = $order->total_shipping_tax_excl;
        $Invoice->total_shipping_incl_btw = $order->total_shipping_tax_incl;
        $Invoice->total_products_exl_btw = $order->total_products;
        $Invoice->total_products_incl_btw = $order->total_products_wt;
        $Invoice->total_paid = $order->total_paid;




        if(!$id_cust_invoice) // Only New Invoice
        {
            $Invoice->id_customer = $data['id_customer'];
            $Customer = PsCustomer::find($Invoice->id_customer);
            $Address = PsAddress::where('id_customer',  $Invoice->id_customer )->first();
            $Invoice->customer_name = $Customer->lastname;
            $Invoice->customer_first_name = $Customer->firstname;
            $Invoice->customer_street_nr = $Address->address1;
            $Invoice->customer_city = $Address->city;
            $Invoice->customer_postal_code = $Address->postcode;
            $Invoice->customer_vat_number = $Address->vat_number;
            if($Address->id_country == 3)
            {
                $Invoice->customer_country = 'BE';
            }
            elseif($Address->id_country == 13 )
            {
                $Invoice->customer_country = 'NL';
            }
            elseif($Address->id_country == 8 )
            {
                $Invoice->customer_country = 'FR';  
            }
            $Invoice->invoice_date = date("Y/m/d");
            $Invoice->order_date = $data['order_date'];;  
        }

         dd($Customer);

             $invoice->order_reference = $order->reference;
             $invoice->total_shipping_btw_procent = $order->carrier_tax_rate;
             $invoice->total_shipping_exl_btw = $order->total_shipping_tax_excl;
             $invoice->total_shipping_incl_btw = $order->total_shipping_tax_incl;
             $invoice->total_products_exl_btw = $order->total_products;
             $invoice->total_products_incl_btw = $order->total_products_wt;
             $invoice->total_paid = $order->total_paid;
             $totalIkpExVat = 0;
             
             foreach ($orderDetails as $orderDetail) 
             {
                $rowTotalIkpExVat = $orderDetail->product_quantity * $orderDetail->purchase_supplier_price;
                $totalIkpExVat = $totalIkpExVat + $rowTotalIkpExVat;                  
             } // end foreach $orderDetails
             $invoice->total_ikp_cz_exl_btw = $totalIkpExVat;
             $invoice->total_costs_bol_exl_btw = 0;
             $invoice->customer_phone = $order->deliveryaddress->phone;
             $invoice->customer_email = $order->customer->email;
             // Shipping Cost berekenen LET OP !!! Indien er in Prestashop nieuwe vervoerders bijkomen dient hier de code aangepast te worden !!!!!!!
             if($order->id_carrier == 23 or $order->id_carrier == 16)    // Afhalen
             {
                  $invoice->total_shipping_cost_exl_btw = 0; 
             }
             else
             {
                if($order->deliveryAddress->id_country == 3) //Belgium
                {   
                    $invoice->total_shipping_cost_exl_btw = $param->shipping_cost_cz_be_ex_btw; 
                    $invoice->invoice_type = '4';

                }
                elseif($order->deliveryAddress->id_country == 13)   // NBetherlands
                {
                    $invoice->total_shipping_cost_exl_btw = $param->shipping_cost_cz_nl_ex_btw;
                    $invoice->invoice_type = '6';
 
                }
                else
                {
                    echo 'Kijk PsCustOrderController.php na de landcode van dit order werd nog niet voorzien !!!!!';
                }
             }
             $invoice->customer_phone_mobile = $order->deliveryAddress->phone_mobile;
             $invoice->company_name = $order->invoiceAddress->company;
             $invoice->total_invoice_exl_btw = $order->total_paid_tax_excl;
             $invoice->total_invoice_incl_btw = $order->total_paid_tax_incl;
             $invoice->total_wrapping_exl_btw = $order->total_wrapping_tax_excl;
             $invoice->total_wrapping_incl_btw = $order->total_wrapping_tax_incl;
             if($invoice->total_wrapping_exl_btw > 0)
             {
                $invoice->total_wrapping_cost_ex_btw = $param->wrapping_cost_ex_btw;
             }
             else
             {
                 $invoice->total_wrapping_cost_ex_btw = 0;
             }
             $invoice->netto_margin_ex_btw = $invoice->total_invoice_exl_btw - $invoice->total_ikp_cz_exl_btw - $invoice->total_shipping_cost_exl_btw - $invoice->total_wrapping_cost_ex_btw;

             // Get new Invoice number 
             $lastInvoiceNr = $invoice::orderBy('id_cust_invoice', 'desc')->first()->id_cust_invoice;
             $invoice->id_cust_invoice = $lastInvoiceNr + 1;
             DB::beginTransaction();
             try 
             {
                $invoice->save();    // Save invoice (header)
                $invoice=CzCustInvoice::find($lastInvoiceNr + 1);
            // 2) Create invoice rows & change to invoice field in products
                foreach ($orderDetails as $orderDetail)     // Loop over order rows and make invoice rows
                {
                    $invoiceRow = new CzCustInvoiceDetail;
                    $invoiceRow->id_cz_cust_invoice = $invoice->id_cz_cust_invoice;
                    $invoiceRow->id_cust_invoice = $invoice->id_cust_invoice;
                    $invoiceRow->id_product = $orderDetail->product_id;
                    $invoiceRow->product_reference = $orderDetail->product_reference;
                    $invoiceRow->product_suppl_reference = $orderDetail->product_supplier_reference;
                    $invoiceRow->product_descr = $orderDetail->product_name;
                    $invoiceRow->quantity = $orderDetail->product_quantity;
                    $invoiceRow->product_unit_price_ex_vat = $orderDetail->unit_price_tax_excl;
                    $invoiceRow->product_ikp_price_cz_ex_vat = $orderDetail->purchase_supplier_price;
                    $invoiceRow->product_total_ikp_cz_ex_vat = ($orderDetail->purchase_supplier_price * $orderDetail->product_quantity);
                    $invoiceRow->product_total_price_ex_vat = ($orderDetail->unit_price_tax_excl * $orderDetail->product_quantity);
                    if($orderDetail->id_tax_rules_group == 1)
                    {
                        $invoiceRow->vat_procent = 21;
                    }
                    elseif($orderDetail->id_tax_rules_group == 2)
                    {
                        $invoiceRow->vat_procent = 12;
                    }
                    elseif($$orderDetail->id_tax_rules_group == 3)
                    {
                        $invoiceRow->vat_procent = 6; 
                    }
                    else
                    {
                        $invoiceRow->vat_procent = 21;
                    }
                    $invoiceRow->product_total_price_incl_vat = $orderDetail->total_price_tax_incl;
                    $invoiceRow->ean_product = $orderDetail->product_ean13;
                    $productInRow = CzProduct::where('id_product',$orderDetail->product_id)->first();
                    $invoiceRow->id_supplier = $productInRow->id_supplier;
                    $invoiceRow->product_unit_price_incl_vat = $orderDetail->unit_price_tax_incl;
                    $invoiceRow->save();
            // Change to invoice field  in Products 
                    $productInRow->quantity_to_invoice = $productInRow->quantity_to_invoice - $orderDetail->product_quantity;
                    $productInRow->save();
                    DB::commit();
                    $notInvoiced = 0;
                } //end foreach   
             } catch (\Exception $e) 
             {      // something went wrong
                 $notInvoiced = 1;
                 DB::rollback();
                 throw $e;
             } 






        dd($data);
        return;

    }


}