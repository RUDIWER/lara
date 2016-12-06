<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
class CzCustInvoice extends Model
{
    protected $table = 'cz_cust_invoice';
    protected $primaryKey = 'id_cust_invoice';
    public $timestamps = false;

     public function invoiceDetails(){
        return $this->hasMany('App\Models\CzCustInvoiceDetail','id_cust_invoice','id_cust_invoice');
    }
    
     public function customer()
    {
      return $this->belongsTo('App\Models\PsCustomer','id_customer','id_customer');
    }



}
