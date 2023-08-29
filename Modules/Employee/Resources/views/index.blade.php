@extends('layouts.app')

@section('title')
{{ $page_title }}
@endsection

@push('stylesheet')
<style>
#prescription .container {
    background-color: #f2f2f2 !important;
}
.header p {
    font-size: 14px;
}
.aside {
    width: 400px;
    border-right: 1px solid #ddd;
    min-height: 600px;
    padding-bottom: 20px;
}

.signatureImage {
    display: inline-block;
    width: 100px;
    object-fit: contain;
    margin-bottom: 5px;
}

.signatureBox {
    position: absolute;
    right: 50px;
    bottom: 30px;
}

.footer {
    padding-top: 20px;
    padding-bottom: 20px;
    border-top: 1px solid #ddd;
}

.footer p {
    font-size: 14px;
}

.apiLogo {
    max-width: 40px;
    transform: translateY(-4px);
    margin-left: 5px;
}

.logoText {
    font-size: 14px;
}

.nextinfo {
    margin-top: 150px;
}

@media (max-width: 767px) {

    #prescription,
    .logoText,
    address p,
    .header p {
        font-size: 12px !important;
    }

    .header h4 {
        font-size: 18px !important;
    }

}

.dropify-wrapper {
    line-height: 50px !important;
}
</style>

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

                @if (permission('employee-add'))
                <button class="btn btn-primary btn-sm" onclick="showFormModal('Add Employee','Save'); removeId()">
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
                                <label for="name">Employee Code</label>
                                <input type="text" class="form-control" name="name" id="name"
                                    placeholder="Enter Employee Code">
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
                                @if (permission('employee-bulk-delete'))
                                <th>
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" id="select_all"
                                            onchange="select_all()">
                                        <label class="custom-control-label" for="select_all"></label>
                                    </div>
                                </th>
                                @endif
                                <th>Sl</th>
                                <th>Employee Code</th>
                                <th>RegistrationNumber</th>
                                <th>FirstName</th>
                                <th>LastName</th>
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
@include('employee::view-modal')
@include('employee::add-edit-modal')
@endsection

@push('script')

<script src="https://cdnjs.cloudflare.com/ajax/libs/Dropify/0.2.2/js/dropify.min.js"></script>

<script>
$(document).ready(function() {
    $('#EmployeeImage').dropify({
        messages: {
            'default': '',
            'replace': '',
            'remove': 'Remove',
            'error': 'Ooops, something wrong happended.'
        }
    });
    $('#EmployeeSignature').dropify({
        messages: {
            'default': '',
            'replace': '',
            'remove': 'Remove',
            'error': 'Ooops, something wrong happended.'
        }
    });
});

var table;
$(document).ready(function() {

    table = $('#dataTable').DataTable({
        "processing": true, //Feature control the processing indicator
        "serverSide": true, //Feature control DataTable server side processing mode
        "order": [], //Initial no order
        "responsive": true, //Make table responsive in mobile device
        "bInfo": true, //TO show the total number of data
        "bFilter": false, //For datatable default search box show/hide
        "pageLength": 10, //number of data show per page
        "language": {
            processing: `<i class="fas fa-spinner fa-spin fa-3x fa-fw text-primary"></i> `,
            emptyTable: '<strong class="text-danger">No Data Found</strong>',
            infoEmpty: '',
            zeroRecords: '<strong class="text-danger">No Data Found</strong>'
        },
        "ajax": {
            "url": "{{route('employee.datatable.data')}}",
            "type": "POST",
            "data": function(data) {
                data.name = $("#form-filter #name").val();
                data._token = _token;
            },
            "error": function(xhr, status, error) {
                console.log(xhr);
                console.log(status);
                console.log(error);
            }
        },
        "columnDefs": [{
                @if(permission('employee-bulk-delete'))
                "targets": [0, 4],
                @else "targets": [3],
                @endif "orderable": false,
                "className": "text-center"
            },
            {
                @if(permission('employee-bulk-delete'))
                "targets": [1, 3],
                @else "targets": [0, 2],
                @endif "className": "text-center"
            }
        ],
        "dom": "<'row'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6' <'float-right'B>>>" +
            "<'row'<'col-sm-12'tr>>" +
            "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'<'float-right'p>>>",

        "buttons": [
            @if(permission('employee-report')) {
                'extend': 'colvis',
                'className': 'btn btn-secondary btn-sm text-white',
                'text': 'Column'
            },
            {
                "extend": 'excel',
                'text': 'Excel',
                'className': 'btn btn-secondary btn-sm text-white',
                "title": "Menu List",
                "filename": "employee",
                "exportOptions": {
                    columns: function(index, data, node) {
                        return table.column(index).visible();
                    }
                }
            },
            {
                "extend": 'pdf',
                'text': 'PDF',
                'className': 'btn btn-secondary btn-sm text-white',
                "title": "Menu List",
                "filename": "employee",
                "orientation": "landscape", //portrait
                "pageSize": "A4", //A3,A5,A6,legal,letter
                "exportOptions": {
                    columns: [1, 2, 3]
                },
            },
            @endif
            @if(permission('employee-bulk-delete')) {
                'className': 'btn btn-danger btn-sm delete_btn d-none text-white',
                'text': 'Delete',
                action: function(e, dt, node, config) {
                    multi_delete();
                }
            }
            @endif
        ],
    });

    $('#btn-filter').click(function() {
        table.ajax.reload();
    });

    $('#btn-reset').click(function() {
        $('#form-filter')[0].reset();
        table.ajax.reload();
    });

    $(document).on('click', '#save-btn', function() {
        var EmployeeId = $('#EmployeeId').val();
        var EmployeeCode = $('#EmployeeCode').val();
        var RegistrationNumber = $('#RegistrationNumber').val();
        var FirstName = $('#FirstName').val();
        var LastName = $('#LastName').val();
        var BirthDate = $('#BirthDate').val();
        var JoiningDate = $('#JoiningDate').val();
        var GenderId = $('#GenderId').val();
        var MaritalStatusId = $('#MaritalStatusId').val();
        var Designation = $('#Designation').val();
        var ReligionId = $('#ReligionId').val();
        var Email = $('#Email').val();
        var Phone = $('#Phone').val();
        var NationalIdNumber = $('#NationalIdNumber').val();
        var EmployeeImage = $('#EmployeeImage').prop('files')[0];
        var EmployeeSignature = $('#EmployeeSignature').prop('files')[0];
        //console.log(EmployeeImage);

        var data = new FormData();
        data.append('EmployeeImage', EmployeeImage);
        data.append('EmployeeSignature', EmployeeSignature);
        data.append('EmployeeCode', EmployeeCode);
        data.append('RegistrationNumber', RegistrationNumber);
        data.append('FirstName', FirstName);
        data.append('LastName', LastName);
        data.append('BirthDate', BirthDate);
        data.append('JoiningDate', JoiningDate);
        data.append('GenderId', GenderId);
        data.append('MaritalStatusId', MaritalStatusId);
        data.append('Designation', Designation);
        data.append('ReligionId', ReligionId);
        data.append('Email', Email);
        data.append('Phone', Phone);
        data.append('NationalIdNumber', NationalIdNumber);
        data.append('EmployeeImage', EmployeeImage);
        data.append('EmployeeSignature', EmployeeSignature);
        data.append('EmployeeId', EmployeeId);

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $.ajax({
            url: "{{route('employee.store.or.update')}}",
            type: "POST",
            data: data,
            dataType: "JSON",
            contentType: false,
            processData: false,
            cache: false,
            success: function(data) {

                if(data.errors.EmployeeCode[0]){
                    $('#EmployeeCode').addClass('is-invalid');
                    document.getElementsByClassName('dn').innerHTML="";
                }
                else if(EmployeeId){
                    Swal.fire({
                        type:'success',
                        title:'success',
                        text:'Data has been updated successfully!',
                        icon: 'success',
                    });
                    // Hide the modal by adding a CSS class
                    $('#store_or_update_modal').modal('hide');
                    table.ajax.reload();
                }else{
                    Swal.fire({
                        type:'success',
                        title:'success',
                        text:'Data has been saved successfully!',
                        icon: 'success',
                    });
                    // Hide the modal by adding a CSS class
                    $('#store_or_update_modal').modal('hide');
                    table.ajax.reload();
                }

            },
            error: function(xhr, ajaxOption, thrownError) {
                console.log(thrownError + '\r\n' + xhr.statusText + '\r\n' + xhr
                    .responseText);
            }
        });
    });


    $(document).on('click', '.view_data', function() {
        let id = $(this).data('id');
        // let date = $(this).data('date');
        if (id) {
            $.ajax({
                url: "{{route('employee.show')}}",
                type: "POST",
                data: {
                    id: id,
                    _token: _token
                },
                success: function(data) {

                    $('#view_modal .details').html();
                    $('#view_modal .details').html(data);

                    $('#view_modal').modal({
                        keyboard: false,
                        backdrop: 'static',
                    });
                    $('#view_modal .modal-title').html(
                        '<i class="fas fa-eye"></i> <span>Employee</span>');
                },
                error: function(xhr, ajaxOption, thrownError) {
                    console.log(thrownError + '\r\n' + xhr.statusText + '\r\n' + xhr
                        .responseText);
                }
            });
        }
    });



    $(document).on('click', '.delete_data', function() {
        let EmployeeId = $(this).data('id');
        // let name  = $(this).data('name');
        let name = "Employee";
        console.log(name);
        let row = table.row($(this).parent('tr'));
        let url = "{{ route('employee.delete') }}";
        let response = delete_data(EmployeeId, url, table, row, name);

    });

    function multi_delete() {
        let ids = [];
        let rows;
        $('.select_data:checked').each(function() {
            ids.push($(this).val());
            rows = table.rows($('.select_data:checked').parents('tr'));
        });
        if (ids.length == 0) {
            Swal.fire({
                type: 'error',
                title: 'Error',
                text: 'Please checked at least one row of table!',
                icon: 'warning',
            });
        } else {
            let url = "{{route('employee.bulk.delete')}}";
            bulk_delete(ids, url, table, rows);
        }
    }

    $(document).on('click', '.change_status', function() {
        let EmployeeId = $(this).data('id');
        let Status = $(this).data('status');
        let name = $(this).data('name');
        let row = table.row($(this).parent('tr'));
        let url = "{{ route('employee.change.status') }}";
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
                    data: {
                        EmployeeId: EmployeeId,
                        Status: Status,
                        _token: _token
                    },
                    dataType: "JSON",
                }).done(function(response) {
                    if (response.status == "success") {
                        Swal.fire("Status Changed", response.message, "success").then(
                            function() {
                                table.ajax.reload(null, false);
                            });
                    }
                    if (response.status == "error") {
                        Swal.fire('Oops...', response.message, "error");
                    }
                }).fail(function() {
                    Swal.fire('Oops...', "Somthing went wrong with ajax!", "error");
                });
            }
        });

    });

});

$(document).on('click', '.edit_data', function() {
    let id = $(this).data('id');
    $('#store_or_update_form')[0].reset();
    $('.dropify-clear').trigger('click');

    if (id) {
        $.ajax({
            url: "{{route('employee.edit')}}",
            type: "POST",
            data: {
                id: id,
                _token: _token
            },
            dataType: "JSON",
            success: function(data) {
                console.log(data);
                $('#EmployeeId').val(data.employee[0].EmployeeId);
                $('#EmployeeCode').val(data.employee[0].EmployeeCode);
                $('#RegistrationNumber').val(data.employee[0].RegistrationNumber);
                $('#FirstName').val(data.employee[0].FirstName);
                $('#LastName').val(data.employee[0].LastName);
                $('#BirthDate').val(data.employee[0].BirthDate);
                $('#JoiningDate').val(data.employee[0].JoiningDate);
                $('#GenderId').val(data.employee[0].GenderId);
                $('#MaritalStatusId').val(data.employee[0].MaritalStatusId);
                $('#Designation').val(data.employee[0].Designation);
                $('#ReligionId').val(data.employee[0].ReligionId);
                $('#EducationId').val(data.employee[0].EducationId);
                $('#Email').val(data.employee[0].Email);
                $('#Phone').val(data.employee[0].Phone);
                $('#NationalIdNumber').val(data.employee[0].NationalIdNumber);

                document.getElementById('PrevEmployeeImage').innerHTML = '<img src="' + data.employee[0].EmployeeImage + '" alt="EmployeeImage" width="auto" height="70"/>';
                document.getElementById('PrevEmployeeSignature').innerHTML = '<img src="' + data.employee[0].EmployeeSignature + '" alt="EmployeeSignature" width="auto" height="70"/>';


                $('#store_or_update_form .selectpicker').selectpicker('refresh');

                $('#store_or_update_modal').modal({
                    keyboard: false,
                    backdrop: 'static',
                });
                $('#store_or_update_modal .modal-title').html(
                    '<i class="fas fa-edit"></i> <span>Edit Employee</span>');
                $('#store_or_update_modal #save-btn').text('Update');
            },
            error: function(xhr, ajaxOption, thrownError) {
                console.log(thrownError + '\r\n' + xhr.statusText + '\r\n' + xhr.responseText);
            }
        });
    }
});

$('#EmployeeImage').click(function(){
    console.log('EmployeeImage');
    document.getElementById('PrevEmployeeImage').innerHTML ="";
});

$('#EmployeeSignature').click(function(){
    console.log('EmployeeSignature');
    document.getElementById('PrevEmployeeSignature').innerHTML ="";
});

function removeId(){
    $('#EmployeeId').val('');
}
</script>
@endpush
