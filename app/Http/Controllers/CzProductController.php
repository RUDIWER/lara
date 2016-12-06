<?php

namespace App\Http\Controllers;

use App\Models\CzProduct;
use App\Models\PsProduct;
use App\Models\PsProductShop;
use App\Models\PsProductLang;
use App\Models\CzParameter;
use App\Models\PsSupplier;
use App\Models\PsCategory;
use App\Models\PsCategoryLang;
use App\Models\PsCategoryProduct;
use App\Models\PsProductSupplier;
use App\Models\PsStockAvailable;
use App\Models\PsImage;
use App\Http\Controllers\Controller;
use Dhtmlx\Connector\GridConnector;
use Illuminate\Http\Request;
use App\Lara_Classes\BolNlClass;
//use Illuminate\Support\Facades\DB;

class CzProductController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function products()
    {
        return view('producten.producten');
    }

    public function productData()
    {
        $connector = new GridConnector(null, "PHPLaravel");
        $connector->configure(
            new CzProduct(),
            "id",
            "id_cz_product,id_product,reference,name,ikp_ex_cz,vkp_cz_in_vat,vkp_bol_be_in_vat"
        );
        $connector->render();
    }

    public function create() {
        $product = new CzProduct();
        $product->ikp_supplier = 0;
        $product->active = 0;
        $product->cost_factor = 1;
        $product->bol_group_cost_fix = 0;
        $product->bol_group_cost_procent = 0;
        $product->vkp_cz_ex_vat = 0;
        $product->vkp_cz_in_vat = 0;
        $product->vkp_bol_be_in_vat = 0;
        $product->vkp_bol_nl_in_vat = 0;
        $product->quantity_in_stock = 0;
        $param = CzParameter::find(1);
        $product->margin_factor_dropshipping = $param->stand_margin_dropshipping;
        $product->margin_factor_wholesale = $param->stand_margin_wholesale;
        $suppliers = PsSupplier::all();
        $categories = PsCategoryLang::where('id_lang', 4)->orderBy('name', 'desc')->get();
        $isNew = 1;
        $imagePath = "";
        return view('producten.edit', compact('product','suppliers','categories','isNew','param','imagePath'));
    }

    public function edit($id_cz_product) {
        $product = CzProduct::find($id_cz_product);
        $param = CzParameter::find(1);
        $suppliers = PsSupplier::all();
        $categories = PsCategoryLang::where('id_lang', 4)->orderBy('name', 'desc')->get();
        $isNew = 0;

        // GeT image ID and create image path !!!

        $image = PsImage::where(['id_product' => $product->id_product, 'cover'=>1])->get();
        if($image->count()){
            echo'ER IS EEN AFBEELDING !!!';
            $imageId = $image[0]->id_image;
            $strlenIdImage = strlen($imageId);
            if ($strlenIdImage = 2) {
                $path1 = substr($imageId,0,1);
                $path2 = substr($imageId,1,1);
                $imagePath = "http://www.cool-zawadi.com/img/p/".$path1."/".$path2."/".$imageId."-large_default.jpg";
            } else if ($strlenIdImage = 3) {
                $path1 = substr($imageId,0,1);
                $path2 = substr($imageId,1,1);
                $path3 = substr($imageId,2,1);
                $imagePath = "http://www.cool-zawadi.com/img/p/".$path1."/".$path2."/".$path3."/".$imageId."-large_default.jpg";
            } else if ($strlenIdImage = 4) {
                $path1 = substr($imageId,0,1);
                $path2 = substr($imageId,1,1);
                $path3 = substr($imageId,2,1);
                $path4 = substr($iimageId,3,1);
                $imagePath = "http://www.cool-zawadi.com/img/p/".$path1."/".$path2."/".$path3."/".$path4."/".$imageId."-large_default.jpg";
            }
        }else{
            $imagePath = "";
        }
        echo $imagePath;
        return view('producten.edit', compact('product','suppliers','categories','isNew','param','imagePath'));
    }

    public function save(Request $request, $id_cz_product)
    {
        $data = $request->all();
        $CzProduct = CzProduct::findornew($id_cz_product);
        $alReadyOnBolNl = $CzProduct->active_bol_nl;
        $CzProduct->fill($data);
// Set product on BOL.NL (Check first if flag is set to on )
        if($alReadyOnBolNl == 0 && $CzProduct->active_bol_nl == 1)
        {
            $bolNlClass = new BolnlClass();
            $offer = $bolNlClass->createOffer();
            dd($offer);
        }
        $CzProduct->date_upd = date('Y-m-d H:i:s');
        if(!$id_cz_product){                            // NIEUW PRODUCT !!!!!
            $CzProduct->date_add = date('Y-m-d H:i:s');
        }
        $CzProduct->save();
// PsProducts ophalen of creeren
        $PsProduct = PsProduct::findornew($CzProduct->id_product);
// Alle waarden van CzProduct toekennen aan PsProduct !!!
        $PsProduct->id_supplier = $CzProduct->id_supplier;
        $PsProduct->id_manufacturer = 0;
        $PsProduct->id_category_default = $CzProduct->id_category_default;
        $PsProduct->id_shop_default = 1;
        if($CzProduct->vat_procent = 21){
            $PsProduct->id_tax_rules_group = 1;
        }else{
            echo('FOUTIEF BTW PERCENTAGE VOORLOPIG ANKEL VOORZIEN OP 21 PROCENT IN CzProductController!!!!!!!!!!');
        }
        $PsProduct->ean13 = $CzProduct->ean13;
        $PsProduct->ecotax = 0.0;
        $PsProduct->quantity = 0;
        $PsProduct->minimal_quantity = 1;
        $PsProduct->price = $CzProduct->vkp_cz_ex_vat;
        $PsProduct->wholesale_price = $CzProduct->ikp_ex_cz;
        $PsProduct->unit_price_ratio = 0;
        $PsProduct->additional_shipping_cost = 0;
        $PsProduct->reference = $CzProduct->reference;
        $PsProduct->active = $CzProduct->active;
        $PsProduct->redirect_type = "404";
        $PsProduct->available_for_order = 1;
        $PsProduct->show_price = 1;
        $PsProduct->cache_default_attribute = 0;
        if(!$PsProduct->exists){                            // NIEUW PRODUCT !!!!!
            $PsProduct->date_add = date('Y-m-d H:i:s');
        }
        $PsProduct->date_upd = date('Y-m-d H:i:s');
        $PsProduct->advanced_stock_management = 0;
        $PsProduct->pack_stock_type = 3;
        $PsProduct->save();
// Na Saven PsProduct id-product ook nog in CzProducts saven !!!!!!
        $CzProduct->id_product = $PsProduct->id_product;
        $CzProduct->save();

// PS_PRODUCT_SHOP Wijzigen of toeveogen
        $PsProductShop = PsProductShop::findornew($CzProduct->id_product);
        // Alle waarden van CzProduct toekennen aan PsProductShop !!!
        $PsProductShop->id_product = $CzProduct->id_product;
        $PsProductShop->id_shop = 1;
        $PsProductShop->id_category_default = $CzProduct->id_category_default;
        if($CzProduct->vat_procent = 21){
            $PsProductShop->id_tax_rules_group = 1;
        }else{
            echo('FOUTIEF BTW PERCENTAGE VOORLOPIG ANKEL VOORZIEN OP 21 PROCENT IN CzProductController!!!!!!!!!!');
        }
        $PsProductShop->price = $CzProduct->vkp_cz_ex_vat;
        $PsProductShop->wholesale_price = $CzProduct->ikp_ex_cz;
        $PsProductShop->unity = "";
        $PsProductShop->active = $CzProduct->active;
        $PsProductShop->redirect_type = "404";
        if(!$PsProductShop->exists){                            // NIEUW PRODUCT !!!!!
            $PsProductShop->date_add = date('Y-m-d H:i:s');
        }
        $PsProductShop->date_upd = date('Y-m-d H:i:s');
        $PsProductShop->save();

// PS_PRODUCT_LANG TOEVOEGEN OF Wijzigen
        $PsProductLang = PsProductLang::firstOrNew(['id_product' => $CzProduct->id_product,'id_lang' => 4]);
        $PsProductLang->id_product = $CzProduct->id_product;
        $PsProductLang->id_shop = 1;
        $PsProductLang->id_lang = 4;
        $PsProductLang->description_short = $CzProduct->descr_short_nl;
        $PsProductLang->link_rewrite = $CzProduct->link_rewrite_nl;
        $PsProductLang->meta_description = $CzProduct->meta_descr_nl;
        $PsProductLang->meta_title = $CzProduct->meta_title_nl;
        $PsProductLang->name = $CzProduct->name;
        $PsProductLang->save();

// PS_PRODUCT_SUPPLIER
        $PsProductSupplier = PsProductSupplier::findornew($CzProduct->id_product);
        $PsProductSupplier->id_product = $CzProduct->id_product;
        $PsProductSupplier->id_product_attribute = 0;
        $PsProductSupplier->id_supplier = $CzProduct->id_supplier;
        $PsProductSupplier->product_supplier_reference = $CzProduct->product_supplier_reference;
        $PsProductSupplier->id_currency = 1;
        $PsProductSupplier->save();

// ps_stock_available
        $PsStockAvailable = PsStockAvailable::findornew($CzProduct->id_product);
        $PsStockAvailable->id_product = $CzProduct->id_product;
        $PsStockAvailable->id_product_attribute = 0;
        $PsStockAvailable->id_shop = 1;
        $PsStockAvailable->id_shop_group = 0;
        $PsStockAvailable->quantity = $CzProduct->quantity_in_stock;
        $PsStockAvailable->depends_on_stock = 0;
        $PsStockAvailable->out_of_stock = 2;
        $PsStockAvailable->save();

// PsCategory
        $PsCategoryProduct = PsCategoryProduct::findornew($CzProduct->id_product);
        if(!$PsCategoryProduct->exists){
        //PRODUCT ZONDER CATEGORIEN !!!!!
        // ADD ROOT Category
            $last = PsCategoryProduct::where('id_category', 2)->orderBy('position', 'desc')->first();
            $PsCategoryProduct->id_category = 2;
            $PsCategoryProduct->id_product = $CzProduct->id_product;
            $PsCategoryProduct->position = $last->position + 1;
            $PsCategoryProduct->save();
        // ADD DEFAULT CATEGORY
            $PsCategoryProduct = new PsCategoryProduct();
            $PsCategoryProduct->id_category = $CzProduct->id_category_default;
            $last = PsCategoryProduct::where('id_category', $CzProduct->id_category_default)->orderBy('position', 'desc')->first();
            $PsCategoryProduct->position = $last->position + 1;
            $PsCategoryProduct->id_product = $CzProduct->id_product;
            $PsCategoryProduct->save();
        }  // end if

        $notification = array(
	        'message' => 'Product succesvol Opgeslagen !',
            'alert-type' => 'success'
        );
        return redirect('/producten')->with($notification);
    }

}
