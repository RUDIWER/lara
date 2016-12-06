<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
class BolBeOrders extends Model
{
    protected $table = 'bol_be_orders';
    protected $primaryKey = 'id_bol_be_orders';
    public $timestamps = false;


    public function bolBeOrderDetails()
    {
        return $this->hasMany('App\Models\BolBeOrderDetail','id_bol_be_orders','id_bol_be_orders');
    }

    public function customer()
    {
        return $this->belongsTo('App\Models\PsCustomer','id_customer','id_customer');
    }

    public function orderState()
    {
        return $this->belongsTo('App\Models\PsOrderStateLang','current_state','id_order_state');
    }

    public function deliveryCountry(){
      return $this->belongsTo('App\Models\PsCountryLang','id_delivery_country','id_country');
    }

      public function invoiceCountry(){
      return $this->belongsTo('App\Models\PsCountryLang','id_invoice_country','id_country');
    }

}
