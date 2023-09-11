@extends('layouts.app')

@section('title')
    {{ $page_title }}
@endsection

@push('stylesheet')

@endpush

@section('content')
<div class="dt-content">

    <!-- Grid -->
    <div class="row">
        <div class="col-xl-12 pb-3">
            <ol class="breadcrumb bg-white">
                <li class="breadcrumb-item"><a href="{{ url('/') }}">Dashboard</a></li>
                <li class="active breadcrumb-item">{{ $sub_title }}</li>
              </ol>
        </div>
        <!-- Grid Item -->
        <div class="col-xl-12">

            <!-- Entry Header -->
            <div class="dt-entry__header">

                <!-- Entry Heading -->
                <div class="dt-entry__heading">
                    <h2 class="dt-page__title mb-0 text-primary"><i class="{{ $page_icon }}"></i> {{ $sub_title }}</h2>
                </div>
                <!-- /entry heading -->
                @if (permission('bformat-add'))
                <button class="btn btn-primary btn-sm" onclick="showFormModal('Add New Barcode Format','Save');remove();">
                    <i class="fas fa-plus-square"></i> Add New
                 </button>
                @endif


            </div>
            <!-- /entry header -->

            <!-- Card -->
            <div class="dt-card">

                <!-- Card Body -->
                <div class="dt-card__body">

                    <form id="form-filter">
                        <div class="row">
                            <div class="form-group col-md-4">
                                <label for="name">Barcode Name</label>
                                <input type="text" class="form-control" name="name" id="name" placeholder="Enter Barcode Format name">
                            </div>
                            <div class="form-group col-md-8 pt-24">
                               <button type="button" class="btn btn-danger btn-sm float-right" id="btn-reset"
                               data-toggle="tooltip" data-placement="top" data-original-title="Reset Data">
                                   <i class="fas fa-redo-alt"></i>
                                </button>
                               <button type="button" class="btn btn-primary btn-sm float-right mr-2" id="btn-filter"
                               data-toggle="tooltip" data-placement="top" data-original-title="Filter Data">
                                   <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </div>
                    </form>
                    <table id="dataTable" class="table table-striped table-bordered table-hover">
                        <thead class="bg-primary">
                            <tr>
                                @if (permission('bformat-bulk-delete'))
                                <th>
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" id="select_all" onchange="select_all()">
                                        <label class="custom-control-label" for="select_all"></label>
                                    </div>
                                </th>
                                @endif
                                <th>Sl</th>
                                <th>Barcode</th>
                                <th>District</th>
                                <th>Upazila</th>
                                <th>Union</th>
                                <th>Community Clinic</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>

                </div>
                <!-- /card body -->

            </div>
            <!-- /card -->

        </div>
        <!-- /grid item -->

    </div>
    <!-- /grid -->

</div>
@include('barcodeformat::modal')
@endsection

@push('script')
<script>
var table;
$(document).ready(function(){

    table = $('#dataTable').DataTable({
        "processing": true, //Feature control the processing indicator
        "serverSide": true, //Feature control DataTable server side processing mode
        "order": [], //Initial no order
        "responsive": true, //Make table responsive in mobile device
        "bInfo": true, //TO show the total number of data
        "bFilter": false, //For datatable default search box show/hide
        "lengthMenu": [
            [5, 10, 15, 25, 50, 100, 1000, 10000, -1],
            [5, 10, 15, 25, 50, 100, 1000, 10000, "All"]
        ],
        "pageLength": 25, //number of data show per page
        "language": {
            processing: `<i class="fas fa-spinner fa-spin fa-3x fa-fw text-primary"></i> `,
            emptyTable: '<strong class="text-danger">No Data Found</strong>',
            infoEmpty: '',
            zeroRecords: '<strong class="text-danger">No Data Found</strong>'
        },
        "ajax": {
            "url": "{{route('bformat.datatable.data')}}",
            "type": "POST",
            "data": function (data) {
                data.name = $("#form-filter #name").val();
                data._token    = _token;
            }
        },
        "columnDefs": [{
                @if (permission('bformat-bulk-delete'))
                "targets": [0,4],
                @else
                "targets": [3],
                @endif
                "orderable": false,
                "className": "text-center"
            },
            {
                @if (permission('bformat-bulk-delete'))
                "targets": [1,3],
                @else
                "targets": [0,2],
                @endif
                "className": "text-center"
            }
        ],
        "dom": "<'row'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6' <'float-right'B>>>" +
            "<'row'<'col-sm-12'tr>>" +
            "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'<'float-right'p>>>",

        "buttons": [
            @if (permission('bformat-report'))
            {
                'extend':'colvis','className':'btn btn-secondary btn-sm text-white','text':'Column'
            },
            {
                "extend": 'print',
                'text':'Print',
                'className':'btn btn-secondary btn-sm text-white',
                "title": "Menu List",
                "orientation": "landscape", //portrait
                "pageSize": "A4", //A3,A5,A6,legal,letter
                "exportOptions": {
                    columns: function (index, data, node) {
                        return table.column(index).visible();
                    }
                },
                customize: function (win) {
                    $(win.document.body).addClass('bg-white');
                },
            },
            {
                "extend": 'csv',
                'text':'CSV',
                'className':'btn btn-secondary btn-sm text-white',
                "title": "Menu List",
                "filename": "bformat-list",
                "exportOptions": {
                    columns: function (index, data, node) {
                        return table.column(index).visible();
                    }
                }
            },
            {
                "extend": 'excel',
                'text':'Excel',
                'className':'btn btn-secondary btn-sm text-white',
                "title": "Menu List",
                "filename": "bformat-list",
                "exportOptions": {
                    columns: function (index, data, node) {
                        return table.column(index).visible();
                    }
                }
            },
            {
                "extend": 'pdf',
                'text':'PDF',
                'className':'btn btn-secondary btn-sm text-white',
                "title": "Menu List",
                "filename": "bformat-list",
                "orientation": "landscape", //portrait
                "pageSize": "A4", //A3,A5,A6,legal,letter
                "exportOptions": {
                    columns: [1, 2, 3]
                },
            },
            @endif
            @if (permission('bformat-bulk-delete'))
            {
                'className':'btn btn-danger btn-sm delete_btn d-none text-white',
                'text':'Delete',
                action:function(e,dt,node,config){
                    multi_delete();
                }
            }
            @endif
        ],
    });

    $('#btn-filter').click(function () {
        table.ajax.reload();
    });

    $('#btn-reset').click(function () {
        $('#form-filter')[0].reset();
        table.ajax.reload();
    });

    $(document).on('click', '#save-btn', function () {
        let form = document.getElementById('store_or_update_form');
        let formData = new FormData(form);
        let url = "{{route('bformat.store.or.update')}}";
        let id = $('#update_id').val();
        let method;
        if (id) {
            method = 'update';
        } else {
            method = 'add';
        }
        store_or_update_data(table, method, url, formData);
    });

    $(document).on('click', '.edit_data', function () {
        let id = $(this).data('id');
        $('#store_or_update_form')[0].reset();
        $('#store_or_update_form').find('.is-invalid').removeClass('is-invalid');
        $('#store_or_update_form').find('.error').remove();
        $('#store_or_update_form #up_id').html('');
        $('#store_or_update_form #un_id').html('');
        if (id) {
            $.ajax({
                url: "{{route('bformat.edit')}}",
                type: "POST",
                data: { id: id,_token: _token},
                dataType: "JSON",
                success: function (data) {
                    if(data) {
                        $('#labS').empty();
                        console.log(data);
                        $('#store_or_update_form #update_id').val(data.id);
                        $('#store_or_update_form #dc_id').val(data.barcode_district);

                        // Add the selected options
                        $('#store_or_update_form #up_id').append(`<option selected value="${data.barcode_upazila}">${data.upazila?.name}</option>`);
                        $('#store_or_update_form #un_id').append(`<option selected value="${data.barcode_union}">${data.union?.name}</option>`);

                        $('#store_or_update_form #barcode_community_clinic').val(data.barcode_community_clinic);
                        $('#store_or_update_form #barcode_prefix').val(data.barcode_prefix);
                        $('#store_or_update_form #barcode_number').val(data.barcode_number);
                        $('#store_or_update_form .selectpicker').selectpicker('refresh');

                        $('#store_or_update_modal').modal({
                            keyboard: false,
                            backdrop: 'static',
                        });
                        $('#store_or_update_modal .modal-title').html(
                            '<i class="fas fa-edit"></i> <span>Edit ' + data.barcode_prefix + '</span>');
                        $('#store_or_update_modal #save-btn').text('Update');
                    }else{
                        $('select[name="barcode_upazila"]').empty();
                    }

                },
                error: function (xhr, ajaxOption, thrownError) {
                    console.log(thrownError + '\r\n' + xhr.statusText + '\r\n' + xhr.responseText);
                }
            });
        }
    });

    $(document).on('click', '.delete_data', function () {
        let id    = $(this).data('id');
        let name  = $(this).data('name');
        let row   = table.row($(this).parent('tr'));
        let url   = "{{ route('bformat.delete') }}";
        delete_data(id, url, table, row, name);
    });

    function multi_delete(){
        let ids = [];
        let rows;
        $('.select_data:checked').each(function(){
            ids.push($(this).val());
            rows = table.rows($('.select_data:checked').parents('tr'));
        });
        if(ids.length == 0){
            Swal.fire({
                type:'error',
                title:'Error',
                text:'Please checked at least one row of table!',
                icon: 'warning',
            });
        }else{
            let url = "{{route('bformat.bulk.delete')}}";
            bulk_delete(ids,url,table,rows);
        }
    }

    $(document).on('click', '.change_status', function () {
        let id    = $(this).data('id');
        let status = $(this).data('status');
        let name  = $(this).data('name');
        let row   = table.row($(this).parent('tr'));
        let url   = "{{ route('bformat.change.status') }}";
        Swal.fire({
            title: 'Are you sure to change ' + name + ' status?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes!'
        }).then((result) => {
            if (result.value) {
                $.ajax({
                    url: url,
                    type: "POST",
                    data: { id: id,status:status, _token: _token},
                    dataType: "JSON",
                }).done(function (response) {
                    if (response.status == "success") {
                        Swal.fire("Status Changed", response.message, "success").then(function () {
                            table.ajax.reload(null, false);
                        });
                    }
                    if (response.status == "error") {
                        Swal.fire('Oops...', response.message, "error");
                    }
                }).fail(function () {
                    Swal.fire('Oops...', "Somthing went wrong with ajax!", "error");
                });
            }
        });

    });

//district upazilla union filter

$('#dc_id').change(function () {
    var dcId = $(this).val();
    console.log(dcId);

    if (dcId) {

        $.ajax({
            type: 'GET',
            url: '{{ route("bformat.get-upazillas", "dcId") }}'.replace('dcId', dcId),
            success: function (data) {
                console.log(data)

                // Add the default empty option
                $('#up_id').html('<option value="">Select State</option>');
                $.each(data, function (key, value) {
                    console.log('gg')
                    $("#up_id").append('<option value="'+ value.id+'">' + value.name + value.id +'</option>');
                    // $("#up_id").append('<option value="' + value.id + '" class="selectpicker">' + value.name + '</option>');
                });
                $("#up_id").addClass("selectpicker");
                $("#up_id").selectpicker('refresh');
            }
        });
    } else {
        $('#up_id').empty();
        $('#up_id').append($('<option>', {
            value: '',
            text: 'Select Upazilla'
        }));
    }

});

$('#up_id').change(function () {
    var upId = $(this).val();
    console.log(upId);

    if (upId) {
        $.ajax({
            type: 'GET',
            url: '{{ route("bformat.get-unions", "upId") }}'.replace('upId', upId),
            success: function (data) {
                console.log(data)
                $('#un_id').empty();


                // Add the default empty option
                $('#un_id').html('<option value="">Select State</option>');
                $.each(data, function (key, value) {
                    console.log('gg')
                    $("#un_id").append('<option value="'+ value.id+'">' + value.name +value.id+ '</option>');
                    // $("#up_id").append('<option value="' + value.id + '" class="selectpicker">' + value.name + '</option>');
                });
                //   $("#up_id").addClass("selectpicker");
                $("#un_id").addClass("selectpicker");
                $("#un_id").selectpicker('refresh');


            }
        });
    } else {
        $('#un_id').empty();
        $('#un_id').append($('<option>', {
            value: '',
            text: 'Select Upazilla'
        }));
    }

});

});

function remove(){
    // $('#store_or_update_form #up_id').append('');
    $('#store_or_update_form #un_id').html('');
    $('select[name="barcode_upazila"]').empty();
    $('#up_id').empty();
}

</script>
@endpush
