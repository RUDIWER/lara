<?php

namespace App\Lara_Classes;

use MCS\BolPlazaClient;

class BolNlClass {
  public function createOffer($CzProduct) {

    $publicKey = env('BOL_NL_PUBLIC_PROD_KEY');
    $privateKey = env('BOL_NL_PRIVATE_PROD_KEY');
    $client = new BolPlazaClient($publicKey, $privateKey, false);
    $strIdProduct = strval($CzProduct->id_product);
    $offerId = 'offerID'. $strIdProduct;
    $created = $client->createOffer($offerId, [
        'EAN' => $CzProduct->ean13,
        'Condition' => 'NEW', // https://developers.bol.com/documentatie/plaza-api/appendix-b-conditions/
        'Price' => $CzProduct->vkp_bol_nl_in_vat,
        'DeliveryCode' => '3-5d',
        'QuantityInStock' => $CzProduct->quantity_in_stock,
        'Publish' => true,
        'ReferenceCode' => strval($CzProduct->id_product),
        'Description' => $CzProduct->name
    ]);
    if ($created) {
        dd($created);
        return 'Offer created';    
    }
  }

    public function deleteOffer($CzProduct) {

    $publicKey = env('BOL_NL_PUBLIC_PROD_KEY');
    $privateKey = env('BOL_NL_PRIVATE_PROD_KEY');
    $client = new BolPlazaClient($publicKey, $privateKey, false);
    $strIdProduct = strval($CzProduct->id_product);
    $offerId = 'offerID'. $strIdProduct;
 //   dd($offerId);
    $deleted = $client->deleteOffer('k002');   
    if ($deleted) {
        return 'Offer deleted';    
    }
  }
}


