<?php

namespace App\Http\Controllers;
use DateTime;
use App\Models\BolBeOrders;
use App\Models\BolBeOrderDetail;
use App\Models\PsCustomer;
use App\Models\PsAddress;
use Illuminate\Http\Request;
use App\Http\Requests;
use MCS\BolPlazaClient;
use App\Lara_Classes\InventoryClass;

class BolTestController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function test()
    {
        $stock = new InventoryClass(11,2);
        $stock->reduceOnDelivery();
    }

    public function delOffers()
    {

        $publicKey = env('BOL_NL_PUBLIC_PROD_KEY');
        $privateKey = env('BOL_NL_PRIVATE_PROD_KEY');
        $client = new BolPlazaClient($publicKey, $privateKey, false);

        $delete = $client->deleteOffer('487');
        if ($delete) {
            echo 'Offer deleted';    
        }  
    }

     public function getOffers()
    {

        $publicKey = env('BOL_NL_PUBLIC_PROD_KEY');
        $privateKey = env('BOL_NL_PRIVATE_PROD_KEY');
        $client = new BolPlazaClient($publicKey, $privateKey, false);

        $offerFile = $client->requestOfferFile();
        sleep(400);
        $offers = $client->getOffers($offerFile);
        dd($offers);
    }


/*

    public function postBolShipment()
    {
        
            //  your public PRODUCTION keys
     //   $publicKey = env('BOL_PUBLIC_PROD_KEY');
     //   $privateKey = env('BOL_PRIVATE_PROD_KEY');

     //  your public TEST keys
        $publicKey = env('BOL_BE_PUBLIC_PROD_KEY');
        $privateKey = env('BOL_BE_PRIVATE_PROD_KEY');


        $client = new BolPlazaClient($publicKey, $privateKey, false);
        // Get an order by it's id and ship it
    
        $order = $client->getOrder('4075698340');
        if ($order) 
        {
            print_r('Order bestaat al met nr :');
            // Bol.com requires you to add an expected deliverydate to a shipment
            $deliveryDate = new DateTime('07-12-2016');
            // This client also provides a helper function to calculate the next deliverydate
            // Ship an order with track and trace. See https://developers.bol.com/documentatie/plaza-api/appendix-a-transporters/ for supported carrier codes
            $shipped = $order->ship($deliveryDate, 'BPOST_BE');    
            // Ship an order without track and trace
            // $shipped = $order->ship($deliveryDate);
       //    dd($shipped);
           $callId = $shipped[0]["id"];
            print_r($shipped);
        }
          $answer = $client->getShippingStatus($callId);
            if ($answer) {  
                dd($answer);
            print_r("GELUKT ????");
          }  

        */  
}
