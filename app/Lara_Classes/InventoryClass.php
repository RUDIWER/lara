<?php

namespace App\Lara_Classes;

use Illuminate\Support\Facades\DB;
use App\Models\CzProduct;
use App\Models\PsStockAvailable;
use App\Models\PsProduct;
use App\Models\PsProductShop;

use MCS\BolPlazaClient;

class InventoryClass {

    private $id_product;
    private $quantity;

    public function __construct($id_product, $quantity)
    {    
         if (!$id_product or !$quantity) 
         {
            throw new Exception('Product Id of Aantal werd niet aan InventoryClass meegegeven');
         }
         else
         {                
            $this->id_product = $id_product;
            $this->quantity = $quantity;
         }
    }

    public function reduceOnDelivery()     // Stock aanpassing na levering voorraad -X   To Invoice +X + Change Cz Stock
    {
        $czProduct = CzProduct::where('id_product',$this->id_product)->first();
        $czProduct->quantity_in_stock =  $czProduct->quantity_in_stock - $this->quantity;
        $czProduct->quantity_to_invoice =  $czProduct->quantity_to_invoice + $this->quantity;
        $this->inventoryUpd($czProduct);
    }

        public function increaseOnAnnul()     // Stock aanpassing bij annulatie VOOR Facturatie voorraad +X   To Invoice -X + Change Cz Stock
    {
        $czProduct = CzProduct::where('id_product',$this->id_product)->first();
        $czProduct->quantity_in_stock =  $czProduct->quantity_in_stock + $this->quantity;
        $czProduct->quantity_to_invoice =  $czProduct->quantity_to_invoice - $this->quantity;
        $this->inventoryUpd($czProduct);
    }



    private function inventoryUpd($czProduct)
    {
    //1) Update Prestashop Stock
        $psStockAvailable = PsStockAvailable::where('id_product',$this->id_product)->first();
        $psStockAvailable->quantity = $czProduct->quantity_in_stock;

        // If Stock NUL OR LOWER DEACTIVATE PRODUCT IN PRESTASHOP
        $psProduct = PsProduct::where('id_product',$this->id_product)->first();
        $psProductShop = PsProductShop::where('id_product',$this->id_product)->first();
        if($czProduct->quantity_in_stock <= 0)
        {
            $czProduct->active = 0;
            $psProduct->active = 0;
            $psProductShop->active = 0;
        }


    //2) Update Bol_NL Stock
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
// BOL BE stock kan nog niet gebruikt worden eerst moeten alle producten opnieuw ingebracht wordenvia de plaza api !
// ALVORENS TE ACTIVEREN CODE VAN NEDERLANDSE VERSIE HIERBOVEN BEKIJKEN EN VERGELIJKEN !!!!!!!!!!!!

    //3) Update Bol_BE Stock
    /*
        if($czProduct->active_bol_nl == 1)
        {
            $publicBeKey = env('BOL_BE_PUBLIC_PROD_KEY');
            $privateBeKey = env('BOL_BE_PRIVATE_PROD_KEY');
            $clientBe = new BolPlazaClient($this->publicBelKey, $this->privateBeKey, false);
            $updateBe = $clientBe->updateOfferStock($id_product, $inventory);
            dd($updateBe);
        }
    */
    }


}
