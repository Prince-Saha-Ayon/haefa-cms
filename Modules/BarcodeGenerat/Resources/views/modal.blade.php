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
                    <x-form.selectbox labelName="Community Clinic Name" name="address" col="col-md-12" class="selectpicker">
                        @if (!$addresses->isEmpty())
                            @foreach ($addresses as $address)
                                <option value="{{ $address->healthCenter->HealthCenterName }},{{ $address->union->UnionName }},{{ $address->upazila->UpazilaName }},{{ $address->district->districtName }}">{{ $address->healthCenter->HealthCenterName }},{{ $address->union->UnionName }},{{ $address->upazila->UpazilaName }},{{ $address->district->districtName }}</option>
                            @endforeach
                        @endif
                    </x-form.selectbox>
                    <x-form.selectbox labelName="Barcode Prefix" name="mdata_barcode_prefix" onchange="getVariantOptionList(this.value)" col="col-md-12" class="selectpicker">
                        @if (!$barcodeFormates->isEmpty())
                            @foreach ($barcodeFormates as $barcodeFormate)
                                <option value="{{ $barcodeFormate->barcode_prefix }}">{{ $barcodeFormate->barcode_prefix }}</option>
                            @endforeach
                        @endif
                    </x-form.selectbox>
                    <x-form.textbox labelName="Barcode Number" name="mdata_barcode_number" col="col-md-12" placeholder="Enter Product Barcode" class="mdata_barcode_number" readonly="readonly"/>
                    <x-form.textbox labelName="Barcode Generate Range" name="mdata_barcode_generate" required="required" col="col-md-12" type="number" placeholder="Enter Barcode Range"/>
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
