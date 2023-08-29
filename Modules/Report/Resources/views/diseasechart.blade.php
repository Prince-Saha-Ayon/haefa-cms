@extends('layouts.app')

@section('title')
    {{ $page_title }}
@endsection

@push('stylesheet')
<style>
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
                    <form id="form-filter" method="POST" action="{{route('date-wise-dx')}}" >
                            @csrf
                            <div class="row">
                                <div class="form-group col-md-3">
                                    <label for="name">Date From</label>
                                    <input type="date" class="form-control" name="starting_date" id="starting_date" placeholder="Date From">
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="name">Date To </label>
                                    <input type="date" class="form-control" name="ending_date" id="ending_date" placeholder="Date To">
                                </div>
                                <div class="col-md-2 warning-searching invisible" id="warning-searching">
                                    <span class="text-danger" id="warning-message">Searching...Please Wait</span>
                                    <span class="spinner-border text-danger"></span>
                                </div>
                                <div class="form-group col-md-4 pt-24">

                                    <button type="submit"  class="btn btn-primary d-none btn-sm float-right mr-2" id="btn-filter"
                                            data-toggle="tooltip" data-placement="top" data-original-title="Filter Data">
                                        <i class="fas fa-search"></i>
                                    </button>
                                </div>
                            </div>
                        </form>
                       
                    <div>
                        <canvas id="diseaseChart"></canvas>
                    </div>



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
        var ctx = document.getElementById('diseaseChart').getContext('2d');
    
    var labels = data.map(item => item.name);
    var occurrences = data.map(item => item.occurrence);
     var backgroundColors = [
        '#FF5733',
        '#C70039',
        '#900C3F',
        '#581845',
        '#4A235A',
        '#2E86C1',
        '#17A589',
        '#229954',
        '#7D6608',
        '#7E5109'
    ]; // Example color codes
    
    var chart = new Chart(ctx, {
        type: 'pie',
        data: {
            labels: labels,
            datasets: [{
                data: occurrences,
                backgroundColor: backgroundColors,
            }]
        }
    });

    </script>
@endpush
