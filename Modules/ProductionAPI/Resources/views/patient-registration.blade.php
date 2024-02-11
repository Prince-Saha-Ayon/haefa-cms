@extends('layouts.app')

@section('title')
    {{ $page_title }}
@endsection

@push('stylesheet')


@endpush


@section('content')
<div class="d-flex flex-column-fluid">
    <div class="container-fluid">
        <!--begin::Notice-->
        <div class="card card-custom gutter-b">
            <div class="card-header flex-wrap py-5">
                <div class="card-title">
                    <h3 class="card-label"><i class="{{ $page_icon }} text-primary"></i> {{ $sub_title }}</h3>
                </div>
                <div class="card-toolbar">
                    <!--begin::Button-->

                    <!--end::Button-->
                </div>
            </div>
        </div>
        <!--end::Notice-->
        <!--begin::Card-->
        <div class="card card-custom" style="padding-bottom: 100px !important;">
            <div class="card-body">
                <form id="store_or_update_form" method="post" enctype="multipart/form-data">
                @csrf
                <!-- Modal Body -->
                <div class="modal-body">
                    <div class="row">
                        <h3>Registration</h3><br>
                        
                        <div class="col-md-12">
                            <div class="row">
                                <x-form.selectbox labelName="Select Facility" name="identifier" id="identifier" required="required" col="col-md-6" class="form-group selectpicker">
                                    @if (!$facilities->isEmpty())
                                        @foreach ($facilities as $facility)
                                        <option value="{{ $facility->identifier }}">{{ $facility->facility_name }} ({{ $facility->identifier }})</option>
                                        @endforeach
                                    @endif
                                </x-form.selectbox>

                                <div class="form-group col-6">

                                </div>

                                <x-form.textbox type="text" labelName="Total Sent" readonly name="total_sent" col="col-md-2" value="{{ $sent ??'' }}" />
                                <x-form.textbox type="text" labelName="Total Unsent" readonly  name="total_unsent" col="col-md-2" value="{{ $unsent ??'' }}" />
                                <x-form.textbox type="text" labelName="Sending Now" name="sending_now" col="col-md-2" value="100" />
                               

                            </div>
                        </div>
               
              
                   

                    </div>
                </div>
                <!-- /modal body -->

                <!-- Modal Footer -->
                <div class="form-group col-md-12 pt-5">
                    <button type="button" class="btn btn-primary btn-sm" id="update-btn">Send</button>
                </div>
                <!-- /modal footer -->
                </form>
            </div>
        </div>
        <!--end::Card-->
    </div>
</div>
@endsection

@push('script')
<script src="js/spartan-multi-image-picker-min.js"></script>


<script>
$(document).ready(function () {
 


    $('.summernote').summernote({
        tabsize: 2,
        height: 120,
        toolbar: [
          ['style', ['style']],
          ['font', ['bold', 'underline', 'clear']],
          ['para', ['ul', 'ol', 'paragraph']],
          ['table', ['table']],
          ['view', ['fullscreen', 'codeview', 'help']]
        ]

      });
    /** Start :: patient Image **/
    $('#image').spartanMultiImagePicker({
        fieldName: 'image',
        maxCount: 1,
        rowHeight: '200px',
        groupClassName: 'col-md-12 com-sm-12 com-xs-12',
        maxFileSize: '',
        dropFileLabel: 'Drop Here',
        allowExt: 'png|jpg|jpeg',
        onExtensionErr: function(index, file){
            Swal.fire({icon:'error',title:'Oops...',text: 'Only png,jpg,jpeg file format allowed!'});
        }
    });

    $('input[name="image"]').prop('required',true);

    $('.remove-files').on('click', function(){
        $(this).parents(".col-md-12").remove();
    });




    /** End :: patient Image **/


    $('input[name="lifestyle_image"]').prop('required',true);



    $('.remove-files').on('click', function(){
        $(this).parents(".col-md-12").remove();
    });




    /****************************/
    // $(document).on('click','#update-btn',function(){

    //     let form = document.getElementById('store_or_update_form');
    //     let formData = new FormData(form);

    //     $.ajax({
    //         url: "{{route('patient.store.or.update1')}}",
    //         type: "POST",
    //         data: formData,
    //         dataType: "JSON",
    //         contentType: false,
    //         processData: false,
    //         cache: false,
    //         beforeSend: function(){
    //             $('#update-btn').addClass('spinner spinner-white spinner-right');
    //         },
    //         complete: function(){
    //             $('#update-btn').removeClass('spinner spinner-white spinner-right');
    //         },
    //         success: function (data) {
    //             $('#store_or_update_form').find('.is-invalid').removeClass('is-invalid');
    //             $('#store_or_update_form').find('.error').remove();
    //             if (data.status == false) {
    //                 $.each(data.errors, function (key, value){
    //                     var key = key.split('.').join('_');
    //                     $('#store_or_update_form input#' + key).addClass('is-invalid');
    //                     $('#store_or_update_form textarea#' + key).addClass('is-invalid');
    //                     $('#store_or_update_form select#' + key).parent().addClass('is-invalid');
    //                     if(key == 'code'){
    //                         $('#store_or_update_form #' + key).parents('.form-group').append(
    //                         '<small class="error text-danger">' + value + '</small>');
    //                     }else{
    //                         $('#store_or_update_form #' + key).parent().append(
    //                         '<small class="error text-danger">' + value + '</small>');
    //                     }
    //                 });
    //             } else {
    //                 notification(data.status, data.message);
    //                 if (data.status == 'success') {
    //                         window.location.replace("{{ route('patient') }}");
    //                 }
    //             }
    //         },
    //         error: function (xhr, ajaxOption, thrownError) {
    //             console.log(thrownError + '\r\n' + xhr.statusText + '\r\n' + xhr.responseText);
    //         }
    //     });
    // });




});


</script>
@endpush
