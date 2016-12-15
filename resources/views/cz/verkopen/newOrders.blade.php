<!-- PRESTASHOP / COOL-ZAWADI -->

@extends('layouts.app')
@section('content')

<div id="neworderapp">
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <h4 class="panel-heading">Nog te verwerken Orders - CZ </h4>
                <div class="panel-body">
                   <body>
                       @foreach($newOrders as $newOrder)
                        @if($newOrder->current_state < 3 or $newOrder->current_state == 16
                                                         or $newOrder->current_state == 17
                                                         or $newOrder->current_state == 12
                                                         or $newOrder->current_state == 10
                                                         or $newOrder->current_state == 11
                                                         or $newOrder->current_state == 8 )
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
                               <div style="height: 23px;padding:0" class="panel-heading"> Order : {{ $newOrder->id_order }} / ref: {{ $newOrder->reference }} / <b>{{ $newOrder->customer->lastname }}  {{ $newOrder->customer->firstname }} ({{ $newOrder->customer->email }})</b>
                                   <div id="current_state_{{ $newOrder->id_order }}" class="pull-right">{{ $newOrder->orderState->name }}   </div><br>
                               </div>
                               <div class="panel-body">
                                  <body>
                      				<div class="row">
                      					<div class="col-xs-3 pull-left">
                                            <b>Datum : {{ $newOrder->date_add }}</b>
                                            <br>
                                            <b>Leveringsadres :</b><br>
                                            {{ $newOrder->deliveryAddress->phone }}    {{ $newOrder->deliveryAddress->phone_mobile}}<br>
                                            {{ $newOrder->deliveryAddress->company }}  {{ $newOrder->deliveryAddress->vat_number }}<br> 
                                            {{ $newOrder->deliveryAddress->firstname }} {{ $newOrder->deliveryAddress->lastname }}<br>
                                            {{ $newOrder->deliveryAddress->address1}}<br>
                                            {{ $newOrder->deliveryAddress->address2}}<br>
                                            {{ $newOrder->deliveryAddress->postcode}} {{ $newOrder->deliveryAddress->city}}  {{ $newOrder->deliveryAddress->country->name }}
                                        </div>
                                        <div class="col-xs-3">
                                            <br>
                                            <b>Facturatieadres :</b><br>
                                            {{ $newOrder->invoiceAddress->phone }}    {{ $newOrder->invoiceAddress->phone_mobile}}<br>
                                            {{ $newOrder->invoiceAddress->company }} {{ $newOrder->invoiceAddress->vat_number }}<br>
                                            {{ $newOrder->invoiceAddress->address1}}<br>
                                            {{ $newOrder->invoiceAddress->address2}}<br>
                                            {{ $newOrder->invoiceAddress->postcode}} {{ $newOrder->invoiceAddress->city}}   {{ $newOrder->invoiceAddress->country->name }}
                                        </div>
                                        <div class="col-xs-6">
                                            @if($newOrder->total_wrapping_tax_incl <> 0)
                                                <div class="pull-right">
                                                    <img src="/gift.png"  width="40" height="40" alt="GIFT">
                                                </div>
                                            @endif
                                            <b>Producten :</b><br>
                                            <table class="order-table">
                                              <tr>
                                                <th type="text">Aantal </th>
                                                <th align="center">Omschrijving</th>
                                                <th type="number">Eenh. Prijs</th>
                                                <th type="number">totaal</th>
                                              </tr>
                                                @foreach( $newOrder->orderDetails as $orderDetail)
                                                  <tr>
                                                    <td  type="text">{{ $orderDetail->product_quantity}}</td>
                                                    <td  type="text">{{ $orderDetail->product_id }} / {{ $orderDetail->product_name }}</td>
                                                    <td  type="number">{{ round($orderDetail->unit_price_tax_incl,2) }}</td>
                                                    <td  type="number">{{ round($orderDetail->total_price_tax_incl,2) }}</td>
                                                  </tr>
                                                @endforeach
                                                <tr>
                                                    <th></th>
                                                    <th type="text">TOTAAL Goederen :</th>
                                                    <th></th>
                                                    <th type="number">{{ round($newOrder->total_products_wt,2)}}</th>
                                                </tr>
                                                <tr>
                                                    <th></th>
                                                    <th type="text">Verzending :</th>
                                                    <th></th>
                                                    <th type="number">{{ round($newOrder->total_shipping,2) }}</th>
                                                </tr>
                                                @if($newOrder->total_wrapping_tax_incl <> 0)
                                                    <tr>
                                                        <th></th>
                                                        <th type="text">Kadoverpakking :</th>
                                                        <th></th>
                                                        <th type="number">{{ round($newOrder->total_wrapping_tax_incl,2) }}</th>
                                                    </tr>
                                                @endif
                                                <tr>
                                                    <th></th>
                                                    <th type="text">TOTAAL PRIJS :</th>
                                                    <th></th>
                                                    <th type="number">{{ round($newOrder->total_paid_tax_incl,2) }}</th>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                             
                                    <div class="btn-group btn-small btn-panel pull-right">
                                        @if($newOrder->current_state < 3 or $newOrder->current_state == 16
                                                                         or $newOrder->current_state == 17
                                                                         or $newOrder->current_state == 12
                                                                         or $newOrder->current_state == 10
                                                                         or $newOrder->current_state == 11
                                                                         or $newOrder->current_state == 8 )
                                            <button type="button" v-on:click="setState({{ $newOrder->id_order }}, $event)" id="btn-1" class='btn btn-state btn-warning btn-sm'>
                                              <span id="spinner_{{ $newOrder->id_order }}" class=" spinner glyphicon glyphicon-refresh glyphicon-refresh-animate"></span> Voorbereiding starten</button>
                                        @elseif($newOrder->current_state == 3)
                                            <button type="button" v-on:click="setState({{ $newOrder->id_order }}, $event)" id="btn-annuleer" class='btn btn-state btn-danger btn-sm  pull-left'>
                                             <span id="spinner2_{{ $newOrder->id_order }}" class=" spinner glyphicon glyphicon-refresh glyphicon-refresh-animate"></span> Annuleer Order</button>
                                            <button type="button" v-on:click="setState({{ $newOrder->id_order }}, $event)" id="btn-2" class='btn btn-state btn-success btn-sm'>
                                             <span id="spinner_{{ $newOrder->id_order }}" class=" spinner glyphicon glyphicon-refresh glyphicon-refresh-animate"></span> Als 'verzonden' plaatsen</button>
                                        @elseif($newOrder->current_state == 4)
                                            <button type="button" v-on:click="setState({{ $newOrder->id_order }}, $event)" id="btn-3" class='btn btn-state btn-primary btn-sm'>
                                             <span id="spinner_{{ $newOrder->id_order }}" class=" spinner glyphicon glyphicon-refresh glyphicon-refresh-animate"></span> Als 'afgeleverd' plaatsen</button>
                                        @elseif($newOrder->current_state == 5)
                                            <button type="button" v-on:click="setState({{ $newOrder->id_order }}, $event)" id="btn-4" class='btn btn-state btn-default btn-sm'>
                                            <span id="spinner_{{ $newOrder->id_order }}" class=" spinner glyphicon glyphicon-refresh glyphicon-refresh-animate"></span> Factuur maken</button>
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
    </div>
    @include('partials.footer')
</div>
</div> <!-- close app -->
<!--  @include('partials.pictureForm') -->


<script src="/js/vue.min.js" type="text/javascript"></script>
<script type="text/javascript" charset="utf-8">

$( document ).ready(function() {
     $('span.spinner').hide();  
});

// Change current_state from a order
var newOrderApp = new Vue({
    el: '#neworderapp',
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
              //      alert(ajaxOptions);
             //       alert(xhr.status);
            //        alert(thrownError);
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
