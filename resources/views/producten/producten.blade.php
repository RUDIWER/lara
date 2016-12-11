@extends('layouts.app')

@section('content')

<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <h4 class="panel-heading">Producten Overzicht
                    <div class="btn-group btn-titlebar pull-right">
                       <a href="{{ URL::to('producten/create') }}" type="button" class='btn btn-default btn-sm pull-right'>toevoegen</a>
                    </div>
                </h4>
                <div class="panel-body">
                   <body>
                       <div id="product_grid" style="width: 99%; height: 480px;"></div>
                   </body>
                </div>
            </div>
        </div>
    </div>
    @include('partials.footer')
</div>

<script type="text/javascript" charset="utf-8">
// Customer Grid
    mygrid = new dhtmlXGridObject('product_grid');
    mygrid.enableSmartRendering(true); // false to disable
    mygrid.setHeader("Id CZ,Id,referentie,Omschrijving,Inkoopprijs,Verkoopprijs CZ,Verkoopprijs Bol.Be");
    mygrid.attachHeader("#numeric_filter,#numeric_filter,#text_filter,#text_filter,#numeric_filter,#numeric_filter,#numeric_filter");
    mygrid.enableKeyboardSupport(true);
    mygrid.setColTypes("ron,ron,ro,ro,ron,ron,ron");
    mygrid.setInitWidths("35,35,*,400,*,*,*");
    mygrid.setNumberFormat("0,000.00",4);
    mygrid.setNumberFormat("0,000.00",5);
    mygrid.setNumberFormat("0,000.00",6);
    mygrid.setNumberFormat("0,000.00",7);
    mygrid.enableLightMouseNavigation(true);
    mygrid.enableStableSorting(true);
    mygrid.setColumnHidden(0,true);
    mygrid.init();
    mygrid.setColAlign("right,right,left,left,right,right,right");
    mygrid.enableAlterCss("even","uneven");
    mygrid.setColSorting("int,int,str,str,int,int,int");
    //                mygrid.sortRows(0, "str", "asc"); // sorts grid
    mygrid.setSortImgState(true, 1, "asc"); // sets icon to sort arrow
    mygrid.load("./product_data",function(){
        mygrid.sortRows(1,"num","asc"); //0 - index of column
    });
    mygrid.attachEvent("onRowDblClicked", function(row,col){
        var cellValue = mygrid.cells(row,0).getValue();
        var path = "./producten/edit/" + cellValue;
        window.location.href = path;
    });
    mygrid.attachEvent("onEnter", function(row,col){
        var cellValue = mygrid.cells(row,0).getValue();
        var path = "./producten/edit/" + cellValue;
        window.location.href = path;
    });

 //   var dp = new dataProcessor("./producten_data");
 //   dp.init(mygrid);
</script>
@endsection
