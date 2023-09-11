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
                    <input type="hidden" name="update_id" id="update_id"/>

                    <div class="form-group col-md-12 required">
                        <label for="barcode_district">Barcode District Name</label>
                        <select name="barcode_district" id="dc_id" class="form-control selectpicker" data-live-search="true" data-live-search-placeholder="Search" title="Choose one of the following" tabindex="null"><option class="bs-title-option" value=""></option>
                            <option value="">Select Please</option>
                            @if (!$districts->isEmpty())
                                @foreach ($districts as $district)
                                    <option value="{{ $district->id }}">{{ $district->name }}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>

                    <div class="form-group col-md-12 required">
                        <label for="barcode_district">Barcode Upazila Name</label>
                        <select name="barcode_upazila" id="up_id" class="form-control selectpicker" data-live-search="true" data-live-search-placeholder="Search" title="Choose one of the following" tabindex="null"><option class="bs-title-option" value=""></option>
                            <option value="">Select Please</option>
                        </select>
                    </div>

                    <div class="form-group col-md-12 required">
                        <label for="barcode_district">Barcode Union Name</label>
                        <select name="barcode_union" id="un_id" class="form-control selectpicker" data-live-search="true" data-live-search-placeholder="Search" title="Choose one of the following" tabindex="null"><option class="bs-title-option" value=""></option>
                            <option value="">Select Please</option>
                        </select>
                    </div>

                    <x-form.selectbox labelName="Barcode Community Clinic Name" name="barcode_community_clinic" required="required" col="col-md-12" class="selectpicker">
                        @if (!$HealthCenters->isEmpty())
                            @foreach ($HealthCenters as $HealthCenter)
                                <option value="{{ $HealthCenter->HealthCenterId }}">{{ $HealthCenter->HealthCenterName }}</option>
                            @endforeach
                        @endif
                    </x-form.selectbox>
                    <x-form.textbox labelName="Barcode Prefix" name="barcode_prefix" required="required" col="col-md-12" placeholder="Enter Barcode Prefix name"/>
                    <x-form.textbox labelName="Barcode Number" type="number" name="barcode_number" value="10000001" required="required" col="col-md-12" placeholder="Barcode Numbers Must Be Greater Than 0" readonly="readonly"/>
                    <p style="margin-left:20px; margin-top:0px; padding:0px;color:red;">Barcode Numbers Must Be Greater Than 0</p>
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
