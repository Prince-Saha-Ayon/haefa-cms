<div class="modal fade" id="store_or_update_modal" tabindex="-1" role="dialog" aria-labelledby="model-1" aria-hidden="true">
    <div class="modal-dialog" role="document">

      <!-- Modal Content -->
      <div class="modal-content">

        <!-- Modal Header -->
        <div class="modal-header bg-primary">
          <h3 class="modal-title text-white" id="model-1"></h3>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <!-- /modal header -->
        <form id="store_or_update_form" method="post">
          @csrf
            <!-- Modal Body -->
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group col-md-12">
                            <label for="upazilla_id">Upazilla</label>
                            <select name="upazilla_id" id="upazilla_id" class="form-control selectpicker" data-live-search="true" >
                                <option value=""> Select Please</option>
                                @foreach ($upazilas as $upazila)
                                    <option value="{{ $upazila->id }}">{{ $upazila->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <input type="hidden" name="id" value="" id="Id"/>
                        <input type="hidden" name="url" id="url" class="form-control " value="null" placeholder="Enter url">
                        <input type="hidden" name="bn_name" id="bn_name" class="form-control " value="bn_name" placeholder="Enter bn_name">
                        <x-form.textbox labelName="Union Name" name="name" id="name" required="required" col="col-md-12" placeholder="Enter union name"/>
                    </div>
                </div>

            </div>
            <!-- /modal body -->

            <!-- Modal Footer -->
            <div class="modal-footer">
            <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal">Close</button>
            <button type="button" class="btn btn-primary btn-sm" id="save-btn"></button>
            </div>
            <!-- /modal footer -->
        </form>
      </div>
      <!-- /modal content -->

    </div>
  </div>
