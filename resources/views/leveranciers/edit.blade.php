
@extends('layouts.app')

@section('content')
@if ($isNew == 0)
    <form action="/leveranciers/save/{{ $supplier->id_supplier }}" name="supplierForm" id="supplierForm" method="post">
@else
    <form action="/leveranciers/save/0" name="supplierForm" id="supplierForm" method="post">
@endif
<input type="hidden" name="_token" value="{{ csrf_token() }}">
<div id="isNew" data-field-id="{{$isNew}}" ></div>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                @if ($isNew == 0)
                    <h4 class="panel-heading">Leverancier {{ $supplier->id_supplier }} wijzigen
                @else
                    <h4 class="panel-heading">Leverancier Toevoegen
                @endif
                    <div class="btn-group btn-titlebar pull-right">
                       <a href="{{ URL::to('leveranciers') }}" type="button" class='btn btn-default btn-sm'>Annuleer</a>
                       <input type="submit" class='btn btn-default btn-sm' value="Opslaan">
                    </div>
                </h4>
                <div class="panel-body panel-body-form">
                    <div class="form-group">
            			<div class="col-xs-12">
            				<div class="row">
            					<div class="col-xs-2 pull-left">
                                    <label class="control-label">Leverancier ID</label><br>
                                    @if ($isNew == 0)
			                            <input type="number" class="form-control input-sm" name="id_supplier" id="id_supplier" value="{{ $supplier->id_supplier }}" placeholder="Lev. id" readonly tabindex="-1">
                                    @else
                                        <input type="number" class="form-control input-sm" placeholder="Nog niet toegekend..." readonly tabindex="-1">
                                    @endif
                            	</div>
                				<div class="col-xs-2 pull-right">
                					<label class="control-label">Creatie Datum</label>
                                    @if ($isNew == 0)
                                        <input type="text" class="form-control input-sm" name="date_add" id="date_add" value="{{ $supplier->date_add }}" placeholder="Creatie Datum" readonly tabindex="-1">
                                    @else
                                        <input type="text" class="form-control input-sm" name="date_add" id="date_add" value="{{ date('Y-m-d H:i:s') }}" placeholder="Creatie Datum" readonly tabindex="-1">
                                    @endif
                                </div>
                				<div class="col-xs-2 pull-right">
                					<label class="control-label">Laatste wijziging</label>
                                    <input type="text" class="form-control input-sm" name="date_upd" id="date_upd" value="{{ $supplier->date_upd }}" placeholder="Laatste wijziging" readonly tabindex="-1">
                				</div>
            			    </div> <!-- row -->
                        </div> <!-- col-->
            		</div> <!-- form group -->
                    <div class="h-line"></div>
                    <div class="form-group col-xs-6">      <!-- BASIS GEGEVENS KLANTEN linke kolom -->
                        <div class="row">
                            <div class="col-xs-5">
                                <div class="form-group">
                                    <label class="control-label">Bedrijfsnaam</label>
                                    <input type="text" class="form-control input-sm input-required" required autofocus name="name" id="name" value="{{ $supplier->name }}" placeholder="Bedrijfsnaam">
                                </div>
                            </div>
                        </div> <!-- row -->
                        <div class="row">
                            <div class="col-xs-6">
                                <label class="control-label">Ondernemingsnummer</label>
                                <input type="text" class="form-control input-sm" name="vat_number" id="vat_number" value="{{ $supplier->vat_number }}" placeholder="BE-0XXXXXXXXX">
                            </div>
                        </div> <!-- row -->
                        <div class="row">
                            <div class="col-xs-6">
                                <label class="control-label">Bankrekening / Iban</label>
                                <input type="text" class="form-control input-sm"  name="bank_account" id="bank_account" value="{{ $supplier->bank_account }}" placeholder="bankrekening">
                            </div>
                        </div> <!-- row -->
                        <div class="row">
                            <div class="col-xs-6">
                                <label class="control-label">Contactpersoon 1</label>
                                <input type="text" class="form-control input-sm"  name="contact_person_1" id="contact_person_1" value="{{ $supplier->contact_person_1 }}" placeholder="Contact persoon 1">
                            </div>
                        </div> <!-- row -->
                        <div class="row">
                            <div class="col-xs-6">
                                <label class="control-label">Contactpersoon 2</label>
                                <input type="text" class="form-control input-sm" name="contact_person_2" id="contact_person_2" value="{{ $supplier->contact_person_2 }}" placeholder="Contact persoon 2">
                            </div>
                        </div> <!-- row -->
                        <div class="row">
            				<div class="col-xs-6">
            					<label class="control-label">Website</label>
                                <input type="text" class="form-control input-sm" name="website" id="website" value="{{ $supplier->website }}" placeholder="website">
            				</div> <!-- col -->
            			</div> <!-- row -->
                    </div> <!-- form group -->
<!-- RECHTER KOLOM ************************************************************** -->
                    <div class="form-group col-xs-6 pull-right">      <!-- Rechter kolom -->
                        <div class="row">
                            <div class="col-xs-6">
                                <label class="control-label">Omschr.</label>
                                <input type="text" class="form-control input-sm" name="email_1_descr" id="email_1_descr" value="{{ $supplier->email_1_descr }}" placeholder="Omschr">
                            </div> <!-- col -->
                            <div class="col-xs-6">
                                <label class="control-label">Email 1</label>
                                <input type="text" class="form-control input-sm" name="email_1" id="email_1" value="{{ $supplier->email_1 }}" placeholder="e-mail 1">
                            </div> <!-- col -->
                        </div> <!-- row -->
                        <div class="row">
                            <div class="col-xs-6">
                                <label class="control-label">Omschr.</label>
                                <input type="text" class="form-control input-sm" name="email_2_descr" id="email_2_descr" value="{{ $supplier->email_2_descr }}" placeholder="Omschr">
                            </div> <!-- col -->
                            <div class="col-xs-6">
                                <label class="control-label">Email 2</label>
                                <input type="text" class="form-control input-sm" name="email_2" id="email_2" value="{{ $supplier->email_2 }}" placeholder="e-mail 2">
                            </div> <!-- col -->
                        </div> <!-- row -->
                        <div class="row">
        					<div class="col-xs-6">
        						<label class="control-label">Tel 1</label>
                                <input type="text" class="form-control input-sm" name="tel_1" id="tel_1" value="{{ $supplier->tel_1 }}" placeholder="Telefoonnummer...">
        					</div>
                            <div class="col-xs-6 pull-right">
                                <label class="control-label">Omschr.</label>
                                <input type="text" class="form-control input-sm" name="tel_1_descr" id="tel_1_descr" value="{{ $supplier->tel_1_descr }}" placeholder="Telefoonnummer...">
                            </div>
        				</div> <!-- row -->
                        <div class="row">
                            <div class="col-xs-4 pull-right">
                            </div>
                        </div> <!-- row -->
                        <div class="row">
                            <div class="col-xs-6">
                                <label class="control-label">Tel 2</label>
                                <input type="text" class="form-control input-sm" name="tel_2" id="tel_1" value="{{ $supplier->tel_2 }}" placeholder="Telefoonnummer...">
                            </div>
                            <div class="col-xs-6 pull-right">
                                <label class="control-label">Omschr.</label>
                                <input type="text" class="form-control input-sm" name="tel_2_descr" id="tel_2_descr" value="{{ $supplier->tel_2_descr }}" placeholder="Telefoonnummer...">
                            </div>
                        </div> <!-- row -->
                        <div class="row">
                            <div class="col-xs-4 pull-right">
                            </div>
                        </div> <!-- row -->
                        <div class="row">
                            <div class="col-xs-6">
                                <label class="control-label">Tel 3</label>
                                <input type="text" class="form-control input-sm" name="tel_3" id="tel_3" value="{{ $supplier->tel_3 }}" placeholder="Telefoonnummer...">
                            </div>
                            <div class="col-xs-6 pull-right">
                                <label class="control-label">Omschr.</label>
                                <input type="text" class="form-control input-sm" name="tel_3_descr" id="tel_3_descr" value="{{ $supplier->tel_3_descr }}" placeholder="Telefoonnummer...">
                            </div>
                        </div> <!-- row -->
                    </div> <!-- form group -->
                    <div class="form-group col-xs-12">      <!-- FORM GROUP TABS - ADDRESSEN -->
                        <ul class="nav nav-tabs">
                            @if ($isNew == 0)
                                <li class="active"><a data-toggle="tab" href="#tab1">Adressen</a></li>
                            @else
                                <li class="active"><a data-toggle="tab" href="#tab1">Adres gegevens</a></li>
                            @endif
                            @if ($isNew == 0)
                                <button type="button" id="add_address" class="btn btn-default btn-sm pull-right">Adres toevoegen</button>
                            @endif
                        </ul>
                        <div class="tab-content">
                            @if ($isNew == 0)
                                <div id="tab1" class="tab-pane fade in active">
                                    <body>
                                        <div id="address_grid" style="width: 100%; height: 130px;"></div>
                                    </body>
                                </div>
                            @else
                                <div id="tab1" class="tab-pane fade in active">
                                    @include('partials.addressForm')
                                    <script>  $('#addressForm').show();  </script>
                                </div>
                            @endif
                        </div> <!-- tab-content -->
                    </div> <!-- form group -->
                </div> <!-- Panel body -->
            </div> <!-- Panel -->
        </div>  <!-- md-12 -->
    </div> <!-- row -->
    @include('partials.footer')
</div> <!-- contaainer -->
</form>

<!-- ADDRESS Form -->
@include('partials.addressForm')

<script type="text/javascript" charset="utf-8">
var name = document.getElementById('name').value;

var isNew = $('#isNew').data("field-id");
if (!isNew){
    var supplier_id = document.getElementById('id_supplier').value;

    // Address Grid + Event handler to change addresss
    mygrid = new dhtmlXGridObject('address_grid');
    mygrid.setHeader("Id,Alias,Bedrijfsnaam,BTW nr.,Adres,Postcode,Gemeente,Landcode,Tel,Mobiel");
    mygrid.enableKeyboardSupport(true);
    mygrid.setColTypes("ro,ro,ro,ro,ro,ro,ro,ro,ro,ro");
    mygrid.setInitWidths("45");
    mygrid.enableLightMouseNavigation(true);
    mygrid.init();
    mygrid.setColAlign("right,left,left,left,left,left,left,left,left,left");
    mygrid.load("/supplier_address_data/" + supplier_id,function(){
        mygrid.sortRows(1,"int","asc"); //0 - index of column
    });
    mygrid.attachEvent("onRowDblClicked", function(row,col){
       var id = mygrid.cells(row,0).getValue();
       $.ajax({
           url: "./address/edit/" + id,
           method: 'GET'
       }).done(function(response) {
           // EDIT EXISTING ADDRESS
           // Populate the form fields with the data returned from server
           $('#addressForm')
               .find('[name="id_address"]').val(response.address.id_address).end()
               .find('[name="id_existing_supplier"]').val(supplier_id).end()
               .find('[name="lastname"]').val(response.address.lastname).end()
               .find('[name="firstname"]').val(response.address.firstname).end()
               .find('[name="alias"]').val(response.address.alias).end()
               .find('[name="vat_number"]').val(response.address.vat_number).end()
               .find('[name="address1"]').val(response.address.address1).end()
               .find('[name="address2"]').val(response.address.address2).end()
               .find('[name="postcode"]').val(response.address.postcode).end()
               .find('[name="city"]').val(response.address.city).end()
               .find('[name="id_country"]').val(response.address.id_country).end()
               .find('[name="phone"]').val(response.address.phone).end()
               .find('[name="phone_mobile"]').val(response.address.phone_mobile).end()
               .find('[name="other"]').val(response.address.other).end();
            // Set country selectpicker correct
            var id_country = response.address.id_country;
            $('select[name="sel_country"]').val(id_country);
            $('.selectpicker').selectpicker('refresh');
           // Show the dialog
           bootbox
               .dialog({
                   size: "large",
                   title: 'Adres wijzigen',
                   message: $('#addressForm'),
                   show: false // We will show it manually later
               })
               .on('shown.bs.modal', function() {
                   $('#addressForm').show();   // Show the addressform
                   var id_supplier =  $('input[name=id_existing_supplier]').val();
                   if(id_supplier){
                       $( ".name-row" ).hide();
                       $('#firstname').val('supplier');
                       $('#lastname').val('supplier');
                   };

                   $('#delButton').show();

               })
               .on('hide.bs.modal', function(e) {
                   $('#addressForm').trigger("reset");
                   mygrid.clearAll();
                   mygrid.load("/supplier_address_data/" + supplier_id);
                   $('#addressForm').hide().appendTo('body');
               })
               .modal('show');
       });
   });
}  // EN id isNew

// Add New address with modal addresform click toevoegen
$('#add_address').click( function(e) {
    $('#addressForm')
        .find('[name="id_existing_supplier"]').val(supplier_id).end()
        .find('[name="company"]').val(name).end()

    bootbox
        .dialog({
            size: "large",
            title: 'Adres Toevoegen',
            message: $('#addressForm'),
            show: false // We will show it manually later
        })
        .on('shown.bs.modal', function() {
            var id_supplier =  $('input[name=id_existing_supplier]').val();
            if(id_supplier){
                $( ".name-row" ).hide();
                $('#firstname').val('supplier');
                $('#lastname').val('supplier');
            };
            $('#addressForm').show();    // Show the addressform
            $('#delButton').hide();
        })
        .on('hide.bs.modal', function(e) {
            $('#addressForm').trigger("reset");
            mygrid.clearAll();
            mygrid.load("/supplier_address_data/" + supplier_id);
            $('#addressForm').hide().appendTo('body');
        })
        .modal('show');
});

</script>
@endsection
