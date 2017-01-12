<!-- BOL BE -->

@extends('layouts.app')
@section('content')

<div id="newBolBeOrderapp">
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <h4 class="panel-heading">Nog te verwerken Orders - BOL BELGIË</h4>
                <div class="panel-body">
                   <body>
                        @foreach($newBolBeOrders as $newOrder)
                            @if($newOrder->current_state < 3) 
                                <div class="panel panel-danger">
                            @elseif($newOrder->current_state == 3)   
                                <div class="panel panel-warning">
                            @elseif($newOrder->current_state == 4)   
                                <div class="panel panel-success">
                            @elseif($newOrder->current_state == 5)
                                <div class="panel panel-info">
                            @else
                                <div class="panel panel-default">
                            @endif
                            <div style="height: 23px;padding:0" class="panel-heading"> Order : BOLBE/{{ $newOrder->id_bol_be_orders }} - Bol-ref: {{ $newOrder->bol_be_order_id }} / <b>{{ $newOrder->delivery_lastname }}  {{ $newOrder->delivery_firstname }} ({{ $newOrder->email_for_delivery }})</b>
                                   <div id="current_state_{{ $newOrder->id_bol_be_orders }}" class="pull-right">{{ $newOrder->orderState->name }}   </div><br>
                            </div>
                            <div class="panel-body">
                                <body>
                                    <div class="row">
                                        <div class="col-xs-3 pull-left">
                                            <b>Datum : {{ $newOrder->date_order }} {{$newOrder->time_order}}</b>
                                            <br>
                                            <b>Leveringsadres :</b><br>
                                            {{ $newOrder->delivery_phone_number }}<br>
                                            {{ $newOrder->delivery_company }}<br>
                                            {{ $newOrder->delivery_first_name}} {{$newOrder->delivery_last_name}}<br>
                                            {{ $newOrder->delivery_address_1}}<br>
                                            {{ $newOrder->delivery_address_2}}<br>   
                                            {{ $newOrder->delivery_extra_address_info}}<br>
                                            {{ $newOrder->delivery_postcode}} {{ $newOrder->delivery_city}}  {{ $newOrder->deliveryCountry->name }}
                                        </div>
                                        <div class="col-xs-3">
                                            <br>
                                            <b>Facturatieadres :</b><br>
                                            {{ $newOrder->invoice_phone_number }}<br>
                                            {{ $newOrder->invoice_company }} {{ $newOrder->invoice_vat_number }}<br>
                                            {{ $newOrder->invoice_first_name}} {{$newOrder->invoice_last_name}}<br>
                                            {{ $newOrder->invoice_address_1}}<br>
                                            {{ $newOrder->invoice_address_2}}<br>
                                            {{ $newOrder->invoice_postcode}} {{ $newOrder->invoice_city}}   {{ $newOrder->invoiceCountry->name }}
                                        </div>    
                                        <div class="col-xs-6">
                                            <b>Producten :</b><br>
                                            <table class="order-table">
                                                <tr>
                                                    <th type="text">Aantal </th>
                                                    <th align="center">Omschrijving</th>
                                                    <th type="number">Eenh. Prijs</th>
                                                    <th type="number">totaal</th>
                                                    <th type="number"> Bol Kost Eff.</th>
                                                    <th type="number"> Bol Kost Calc.</th>
                                                </tr>
                                                @foreach( $newOrder->bolBeOrderDetails as $orderDetail)
                                                    <tr>
                                                        <td type="text">{{ $orderDetail->quantity}}</td>
                                                        <td type="text">{{ $orderDetail->id_product }} / {{ $orderDetail->product_name }}</td>
                                                        <td type="number">{{ round($orderDetail->unit_price_incl_vat,2) }}</td>
                                                        <td type="number">{{ round($orderDetail->row_price_incl_vat,2) }}</td>
                                                        <td type="number">{{ round($orderDetail->transaction_fee,2) }}</td>
                                                        <td type="number">{{ round(($orderDetail->quantity * $orderDetail->calc_bol_be_cost),2) }}</td>
                                                    </tr> 
                                                @endforeach
                                            </table>
                                        </div>
                                    </div>
                                   
                                    <div class="btn-group btn-small btn-panel pull-right">
                                        @if($newOrder->current_state < 3)
                                            <button type="button" v-on:click="setState({{ $newOrder->id_bol_be_orders }}, $event)" id="btn-1" class='btn btn-state btn-warning btn-sm'>
                                                <span id="spinner_{{ $newOrder->id_bol_be_orders }}" class=" spinner glyphicon glyphicon-refresh glyphicon-refresh-animate"></span> Voorbereiding Starten</button>
                                        @elseif($newOrder->current_state == 3)
                                            <button type="button" v-on:click="setState({{ $newOrder->id_bol_be_orders }}, $event)" id="btn-annuleer" class='btn btn-state btn-danger btn-sm  pull-left'>
                                                <span id="spinner2_{{ $newOrder->id_bol_be_orders }}" class=" spinner glyphicon glyphicon-refresh glyphicon-refresh-animate"></span> Annuleer Order</button>
                                            <button type="button" v-on:click="setState({{ $newOrder->id_bol_be_orders }}, $event)" id="btn-2" class='btn btn-state btn-success btn-sm'>
                                                <span id="spinner_{{ $newOrder->id_bol_be_orders }}" class=" spinner glyphicon glyphicon-refresh glyphicon-refresh-animate"></span> Als 'verzonden' plaatsen</button>
                                        @elseif($newOrder->current_state == 4)
                                            <button type="button" v-on:click="setState({{ $newOrder->id_bol_be_orders}}, $event)" id="btn-3" class='btn btn-state btn-primary btn-sm'>
                                                <span id="spinner_{{ $newOrder->id_bol_be_orders }}" class=" spinner glyphicon glyphicon-refresh glyphicon-refresh-animate"></span> Als 'afgeleverd' plaatsen</button>
                                        @elseif($newOrder->current_state == 5)
                                            <button type="button" v-on:click="setState({{ $newOrder->id_bol_be_orders }}, $event)" id="btn-4" class='btn btn-state btn-default btn-sm'>
                                            <span id="spinner_{{ $newOrder->id_bol_be_orders }}" class=" spinner glyphicon glyphicon-refresh glyphicon-refresh-animate"></span> Factuur maken</button>
                                        @endif
                                    </div>
                                </body>
                            </div>
                        </div>
                    @endforeach
                   </body>
                </div>
            </div>
        </div>
        @include('partials.footer')
    </div>
</div>


<script src="/js/vue.min.js" type="text/javascript"></script>
<script type="text/javascript" charset="utf-8">

$( document ).ready(function() {
     $('span.spinner').hide();    
});

// Change current_state from a order
var newBolBeOrderApp = new Vue({
    el: '#newBolBeOrderapp',
    data: {
    },
    methods: 
    {
        setState: function (id_order, event) 
        {
            var currentState = event.target.id;
            if (currentState == "btn-1"){
                var newState = 3;
            }else if (currentState == "btn-2"){
                var newState = 4;
            }else if (currentState == "btn-3") {
                var newState = 5;
            }else if (currentState == "btn-4") {
                var newState = 19;
            }else if (currentState == "btn-annuleer") {
                var newState = 6;
            }
            var formData = {
                "_token": "{{ csrf_token() }}",
                "newState": newState,
            }
            // Jquery spinner code when loading ajax
            var spinId = "#spinner_" + id_order; 
            $.ajaxSetup({
                beforeSend: function() 
                {
                    if (currentState == "btn-annuleer"){
                         $("#spinner2_" + id_order).show();
                    }
                    else
                    {
                        $(spinId).show();
                    }
                 },
            });

            $.ajax({
                type: "post",
                url: "./nieuwe-orders/wijzig-status/" + id_order + "/" + newState,
                data: formData,
                cache: false,
                error: function (xhr, ajaxOptions, thrownError, data) 
                {
                    console.log('data : ' + data);
                 //alert(ajaxOptions);
                 // alert(xhr.status);
                 //   alert(thrownError);
                }
            })
            .done(function(data){     
               location.reload();
               $(location ).ready(function() {
                   toastr["success"]("Status werd succesvol aangepast !");
                   $(spinId).hide();
               });
            }); // End Done
        } // end set state
    }  // End methods
})
</script>
@endsection
