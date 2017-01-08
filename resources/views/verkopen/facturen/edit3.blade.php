@extends('layouts.app')
@section('content')
<div id="custInvoiceApp">
    <div id="isNew" data-field-id="{{$isNew}}" ></div>

    @if ($isNew == 0)
        <form action="/verkopen/facturen/save/{{ $invoice->id_cust_invoice }}" name="custInvoiceForm" id="custInvoiceForm" method="post">
    @else
        <form action="/verkopen/facturen/save/0" name="custInvoiceForm" id="custInvoiceForm" method="post">
    @endif
    <input type="hidden" name="_token" value="{{ csrf_token() }}">
    <input type="hidden" name="stand_vat_procent" id="stand_vat_procent" data-field-id="{{ $param->stand_vat_procent }}" v-model="stand_vat_procent">

    @if ($isNew == 0)
        <input type="hidden" name="id_cust_invoice" id="id_cust_invoice" value="{{ $invoice->id_cust_invoice }}">
    @endif
    <div id="isNew" data-field-id="{{$isNew}}" ></div>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    @if ($isNew == 0)
                        <h4 class="panel-heading">Factuur {{ $invoice->id_cust_invoice }} wijzigen {{ $isNew }}
                    @else
                        <h4 class="panel-heading">Factuur Toevoegen {{ $isNew }}
                    @endif
                        <div class="btn-group btn-titlebar pull-right">
                        <a href="{{ URL::to('/verkopen/facturen/print/'.$invoice->id_cust_invoice)}}" type="button" class='btn btn-default btn-sm'>Print</a>
                        <a href="{{ URL::to('/verkopen/facturen') }}" type="button" class='btn btn-default btn-sm'>Annuleer</a>
                        <input type="submit" class='btn btn-default btn-sm' value="Opslaan">
                        </div>
                    </h4>
                    <div class="panel-body panel-body-form">
                        <div class="form-group">
                            <div class="col-xs-12">
                                <div class="row">
                                    <div class="col-xs-2">
                                        <label class="control-label">Factuurnr</label>
                                        @if ($isNew == 0)
                                            <input type="number" class="form-control input-sm" name="id_cust_invoice" id="id_cust_invoice" value="{{ $invoice->id_cust_invoice }}" readonly tabindex="-1">
                                        @else
                                            <input type="text" class="form-control input-sm" name="id_cust_invoice" id="id_cust_invoice" value="Nog niet toegekend" readonly tabindex="-1">
                                        @endif                              
                                    </div>
                                    <div class="col-xs-2 pull-right">
                                        <label class="control-label">Factuur Datum</label>
                                        @if ($isNew == 0)
                                            <input type="text" class="form-control input-sm" name="invoice_date" id="invoice_date" value="{{ $invoice->invoice_date }}" readonly tabindex="-1">
                                        @else
                                            <input type="text" class="form-control input-sm" name="invoice_date" id="invoice_date" value="{{  date('Y-m-d H:i:s') }}" readonly tabindex="-1">
                                        @endif          
                                    </div>
                                    <div class="col-xs-2 pull-right">
                                        <label class="control-label">Order Datum</label>
                                        @if ($isNew == 0)
                                            <input type="text" class="form-control input-sm" name="order_date" id="order_date" value="{{ $invoice->order_date }}" readonly tabindex="-1">
                                        @else
                                            <input type="text" class="form-control input-sm" name="order_date" id="order_date" value="{{ date('Y-m-d H:i:s') }}" readonly tabindex="-1">
                                        @endif                      
                                    </div>
                                </div> <!-- row -->
                            </div> <!-- class xs-12 -->  
                            <div class="h-line"></div>   
                            <div class="form-group">                        
                                <div class="col-xs-6">                  
                                    <div class="row">
                                        <div class="col-xs-4">                                                                 
                                            <label class="control-label">Klant</label>
                                            <select class="form-control selectpicker show-tick show-menu-arrow" data-live-search="true" data-style="btn-default btn-sm btn-required" title="klant..." name="id_customer" id="id_customer" required >
                                                @foreach ($customers as $customer)
                                                    @if($customer->id_customer == $invoice->id_customer)
                                                        <option value="{{ $customer->id_customer }}" selected="selected">{{ $customer->firstname }} {{ $customer->lastname }} / {{ $customer->id_customer }}</option>
                                                    @else
                                                        <option value="{{ $customer->id_customer }}">{{ $customer->firstname }} {{ $customer->lastname }} / {{ $customer->id_customer }}</option>
                                                    @endif
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-xs-4">
                                            Factuur Adres :<br>
                                            <b>{{ $invoice->company }}</b><br> 
                                            <b>{{ $invoice->customer_street_nr }}</b><br> 
                                            <b>{{ $invoice->customer_postal_code}} {{ $invoice->customer_city }} {{ $invoice->customer_country }}</b><br>
                                            <b>{{ $invoice->customer_vat_number}} </b><br>
                                        </div>
                                    </div> <!--row -->                  
                                </div> <!-- class xs 6 -->
                                <div class="col-xs-2 pull-right">
                                    <label class="control-label">CZ Ordernr</label>
                                    <input type="number" class="form-control input-sm" name="id_cust_order" id="id_cust_order" value="{{ $invoice->id_cust_order }}">
                                    <label class="control-label">Bol Ordernr</label>
                                    <input type="number" class="form-control input-sm" name="ordernr_bol" id="ordernr_bol" value="{{ $invoice->ordernr_bol }}"><br>
                                </div> <!-- class xs 6 -->
                            </div>
                        <!-- Faktuur Regels -->
                            <div class="form-group">
                                <div class="invoiceDetail">
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <button @click="addRow" type="button" class="table-add">+</button>
                                            <div id="table" class="table-editable">
                                                <table name='table' class="table">
                                                    <tr> 
                                                        <th>Ref.</th>
                                                        <th>Omschrijving</th>
                                                        <th>Aantal</th>
                                                        <th>Eenh.Prijs</th>
                                                        <th>Btw %</th>
                                                        <th>Tot. Ex. BTW</th>
                                                        <th>Tot. Incl BTW</th>
                                                        <th>Acties</th>
                                                    </tr>
                                                    <tr v-for="row in rows" >
                                                        <td class="col-xs-1"><input type="number" class="form-control input-sm" name="id_product" v-model="row.id_product"></td>
                                                        <td class="col-xs-4"><input type="text" class="form-control input-sm" name="product_descr" v-model="row.product_descr"></td>  
                                                        <td class="col-xs-1"><input type="number" class="form-control input-sm" name="quantity" v-model="row.quantity"></td>
                                                        <td class="col-xs-1"><input type="number" class="form-control input-sm" name="product_unit_price_ex_vat" v-model="row.product_unit_price_ex_vat"></td>
                                                        <td class="col-xs-1"><input type="number" class="form-control input-sm" name="product_vat_procent" v-model.number="row.product_vat_procent"></td>
                                                        <td class="col-xs-2"><input type="number" class="form-control input-sm" name="product_total_price_ex_vat" 
                                                            :value="row.product_unit_price_ex_vat * row.quantity"  readonly tabindex="-1" ></td>
                                                        <td class="col-xs-2"><input type="number" class="form-control input-sm" name="product_total_price_incl_vat" 
                                                            :value="(((row.product_unit_price_ex_vat/100) * row.product_vat_procent) + row.product_unit_price_ex_vat) * row.quantity" readonly tabindex="-1"></td>
                                                        <td class="col-xs-1">
                                                            <button @click="delRow(row)" type="button" class="table-remove glyphicon glyphicon-remove pull-right"></button>
                                                        </td>
                                                    </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <button type="button" id="export-btn" class="btn btn-primary">Export Data</button>
                            <p id="export"></p>


                        <!-- FACTUUR VOET -->
                            <div class="h-line"></div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-xs-4">
                                        <div class="col-xs-7">
                                        <label class="control-label">BTW % Verz / Verp.</label>
                                        <input type="number"  step="0.01" class="form-control input-sm" name="total_shipping_btw_procent" id="total_shipping_btw_procent" value="{{ round($invoice->total_shipping_btw_procent,2) }}" readonly tabindex="-1">
                                        </div>
                                    </div>

                                    <div class="col-xs-4">
                                        <div class="col-xs-7">
                                        <label class="control-label">Totaal IKP ex. BTW</label>
                                        <input type="number" class="form-control input-sm" name="total_ikp_cz_exl_btw" id="total_ikp_cz_exl_btw" value="{{ round($invoice->total_ikp_cz_exl_btw,2) }}" readonly tabindex="-1">
                                        </div>
                                    </div>

                                    <div class="col-xs-4 pull-right">
                                        <div class="col-xs-7 pull-right">
                                        <label class="control-label">Tot. Producten ex. BTW</label>
                                        <input type="number" class="form-control input-sm" name="total_products_exl_btw " id="total_products_exl_btw " value="{{ round($invoice->total_products_exl_btw,2) }}" readonly tabindex="-1">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-xs-4">
                                        <div class="col-xs-7">
                                        <label class="control-label">Verzending Ex. BTW</label>
                                        <input type="number" class="form-control input-sm" name="total_shipping_exl_btw" id="total_shipping_exl_btw" value="{{ round($invoice->total_shipping_exl_btw,2) }}" readonly tabindex="-1">
                                        </div>
                                    </div>

                                    <div class="col-xs-4">
                                        <div class="col-xs-7">
                                        <label class="control-label">Kost. Verzending Ex. BTW</label>
                                        <input type="number" class="form-control input-sm" name="total_shipping_cost_exl_btw" id="total_shipping_cost_exl_btw" value="{{ round($invoice->total_shipping_cost_exl_btw,2) }}" readonly tabindex="-1">
                                        </div>
                                    </div>

                                    <div class="col-xs-4 pull-right">
                                        <div class="col-xs-7 pull-right">
                                        <label class="control-label">Tot. Producten Incl. BTW</label>
                                        <input type="number" class="form-control input-sm" name="total_products_incl_btw" id="total_products_incl_btw" value="{{ round($invoice->total_products_incl_btw,2) }}" readonly tabindex="-1">
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-xs-4">
                                        <div class="col-xs-7">
                                        <label class="control-label">Verzending Incl. BTW</label>
                                        <input type="number" class="form-control input-sm" name="total_shipping_incl_btw " id="total_shipping_incl_btw" value="{{ round($invoice->total_shipping_incl_btw,2) }}" readonly tabindex="-1">
                                        </div>
                                    </div>

                                    <div class="col-xs-4">
                                        <div class="col-xs-7">
                                        <label class="control-label">Kost. Geschenkverp. Ex. BTW</label>
                                        <input type="number" class="form-control input-sm" name="total_wrapping_exl_btw" id="total_wrapping_exl_btw" value="{{ round($invoice->total_wrapping_cost_ex_btw,2) }}" readonly tabindex="-1">
                                        </div>
                                    </div>

                                    <div class="col-xs-4 pull-right">
                                        <div class="col-xs-7 pull-right">
                                        <label class="control-label">Tot. Factuur Ex. BTW</label>
                                        <input type="number" class="form-control input-sm" name="total_invoice_exl_btw" id="total_invoice_exl_btw" value="{{ round($invoice->total_invoice_exl_btw,2) }}" readonly tabindex="-1">
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-xs-4">
                                        <div class="col-xs-7">
                                        <label class="control-label">Geschenkverp. Ex. BTW</label>
                                        <input type="number" class="form-control input-sm" name="total_wrapping_exl_btw" id="total_wrapping_exl_btw" value="{{ round($invoice->total_wrapping_exl_btw,2) }}" readonly tabindex="-1">
                                        </div>
                                    </div>

                                    <div class="col-xs-4">
                                    </div>

                                    <div class="col-xs-4 pull-right">
                                        <div class="col-xs-7 pull-right">
                                        <label class="control-label">Tot. Factuur Incl. BTW</label>
                                        <input type="number" class="form-control input-sm" name="total_invoice_incl_btw" id="total_invoice_incl_btw" value="{{ round($invoice->total_invoice_incl_btw,2) }}" readonly tabindex="-1">
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-xs-4">
                                        <div class="col-xs-7">
                                        <label class="control-label">Geschenkverp. Incl. BTW</label>
                                        <input type="number" class="form-control input-sm" name="total_wrapping_incl_btw" id="total_wrapping_incl_btw" value="{{ round($invoice->total_wrapping_incl_btw,2) }}" readonly tabindex="-1">
                                        </div>
                                    </div>

                                    <div class="col-xs-4">
                                        <div class="col-xs-7">
                                        <label class="control-label">Netto Marge Ex. BTW</label>
                                        <input type="number" class="form-control input-sm" name="netto_margin_ex_btw" id="netto_margin_ex_btw" value="{{ round($invoice->netto_margin_ex_btw,2) }}" readonly tabindex="-1">
                                        </div>
                                    </div>

                                    <div class="col-xs-4 pull-right">
                                        <div class="col-xs-7 pull-right">
                                        <label class="control-label">Totaal Betaald</label>
                                        <input type="number" class="form-control input-sm" name="total_paid" id="total_paid" value="{{ round($invoice->total_paid,2) }}" readonly tabindex="-1">
                                        </div>
                                    </div> <!-- col-xs-4 -->
                                </div> <!-- Row -->
                            </div> <!-- form group -->
                        </div> <!-- form-group-->
                    </div> <!-- panel-body -->
                </div> <!-- panel-default -->
            </div> <!-- class col-md-12 -->
        </div> <!-- row -->
    </div> <!-- container-fluid -->
    @include('partials.footer')
</div> <!-- App -->

<script src="/js/vue.js" type="text/javascript"></script>
<script type="text/javascript" charset="utf-8">

var productApp = new Vue({
    el: '#custInvoiceApp',
    data: 
    { 
        stand_vat_procent: $('#stand_vat_procent').data("field-id"),
        rows: 
        [{
            id_product: 0,
            product_descr: '',
            product_vat_procent:  $('#stand_vat_procent').data("field-id"),
            quantity: 1,
            product_unit_price_ex_vat: 0,
            product_total_price_ex_vat: $('#product_total_price_ex_vat).data("field-id")
            
        }]
    },
    methods: 
    {
        addRow: function()
        {
            this.rows.push
            ({
                id_product: 0,
                product_descr: '',
                quantity: 1,
                product_vat_procent: this.stand_vat_procent,
                product_unit_price_ex_vat: 0,
            })
        },
        delRow: function(row)
        {
            var index = this.rows.indexOf(row)
            this.rows.splice(index,1)
        }
     
    }

 })


</script>
@endsection


