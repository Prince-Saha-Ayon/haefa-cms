@extends('layouts.app')

@section('title')
    {{ $page_title }}
@endsection

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
                <form id="syncForm" method="POST" action="{{url('data-sync-perform')}}">
                  @csrf
                  <button type="submit" class="btn btn-primary btn-sm" id="syncBtn">
                    Synchronize Database
                  </button>
               </form>
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

            // After performing your actions, you can submit the form
            // This assumes that your form has an ID attribute (e.g., 'syncForm')
            $('#syncForm').submit();
        });
    });

</script>
@endpush

