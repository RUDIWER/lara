<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Models\BolBeOrders;
use App\Models\BolBeOrderDetail;
use App\Models\PsCustomer;
use App\Models\CzParameter;
use App\Models\PsAddress;
use App\Models\CzProduct;
use App\Models\PsProduct;
use App\Models\PsProductShop;
use App\Models\PsStockAvailable;
use App\Models\CzCustInvoice;
use App\Models\CzCustInvoiceDetail;
use App\Lara_Classes\InventoryClass;
use Illuminate\Http\Request;
use App\Http\Requests;
use MCS\BolPlazaClient;


class BolCustOrderController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function changeState(Request $request,$id_order,$newState)
    {
        $notCommited = 0; // var used to check if transaction is commited or not !
        $param = CzParameter::find(1);
        $order = BolBeOrders::find($id_order);
        $orderDetails = BolBeOrders::find($id_order)->bolBeOrderDetails;
        if($newState == 3)  // Order wordt overgezet van ontvangen naar -> Wordt voorbereid (in verschillende stappen !)
        {   
            // 1) PAS VOORRAAD AAN ZOWEL CZ_PRODUT ALS PS_PRODUCT ....
            foreach($orderDetails as $orderDetail) 
            {
            //1) CZ_product voorraad -x / Te factureren +x
                $czProduct = CzProduct::where('id_product',$orderDetail->id_product)->first();
                $czProduct->quantity_in_stock = $czProduct->quantity_in_stock - $orderDetail->quantity;
                $czProduct->quantity_to_invoice = $czProduct->quantity_to_invoice + $orderDetail->quantity;
            //2) Ps_product (Via Ps_stock_available)
                $psStockAvailable = PsStockAvailable::where('id_product',$orderDetail->id_product)->first();
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
                $psProduct = PsProduct::where('id_product',$orderDetail->id_product)->first();
                $psProductShop = PsProductShop::where('id_product',$orderDetail->id_product)->first();
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
                    $czProduct->save();     
                    $psProduct->save();
                    $psProductShop->save();
                    $psStockAvailable->save();
                    DB::commit();
                } catch (\Exception $e) 
                {      // something went wrong
                    $notCommited = 1;
                    DB::rollback();
                    throw new Exception($e);
                } 
            } // end foreach $orderDetails  
         // After all changes are done ->Change order_state
            if($notCommited != 1)     // LET OP DEZE WAARDE MOET 1 ZIJN ALS TESTEN GEDAAN IS !!!!!!!!!!
            {
                $order->current_state = $newState;
                $orderStateSaved = $order->save();   
            } 
         //indien gelukt -> mail naar klant !!!    
         // LET OP gebruik Email uit BolBeOrders !!! Niet uit klantenbestand !!
            if($orderStateSaved)
            {
             // Send mail to client to inform order state changeState
            }
 
        } // end if $newState=3
    
        elseif($newState == 4)
        {
            //Plaats status op verzonden
            if($notCommited != 1)     // LET OP DEZE WAARDE MOET 1 ZIJN ALS TESTEN GEDAAN IS !!!!!!!!!!
            {
                $order->current_state = $newState;
                $orderStateSaved = $order->save();   
            }  
        //indien gelukt -> mail naar klant !!!    
            if($orderStateSaved)
            {
             // Send mail to client to inform order state changeState
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
                // Send EMail to client !!!

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
             $invoice->ordernr_bol = ($order->bol_be_order_id);
             $invoice->id_customer = $order->id_customer;
             $invoice->customer_name = $order->customer->lastname;
             $invoice->customer_first_name = $order->customer->firstname;
             $invoice->customer_street_nr = $order->invoice_address_1;
             $invoice->customer_city = $order->invoice_city;
             $invoice->customer_postal_code = $order->invoice_postcode;
             $invoice->customer_vat_number = $order->invoice_vat_number;

             if($order->id_invoice_country == 3)
             {
                $invoice->customer_country = 'BE';
             }
             elseif($order->id_invoice_country == 13 )
             {
                $invoice->customer_country = 'NL';
             }
             elseif($order->id_invoice_country == 8 )
             {
                $invoice->customer_country = 'FR';  
             }
             $invoice->invoice_date = date("Y/m/d");
             $invoice->order_date = $order->date_order;   
             $invoice->order_reference = $order->id_bol_be_orders;
             $invoice->payment_method = 'Via bol.com';
             $invoice->total_shipping_btw_procent = $param->stand_vat_procent;
             $invoice->total_shipping_exl_btw = 0;
             $invoice->total_shipping_incl_btw = 0;       
             foreach ($orderDetails as $orderDetail) 
             {
             //  $czProduct = CzProduct::where('id_product',$orderDetail->id_product)->first();
                // Calculate Rowtotals
                $rowTotalVkpInclVat = $orderDetail->unit_price_incl_vat * $orderDetail->quantity;
                $rowTotalVkpExVat = ($rowTotalVkpInclVat / ((($orderDetail->vat_procent)/100)+1));
                $rowTotalIkpExVat = $orderDetail->unit_ikp_cz_ex_vat * $orderDetail->quantity;
                // Calculate invoice Totals
                $invoice->total_products_incl_btw = $invoice->total_products_incl_btw + $rowTotalVkpInclVat;
                $invoice->total_products_exl_btw = $invoice->total_products_exl_btw + $rowTotalVkpExVat;
                $invoice->total_paid =  $invoice->total_products_incl_btw;
                $invoice->total_ikp_cz_exl_btw = $invoice->total_ikp_cz_exl_btw + $rowTotalIkpExVat;
                $invoice->total_costs_bol_exl_btw = $invoice->total_costs_bol_exl_btw + $orderDetail->transaction_fee;
             }

             $invoice->customer_phone = $order->delivery_phone_number;
             $invoice->customer_email = $order->customer->email_for_delivery;
             // Shipping Cost berekenen LET OP !!! Indien er in Prestashop nieuwe vervoerders bijkomen dient hier de code aangepast te worden !!!!!!!
             $invoice->total_shipping_cost_exl_btw = $param->shipping_cost_bol_be_ex_btw; 
             $invoice->company_name = $order->invoice_company;
             $invoice->total_invoice_exl_btw = $invoice->total_products_exl_btw; 
             $invoice->total_invoice_incl_btw = $invoice->total_products_incl_btw;
             $invoice->total_wrapping_exl_btw = 0;
             $invoice->total_wrapping_incl_btw = 0;
             $invoice->invoice_type = '3';
             $invoice->total_wrapping_cost_ex_btw = 0;
             $invoice->netto_margin_ex_btw = $invoice->total_invoice_exl_btw - $invoice->total_ikp_cz_exl_btw - $invoice->total_shipping_cost_exl_btw - $invoice->total_costs_bol_exl_btw;
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
                    $productInRow = CzProduct::where('id_product',$orderDetail->id_product)->first();
                    $invoiceRow->id_cz_cust_invoice = $invoice->id_cz_cust_invoice;
                    $invoiceRow->id_cust_invoice = $invoice->id_cust_invoice;
                    $invoiceRow->id_product = $orderDetail->id_product;
                    $invoiceRow->product_reference = $productInRow->reference;
                    $invoiceRow->bol_procent_cost = $productInRow->bol_group_cost_fix;
                    $invoiceRow->bol_fix_cost = $productInRow->bol_group_cost_procent;
                    $invoiceRow->product_suppl_reference = $productInRow->product_supplier_reference;
                    $invoiceRow->product_descr = $orderDetail->product_name;
                    $invoiceRow->quantity = $orderDetail->quantity;
                    $invoiceRow->product_unit_price_ex_vat = ($orderDetail->unit_price_incl_vat / ((($orderDetail->vat_procent)/100)+1));
                    $invoiceRow->product_ikp_price_cz_ex_vat = $orderDetail->unit_ikp_cz_ex_vat;
                    $invoiceRow->product_total_ikp_cz_ex_vat = ($orderDetail->unit_ikp_cz_ex_vat * $orderDetail->quantity);
                    $invoiceRow->product_total_price_ex_vat = ( $invoiceRow->product_unit_price_ex_vat * $orderDetail->quantity);
                    $invoiceRow->vat_procent = $orderDetail->vat_procent;
                    $invoiceRow->row_bol_cost_amount = round((($productInRow->bol_group_cost_procent / 100) * $invoiceRow->product_total_price_ex_vat) + ($productInRow->bol_group_cost_fix / (( $invoiceRow->vat_procent / 100)+1)),2);
                    $invoiceRow->product_total_price_incl_vat = ($orderDetail->unit_price_incl_vat * $orderDetail->quantity);
                    $invoiceRow->ean_product = $orderDetail->ean_code;
                    $invoiceRow->id_supplier = $productInRow->id_supplier;
                    $invoiceRow->product_unit_price_incl_vat = $orderDetail->unit_price_incl_vat;
                    $invoiceRow->save();
            // Change to invoice field  in Products 
                    $productInRow->quantity_to_invoice = $productInRow->quantity_to_invoice - $orderDetail->quantity;
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
            } // end invoiced 
        } // end newState = 19
    }  //end public function changeState

//************************************************************************************
//*****
//*****                       GET BOL getBolOrders
//************************************************************************************


    public function getBolOrders()
    {
        // or live API: https://plazaapi.bol.com
       //  $url = 'https://test-plazaapi.bol.com';     // test url
        $url = 'https://plazaapi.bol.com';    // Productie url
        $uri = '/services/rest/orders/v2';
        // Your BOL keys
        $publicKey = env('BOL_BE_PUBLIC_PROD_KEY');
        $privateKey = env('BOL_BE_PRIVATE_PROD_KEY');
        $param = CzParameter::find(1);


        $client = new BolPlazaClient($publicKey, $privateKey, false);
        $newPlazaOrders = $client->getOrders();  
        if ($newPlazaOrders) 
        { 
    //    dd($newPlazaOrders);
        // Create orders in Mysql from incoming JSONDataUpdate
            // Loop over each order
            foreach($newPlazaOrders as $plazaOrder)
            {             
                $plazaOrderId = $plazaOrder->id;
                $orderExist = BolBeOrders::where('bol_be_order_id',$plazaOrderId)->get();
                if($orderExist->isEmpty())     // If new order not yet imported !
                { 
                    // 1) Look if it is existing client of new client based on name + address + HousNumber
                    $clientFirstName = $plazaOrder->BillingAddress->Firstname;
                    $clientLastName = $plazaOrder->BillingAddress->Surname;
                    $clientStreet = $plazaOrder->BillingAddress->Streetname;
                    $clientHouseNumber = $plazaOrder->BillingAddress->Housenumber;
                    $clientCity = $plazaOrder->BillingAddress->City;
                    $customerExist = PsAddress::where('firstname','LIKE', '%'.$clientFirstName.'%')
                                            ->where('lastname','LIKE', '%'.$clientLastName.'%')
                                            ->where('address1','LIKE', '%'.$clientStreet.$clientHouseNumber.'%')
                                            ->where('city','LIKE', '%'.$clientCity.'%')
                                            ->get();
                                                                                                // id_customer <> 0 because we search on address and there are also Suppliers in it
                    if(!$customerExist->isEmpty() and $customerExist[0]->id_customer <> 0 )    // Customer exist in our database 
                    {
                        // GET CUSTOMER and change email adres with new bol email !!!!
                        $idBolCustomer = $customerExist[0]->id_customer;
                    }
                    else      // Customer does not exist !!!
                    {
                        $customer = new PsCustomer;
                        $customer->id_shop_group = '1';
                        $customer->id_shop = '1';
                        $customer->id_gender = '3';
                        $customer->id_default_group = '3';
                        $customer->id_lang = '4';
                        $customer->id_risk = '0';
                        $customer->max_payment_days = 0;
                        if($plazaOrder->BillingAddress->Company)
                        {
                            $customer->company = $plazaOrder->BillingAddress->Company;
                        }
                        $customer->firstname = $clientFirstName;
                        $customer->lastname = $clientLastName;
                        if($plazaOrder->BillingAddress->Email)
                        {
                            $customer->email = $plazaOrder->BillingAddress->Email;
                        }
                        $customer->passwd = 'ZWD1234567890';
                        $customer->last_passwd_gen = date('Y-m-d H:i:s');
                        $customer->newsletter = '0';
                        $customer->note = 'Bol.com BE';
                        $customer->active = '1';
                        $customer->is_guest = '0';
                        $customer->date_add = date('Y-m-d H:i:s');
                        $customer->date_upd = date('Y-m-d H:i:s');
                    //    dd($customer);
                        $customer->save();
                        $idBolCustomer = $customer->id_customer;

                        // Add new delivery <address>      
                        $address = new PsAddress;
                        $address->id_customer = $customer->id_customer;
                        if($plazaOrder->ShippingAddress->Surname)
                        {
                            $address->lastname =  $plazaOrder->ShippingAddress->Surname;
                        }
                        if($plazaOrder->ShippingAddress->Firstname)
                        {
                            $address->firstname = $plazaOrder->ShippingAddress->Firstname;
                        }
                        if($plazaOrder->ShippingAddress->Company)
                        {
                            $address->company = $plazaOrder->ShippingAddress->Company;
                        }
                        if($plazaOrder->ShippingAddress->CountryCode)    // COuntryCode kan bij bol momenteel enkel BE of NL zijn
                        {
                            $countryDelivery = $plazaOrder->ShippingAddress->CountryCode;
                            if($countryDelivery == 'BE')
                            {
                                $address->id_country = '3';
                            }
                            else
                            {
                                $address->id_country = '13';
                            }
                        }
                        $address->id_state = 0;
                        $address->id_manufacturer = 0;
                        $address->id_supplier= 0;
                        $address->id_warehouse = 0;
                        $address->active = 1;
                        $address->deleted = 0;
                        $address->alias = 'Leveringsadres';
                        if($plazaOrder->ShippingAddress->Streetname)
                        {
                            $streetDelivery = $plazaOrder->ShippingAddress->Streetname;
                        }
                        if($plazaOrder->ShippingAddress->Housenumber)
                        {
                            $nrDelivery = $plazaOrder->ShippingAddress->Housenumber;                 
                        }
                        if($plazaOrder->ShippingAddress->HousenumberExtended)
                        {
                            $nrExtDelivery = $plazaOrder->ShippingAddress->HousenumberExtended;
                        }
                        if(isset($nrExtDelivery))
                        {
                            $address->address1 = $streetDelivery . " " . $nrDelivery . " " . $nrExtDelivery;
                        }
                        else
                        {
                            $address->address1 = $streetDelivery . " " . $nrDelivery;

                        }
                        if($plazaOrder->ShippingAddress->AddressSupplement)
                        {
                            $address->address2 = $plazaOrder->ShippingAddress->AddressSupplement;
                        }                   
                        $address->vat_number = '';
                        if($plazaOrder->ShippingAddress->ZipCode)
                        {
                            $address->postcode = $plazaOrder->ShippingAddress->ZipCode;         
                        }
                        if($plazaOrder->ShippingAddress->City)
                        {
                            $address->city = $plazaOrder->ShippingAddress->City;
                        }
                        if($plazaOrder->ShippingAddress->DeliveryPhoneNumber)
                        {
                            $address->phone = $plazaOrder->ShippingAddress->DeliveryPhoneNumber;
                        }
                        $address->phone_mobile = '';
                        if($plazaOrder->ShippingAddress->ExtraAddressInformation)
                        {
                            $address->other = $plazaOrder->ShippingAddress->ExtraAddressInformation;
                        } 
                        $address->date_add = date('Y-m-d H:i:s');
                        $address->date_upd = date('Y-m-d H:i:s');
                    //   dd($address);
                        $address->save();

                // Add new Billing / Invoice Address      
                        $invAddress = new PsAddress;
                        $invAddress->id_customer = $customer->id_customer;
                        if($plazaOrder->BillingAddress->Surname)
                        {
                            $invAddress->lastname = $plazaOrder->BillingAddress->Surname;
                        }
                        if($plazaOrder->BillingAddress->Firstname)
                        {
                            $invAddress->firstname = $plazaOrder->BillingAddress->Firstname;
                        }
                        if($plazaOrder->BillingAddress->Company)
                        {
                            $invAddress->company = $plazaOrder->BillingAddress->Company;
                        }
                        if($plazaOrder->BillingAddress->CountryCode)
                        {
                            $countryInvoice = $plazaOrder->BillingAddress->CountryCode; 
                            if($countryInvoice == 'BE')
                            {
                                $invAddress->id_country = '3';
                            }
                            else
                            {
                                $invAddress->id_country = '13';
                            }
                        }
                        $invAddress->id_state = 0;
                        $invAddress->id_manufacturer = 0;
                        $invAddress->id_supplier= 0;
                        $invAddress->id_warehouse = 0;
                        $invAddress->active = 1;
                        $invAddress->deleted = 0;
                        $invAddress->alias = 'facturatieadres';
                        if($plazaOrder->BillingAddress->Streetname)
                        {
                            $streetInvoice = $plazaOrder->BillingAddress->Streetname;
                        }
                        if($plazaOrder->BillingAddress->Housenumber)
                        {
                            $nrInvoice = $plazaOrder->BillingAddress->Housenumber;
                        }
                        if($plazaOrder->BillingAddress->HousenumberExtended) 
                        {
                            $nrExtInvoice =  $plazaOrder->BillingAddress->HousenumberExtended;
                        }
                        if(isset($nrExtInvoice))
                        {
                            $invAddress->address1 = $streetInvoice . " " . $nrInvoice . " " . $nrExtInvoice;
                        }
                        else
                        {
                            $invAddress->address1 = $streetInvoice . " " . $nrInvoice;
                        }
                        if($plazaOrder->BillingAddress->AddressSupplement)
                        {
                            $invAddress->address2 = $plazaOrder->BillingAddress->AddressSupplement;
                        }
                        if($plazaOrder->BillingAddress->VatNumber)
                        {
                            $invAddress->vat_number = $plazaOrder->BillingAddress->VatNumber;
                        }
                        if($plazaOrder->BillingAddress->ZipCode)
                        {
                            $invAddress->postcode = $plazaOrder->BillingAddress->ZipCode;
                        }
                        if($plazaOrder->BillingAddress->City)
                        {
                            $invAddress->city = $plazaOrder->BillingAddress->City;
                        }
                        if($plazaOrder->BillingAddress->DeliveryPhoneNumber)
                        {
                            $invAddress->phone = $plazaOrder->BillingAddress->DeliveryPhoneNumber;
                        }
                        $invAddress->phone_mobile = '';
                        if($plazaOrder->BillingAddress->ExtraAddressInformation)
                        {
                            $invAddress->other = $plazaOrder->BillingAddress->ExtraAddressInformation;
                        }
                        $invAddress->date_add = date('Y-m-d H:i:s');
                        $invAddress->date_upd = date('Y-m-d H:i:s');
                   //     dd($invAddress);
                        $invAddress->save();        
                    }  // End If customer exist or not 
                    // Create ORDER !!!!! (include last delivery address in order to be sure that delivery goes to correct address with all info )
                    $bolOrder = new BolBeOrders;
                    $bolOrder->bol_be_order_id = $plazaOrderId;
                    $bolOrder->current_state = 2;
                    $bolOrder->id_customer = $idBolCustomer;
                    $bolOrder->date_order = substr(($plazaOrder->date),0,10);
                    $bolOrder->time_order = substr(($plazaOrder->date),11,8);
                    // Fill Delivery address in Bol_be_order
                    if($plazaOrder->ShippingAddress->SalutationCode) 
                    {
                        $salutationCode = $plazaOrder->ShippingAddress->SalutationCode;
                        if($salutationCode == '01')
                        {
                            $bolOrder->delivery_id_gender = '1';
                        }
                        elseif($salutationCode == '02')
                        {
                            $bolOrder->delivery_id_gender = '2';
                        }
                        else
                        {
                            $bolOrder->delivery_id_gender = '3';
                        }
                    }
                    if($plazaOrder->ShippingAddress->Firstname) 
                    {
                        $bolOrder->delivery_first_name = $plazaOrder->ShippingAddress->Firstname;    
                    }
                    if($plazaOrder->ShippingAddress->Surname) 
                    {
                        $bolOrder->delivery_last_name = ($plazaOrder->ShippingAddress->Surname);
                    }
                    if($plazaOrder->ShippingAddress->Streetname)
                    {
                        $streetDelivery = $plazaOrder->ShippingAddress->Streetname;
                    }
                    if($plazaOrder->ShippingAddress->Housenumber)
                    {
                        $houseNrDelivery = $plazaOrder->ShippingAddress->Housenumber;                 
                    }
                    if($plazaOrder->ShippingAddress->HousenumberExtended)
                    {
                        $nrExtDelivery = $plazaOrder->ShippingAddress->HousenumberExtended;
                    }
                    if(isset($nrExtDelivery))
                    {
                        $bolOrder->delivery_address_1 = $streetDelivery . " " . $houseNrDelivery . " " . $nrExtDelivery;
                    }
                    else
                    {
                        $bolOrder->delivery_address_1 = $streetDelivery . " " . $houseNrDelivery;
                    }
                    if($plazaOrder->ShippingAddress->AddressSupplement)
                    {
                        $bolOrder->delivery_address_2 = $plazaOrder->ShippingAddress->AddressSupplement;
                    }  
                    if($plazaOrder->ShippingAddress->ExtraAddressInformation)
                    {
                        $bolOrder->delivery_extra_address_info = $plazaOrder->ShippingAddress->ExtraAddressInformation;
                    } 
                    if($plazaOrder->ShippingAddress->ZipCode)
                    {
                        $bolOrder->delivery_postcode = $plazaOrder->ShippingAddress->ZipCode;         
                    }
                    if($plazaOrder->ShippingAddress->City)
                    {
                        $bolOrder->delivery_city = $plazaOrder->ShippingAddress->City;
                    } 
                    if($plazaOrder->ShippingAddress->CountryCode)
                    {
                        $countryDelivery = $plazaOrder->ShippingAddress->CountryCode;
                        if($countryDelivery == 'BE')
                        {
                            $bolOrder->id_delivery_country = '3';
                        }
                        else
                        {
                            $bolOrder->id_delivery_country = '13';
                        }
                    }
                    if($plazaOrder->ShippingAddress->Email)
                    {
                        $bolOrder->email_for_delivery = $plazaOrder->ShippingAddress->Email;
                    }
                    if($plazaOrder->ShippingAddress->Company)
                    {
                        $bolOrder->delivery_company = $plazaOrder->ShippingAddress->Company;
                    } 
                    if($plazaOrder->ShippingAddress->DeliveryPhoneNumber)
                    {
                        $bolOrder->delivery_phone_number = $plazaOrder->ShippingAddress->DeliveryPhoneNumber;
                    }   
        // Fill Invoice address in Bol_be_order
                    if($plazaOrder->BillingAddress->SalutationCode) 
                    {
                        $salutationCode = $plazaOrder->BillingAddress->SalutationCode;
                        if($salutationCode == '01')
                        {
                            $bolOrder->invoice_id_gender = '1';
                        }
                        elseif($salutationCode == '02')
                        {
                            $bolOrder->invoice_id_gender = '2';
                        }
                        else
                        {
                            $bolOrder->invoice_id_gender = '3';
                        }
                    }
                    if($plazaOrder->BillingAddress->Firstname) 
                    {
                        $bolOrder->invoice_first_name = $plazaOrder->BillingAddress->Firstname;    
                    }
                    if($plazaOrder->BillingAddress->Surname) 
                    {
                        $bolOrder->invoice_last_name = $plazaOrder->BillingAddress->Surname;
                    }
                    if($plazaOrder->BillingAddress->Streetname)
                    {
                        $streetInvoice = $plazaOrder->BillingAddress->Streetname;
                    }
                    if($plazaOrder->BillingAddress->Housenumber)
                    {
                        $houseNrInvoice = $plazaOrder->BillingAddress->Housenumber;                 
                    }
                    if($plazaOrder->BillingAddress->HousenumberExtended)
                    {
                        $nrExtInvoice = $plazaOrder->BillingAddress->HousenumberExtended;
                    }
                    if(isset($nrExtInvoice))
                    {
                        $bolOrder->invoice_address_1 = $streetInvoice . " " . $houseNrInvoice . " " . $nrExtInvoice;
                    }
                    else
                    {
                        $bolOrder->invoice_address_1 = $streetInvoice . " " . $houseNrInvoice;
                    }
                    if($plazaOrder->BillingAddress->AddressSupplement)
                    {
                        $bolOrder->invoice_address_2 = $plazaOrder->BillingAddress->AddressSupplement;           
                    }  
                    if($plazaOrder->BillingAddress->ExtraAddressInformation)
                    {
                        $bolOrder->invoice_extra_address_info = $plazaOrder->BillingAddress->ExtraAddressInformation;
                    } 
                    if($plazaOrder->BillingAddress->ZipCode)
                    {
                        $bolOrder->invoice_postcode = $plazaOrder->BillingAddress->ZipCode;         
                    }
                    if($plazaOrder->BillingAddress->City)
                    {
                        $bolOrder->invoice_city = $plazaOrder->BillingAddress->City;
                    } 
                    if($plazaOrder->BillingAddress->CountryCode)
                    {
                        $countryInvoice = $plazaOrder->BillingAddress->CountryCode;
                        if($countryInvoice == 'BE')
                        {
                            $bolOrder->id_invoice_country = '3';
                        }
                        else
                        {
                            $bolOrder->id_invoice_country = '13';
                        }
                    }
                    if($plazaOrder->BillingAddress->Email)
                    {
                        $bolOrder->email_for_invoice = $plazaOrder->BillingAddress->Email;
                    }
                    if($plazaOrder->BillingAddress->Company)
                    {
                        $bolOrder->invoice_company = $plazaOrder->BillingAddress->Company;
                    } 
                    if($plazaOrder->BillingAddress->InvoicePhoneNumber)
                    {
                        $bolOrder->invoice_phone_number = $plazaOrder->BillingAddress->InvoicePhoneNumber;
                    } 
                    if($plazaOrder->BillingAddress->VatNumber)
                    {
                        $bolOrder->invoice_vat_number = $plazaOrder->BillingAddress->VatNumber;
                    }   

                    $bolOrder->date_add =  date('Y-m-d H:i:s');
                    $bolOrder->date_upd =  date('Y-m-d H:i:s');
                //    dd($bolOrder);
                    $bolOrder->save();
                    $lastBolBeOrder = BolBeOrders::orderBy('id_bol_be_orders', 'desc')->first();
                    $lastIdBolBeOrders = $lastBolBeOrder->id_bol_be_orders;




    //*************************************************************
    //*****        Make Orderdetails - rows                     ***
    //*************************************************************

                    $plazaOrderItems = $plazaOrder->OrderItems;
                //    dd($orderItems);
                    foreach($plazaOrderItems as $plazaOrderItem)
                    {
                 //       dd($plazaOrderItem);
                        $orderRow = new BolBeOrderDetail;
                        $orderRow->id_bol_be_orders = $lastIdBolBeOrders;
                        $orderRow->bol_be_order_id = $plazaOrderId;
                        if($plazaOrderItem->OrderItemId)
                        {
                            $orderRow->bol_item_id = $plazaOrderItem->OrderItemId;
                        }
                        if($plazaOrderItem->OfferReference)
                        {
                            $orderRow->id_product = $plazaOrderItem->OfferReference;
                            // Get extra Product info 
                            $czProduct = CzProduct::where('id_product',$orderRow->id_product)->first();

                            $orderRow->vat_procent = $czProduct->vat_procent;
                            $orderRow->shipping_cost_bol_be = $param->shipping_cost_bol_be_ex_btw;
                            $orderRow->calc_bol_be_cost = $czProduct->bol_be_cost; 
                            $orderRow->unit_ikp_cz_ex_vat = $czProduct->ikp_ex_cz;                                         
                        }
                    
                        if($plazaOrderItem->Title)
                        {
                            $orderRow->product_name = $plazaOrderItem->Title;
                        }
                        if($plazaOrderItem->Quantity)
                        {
                            $orderRow->quantity = $plazaOrderItem->Quantity;
                        }
                        if($plazaOrderItem->EAN)
                        {
                            $orderRow->ean_code = $plazaOrderItem->EAN;
                        }
                        if($plazaOrderItem->OfferPrice)
                        {
                            $orderRow->row_price_incl_vat = $plazaOrderItem->OfferPrice;
                            $orderRow->unit_price_incl_vat = $orderRow->row_price_incl_vat / $orderRow->quantity; 
                        }
                        if($plazaOrderItem->TransactionFee)
                        {
                            $orderRow->transaction_fee = $plazaOrderItem->TransactionFee; 
                        }
                        if($plazaOrderItem->PromisedDeliveryDate)
                        {
                            $orderRow->promised_delivery_date = $plazaOrderItem->PromisedDeliveryDate;
                        }  
                  //      dd($orderRow);            
                        $orderRow->save();
                    } // end foreach orderitems    
                } // End if new Order (!orderExist)
            }  // End foreach orders
        } // En if orders
        // Return New BOL-BE orders to the view
      //  $newBolBeOrders = BolBeOrders::all();
        $newBolBeOrders = BolBeOrders::where('current_state','<=',5)
                            ->orwhere('current_state','=',11)
                            ->orwhere('current_state','=',12)
                            ->orwhere('current_state','=',14)
                            ->orwhere('current_state','=',16)
                            ->orderBy('id_bol_be_orders', 'desc')
                            ->orwhere('current_state','=',17)->get();
        return view('bol.be.newOrders', compact('newBolBeOrders'));
    } // En function GetBolOrders

} // End class



