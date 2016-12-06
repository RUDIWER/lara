<?php

namespace App\Http\Controllers;
use App\Models\BolBeOrders;
use App\Models\BolBeOrderDetail;
use App\Models\PsCustomer;
use App\Models\PsAddress;
use Illuminate\Http\Request;
use App\Http\Requests;
use MCS\BolPlazaClient;

class BolCustOrderController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function getBolOrders()
    {
        // or live API: https://plazaapi.bol.com
       //  $url = 'https://test-plazaapi.bol.com';     // test url
        $url = 'https://plazaapi.bol.com';    // Productie url
        $uri = '/services/rest/orders/v2';
        // Your BOL keys
        $publicKey = env('BOL_BE_PUBLIC_PROD_KEY');
        $privateKey = env('BOL_BE_PRIVATE_PROD_KEY');

        $client = new BolPlazaClient($publicKey, $privateKey, false);
        $newPlazaOrders = $client->getOrders();  
        if ($newPlazaOrders) 
        { 
  //      dd($newPlazaOrders);
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
                        $customer->note = 'Bol.com';
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
                        if($plazaOrder->ShippingAddress->SalutationCode)
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
        $newBolBeOrders = BolBeOrders::all();
        return view('bol.be.newOrders', compact('newBolBeOrders'));
    } // En function GetBolOrders

    public function postBolShipment()
    {
 
    }

} // End class



