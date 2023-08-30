@extends('layouts.app')

@section('title')
    {{ $page_title }}
@endsection

@push('stylesheet')
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
    <style>
        {{--        pagination style--}}

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


        {{--pagination style ends--}}

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
            border: 10px solid rgba(122,122,122,.15);
        }

        .dataItem p{
            font-weight: 400;
            font-size: 15px;
        }
        .dataItem span{
            font-weight: 600;
            font-size: 15px;
        }

        @media (max-width: 767px){
            #prescription, .logoText, address p, .header p{
                font-size: 12px !important;
            }
            .header h4{
                font-size: 18px !important;
            }
            .patientageLeftSide {
                width: 100% !important;
                min-height: auto !important;
                border: 0 !important;
            }
            .itemMerge{
                flex-direction: column;
            }
            .patientageLeftSide h5{
                font-size: 18px !important;
            }
            .userImg {
                width: 140px !important;
                height: 140px !important;
                border-width: 5px;
            }
            .patientageRightSide .dataItem p,
            .patientageRightSide .dataItem span,
            .patientageLeftSide p{
                margin-bottom: 0;
                font-size: 14px;
            }
            .patientageRightSide .dataItem h5{
                font-size: 16px !important;
                margin-bottom: 5px !important;
            }
            .patientageRightSide{
                padding: 10px 10px !important;
            }
            .patientageRightSide .dataItem{
                margin-top: 15px !important;
            }

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

                        <form id="form-filter" method="POST" action="{{route('branch-wise-patients')}}" >
                            @csrf
                            <div class="row">
                                <div class="form-group col-md-3">
                                    <label for="daterange">Date Range</label>
                                    <input type="text" class="form-control daterangepicker-start" name="daterange" >
                                </div>

                                <div class="form-group col-md-3">
                                    <label for="name">Branches</label>

                                    <select class="selectpicker" data-live-search="true" name="patient_id" id="patient_id">
                                        <option value="">Select Branch</option> <!-- Empty option added -->
                                        @foreach($branches as $branch)
                                         <option value="{{$branch->barcode_prefix}}">{{$branch->HealthCenterName}}</option>
                                       @endforeach
                                    </select>
                                </div>

                                <div class="col-md-2 warning-searching invisible" id="warning-searching">
                                    <span class="text-danger" id="warning-message">Searching...Please Wait</span>
                                    <span class="spinner-border text-danger"></span>
                                </div>
                                <div class="form-group col-md-4 pt-24">

                                    <button type="submit"  class="btn btn-primary btn-sm float-right mr-2" id="btn-filter"
                                            data-toggle="tooltip" data-placement="top" data-original-title="Filter Data">
                                        <i class="fas fa-search"></i>
                                    </button>
                                </div>
                            </div>
                        </form>

                        <div class="row mb-3">
                            <div class="col-md-3">
                                <span>Branch Name : <span style="color:darkred">{{$branchName ?? ''}}</span> </span>
                            </div>

                            <div class="col-md-3">
                                <span>Total Patient:<span style="color:darkred">{{$Total ?? ''}}</span> </span>
                            </div>
                            <div class="col-md-3">
                                <span>Male:<span style="color:darkred">{{$male ?? ''}}</span> </span>
                            </div>
                            <div class="col-md-3">
                                <span>Female:<span style="color:darkred">{{$female ?? ''}}</span> </span>
                            </div>

                        </div>

                        <table id="dataTable" class="table table-striped table-bordered table-hover">
                            <thead class="bg-primary">
                            <tr>
                                <th>No</th>
                                <th>Name</th>
                                <th>Age</th>
                                <th>Gender</th>
                                <th>IdNumber</th>
                            </tr>

                            </thead>
                            @if($results ?? '')
                                <tbody>
                                @foreach($results as $result)
                                    <tr>
                                        <td>{{$loop->iteration}}</td>
                                        <td>{{$result->GivenName??""}}{{$result->FamilyName??""}}</td>
                                        <td>{{$result->Age??''}}</td>
                                        <td>{{$result->GenderCode??''}}</td>
                                        <td>{{$result->IdNumber??''}}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            @endif
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
@endsection

@push('script')
{{--    <script type="text/javascript" src="{{asset('js/jquery.js')}}"></script>--}}
    <script type="text/javascript" src="{{asset('js/moment.js')}}"></script>
    <script type="text/javascript" src="{{asset('js/daterangepicker.js')}}"></script>

    <script src="js/dataTables.buttons.min.js"></script>
    <script src="js/buttons.html5.min.js"></script>
    <script>

        var start = moment().subtract(29, 'days');
        var end = moment();

        function cb(start, end) {
            console.log("Selected Date Range: " + start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
        }

        $('input[name="daterange"]').daterangepicker({
            startDate: start,
            endDate: end,
            showDropdowns: true,
            ranges: {
                'Today': [moment(), moment()],
                'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                'This Month': [moment().startOf('month'), moment().endOf('month')],
                'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
                // 'This Quarter': [moment().startOf('quarter'), moment().endOf('quarter')],
                'This Year': [moment().startOf('year'), moment().endOf('year')],
                // Add more custom ranges here...
            }
        }, cb);


        cb(start, end);
        $('.daterangepicker').mouseleave(function() {
            $(this).hide();
        });
        $('.daterangepicker-start').click(function() {
            $('.daterangepicker').show();
        });


        var table;
        $(document).ready(function () {
            $('#dataTable').DataTable({
                pagingType: 'full_numbers',
                dom: 'Bfrtip',
                orderCellsTop: true,
                buttons: [
                    {
                        extend: 'excel',
                        text: 'Export to Excel',

                    },
                ],
            });



        });
        $('#btn-filter').on('click', function (event) {
            $('#warning-searching').removeClass('invisible');
        });

        $(function () {

            $('#starting_age, #ending_age').on('input', function () {
                if ($('#starting_age').val() != '' && $('#ending_age').val() != '') {
                    $('#btn-filter').removeClass('d-none');
                } else {
                    $('#btn-filter').addClass('d-none');
                }
            });
        });

    </script>
@endpush
