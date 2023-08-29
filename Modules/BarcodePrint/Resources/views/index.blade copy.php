@extends('layouts.app')

@section('title', $page_title)

@push('styles')
<link rel="stylesheet" href="css/jquery-ui.css" />
@endpush

@section('content')
<div class="d-flex flex-column-fluid">
    <div class="container-fluid">
        <!--begin::Notice-->
        <div class="card card-custom gutter-b">
            <div class="card-header flex-wrap py-5">
                <div class="card-title">
                    <h3 class="card-label"><i class="{{ $page_icon }} text-primary"></i> {{ $sub_title }}</h3>
                </div>
                <div class="card-toolbar">
                    <!--begin::Button-->
                    <a href="{{ route('barcodeprint') }}" class="btn btn-warning btn-sm font-weight-bolder"> 
                        <i class="fas fa-arrow-left"></i> Back</a>
                    <!--end::Button-->
                </div>
            </div>
        </div>
        <!--end::Notice-->
        <!--begin::Card-->
        <div class="card card-custom" style="padding-bottom: 100px !important;">
            <div class="card-body" style="padding-bottom: 100px !important;">
                <!--begin: Datatable-->
                <div id="kt_datatable_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer pb-5">
                    
                    <form id="generate-barcode-form" method="POST">
                        @csrf
                        <div class="row">
                            <input type="hidden" class="form-control" name="barcodeprint_name" id="barcodeprint_name">
                            <input type="hidden" class="form-control" name="barcodeprint_code" id="barcodeprint_code">
                            <input type="hidden" class="form-control" name="barcodeprint_price" id="barcodeprint_price">
                            <input type="hidden" class="form-control" name="barcode_symbology" id="barcode_symbology">
                            <input type="hidden" class="form-control" name="tax_rate" id="tax_rate">
                            <input type="hidden" class="form-control" name="tax_method" id="tax_method">
                            
                            <x-form.selectbox labelName="Barcode Start" name="mdata_barcode_prefix_number_start" onchange="getBarcodeList(this.value)" col="col-md-4" class="selectpicker">
                                @if (!$barcodeGenerates->isEmpty())
                                    @foreach ($barcodeGenerates as $barcodeGenerate)
                                        <option value="{{ $barcodeGenerate->mdata_barcode_prefix_number }}">{{ $barcodeGenerate->mdata_barcode_prefix_number }}</option>
                                    @endforeach
                                @endif
                            </x-form.selectbox>
                            <x-form.selectbox labelName="Barcode End" name="mdata_barcode_prefix_number_end" col="col-md-4 " class="selectpicker mdata_barcode_prefix_number_end" />
                            <x-form.textbox labelName="No. of Barcode" name="barcode_qty" required="required" col="col-md-2" class="text-center" value="1" placeholder="Enter barcode print quantity"/>
                            <x-form.textbox labelName="Qunatity Each Row" name="row_qty" required="required" col="col-md-2" class="text-center" value="1" placeholder="Enter barcode print quantity"/>
                            <div class="form-group col-md-2">
                                <label for="">Print With</label>
                                <div class="div">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" id="pname">
                                        <label class="custom-control-label" for="pname">barcodeprint Name</label>
                                    </div>
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" id="price">
                                        <label class="custom-control-label" for="price">Price</label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group col-md-3" style="padding-top:28px;">
                                <button type="button" class="btn btn-primary btn-sm" id="generate-barcode"><i class="fas fa-barcode"></i>Generate Barcode</button>
                            </div>
                        </div>
                    </form>
                    
                    <div class="row" id="barcode-section">

                    </div>
                </div>
                <!--end: Datatable-->
            </div>
        </div>
        <!--end::Card-->
    </div>
</div>
@endsection

@push('scripts')
<script src="js/jquery-ui.js"></script>
<script src="js/jquery.printarea.js"></script>
<script>
$(document).ready(function () {
    $('#barcodeprint_code_name').autocomplete({
        source: function( request, response ) {
          // Fetch data
          $.ajax({
            url:"{{url('barcode/barcodeprint-autocomplete-search')}}",
            type: 'post',
            dataType: "json",
            data: {
               token: token,
               search: request.term
            },
            success: function( data ) {
               response( data );
            }
          });
        },
        minLength: 3,
        response: function(event, ui) {
            if (ui.content.length == 1) {
                // var data = ui.content[0].code;
                $('#barcodeprint_code_name').val(ui.content[0].value)
                $('#barcodeprint_name').val(ui.content[0].name);
                $('#barcodeprint_code').val(ui.content[0].code);
                $('#barcodeprint_price').val(ui.content[0].price);
                $('#barcode_symbology').val(ui.content[0].barcode_symbology);
                $('#tax_rate').val(ui.content[0].tax_rate);
                $('#tax_method').val(ui.content[0].tax_method);
                $(this).autocomplete( "close" );
            };
        },
        select: function (event, ui) {
            $('#barcodeprint_code_name').val(ui.item.value)
            $('#barcodeprint_name').val(ui.item.name);
            $('#barcodeprint_code').val(ui.item.code);
            $('#barcodeprint_price').val(ui.item.price);
            $('#barcode_symbology').val(ui.item.barcode_symbology);
            $('#tax_rate').val(ui.item.tax_rate);
            $('#tax_method').val(ui.item.tax_method);

        },
    }).data('ui-autocomplete')._renderItem = function (ul, item) {
        return $("<li class='ui-autocomplete-row'></li>")
            .data("item.autocomplete", item)
            .append(item.label)
            .appendTo(ul);
    };
   

    $(document).on('click','#print-barcode',function(){
        var mode = 'iframe'; // popup
        var close = mode == "popup";
        var options = {
            mode: mode,
            popClose: close
        };
        $("#printableArea").printArea(options);
    });

    function getBarcodeList(mdata_barcode_prefix_number_start,mdata_barcode_prefix_number_end=''){
    $.ajax({
        console.log('ok');
        url:"{{ url('latest-range') }}/"+mdata_barcode_prefix_number_start,
        type:"GET",
        dataType:"JSON",
        success:function(data){
            $('#generate-barcode-form #mdata_barcode_prefix_number_end').empty();
            $.each(data, function(key, value) {
                if (!$.trim(data)){
                    $('#generate-barcode-form .mdata_barcode_prefix_number_end').addClass('d-none');
                }
                else{
                    $('#generate-barcode-form .mdata_barcode_prefix_number_end').removeClass('d-none');
                    $('#generate-barcode-form #mdata_barcode_prefix_number_end').append('<option value="'+ key +'">'+ value +'</option>');
                    $('#generate-barcode-form #mdata_barcode_prefix_number_end.selectpicker').selectpicker('refresh');
                }
            });
            $('.selectpicker').selectpicker('refresh');


        },
    });
}

    
});






</script>
@endpush