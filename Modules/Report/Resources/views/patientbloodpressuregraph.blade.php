@extends('layouts.app')

@section('title')
{{ $page_title }}
@endsection

@push('stylesheet')
<style>
{
        {
        -- pagination style--
    }
}

.pagination {
    display: flex;
    justify-content: center;
    align-items: center;
    list-style: none;
    padding: 0;
}

.pagination li {
    margin: 0 5px;
}

.pagination .active {
    font-weight: bold;
    color: #000;
}

.pagination a {
    color: #007bff;
    text-decoration: none;
    padding: 5px 10px;
    border: 1px solid #007bff;
    border-radius: 5px;
}

.pagination a:hover {
    background-color: #007bff;
    color: #fff;
}


    {
        {
        --pagination style ends--
    }
}

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

.userImg {
    margin-top: 20px;
    width: 200px;
    height: 200px;
    object-fit: cover;
    border-radius: 20px;
    border: 10px solid rgba(122, 122, 122, .15);
}

.dataItem p {
    font-weight: 400;
    font-size: 15px;
}

.dataItem span {
    font-weight: 600;
    font-size: 15px;
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

    .patientageLeftSide {
        width: 100% !important;
        min-height: auto !important;
        border: 0 !important;
    }

    .itemMerge {
        flex-direction: column;
    }

    .patientageLeftSide h5 {
        font-size: 18px !important;
    }

    .userImg {
        width: 140px !important;
        height: 140px !important;
        border-width: 5px;
    }

    .patientageRightSide .dataItem p,
    .patientageRightSide .dataItem span,
    .patientageLeftSide p {
        margin-bottom: 0;
        font-size: 14px;
    }

    .patientageRightSide .dataItem h5 {
        font-size: 16px !important;
        margin-bottom: 5px !important;
    }

    .patientageRightSide {
        padding: 10px 10px !important;
    }

    .patientageRightSide .dataItem {
        margin-top: 15px !important;
    }

}
</style>
@endpush

<script src="js/dataTables.buttons.min.js"></script>
    <script src="js/buttons.html5.min.js"></script>
    <script src="js/highcharts.js"></script>
    <script src="js/series-label.js"></script>
    <script src="js/exporting.js"></script>
    <script src="js/export-data.js"></script>
    <script src="js/accessibility.js"></script>

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
                @if (permission('patientage-add'))
                <button class="btn btn-primary btn-sm" onclick="showFormModal('Add New patientage','Save')">
                    <i class="fas fa-plus-square"></i> Add New
                </button>
                @endif


            </div>
            <!-- /entry header -->

            <!-- Card -->
            <div class="dt-card">

                <!-- Card Body -->
                <div class="dt-card__body">

                    <form id="form-filter" method="GET" action="{{url('patient-blood-pressure-graph')}}">

                        <div class="row">
                            <div class="form-group col-md-2">
                                <label for="name">Date From</label>
                                <input type="date" class="form-control" value="<?php echo $_GET['starting_date']??'' ?>" name="starting_date" id="starting_date"
                                    placeholder="Date From">
                            </div>
                            <div class="form-group col-md-2">
                                <label for="name">Date To </label>
                                <input type="date" class="form-control" value="<?php echo $_GET['ending_date']??'' ?>" name="ending_date" id="ending_date"
                                    placeholder="Date To">
                            </div>

                            <div class="form-group col-md-3">
                                <label for="name">Registration Id</label>

                                <select class="selectpicker" data-live-search="true" name="registration_id" id="registration_id">
                                    <option value="">Select Registration ID</option> <!-- Empty option added -->

                                    @foreach($registrationId as $registration_id)
                                        <option value="{{$registration_id->RegistrationId}}">{{$registration_id->RegistrationId}}</option>

                                    @endforeach

                                </select>
                            </div>
                            <div class="col-md-2 warning-searching invisible" id="warning-searching">
                                <span class="text-danger" id="warning-message">Searching...Please Wait</span>
                                <span class="spinner-border text-danger"></span>
                            </div>

                            <div class="form-group col-md-2 pt-24">

                                <button type="button" id="search" class="btn btn-primary btn-sm float-right mr-2">
                                    <i class="fas fa-search"></i>
                                </button>

                                <button type="button" id="refresh" class="btn btn-primary btn-sm float-right mr-2 refresh">
                                <i class="fas fa-sync-alt"></i></button>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <figure class="highcharts-figure">
                                    <div id="container_bloodp"></div>
                                </figure>
                            </div>
                        </div>
                    </form>



                </div>
                <!-- /card body -->

            </div>
            <!-- /card -->

        </div>
        <!-- /grid item -->

    </div>
    <!-- /grid -->

</div>
@endsection

@push('script')

<script>
$('#refresh').click(function(){
    $('#starting_date').val('');
    $('#ending_date').val('');

    $('.selectpicker').selectpicker('val', '');
    $('#container_bloodp').html('');
});

$('#search').click(function() {
    var starting_date = $('#starting_date').val();
    var ending_date = $('#ending_date').val();
    var registration_id = $('#registration_id').val();

    $.ajax({
        url: "{{ url('ajax-patient-blood-pressure') }}",
        type: "get",
        data: { starting_date: starting_date, ending_date: ending_date, registration_id: registration_id },
        dataType: "html",
        beforeSend: function(){
            $('#warning-searching').removeClass('invisible');
        },
        complete: function(){
            $('#warning-searching').addClass('invisible');
        },
        success: function(data) {
            $('#container_bloodp').html(data);
        },
        error: function(xhr, ajaxOption, thrownError) {
            console.log(thrownError + '\r\n' + xhr.statusText + '\r\n' + xhr.responseText);
        }
    });
});


</script>
@endpush
