@extends('layouts.app')

@section('title')
{{ $page_title }}
@endsection

@push('stylesheet')
<style>
.dataTables_info{
    display: none;
}
#dataTable_paginate{
    display: none;
}
.dataTables_filter{
    display: none !important;
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

                     <table id="dataTable" class="table table-striped table-bordered table-hover d-none">
                            <thead class="bg-primary">
                            <tr>
                                <th>PatientID</th>
                                <th>CollectionDate</th>
                                <th>RegistrationID</th>
                                <th>GivenName</th>
                                <th>FamilyName</th>
                                <th>Gender</th>
                                <th>BirthDate</th>
                                <th>Age</th>
                                <th>Mobile</th>
                                <th>Height</th>
                                <th>Weight</th>
                                <th>BMI</th>
                                <th>BPSystolic1</th>
                                <th>BPDiastolic1</th>
                                <th>BPSystolic2</th>
                                <th>BPDiastolic2</th>
                                <th>HeartRate</th>
                                <th>RBG</th>
                                <th>FBG</th>
                                <th>HrsFromLastEat</th>
                                <th>Hemoglobin</th>
                                <th>AnemiaSeverity</th>
                                <th>CoughGreaterThanMonth</th>
                                <th>LGERF</th>
                                <th>NightSweat</th>
                                <th>WeightLoss</th>
                                <th>ChiefComplainWithDuration</th>
                                <th>PhysicalFinding</th>
                                <th>RxDetails</th>
                                <th>PatientIllnessHistory</th>
                                <th>FamilyIllnessHistory</th>
                                <th>Vaccination</th>
                                <th>SocialBehavior</th> 
                                <th>Gravida</th>
                                <th>StillBirth</th>
                                <th>MisCarriageOrAbortion</th>
                                <th>MR</th>
                                <th>LivingBirth</th>
                                <th>LivingMale</th>
                                <th>LivingFemale</th>
                                <th>ChildMorality0To1</th>
                                <th>ChildMoralityBelow5</th>
                                <th>ChildMoralityOver5</th>
                                <th>LMP</th>
                                <th>ContraceptionMethod</th>
                                <th>OtherContraceptionMethod</th>
                                <th>MenstruationProduct</th>
                                <th>OtherMenstruationProduct</th>
                                <th>MenstruationProductUsageTime</th>
                                <th>OtherMenstruationProductUsageTime</th>
                                <th>ProvisionalDiagnosis</th>
                                <th>TreatmentSuggestion</th>
                                <th>DiagnosticSuggestion</th>
                                <th>FollowUpDate</th>

                                <th>Father Cancer</th>
                                <th>Father Malaria</th>
                                <th>Father Skin Disease</th>
                                <th>Father Asthma</th>
                                <th>Father Hypertension</th>
                                <th>Father Diabetes</th>
                                <th>Father Surgery</th>
                                <th>Father Others</th>
                                <th>Father TB</th>
                                <th>Father Typhoid</th>
                                <th>Father Fracture/Injury</th>
                                <th>Father Hepatitis</th>
                                <th>Father IHD</th>
                                <th>Father Depression</th>
                                <th>Father Stroke</th>
                                <th>Father Dengue</th>

                                <th>Mother Cancer</th>
                                <th>Mother Malaria</th>
                                <th>Mother Skin Disease</th>
                                <th>Mother Asthma</th>
                                <th>Mother Hypertension</th>
                                <th>Mother Diabetes</th>
                                <th>Mother Surgery</th>
                                <th>Mother Others</th>
                                <th>Mother TB</th>
                                <th>Mother Typhoid</th>
                                <th>Mother Fracture/Injury</th>
                                <th>Mother Hepatitis</th>
                                <th>Mother IHD</th>
                                <th>Mother Depression</th>
                                <th>Mother Stroke</th>
                                <th>Mother Dengue</th>

                                <th>Siblings Cancer</th>
                                <th>Siblings Malaria</th>
                                <th>Siblings Skin Disease</th>
                                <th>Siblings Asthma</th>
                                <th>Siblings Hypertension</th>
                                <th>Siblings Diabetes</th>
                                <th>Siblings Surgery</th>
                                <th>Siblings Others</th>
                                <th>Siblings TB</th>
                                <th>Siblings Typhoid</th>
                                <th>Siblings Fracture/Injury</th>
                                <th>Siblings Hepatitis</th>
                                <th>Siblings IHD</th>
                                <th>Siblings Depression</th>
                                <th>Siblings Stroke</th>
                                <th>Siblings Dengue</th>

                                <th>Aunt Cancer</th>
                                <th>Aunt Malaria</th>
                                <th>Aunt Skin Disease</th>
                                <th>Aunt Asthma</th>
                                <th>Aunt Hypertension</th>
                                <th>Aunt Diabetes</th>
                                <th>Aunt Surgery</th>
                                <th>Aunt Others</th>
                                <th>Aunt TB</th>
                                <th>Aunt Typhoid</th>
                                <th>Aunt Fracture/Injury</th>
                                <th>Aunt Hepatitis</th>
                                <th>Aunt IHD</th>
                                <th>Aunt Depression</th>
                                <th>Aunt Stroke</th>
                                <th>Aunt Dengue</th>

                                <th>GrandParents Cancer</th>
                                <th>GrandParents Malaria</th>
                                <th>GrandParents Skin Disease</th>
                                <th>GrandParents Asthma</th>
                                <th>GrandParents Hypertension</th>
                                <th>GrandParents Diabetes</th>
                                <th>GrandParents Surgery</th>
                                <th>GrandParents Others</th>
                                <th>GrandParents TB</th>
                                <th>GrandParents Typhoid</th>
                                <th>GrandParents Fracture/Injury</th>
                                <th>GrandParents Hepatitis</th>
                                <th>GrandParents IHD</th>
                                <th>GrandParents Depression</th>
                                <th>GrandParents Stroke</th>
                                <th>GrandParents Dengue</th>

                                <th>Other Cancer</th>
                                <th>Other Malaria</th>
                                <th>Other Skin Disease</th>
                                <th>Other Asthma</th>
                                <th>Other Hypertension</th>
                                <th>Other Diabetes</th>
                                <th>Other Surgery</th>
                                <th>Other Others</th>
                                <th>Other TB</th>
                                <th>Other Typhoid</th>
                                <th>Other Fracture/Injury</th>
                                <th>Other Hepatitis</th>
                                <th>Other IHD</th>
                                <th>Other Depression</th>
                                <th>Other Stroke</th>
                                <th>Other Dengue</th>


                               
                                <th>Cancer</th>
                                <th>Skin Disease</th>
                                <th>Hypertension</th>
                                <th>Diabetes</th>
                                <th>Surgery</th>
                                <th>Others</th>
                                <th>TB</th>
                                <th>Typhoid</th>
                                <th>Fracture/Injury</th>
                                <th>Hepatitis</th>
                                <th>IHD</th>
                                <th>Depression</th>
                                <th>Stroke</th>
                                <th>Dengue</th>
                                <th>Malaria</th>
                                <th>Asthma</th>

                               
                            </tr>

                            </thead>
                            
                         
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
            'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
            // 'This Quarter': [moment().startOf('quarter'), moment().endOf('quarter')],
      
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
    var formattedDate = now.getDate().toString().padStart(2, '0') + '_' +
    (now.getMonth() + 1).toString().padStart(2, '0') + '_' +
    now.getFullYear() + '_' +
    now.getHours().toString().padStart(2, '0') + '_' +
    now.getMinutes().toString().padStart(2, '0') + '_' +
    now.getSeconds().toString().padStart(2, '0');
    var filename = 'FullDataExport_' + formattedDate;

  

    
    $(document).ready(function () {
    table = $('#dataTable').DataTable({
        pagingType: 'full_numbers',
        dom: 'Bfrtip',
        orderCellsTop: true,
            ordering: false,
        columnDefs: [
        { targets: [6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25, 26, 27, 28, 29, 30, 31, 32, 33, 34, 35, 36, 37, 38, 39, 40, 41, 42, 43, 44, 45, 46, 47,48,49,50,51,52,53, 54, 55, 56, 57, 58, 59, 60, 61, 62, 63, 64, 65, 66, 67, 68, 69, 70, 71, 72, 73,74,75,76,77,78,79,80], visible: false }, // Hide the columns
    ],
        buttons: [
            {
                extend: 'excel',
                text: 'Export to Excel',
                filename: filename,
                title: '',
               
            customize: function(xlsx,resultCount) {
            var sheet = xlsx.xl.worksheets['sheet1.xml'];
            var downrows = 5; // Number of rows to add
            var clRow = $('row', sheet);

            // Update Row
            clRow.each(function() {
                var attr = $(this).attr('r');
                var ind = parseInt(attr);
                ind = ind + downrows;
                $(this).attr("r", ind);
            });

            // Update row > c
            $('row c', sheet).each(function() {
                var attr = $(this).attr('r');
                var pre = attr.substring(0, 1);
                var ind = parseInt(attr.substring(1, attr.length));
                ind = ind + downrows;
                $(this).attr("r", pre + ind);
            });
         

            function Addrow(index, data) {
                var msg = '<row r="' + index + '">';
                for (var i = 0; i < data.length; i++) {
                    var key = data[i].k;
                    var value = data[i].v;
                    msg += '<c t="inlineStr" r="' + key + index + '">';
                    msg += '<is>';
                    msg += '<t>' + value + '</t>';
                    msg += '</is>';
                    msg += '</c>';
                }
                msg += '</row>';
                return msg;
            }

            var r1 = Addrow(1, [{
                k: 'A',
                v: 'App Name: Nirog Plus'
            }]);

            var r2 = Addrow(2, [{
                k: 'A',
                v: 'Branch: ' + healthcenter ,
            }]);

            var r3 = Addrow(3, [{
                k: 'A',
                v: 'Collection Date:' + collectionDate,
            }]);

            var r4 = Addrow(4, [{
                k: 'A',
                v: 'Report Type: FullDataExport',
            }]);
             var r5 = Addrow(4, [{
                k: 'A',
                v: ''
            }, {
                k: 'B',
                v: ''
            }]);

            sheet.childNodes[0].childNodes[1].innerHTML = r1 + r2 + r3 + r4 + sheet.childNodes[0].childNodes[1].innerHTML;
            table.clear().draw();
            $('#hc_id').val('').selectpicker('refresh');
    },
            },
        ],
    });

     $('#search').click(function () {
        var daterange = $('#daterange').val();
        var hc_id = $('#hc_id').val();
        const parts = daterange.split(" - ");
        const fdate = parts[0];
        const ldate = parts[1];

        $.ajax({
            type: "GET",
            url: "{{ url('fulldataexportreport') }}",
            data: { hc_id: hc_id, fdate: fdate, ldate: ldate },
            beforeSend: function () {
                $('#warning-searching').removeClass('invisible');
            },
            complete: function () {
                $('#warning-searching').addClass('invisible');
            },
            success: function (response) {
             
                var data = response.data_dump;
                console.log(data);
                healthcenter = response?.healthcenter?.HealthCenterName || 'ALL';
             
              
                // healthcenter = response.healthcenter;
                collectionDate=fdate+"_To_"+ldate;
                // patients=response.resultCount;
                var tableBody = $('#dataTable tbody');

                // Clear the existing table rows
                table.clear().draw();

            if (data.length > 0) {
                table.buttons(0).enable();
                $.each(data, function (index, result) {
                    var newRow = [
                        result.PatientId || '-',
                        result.CollectionDates || '-',
                        result.RegistrationId || '-',
                        result.GivenName || '-',
                        result.FamilyName || '-',
                        result.Gender || '-',
                        result.BirthDate || '-',
                        result.Age || '-',
                        result.MobileNo || '-',
                        result.Height || "-", // Handle missing data
                        result.Weight || "-", // Handle missing data
                        result.BMI || "-", // Handle missing data
                        result.BPSystolic1 || "-", // Handle missing data
                        result.BPDiastolic1 || "-", // Handle missing data
                        result.BPSystolic2 || "-", // Handle missing data
                        result.BPDiastolic2 || "-", // Handle missing data
                        result.HeartRate || "-", // Handle missing data
                        result.RBG || "-", // Handle missing data
                        result.FBG || "-", // Handle missing data
                        result.HrsFromLastEat || "-", // Handle missing data
                        result.Hemoglobin || "-", // Handle missing data
                        result.AnemiaSeverity || "-", // Handle missing data
                        result.CoughGreaterThanMonth || "-", // Handle missing data
                        result.LGERF || "-", // Handle missing data
                        result.NightSweat || "-", // Handle missing data
                        result.WeightLoss || "-", // Handle missing data
                        result.ChiefComplainWithDuration || "-", // Handle missing data
                        result.PhysicalFinding || "-", // Handle missing data
                        result.RxDetails || "-", // Handle missing data
                        result.Illnesses || "-", // Handle missing data
                        result.Diseases || "-", // Handle missing data
                        result.Vaccines || "-", // Handle missing data
                        result.SocialBehaviorCode || "-", // Handle missing data
                        result.Gravida || "-", // Handle missing data
                        result.StillBirth || "-", // Handle missing data
                        result.MisCarriageOrAbortion || "-", // Handle missing data
                        result.MR || "-", // Handle missing data
                        result.LivingBirth || "-", // Handle missing data
                        result.LivingMale || "-", // Handle missing data
                        result.LivingFemale || "-", // Handle missing data
                        result.ChildMorality0To1 || "-", // Handle missing data
                        result.ChildMoralityBelow5 || "-", // Handle missing data
                        result.ChildMoralityOver5 || "-", // Handle missing data
                        result.LMP || "-", // Handle missing data
                        result.ContraceptionMethod || "-", // Handle missing data
                        result.OtherContraceptionMethod || "-", // Handle missing data
                        result.MenstruationProduct || "-", // Handle missing data
                        result.OtherMenstruationProduct || "-", // Handle missing data
                        result.MenstruationProductUsageTime || "-", // Handle missing data
                        result.OtherMenstruationProductUsageTime || "-", // Handle missing data
                        result.ProvisionalDiagnosis || "-", // Handle missing data
                        result.PrescribedDrugs || "-", // Handle missing data
                        result.DiagnosticSuggestion || "-", // Handle missing data
                        result.FollowUpdate || "-", // Handle missing data

                        result.Father_Cancer || "-",
                        result.Father_Malaria || "-",
                        result.Father_Skin_disease || "-",
                        result.Father_Asthma || "-",
                        result.Father_Hypertension || "-",
                        result.Father_Diabetes || "-",
                        result.Father_Surgery || "-",
                        result.Father_Others || "-",
                        result.Father_TB || "-",
                        result.Father_Typhoid || "-",
                        result.Father_Fracture_injury || "-",
                        result.Father_Hepatitis || "-",
                        result.Father_IHD || "-",
                        result.Father_Depression || "-",
                        result.Father_Stroke || "-",
                        result.Father_Dengue || "-",

                        result.Mother_Cancer || "-",
                        result.Mother_Malaria || "-",
                        result.Mother_Skin_disease || "-",
                        result.Mother_Asthma || "-",
                        result.Mother_Hypertension || "-",
                        result.Mother_Diabetes || "-",
                        result.Mother_Surgery || "-",
                        result.Mother_Others || "-",
                        result.Mother_TB || "-",
                        result.Mother_Typhoid || "-",
                        result.Mother_Fracture_injury || "-",
                        result.Mother_Hepatitis || "-",
                        result.Mother_IHD || "-",
                        result.Mother_Depression || "-",
                        result.Mother_Stroke || "-",
                        result.Mother_Dengue || "-",

                        result.Siblings_Cancer || "-",
                        result.Siblings_Malaria || "-",
                        result.Siblings_Skin_disease || "-",
                        result.Siblings_Asthma || "-",
                        result.Siblings_Hypertension || "-",
                        result.Siblings_Diabetes || "-",
                        result.Siblings_Surgery || "-",
                        result.Siblings_Others || "-",
                        result.Siblings_TB || "-",
                        result.Siblings_Typhoid || "-",
                        result.Siblings_Fracture_injury || "-",
                        result.Siblings_Hepatitis || "-",
                        result.Siblings_IHD || "-",
                        result.Siblings_Depression || "-",
                        result.Siblings_Stroke || "-",
                        result.Siblings_Dengue || "-",

                        result.Aunt_Cancer || "-",
                        result.Aunt_Malaria || "-",
                        result.Aunt_Skin_disease || "-",
                        result.Aunt_Asthma || "-",
                        result.Aunt_Hypertension || "-",
                        result.Aunt_Diabetes || "-",
                        result.Aunt_Surgery || "-",
                        result.Aunt_Others || "-",
                        result.Aunt_TB || "-",
                        result.Aunt_Typhoid || "-",
                        result.Aunt_Fracture_injury || "-",
                        result.Aunt_Hepatitis || "-",
                        result.Aunt_IHD || "-",
                        result.Aunt_Depression || "-",
                        result.Aunt_Stroke || "-",
                        result.Aunt_Dengue || "-",

                        result.GrandParents_Cancer || "-",
                        result.GrandParents_Malaria || "-",
                        result.GrandParents_Skin_disease || "-",
                        result.GrandParents_Asthma || "-",
                        result.GrandParents_Hypertension || "-",
                        result.GrandParents_Diabetes || "-",
                        result.GrandParents_Surgery || "-",
                        result.GrandParents_Others || "-",
                        result.GrandParents_TB || "-",
                        result.GrandParents_Typhoid || "-",
                        result.GrandParents_Fracture_injury || "-",
                        result.GrandParents_Hepatitis || "-",
                        result.GrandParents_IHD || "-",
                        result.GrandParents_Depression || "-",
                        result.GrandParents_Stroke || "-",
                        result.GrandParents_Dengue || "-",

                        result.Other_Cancer || "-",
                        result.Other_Malaria || "-",
                        result.Other_Skin_disease || "-",
                        result.Other_Asthma || "-",
                        result.Other_Hypertension || "-",
                        result.Other_Diabetes || "-",
                        result.Other_Surgery || "-",
                        result.Other_Others || "-",
                        result.Other_TB || "-",
                        result.Other_Typhoid || "-",
                        result.Other_Fracture_injury || "-",
                        result.Other_Hepatitis || "-",
                        result.Other_IHD || "-",
                        result.Other_Depression || "-",
                        result.Other_Stroke || "-",
                        result.Other_Dengue || "-",

                        result.OtherIllFamilyMember || "-", // Handle missing data

                      
                        result.Cancer || "-",
                        result.Skin_disease || "-",
                        result.Hypertension || "-",
                        result.Diabetes || "-",
                        result.Surgery || "-",
                        result.Others || "-",
                        result.TB || "-",
                        result.Typhoid || "-",
                        result.Fracture_injury || "-",
                        result.Hepatitis || "-",
                        result.IHD || "-",
                        result.Depression || "-",
                        result.Stroke || "-",
                        result.Dengue || "-",
                        result.Malaria || "-",
                        result.Asthma || "-"

                    ];

                    // Add a new row to the table
                    table.row.add(newRow).draw();
                });
            } else {
                // Handle the case where there are no results (optional)
                tableBody.html('<tr><td colspan="46">No results found</td></tr>');
            }
            },
        });
    });
    table.buttons(0).disable();
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
