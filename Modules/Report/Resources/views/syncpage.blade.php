@extends('layouts.app')

@section('title')
    {{ $page_title }}
@endsection
<style type="text/css">
.dt-error-code {
    font-size: 4rem !important;
}
.sync-record{
    font-size: 15px;
}


</style>

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
      

       


      </div>

    </div>

        <!-- Grid Item -->
        <div class="col-xl-12">

            <!-- Entry Header -->
            <div class="dt-entry__header">

                <!-- Entry Heading -->
                <div class="dt-entry__heading col-6">
                    <h2 class="dt-page__title mb-0 text-primary"><i class="{{ $page_icon }}"></i> {{ $sub_title }}
                    <span class="spinner-border text-danger d-none" id="spin-order"></span></h2>
                </div>
                <!-- /entry heading -->

            </div>
            <!-- /entry header -->

            <!-- Card -->
            <div class="dt-card">

                <!-- Card Body -->
                <div class="dt-card__body">
                <form id="syncForm">
                  @csrf
                  <button type="submit" class="btn btn-primary btn-sm" id="syncBtn">
                    Synchronize Database
                  </button>
               </form>
               <p class="sync-record">Last Sync: {{$newDate}}</p>
                <div class="error-page text-center d-none" id="sync-success">
                        <h3 class="dt-error-code">Synchronization Success</h3>
                        <h4 class="mb-10">Data has been synced successfully</h4>
                </div>
                 <div class="error-page text-center d-none" id="sync-failure">
                        <h3 class="dt-error-code text-danger">Synchronization Failed</h3>
                        <h4 class="mb-10 text-danger">Check The Database Connections</h4>
                </div>
                    <!-- 404 Page -->
                    {{-- <div class="error-page text-center">

                        <!-- Title -->
                        <h1 class="dt-error-code">Success</h1>
                        <!-- /title -->

                        <h2 class="mb-10">Synced Successfully</h2>

                        <p class="text-center mb-6"><a href="{{ url('/') }}" class="btn btn-primary">Go to Home</a></p>


                    </div> --}}
                    <!-- /404 page -->
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
    <script src="js/jquery.min.js"></script>
    
   <script>
       $(document).ready(function () {
        $(document).on('click', '#syncBtn', function (e) {
            // Prevent the default form submission behavior to allow your code to execute first
            e.preventDefault();
            
            // Perform your actions here (e.g., show a spinner)
            $('#spin-order').removeClass('d-none');
            $("#syncBtn").attr("disabled", "true");
            console.log('i am here');
            $.ajax({
            url: "{{ url('data-sync-perform') }}",
            type: "get",
            complete: function(){
                
            },
            success: function(data) {
                if(data=='success'){
                $('#spin-order').addClass('d-none');
                $('#sync-success').removeClass('d-none');
                 console.log(data);
                }else{
                $('#spin-order').addClass('d-none');
                $('#sync-failure').removeClass('d-none'); 
                }
            
            },
            error: function(xhr, ajaxOption, thrownError) {
                console.log(thrownError + '\r\n' + xhr.statusText + '\r\n' + xhr.responseText);
            }
        });

            // After performing your actions, you can submit the form
            // This assumes that your form has an ID attribute (e.g., 'syncForm')
           
        });
    });

</script>
@endpush

