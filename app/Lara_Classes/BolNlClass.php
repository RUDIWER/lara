<?php

namespace App\Lara_Classes;

use MCS\BolPlazaClient;

class BolNlClass {
  public function createOffer() {

    $publicKey = env('BOL_NL_PUBLIC_PROD_KEY');
    $privateKey = env('BOL_NL_PRIVATE_PROD_KEY');
    $client = new BolPlazaClient($publicKey, $privateKey, false);

    $created = $client->createOffer('k002', [
        'EAN' => '8711145678987',
        'Condition' => 'NEW', // https://developers.bol.com/documentatie/plaza-api/appendix-b-conditions/
        'Price' => 189.99,
        'DeliveryCode' => '24uurs-21',
        'QuantityInStock' => 100,
        'Publish' => true,
        'ReferenceCode' => 'sku002',
        'Description' => 'Description...'
    ]);
    if ($created) {
        return 'Offer created';    
    }
  }
}
