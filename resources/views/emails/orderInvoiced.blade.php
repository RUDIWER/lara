<table width="100%" border="0">
    <tr>
        <td width="60%" align="left">
            <img src="{{ $message->embed(public_path() . '/img/cz-logo-trends.png') }}" alt="cool-zawadi" style="max-width: 80%; height: auto;"><br>
        </td>
         <td width="40%" valign="top" rowspan="3" align="right">
            Factuurnr : {{$invoice->id_cust_invoice}}<br>
            @if($invoice->id_cust_order)
                Ordernr CZ : {{$invoice->order_reference}} {{$invoice->id_cust_order}}<br>
            @endif
            @if($invoice->ordernr_bol)
                Ordernr BOL : {{$invoice->ordernr_bol}}<br>
            @endif
            <br>
            Datum Order : {{$invoice->order_date}}<br>
            Datum Factuur : {{$invoice->invoice_date}}<br>
            Betaalwijze : {{$invoice->payment_method}}
        </td>
    </tr>
</table>
<table width="100%" border="0">
    <tr>
        <td width="55%" align="left">
            <b>Cool-Zawadi bvba</b><br>
            <b>Maatschappelijke zetel :</b><br>
            Woestenhof 6,<br>
            8954 Westouter (Belgium)<br>
            Ondernemingsnummer : BE0525.673.088 <br>
            IBAN nr : BE02001694157540  <br>         
            <br>
            <b>Burelen & Magazijn</b><br>
            Diksmuidestraat 2,<br>
            8900 Ieper (Belgium)<br>
            Tel : +32/(0)483/50.30.34<br>
            email : info@cool-zawadi.com<br>
            web : www.cool-zawadi.com<br>     
        </td>
        <td width="45%" align="left">
            <br>
            <h3> {{$invoice->company_name}}</h3>
            <h3> {{$invoice->customer_first_name}} {{$invoice->customer_name}}</h3>
            {{$invoice->customer_street_nr}}<br> 
            {{$invoice->customer_country}} {{$invoice->customer_postal_code}} {{$invoice->customer_city}}<br>
            BTW nr : {{$invoice->customer_vat_number}}<br>
            Email : {{$invoice->customer_email}}
        </td> 
    </tr>
</table>
<br>
<br>
  
<table width="100%" border="0">
  <tr>
    <th>Id.</th>
    <th>Ref.</th>
    <th>Omschr</th> 
    <th>Aantal</th>
    <th>Eenh.Prijs ex. BTW</th>
    <th>Eenh.Prijs incl. BTW</th>
    <th>Totaal ex.</th> 
    <th>Totaal incl.</th> 
    <th>BTW %</th>    
  </tr>
  @foreach ($invoiceRows as $invoiceRow)
    <tr>
        <td align="left"> {{ $invoiceRow->id_product }} </td>
        <td> {{ $invoiceRow->product_reference }} </td>
        <td> {{ $invoiceRow->product_descr }} </td>
        <td> {{ $invoiceRow->quantity }} </td>
        <td align="right"> {{ number_format($invoiceRow->product_unit_price_ex_vat,2) }} </td>
        <td align="right"> {{ number_format($invoiceRow->product_unit_price_incl_vat,2) }} </td>
        <td align="right"> {{ number_format($invoiceRow->product_total_price_ex_vat,2) }} </td>
        <td align="right"> {{ number_format($invoiceRow->product_total_price_incl_vat,2) }} </td>
        <td align="right"> {{ number_format($invoiceRow->vat_procent,2) }} </td>
    </tr>
  @endforeach
</table>
<br>
<br>
<table width="100%" border="0" cellpadding="0" cellspacing="0" style="margin-right:auto;margin-left:0px">
    <tr>
        <td width="60%" valign="top" rowspan="3";>
        </td>
        <td width="30%" valign="top" rowspan="3">
            Netto Producten :<br>
            Verzending ex. BTW :<br> 
            Geschenkverpakking ex. BTW :<br>
            Totaal ex. BTW :<br>
            BTW bedrag :<br>
            <b>TOTAAL INCL BTW : </b>

        </td>
        <td width="10%" valign="top" rowspan="3" align="right">
            {{ number_format($invoice->total_products_exl_btw,2) }}<br>
            {{ number_format($invoice->total_shipping_exl_btw,2) }}<br>
            {{ number_format($invoice->total_wrapping_exl_btw,2) }}<br>
            {{ number_format($invoice->total_invoice_exl_btw,2) }}<br>
            {{ number_format(($invoice->total_invoice_incl_btw - $invoice->total_invoice_exl_btw),2) }}<br>
            <b>{{ number_format($invoice->total_invoice_incl_btw,2) }}</b>
        </td>
    </tr>
</table>


