
<form id="addressForm" name="addressForm" method="post" class="form-horizontal" style="display: none;">
    <input  type="hidden" name="id_existing_customer" id="id_existing_customer" readonly>
    <input  type="hidden" name="id_existing_supplier" id="id_existing_supplier" readonly>
    <input  type="hidden" name="company" id="company" readonly>

    <container>
        <div class="row">
            <div class="col-xs-4">
                <label class="control-label">Alias</label>
                <input type="text" class="form-control input-sm input-required" required autofocus name="alias" placeholder="bv. mijn facturatie adres">
            </div>
            <div class="col-xs-4 pull-right">
                <label class="control-label">Adres ID</label>
                <input type="number" class="form-control input-sm" name="id_address" id="id_address" placeholder="Adres ID." readonly>
            </div>

        </div> <!-- row -->
        @if ($isNew == 0)
            <div class="h-line"></div>
            <div class="row name-row">
                <div class="col-xs-4">
                    <label class="control-label">Voornaam</label>
                    <input type="text" class="form-control input-sm input-required" required name="firstname" id="firstname" placeholder="Voornaam">
                </div>
                <div class="col-xs-4">
                    <label class="control-label">Familienaam</label>
                    <input type="text" class="form-control input-sm input-required" required name="lastname" id="lastname" placeholder="Familienaam">
                </div>
            </div> <!-- row -->
        @endif
        <div class="h-line"></div>
        <!--  Linkergedelte-->
        <div class="form-group  col-xs-6">
            <div class = "row">
                <div class="col-xs-5">
                    <label class="control-label">BTW Nummer</label>
                    <input type="text" class="form-control input-sm "  name="vat_number" placeholder="btw nummer">
                </div>
            </div> <!-- row -->
            <div class="row">
                <div class="col-xs-7">
                    <label class="control-label">Straat + Nr</label>
                    <input type="text" class="form-control input-sm input-required" required  name="address1" placeholder="Straat + nr">
                </div>
                <div class="col-xs-5">
                    <label class="control-label">Bus / App.</label>
                    <input type="text" class="form-control input-sm" name="address2" placeholder= "bv. appartment...">
                </div>
            </div> <!-- row -->
            <div class="row">
                <div class="col-xs-4">
                    <label class="control-label">Postcode</label>
                    <input type="text" class="form-control input-sm input-required" required name="postcode" placeholder="Postcode">
                </div>
                <div class="col-xs-6">
                    <label class="control-label">Gemeente</label>
                    <input type="text" class="form-control input-sm input-required" required name="city" placeholder="Gemeente">
                </div>
            </div>
            <div class="row">
                <div class="col-xs-6">
                    <label class="control-label">Land</label><br>
                    <select class="selectpicker form-control"  name="id_country" required data-style="btn-default btn-sm btn-required" id="id_country" title="Land...">
                            <option value="3" selected="selected">Belgie</option>
                            <option value="13">Nederland</option>
                    </select>
                </div>
            </div> <!-- row -->
        </div> <!-- form-group linker gedeelte -->
        <div class="form-group  col-xs-6"> 					<!--   RECHTERGEDEELTE-->
            <div class = "row">
                <div class="col-xs-5 pull-right">
                    <label class="control-label">Telefoon</label>
                    <input type="text" class="form-control input-sm"  name="phone" placeholder="Phone">
                </div>
            </div> <!-- row -->
            <div class = "row">
                <div class="col-xs-5 pull-right">
                    <label class="control-label">Mobiel</label>
                    <input type="text" class="form-control input-sm"  name="phone_mobile" placeholder="Mobiel nummer">
                </div>
            </div> <!-- row -->
        </div> <!-- form-group -->
        <div class="h-line"></div>
        <div class="row">
            <div class="col-xs-12">
                <label class="control-label">Opmerking</label>
                <input type="text" class="form-control input-sm"  name="other" placeholder="Opmerking...">
            </div>
        </div> <!-- row -->
        @if ($isNew == 0)
            <div class="h-line"></div>
            <div class="row">
                <div class="col-xs-12">
                    <input type="submit" id="submitButton" class="btn btn-primary pull-right" data-dismiss="modal" value="Opslaan">
                    <button type="button" id="delButton" class="btn btn-danger btn-bottom pull-right" data-dismiss="modal">Verwijderen</button>
            </div>
        @endif
    </container>
</form>


<script type="text/javascript" charset="utf-8">
// Address Opslaan (Toevoegen of Wijzigen)
$('#submitButton').click( 'submit', function(e) {
    e.preventDefault();
    var address_id = document.getElementById('id_address').value;
    var customer_id = document.getElementById('id_existing_customer').value;
    var supplier_id = document.getElementById('id_existing_supplier').value;

    if(customer_id && !supplier_id){
        var supplier_id = 0;
    }else if(supplier_id && !customer_id){
        var customer_id = 0;
    }
    var formData = {
        "_token": "{{ csrf_token() }}",
        id_customer: customer_id,
        id_supplier: supplier_id,
        company: $('#addressForm input[name=company]').val(),
        lastname: $('#addressForm input[name=lastname]').val(),
        firstname: $('#addressForm input[name=firstname]').val(),
        alias: $('input[name=alias]').val(),
        vat_number: $('input[name=vat_number]').val(),
        address1: $('input[name=address1]').val(),
        address2: $('input[name=address2]').val(),
        postcode: $('input[name=postcode]').val(),
        city: $('input[name=city]').val(),
        id_country: $('select[name=id_country]').val(),
        phone: $('input[name=phone]').val(),
        phone_mobile: $('input[name=phone_mobile]').val(),
        other: $('input[name=other]').val(),
    }
    if(!address_id || isNaN(address_id)){
            $.ajax({
            type: "post",
            url: "./address/create",
            data: formData,
            cache: false,
            error:function(xhr, ajaxOptions, thrownError,data){
               console.log(data);
               alert(ajaxOptions);
               alert(xhr.status);
               alert(thrownError);

            }
        }).done(function(data){
              toastr["success"]("Adres werd Succesvol toegevoegd !");
          });
    }else{                        // Store address record UPDATE Existing
        $.ajax({
            type: "post",
            url: "./address/update/" + address_id,
            data: formData,
            cache: false,
            error: function (xhr, ajaxOptions, thrownError,data) {
                console.log(data);
                alert(ajaxOptions);
                alert(xhr.status);
                alert(thrownError);
            }
        })
        .done(function(){
              toastr["success"]("Adres werd Succesvol opgeslagen !");
         })
    }});

// Verwijder address
$("#delButton").click( function(){
    var address_id = document.getElementById('id_address').value;
    bootbox.confirm({
     title: "ADRES VERWIJDEREN ?",
    message: "Bent U zeker dat U dit adres 'DEFINITEF !' wenst te VERWIJDEREN ?",
    buttons: {
        confirm: {
            label: 'Ja',
            className: 'btn-success'
        },
        cancel: {
            label: 'Neen',
            className: 'btn-danger'
        }
    },
    callback: function (result) {
        if(result){                                 // ADRES EFFECTIEF VERWIJDEREN
            var formData = {
                "_token": "{{ csrf_token() }}",
                id_customer: $('input[name=id_existing_customer]').val(),
                id_supplier: $('input[name=id_existing_supplier]').val(),
            }
            $.ajax({
                type: "post",
                url: "./address/delete/" + address_id,
                data: formData,
                cache: false,
                error:function(xhr, ajaxOptions, thrownError,data){
                   console.log(data);
                   alert(ajaxOptions);
                   alert(xhr.status);
                   alert(thrownError);

               }

           }).done(function(){
                 toastr["warning"]("Adres werd Succesvol verwijderd !");
             });
            // CLear address grid and rebuild
            $('#addressForm').trigger("reset");
            mygrid.clearAll();
            var customer_id = document.getElementById('id_existing_customer').value;
            var supplier_id = document.getElementById('id_existing_supplier').value;
            if(customer_id && !supplier_id){
                var supplier_id = 0;
                mygrid.load("/klant_address_data/" + customer_id);
            }else if(supplier_id && !customer_id){
                var_customer_id = 0;
                mygrid.load("/supplier_address_data/" + supplier_id);
            }
            $('#addressForm').hide().appendTo('body');

        }else{                                      // ADRES NIET VERWIJDEREN
            console.log('RESULT IS FALSE');
        }
    }
});
});

</script>
