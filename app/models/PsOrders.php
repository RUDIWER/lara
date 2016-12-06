<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use App\Models\PsCustomer;



class PsOrders extends Model
{
    protected $table = 'ps_orders';
    protected $primaryKey = 'id_order';
    public $timestamps = false;

    public function customer()
    {
      return $this->belongsTo('App\Models\PsCustomer','id_customer','id_customer');
    }

    public function deliveryAddress()
    {
      return $this->belongsTo('App\Models\PsAddress','id_address_delivery','id_address');
    }

    public function invoiceAddress()
    {
      return $this->belongsTo('App\Models\PsAddress','id_address_invoice','id_address');
    }

    public function orderState()
    {
      return $this->belongsTo('App\Models\PsOrderStateLang','current_state','id_order_state');
    }

    public function orderDetails()
    {
        return $this->hasMany('App\Models\PsOrderDetail','id_order','id_order');
    }
}
