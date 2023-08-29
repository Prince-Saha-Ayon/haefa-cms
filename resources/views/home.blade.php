@extends('layouts.app')

@push('stylesheet')
<link rel="stylesheet" href="css/chart.min.css">
@endpush

    <script src="js/highcharts.js"></script>
    <script src="js/series-label.js"></script>
    <script src="js/exporting.js"></script>
    <script src="js/export-data.js"></script>
    <script src="js/accessibility.js"></script>

@section('content')
<div class="dt-content">
    
    <div class="row pt-5">
      <div class="col-xl-2 col-sm-4">
        <div class="dt-card dt-chart dt-card__full-height bg-warning align-items-center pt-5">
        <img src="images/patient.png" alt="Customer" width="30px;">
          <h4 class="text-white mt-3 mb-0">{{ $patient_count }}</h4>
          <h2 class="text-white mt-1">All Patient</h2>
        </div>
      </div>
      <div class="col-xl-3 col-sm-5">
        <div class="dt-card dt-chart dt-card__full-height bg-danger align-items-center pt-5">
        <img src="images/patient.png" alt="Customer" width="30px;">
          <h4 class="text-white mt-3 mb-0" id="expense">{{ $patient_today_count }}</h4>
          <h2 class="text-white mt-1">Today Registration Patient</h2>
        </div>
      </div>
      <div class="col-xl-2 col-sm-4">
        <div class="dt-card dt-chart dt-card__full-height bg-info align-items-center pt-5">
          <img src="images/customer.svg" alt="Customer" width="30px;">
          <h4 class="text-white mt-3 mb-0">{{$prescription_total_count}}</h4>
          <h2 class="text-white mt-1">Total Prescription</h2>
        </div>
      </div>
      <div class="col-xl-2 col-sm-4">
        <div class="dt-card dt-chart dt-card__full-height bg-info align-items-center pt-5">
          <img src="images/customer.svg" alt="Customer" width="30px;">
          <h4 class="text-white mt-3 mb-0">{{$prescription_today_count}}</h4>
          <h2 class="text-white mt-1">Today Prescription</h2>
        </div>
      </div>
      <div class="col-xl-2 col-sm-4">
        <div class="dt-card dt-chart dt-card__full-height bg-success align-items-center pt-5">
        <img src="images/customer.svg" alt="Customer" width="30px;">
          <h4 class="text-white mt-3 mb-0">{{ $doctor_count }}</h4>
          <h2 class="text-white mt-1">All Doctor</h2>
        </div>
      </div>
    </div>
    <!-- Start :: Bar Chart-->
        <div class="row py-5">
            <div class="col-md-12">
            <div class="card bar-chart">
                <div class="card-header d-flex align-items-center">
                <h4>Patient wise Blood Pressure Graph </h4>
                </div>
            </div>

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




            <!-- heart rate graph -->

            <div class="card bar-chart">
                <div class="card-header d-flex align-items-center">
                <h4>Hear Rate Graph </h4>
                </div>
            </div>

            <!-- Card -->
            <div class="dt-card">

                <!-- Card Body -->
                <div class="dt-card__body">

                    <form id="form-filter" method="GET" action="{{url('patient-blood-pressure-graph')}}">

                        <div class="row">
                            <div class="form-group col-md-2">
                                <label for="name">Date From</label>
                                <input type="date" class="form-control" value="<?php echo $_GET['starting_date']??'' ?>" name="starting_date_heart" id="starting_date_heart"
                                    placeholder="Date From">
                            </div>
                            <div class="form-group col-md-2">
                                <label for="name">Date To </label>
                                <input type="date" class="form-control" value="<?php echo $_GET['ending_date']??'' ?>" name="ending_date_heart" id="ending_date_heart"
                                    placeholder="Date To">
                            </div>

                            <div class="form-group col-md-3">
                                <label for="name">Registration Id</label>

                                <select class="selectpicker" data-live-search="true" name="registration_id_heart" id="registration_id_heart">
                                    <option value="">Select Registration ID</option> <!-- Empty option added -->

                                    @foreach($registrationId as $registration_id)
                                        <option value="{{$registration_id->RegistrationId}}">{{$registration_id->RegistrationId}}</option>

                                    @endforeach

                                </select>
                            </div>
                            <div class="col-md-2 warning-searching invisible" id="warning-searching_heart">
                                <span class="text-danger" id="warning-message">Searching...Please Wait</span>
                                <span class="spinner-border text-danger"></span>
                            </div>

                            <div class="form-group col-md-2 pt-24">

                                <button type="button" id="search_heart" class="btn btn-primary btn-sm float-right mr-2">
                                    <i class="fas fa-search"></i>
                                </button>

                                <button type="button" id="refresh_heart" class="btn btn-primary btn-sm float-right mr-2 refresh">
                                <i class="fas fa-sync-alt"></i></button>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <figure class="highcharts-figure">
                                    <div id="container_heart"></div>
                                </figure>
                            </div>
                        </div>
                    </form>



                </div>
                <!-- /card body -->

            </div>
            <!-- /card -->

          <!-- Temperature graph  -->

          <div class="card bar-chart">
                <div class="card-header d-flex align-items-center">
                <h4>Temperature Graph </h4>
                </div>
            </div>

          <!-- Card -->
          <div class="dt-card">

          <!-- Card Body -->
          <div class="dt-card__body">

              <form id="form-filter" method="GET" action="{{url('patient-blood-pressure-graph')}}">

                  <div class="row">
                      <div class="form-group col-md-2">
                          <label for="name">Date From</label>
                          <input type="date" class="form-control" value="<?php echo $_GET['starting_date']??'' ?>" name="starting_date_temperature" id="starting_date_temperature"
                              placeholder="Date From">
                      </div>
                      <div class="form-group col-md-2">
                          <label for="name">Date To </label>
                          <input type="date" class="form-control" value="<?php echo $_GET['ending_date']??'' ?>" name="ending_date_temperature" id="ending_date_temperature"
                              placeholder="Date To">
                      </div>

                      <div class="form-group col-md-3">
                          <label for="name">Registration Id</label>

                          <select class="selectpicker" data-live-search="true" name="registration_id_temperature" id="registration_id_temperature">
                              <option value="">Select Registration ID</option> <!-- Empty option added -->

                              @foreach($registrationId as $registration_id)
                                  <option value="{{$registration_id->RegistrationId}}">{{$registration_id->RegistrationId}}</option>

                              @endforeach

                          </select>
                      </div>
                      <div class="col-md-2 warning-searching invisible" id="warning-searching_temperature">
                          <span class="text-danger" id="warning-message">Searching...Please Wait</span>
                          <span class="spinner-border text-danger"></span>
                      </div>

                      <div class="form-group col-md-2 pt-24">

                          <button type="button" id="search_temperature" class="btn btn-primary btn-sm float-right mr-2">
                              <i class="fas fa-search"></i>
                          </button>

                          <button type="button" id="refresh_temperature" class="btn btn-primary btn-sm float-right mr-2 refresh_temperature">
                          <i class="fas fa-sync-alt"></i></button>
                      </div>
                  </div>

                  <div class="row">
                      <div class="col-md-12">
                          <figure class="highcharts-figure">
                              <div id="container_temperature"></div>
                          </figure>
                      </div>
                  </div>
              </form>

          </div>
          <!-- /card body -->

          </div>
          <!-- /card -->


            </div>
        </div>
        <!-- End :: Bar Chart-->
    
  </div>
@endsection


@push('script')
<script src="js/chart.min.js"></script>

<script>
$(document).ready(function(){

  $('.data-btn').on('click',function(){
    $('.data-btn').removeClass('active');
    $(this).addClass('active');
    var start_date = $(this).data('start_date');
    var end_date = $(this).data('end_date');

    $.get("{{ url('dashboard-data') }}", function(data){
      $('#sale').text(data.sale);
      $('#patient').text(data.patient);
      $('#profit').text(data.profit);
      $('#expense').text(data.expense);
      $('#customer').text(data.customer);
      $('#supplier').text(data.supplier);
    });
  });


  var brandPrimary;
  var brandPrimaryRgba;

  //Cash Flow Chart
  var CASHFLOW = $('#cashFlow');
  if(CASHFLOW.length > 0)
  {
    brandPrimary = CASHFLOW.data('color');
    brandPrimaryRgba = CASHFLOW.data('color_rgba');
    var received = CASHFLOW.data('received');
    var sent = CASHFLOW.data('sent');
    var month = CASHFLOW.data('month');
    var label1 = CASHFLOW.data('label1');
    var label2 = CASHFLOW.data('label2');
    var cashFlow_chart = new Chart(CASHFLOW, {
      type:'line',
      data:{
        labels:[month[0],month[1],month[2],month[3],month[4],month[5],month[6]],
        datasets:[
          {
            label:label1,
            fill:true,
            lineTension:0.3,
            backgroundColor: 'transparent',
            borderColor: brandPrimary,
            borderCapStyle: 'butt',
            borderDash:[],
            borderDashOffset:0.0,
            borderJoinStyle:'miter',
            borderWidth:3,
            pointBorderColor: brandPrimary,
            pointBackgroundColor:'#fff',
            pointBorderWidth:5,
            pointHoverRadius:5,
            pointHoverBackgroundColor:brandPrimary,
            pointHoverBorderColor:brandPrimaryRgba,
            pointHoverBorderWidth:2,
            pointRadius:1,
            pointHitRadius:10,
            data:[received[0],received[1],received[2],received[3],received[4],received[5],received[6]],
            spanGaps:false
          },
          {
            label:label2,
            fill:true,
            lineTension:0.3,
            backgroundColor: 'transparent',
            borderColor: '#f5222d',
            borderCapStyle: 'butt',
            borderDash:[],
            borderDashOffset:0.0,
            borderJoinStyle:'miter',
            borderWidth:3,
            pointBorderColor: 'rgba(245, 34, 45, 1)',
            pointBackgroundColor:'#fff',
            pointBorderWidth:5,
            pointHoverRadius:5,
            pointHoverBackgroundColor:'#f5222d',
            pointHoverBorderColor:'rgba(245, 34, 45, 1)',
            pointHoverBorderWidth:2,
            pointRadius:1,
            pointHitRadius:10,
            data:[sent[0],sent[1],sent[2],sent[3],sent[4],sent[5],sent[6]],
            spanGaps:false
          }
        ]
      }
    });
  }

  //Transaction Chart
  var TRANSACTIONCHART = $('#transactionChart');
  if(TRANSACTIONCHART.length > 0)
  {
    brandPrimary = TRANSACTIONCHART.data('color');
    brandPrimaryRgba = TRANSACTIONCHART.data('color_rgba');
    var sale = TRANSACTIONCHART.data('sale');
    var purchase = TRANSACTIONCHART.data('purchase');
    var expense = TRANSACTIONCHART.data('expense');
    var label1 = TRANSACTIONCHART.data('label1');
    var label2 = TRANSACTIONCHART.data('label2');
    var label3 = TRANSACTIONCHART.data('label3');
    var transaction_chart = new Chart(TRANSACTIONCHART, {
      type:'doughnut',
      data:{
        labels:[label1,label2,label3],
        datasets:[
          {
            data:[purchase,sale,expense],
            borderWidth:[1,1,1],
            backgroundColor:[ brandPrimary,'#52c41a','#f5222d'],
            hoverBackgroundColor:[
              brandPrimaryRgba,
              'rgba(82, 196, 26, 1)',
              'rgba(245, 34, 45, 1)'
            ],
            hoverBorderWidth:[4,4,4],
            hoverBorderColor:[
              brandPrimaryRgba,
              'rgba(82, 196, 26, 1)',
              'rgba(245, 34, 45, 1)'
            ]
          }
        ]
      }
    });
  }

  //Yearly Report Chart
  var YEARLYREPORTCHART = $('#yearlyReportChart');

  if(YEARLYREPORTCHART.length > 0)
  {
    var yearly_sale_amount = YEARLYREPORTCHART.data('sale_chart_value');
    var yearly_purchase_amount = YEARLYREPORTCHART.data('purchase_chart_value');
    var label1 = YEARLYREPORTCHART.data('label1');
    var label2 = YEARLYREPORTCHART.data('label2');

    var yearly_report_chart = new Chart(YEARLYREPORTCHART, {
      type:'bar',
      data:{
        labels:["January","February","March","April","May","June","July","August","September","October","November","December"],
        datasets:[
          {
            label:label1,
            backgroundColor:[
              brandPrimaryRgba,
              brandPrimaryRgba,
              brandPrimaryRgba,
              brandPrimaryRgba,
              brandPrimaryRgba,
              brandPrimaryRgba,
              brandPrimaryRgba,
              brandPrimaryRgba,
              brandPrimaryRgba,
              brandPrimaryRgba,
              brandPrimaryRgba,
              brandPrimaryRgba,
              brandPrimaryRgba,
            ],
            borderColor:[
              brandPrimary,
              brandPrimary,
              brandPrimary,
              brandPrimary,
              brandPrimary,
              brandPrimary,
              brandPrimary,
              brandPrimary,
              brandPrimary,
              brandPrimary,
              brandPrimary,
              brandPrimary,
              brandPrimary,
            ],
            borderWidth:1,
            data:[
              yearly_purchase_amount[0],yearly_purchase_amount[1],yearly_purchase_amount[2],yearly_purchase_amount[3],
              yearly_purchase_amount[4],yearly_purchase_amount[5],yearly_purchase_amount[6],yearly_purchase_amount[7],
              yearly_purchase_amount[8],yearly_purchase_amount[9],yearly_purchase_amount[10],yearly_purchase_amount[11], 0
              ],
          },
          {
            label:label2,
            backgroundColor:[
              'rgba(82, 196, 26, 1)',
              'rgba(82, 196, 26, 1)',
              'rgba(82, 196, 26, 1)',
              'rgba(82, 196, 26, 1)',
              'rgba(82, 196, 26, 1)',
              'rgba(82, 196, 26, 1)',
              'rgba(82, 196, 26, 1)',
              'rgba(82, 196, 26, 1)',
              'rgba(82, 196, 26, 1)',
              'rgba(82, 196, 26, 1)',
              'rgba(82, 196, 26, 1)',
              'rgba(82, 196, 26, 1)',
              'rgba(82, 196, 26, 1)',
            ],
            borderColor:[
              '#52c41a',
              '#52c41a',
              '#52c41a',
              '#52c41a',
              '#52c41a',
              '#52c41a',
              '#52c41a',
              '#52c41a',
              '#52c41a',
              '#52c41a',
              '#52c41a',
              '#52c41a',
              '#52c41a',
            ],
            borderWidth:1,
            data:[
              yearly_sale_amount[0],yearly_sale_amount[1],yearly_sale_amount[2],yearly_sale_amount[3],
              yearly_sale_amount[4],yearly_sale_amount[5],yearly_sale_amount[6],yearly_sale_amount[7],
              yearly_sale_amount[8],yearly_sale_amount[9],yearly_sale_amount[10],yearly_sale_amount[11], 0
              ],
          },
        ]
      }
    });
  }
});


// Report patient blood pressure

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

//hear rate graph 
$('#refresh_heart').click(function(){
    $('#starting_date_heart').val('');
    $('#ending_date_heart').val('');
    $('.selectpicker').selectpicker('val', '');
    $('#container_heart').html('');
});

$('#search_heart').click(function() {
    var starting_date_heart = $('#starting_date_heart').val();
    var ending_date_heart = $('#ending_date_heart').val();
    var registration_id_heart = $('#registration_id_heart').val();
    console.log(starting_date_heart);
    console.log(starting_date_heart);

    $.ajax({
        url: "{{ url('ajax-heart-rate-graph') }}",
        type: "get",
        data: { starting_date: starting_date_heart, ending_date: ending_date_heart, registration_id: registration_id_heart },
        dataType: "html",
        beforeSend: function(){
            $('#warning-searching_heart').removeClass('invisible');
        },
        complete: function(){
            $('#warning-searching_heart').addClass('invisible');
        },
        success: function(data) {
            $('#container_heart').html(data);
        },
        error: function(xhr, ajaxOption, thrownError) {
            console.log(thrownError + '\r\n' + xhr.statusText + '\r\n' + xhr.responseText);
        }
    });
});

// temperature graph
$('#refresh_temperature').click(function(){
    $('#starting_date_temperature').val('');
    $('#ending_date_temperature').val('');
    $('.selectpicker').selectpicker('val', '');
    $('#container_temperature').html('');
});

$('#search_temperature').click(function() {
    var starting_date_temperature = $('#starting_date_temperature').val();
    var ending_date_temperature = $('#ending_date_temperature').val();
    var registration_id_temperature = $('#registration_id_temperature').val();

    $.ajax({
        url: "{{ url('ajax-temperature-graph') }}",
        type: "get",
        data: { starting_date: starting_date_temperature, ending_date: ending_date_temperature, registration_id: registration_id_temperature },
        dataType: "html",
        beforeSend: function(){
            $('#warning-searching_temperature').removeClass('invisible');
        },
        complete: function(){
            $('#warning-searching_temperature').addClass('invisible');
        },
        success: function(data) {
          console.log(data)
            $('#container_temperature').html(data);
        },
        error: function(xhr, ajaxOption, thrownError) {
            console.log(thrownError + '\r\n' + xhr.statusText + '\r\n' + xhr.responseText);
        }
    });
});

</script>
@endpush
