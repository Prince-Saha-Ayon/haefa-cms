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

                    <form id="form-filter" method="POST" action="" >
                        @csrf
                        <div class="row">
                            <div class="form-group col-md-3">
                                <label for="daterange">Date Range</label>
                                <input type="text" class="form-control daterangepicker-start" id="daterange" name="daterange" >
                            </div>

                            <div class="form-group col-md-3">
                                <label for="name">Branches</label>

                                <select class="selectpicker" data-live-search="true" name="hc_id" id="hc_id">
                                    <option value="">Select Branch</option> <!-- Empty option added -->
                                    @foreach($branches as $branch)
                                    <option value="{{$branch->barcode_prefix}}">{{$branch->healthCenter->HealthCenterName}}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-2 warning-searching invisible" id="warning-searching">
                                <span class="text-danger" id="warning-message">Searching...Please Wait</span>
                                <span class="spinner-border text-danger"></span>
                            </div>
                            <div class="form-group col-md-4 pt-24">
                                    <button type="button" class="btn btn-danger btn-sm float-right" id="btn-reset"
                                    data-toggle="tooltip" data-placement="top" data-original-title="Reset Data">
                                    <i class="fas fa-redo-alt"></i>
                                    </button>

                                <button type="button"  class="btn btn-primary btn-sm float-right mr-2" id="search"
                                        data-toggle="tooltip" data-placement="top" data-original-title="Filter Data">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </div>
                    </form>

                     <table id="dataTable" class="table table-striped table-bordered table-hover">
                            <thead class="bg-primary">
                            <tr>
                                <th>No</th>
                                <th>Rohingya NCD Screening</th>
                                <th>Rohingya</th>
                            </tr>

                            </thead>
                            <tbody>
                                <tr>
                                    <td></td>
                                    <td>Number of beneficiaries screened for Hyper Tension - Male</td>
                                    <td id="bpmalerohingya"></td>
                                </tr>
                                 <tr>
                                    <td></td>
                                    <td>Number of beneficiaries screened for Hyper Tension - Female</td>
                                    <td id="bpfemalerohingya"></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td>Number of beneficiaries detected with High Blood Pressure - Male</td>
                                    <td id="htnmalerohingya"></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td>Number of beneficiaries detected with High Blood Pressure - Female</td>
                                    <td id="htnfemalerohingya"></td>
                                </tr>

                                 <tr>
                                    <td></td>
                                    <td>Number of beneficiaries screened for Diabetes Mellitus - Male</td>
                                    <td id="glucosemalerohingya"></td>
                                </tr>
                                 <tr>
                                    <td></td>
                                    <td>Number of beneficiaries screened for Diabetes Mellitus - Female</td>
                                    <td id="glucosefemalerohingya"></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td>Number of beneficiaries detected with High Blood Sugar - Male</td>
                                    <td id="dmmalerohingya"></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td>Number of beneficiaries detected with High Blood Sugar - Female</td>
                                    <td id="dmfemalerohingya"></td>
                                </tr>
                            </tbody>
                    
                         
                    </table>


                 <table id="dataTable2" class="table table-striped table-bordered table-hover">
                            <thead class="bg-primary">
                            <tr>
                                <th>No</th>
                                <th>Host NCD Screening</th>
                                <th>Host</th>
                            </tr>

                            </thead>
                            <tbody>
                                <tr>
                                    <td></td>
                                    <td>Number of beneficiaries screened for Hyper Tension - Male</td>
                                    <td id="bpmalehost"></td>

                                </tr>
                                 <tr>
                                    <td></td>
                                    <td>Number of beneficiaries screened for Hyper Tension - Female</td>
                                    <td id="bpfemalehost"></td>

                                </tr>
                                <tr>
                                    <td></td>
                                    <td>Number of beneficiaries detected with High Blood Pressure - Male</td>
                                    <td id="htnmalehost"></td>

                                </tr>
                                <tr>
                                    <td></td>
                                    <td>Number of beneficiaries detected with High Blood Pressure - Female</td>
                                    <td id="htnfemalehost"></td>

                                </tr>

                                 <tr>
                                    <td></td>
                                    <td>Number of beneficiaries screened for Diabetes Mellitus - Male</td>
                                    <td id="glucosemalehost"></td>

                                </tr>
                                 <tr>
                                    <td></td>
                                    <td>Number of beneficiaries screened for Diabetes Mellitus - Female</td>
                                    <td id="glucosefemalehost"></td>

                                </tr>
                                <tr>
                                    <td></td>
                                    <td>Number of beneficiaries detected with High Blood Sugar - Male</td>
                                    <td id="dmmalehost"></td>

                                </tr>
                                <tr>
                                    <td></td>
                                    <td>Number of beneficiaries detected with High Blood Sugar - Female</td>
                                    <td id="dmfemalehost"></td>

                                </tr>
                            </tbody>
                    
                         
                </table>
                <button id="export-button" class="btn btn-primary">Export to Excel</button>

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





<script src="js/dataTables.buttons.min.js"></script>
<script src="js/buttons.html5.min.js"></script>
<script>

// disease rate by date range start




// disease rate by date range end

    var start = moment().subtract(29, 'days');
    var end = moment();


    function cb(start, end) {
        console.log("Selected Date Range: " + start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
       
    }

    $('input[name="daterange"]').daterangepicker({
        startDate: start,
        endDate: end,
        showDropdowns: true,
        linkedCalendars: false,
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
    var healthcenter='';
    var collectionDate='';
    var patients;
    var now = new Date();
    var formattedDate = now.getDate().toString().padStart(2, '0') + '_' + (now.getMonth() + 1).toString().padStart(2, '0') + '_' + now.getFullYear();
    var filename = 'ProvisionalDiagnosis_Datewise_' + formattedDate;

  

    
    $(document).ready(function () {
   
    var table1 = $('#dataTable').DataTable();
    var table2 = $('#dataTable2').DataTable();

    $('#export-button').click(function () {
        var data1 = table1.rows().data().toArray();
        var data2 = table2.rows().data().toArray();

        var combinedData = data1.concat(data2);
        var header = [
            ["No", "Description", "Value"],
            ["No", "Description", "Value"]
        ];

        var wb = XLSX.utils.book_new();
        XLSX.utils.book_append_sheet(wb, XLSX.utils.aoa_to_sheet(header), "Sheet 1");
        XLSX.utils.book_append_sheet(wb, XLSX.utils.aoa_to_sheet(combinedData), "Sheet 2");

        XLSX.writeFile(wb, 'exported_data.xlsx');
    });


     $('#search').click(function () {
        var daterange = $('#daterange').val();
        var hc_id = $('#hc_id').val();
        const parts = daterange.split(" - ");
        const fdate = parts[0];
        const ldate = parts[1];

        $.ajax({
            type: "GET",
            url: "{{ url('custom-report') }}",
            data: { hc_id: hc_id, fdate: fdate, ldate: ldate },
            beforeSend: function () {
                $('#warning-searching').removeClass('invisible');
            },
            complete: function () {
                $('#warning-searching').addClass('invisible');
            },
            success: function (response) {
               let bpmalehost=response.total_bp_male_host ? response.total_bp_male_host : '0';
               let bpmalerohingya=response.total_bp_male_rohingya ? response.total_bp_male_rohingya : '0';
               let bpfemalehost=response.total_bp_female_host ? response.total_bp_female_host : '0';
               let bpfemalerohingya=response.total_bp_female_rohingya ? response.total_bp_female_rohingya : '0';

               let htnmalehost=response.total_htn_male_host ? response.total_htn_male_host : '0';
               let htnmalerohingya=response.total_htn_male_rohingya ? response.total_htn_male_rohingya : '0';
               let htnfemalehost=response.total_htn_female_host ? response.total_htn_female_host : '0';
               let htnfemalerohingya=response.total_htn_female_rohingya ? response.total_htn_female_rohingya : '0';

               let glucosemalehost=response.total_glucose_male_host ? response.total_glucose_male_host : '0';
               let glucosemalerohingya=response.total_glucose_male_rohingya ? response.total_glucose_male_rohingya : '0';
               let glucosefemalehost=response.total_glucose_female_host ? response.total_glucose_female_host : '0';
               let glucosefemalerohingya=response.total_glucose_female_rohingya ? response.total_glucose_female_rohingya : '0';

               let dmmalehost=response.total_dm_male_host ? response.total_dm_male_host : '0';
               let dmmalerohingya=response.total_dm_male_rohingya ? response.total_dm_male_rohingya : '0';
               let dmfemalehost=response.total_dm_female_host ? response.total_dm_female_host : '0';
               let dmfemalerohingya=response.total_dm_female_rohingya ? response.total_dm_female_rohingya : '0';

                 


             
               $('#bpmalehost').text(bpmalehost);
               $('#bpmalerohingya').text(bpmalerohingya);
               $('#bpfemalehost').text(bpfemalehost);
               $('#bpfemalerohingya').text(bpfemalerohingya);

               $('#htnmalehost').text(htnmalehost);
               $('#htnmalerohingya').text(htnmalerohingya);
               $('#htnfemalehost').text(htnfemalehost);
               $('#htnfemalerohingya').text(htnfemalerohingya);

               $('#glucosemalehost').text(glucosemalehost);
               $('#glucosemalerohingya').text(glucosemalerohingya);
               $('#glucosefemalehost').text(glucosefemalehost);
               $('#glucosefemalerohingya').text(glucosefemalerohingya);


               $('#dmmalehost').text(dmmalehost);
               $('#dmmalerohingya').text(dmmalerohingya);
               $('#dmfemalehost').text(dmfemalehost);
               $('#dmfemalerohingya').text(dmfemalerohingya);
              
                var firstDate = new Date(response.first_date);
                var lastDate = new Date(response.last_date);

                    var formatDate = function (date) {
                    var day = date.getDate().toString().padStart(2, '0');
                    var monthNames = [
                        "January", "February", "March", "April", "May", "June",
                        "July", "August", "September", "October", "November", "December"
                    ];
                    var monthName = monthNames[date.getMonth()];
                    var year = date.getFullYear();
                    return day + "-" + monthName + "-" + year;
                };

                collectionDate = formatDate(firstDate) + "_To_" + formatDate(lastDate);
               
                // patients=response.resultCount;
            
                // var tableBody = $('#dataTable tbody')

                // // Clear the existing table rows
                // table.clear().draw();

                // if (results.length > 0) {
                //     $.each(results, function (index, result) {
                //         var newRow = [
                //             (index + 1),
                //             result.ProvisionalDiagnosis,
                //             (result.CreateDate || ""),
                //             (result.Total || ""),
                      
                //         ];

                //         // Add a new row to the table
                //         table.row.add(newRow).draw();
                //     });
                // } else {
                //     // Handle the case where there are no results (optional)
                //     tableBody.html('<tr><td colspan="9">No results found</td></tr>');
                // }
            },
        });
    });

    });
      $('#btn-reset').click(function () {
       
        table.clear().draw();
        $('#hc_id').val('').selectpicker('refresh');
        
    });

    $('#btn-filter').on('click', function (event) {
        $('#warning-searching').removeClass('invisible');
    });

    

</script>
@endpush
