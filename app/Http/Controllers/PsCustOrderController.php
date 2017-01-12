<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\CzParameter;
use App\Models\PsOrders;
use App\Models\CzProduct;
use App\Models\PsProduct;
use App\Models\PsProductShop;
use App\Models\PsStockAvailable;
use App\Models\CzCustInvoice;
use App\Models\CzCustInvoiceDetail;
use App\Lara_Classes\InventoryClass;
use Illuminate\Http\Request;
use MCS\BolPlazaClient;
use Mail;

class PsCustOrderController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function newOrders()
    {
        $newOrders = PsOrders::where('current_state','<=',5)
                            ->orwhere('current_state','=',11)
                            ->orwhere('current_state','=',12)
                            ->orwhere('current_state','=',14)
                            ->orwhere('current_state','=',16)
                            ->orderBy('id_order', 'desc')
                            ->orwhere('current_state','=',17)->get();
        return view('cz.verkopen.newOrders', compact('newOrders'));
    }

    public function changeState(Request $request,$id_order,$newState)
    {
        $notCommited = 0; // var used to check if transaction is commited or not !
        $param = CzParameter::find(1);
        $order = PsOrders::find($id_order);
        $orderDetails = PsOrders::find($id_order)->orderDetails;
        if($newState == 3)  // Order wordt overgezet van ontvangen naar -> Wordt voorbereid (in verschillende stappen !)
        {          
     //***** VOORRAAD ***************************************************      
            // 1) PAS VOORRAAD AAN ZOWEL CZ_PRODUT ALS PS_PRODUCT ALS BOL ....
            foreach ($orderDetails as $orderDetail) 
            {    
            //1) CZ_product voorraad -x / Te factureren +x
                $czProduct = CzProduct::where('id_product',$orderDetail->product_id)->first();
                $czProduct->quantity_in_stock = $czProduct->quantity_in_stock - $orderDetail->product_quantity;
                $czProduct->quantity_to_invoice = $czProduct->quantity_to_invoice + $orderDetail->product_quantity;
            //2) Ps_product (Via Ps_stock_available)
                $psStockAvailable = PsStockAvailable::where('id_product',$orderDetail->product_id)->first();
                $psStockAvailable->quantity = $czProduct->quantity_in_stock;
            //3) BOL NL  
                if($czProduct->active_bol_nl == 1)
                {
                    $publicNlKey = env('BOL_NL_PUBLIC_PROD_KEY');
                    $privateNlKey = env('BOL_NL_PRIVATE_PROD_KEY');
                    $clientNl = new BolPlazaClient($publicNlKey, $privateNlKey, false);
                    $inventory = 0;
                    if($czProduct->quantity_in_stock < 0)
                    {
                        $inventory = 0;
                    }
                    else
                    {
                        $inventory = $czProduct->quantity_in_stock;
                    }
                    $updateNl = $clientNl->updateOfferStock($czProduct->id_product, $inventory);
                }
            // 4) BOL BE (TO DO After reimport of products)
            //
            // If stock is Nul or lower -> Deactivate in Shop AND in BOL BOL TO DO !!!!!!!!!!!!!!!!!!!!
                $psProduct = PsProduct::where('id_product',$orderDetail->product_id)->first();
                $psProductShop = PsProductShop::where('id_product',$orderDetail->product_id)->first();
                if($czProduct->quantity_in_stock <= 0)
                {
                    $czProduct->active = 0;
                    $psProduct->active = 0;
                    $psProductShop->active = 0;
                }
                DB::beginTransaction();
                try 
                {
                    $czProduct->save();
                    $psProduct->save();
                    $psProductShop->save();
                    $psStockAvailable->save();
                    DB::commit();
                } catch (\Exception $e) 
                {      // something went wrong
                    $notCommited = 1;
                    DB::rollback();
                    throw $e;
                }       
            } // end foreach $orderDetails
         // After all changes are done ->Change order_state
            if($notCommited != 1)     // LET OP DEZE WAARDE MOET 1 ZIJN ALS TESTEN GEDAAN IS !!!!!!!!!!
            {
                $order->current_state = $newState;
                $orderStateSaved = $order->save();   
            }  
        // Send mail to client to inform order state changeState
            if($orderStateSaved)
            {
                $customer = PsOrders::find($id_order)->customer;
                Mail::send('emails.orderPackingStarted', ['customer' => $customer, 'order' => $order], function ($m) use ($customer,$order) {
                    $m->from('info@cool-zawadi.com', 'Dienst Dispatching cool-zawadi');
                    $m->to($customer->email, $customer->firstname)->subject('Uw order bij cool-zawadi.com wordt momenteel behandeld !');
                });
            }
        } // end if $newState=3
        elseif($newState == 4)   // UW order is verzonden
        {
            //Plaats status op verzonden
            if($notCommited != 1)     // LET OP DEZE WAARDE MOET 1 ZIJN ALS TESTEN GEDAAN IS !!!!!!!!!!
            {
                $order->current_state = $newState;
                $orderStateSaved = $order->save();   
            }  
        //indien gelukt -> mail naar klant !!!    
        // Send mail to client to inform order state changeState
            if($orderStateSaved)
            {
                $customer = PsOrders::find($id_order)->customer;
                Mail::send('emails.orderSend', ['customer' => $customer, 'order' => $order], function ($m) use ($customer,$order) {
                    $m->from('info@cool-zawadi.com', 'Dienst Dispatching cool-zawadi');
                    $m->to($customer->email, $customer->firstname)->subject('Uw order bij cool-zawadi.com is verzonden !');
                });
            }
        } // end if $newState=4
        elseif($newState == 5)  
        {
           //Plaats status op Geleverd
            if($notCommited != 1)     // LET OP DEZE WAARDE MOET 1 ZIJN ALS TESTEN GEDAAN IS !!!!!!!!!!
            {
                $order->current_state = $newState;
                $orderStateSaved = $order->save();   
            }  
     //indien gelukt -> mail naar klant !!!    
        // Send mail to client to inform order state changeState
            if($orderStateSaved)
            {
                $customer = PsOrders::find($id_order)->customer;
                Mail::send('emails.orderDelivered', ['customer' => $customer, 'order' => $order], function ($m) use ($customer,$order) {
                    $m->from('info@cool-zawadi.com', 'Dienst Dispatching cool-zawadi');
                    $m->to($customer->email, $customer->firstname)->subject('Uw order bij cool-zawadi.com zou geleverd moeten zijn !');
                });
            }
        } // end if $newState=5
                // newState is 6 order annuleren
        elseif($newState == 6)
        {
            //Plaats status op Geannuleerd
            if($notCommited != 1)     // LET OP DEZE WAARDE MOET 1 ZIJN ALS TESTEN GEDAAN IS !!!!!!!!!!
            {
                $order->current_state = $newState;
                foreach($orderDetails as $orderDetail) 
                {
                    $stock = new InventoryClass($orderDetail->id_product,$orderDetail->quantity);
                    $stock->increaseOnAnnul();
                }
                $orderStateSaved = $order->save();
            }  
        }
        elseif($newState == 19)         // Order to invoice
        {
          // 1) Create invoice
             $invoice = new CzCustInvoice;
             $invoice->id_cust_order = $order->id_order;
             $invoice->id_customer = $order->id_customer;
             $invoice->customer_name = $order->customer->lastname;
             $invoice->customer_first_name = $order->customer->firstname;
             $invoice->customer_street_nr = $order->invoiceAddress->address1;
             $invoice->customer_city = $order->invoiceAddress->city;
             $invoice->customer_postal_code = $order->invoiceAddress->postcode;
             $invoice->customer_vat_number = $order->invoiceAddress->vat_number;
             if($order->invoiceAddress->id_country == 3)
             {
                $invoice->customer_country = 'BE';
             }
             elseif($order->invoiceAddress->id_country == 13 )
             {
                $invoice->customer_country = 'NL';
             }
             elseif($order->invoiceAddress->id_country == 8 )
             {
                $invoice->customer_country = 'FR';  
             }
             $invoice->invoice_date = date("Y/m/d");
             $invoice->order_date = date('Y/m/d', strtotime($order->date_add));  
             $invoice->order_reference = $order->reference;
             $invoice->payment_method = $order->payment;
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
                    $notInvoiced = 0;
                } //end foreach 
                DB::commit();  
             } catch (\Exception $e) 
             {      // something went wrong
                 $notInvoiced = 1;
                 DB::rollback();
                 throw $e;
             } 
             // If invoice is created ...
             if(!$notInvoiced) 
             {       
                //3) Status order wijzigen !!!!!!!!
                $order->current_state = $newState;
                $orderStateSaved = $order->save();   
                //4) Send Mail to customer  !!!!!
     
                $customer = CzCustInvoice::find($invoice->id_cust_invoice)->customer;
                $invoiceRows = CzCustInvoiceDetail::where('id_cust_invoice',$invoice->id_cust_invoice)->get();
                Mail::send('emails.orderInvoiced', ['customer' => $customer, 'invoice'=> $invoice, 'invoiceRows' => $invoiceRows], function ($m) use ($customer, $invoiceRows, $invoice) 
                {
                    $m->from('info@cool-zawadi.com', 'Dienst Facuratie cool-zawadi');
                    $m->to($customer->email, $customer->firstname)->subject('Factuur van uw order Bij Cool-Zawadi en alvast bedankt voor het vertrouwen !');
                });
                
            } // end invoiced 
        } // end newState = 19
    }  //end public function changeState
} // end class PsOrdersController

