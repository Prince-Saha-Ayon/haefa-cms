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
                    {{-- <input type="hidden" name="update_id" id="update_id"/> --}}
                    <input type="hidden" name="RefProvisionalDiagnosisGroupId" value="" id="RefProvisionalDiagnosisGroupId"/>
                     <input type="hidden" name="SortOrder" value="1" />
                    <x-form.textbox labelName="ProvisionalDiagnosis Group Code" name="RefProvisionalDiagnosisGroupCode" id="RefProvisionalDiagnosisGroupCode" required="required" col="col-md-12"  placeholder="Enter ProvisionalDiagnosis Group Code"/>
                     <x-form.textbox labelName="Category" name="Category" id="Category" required="required"  col="col-md-12"  placeholder="Enter Category"/>
                    <x-form.textbox labelName="CommonTerm" name="CommonTerm" id="CommonTerm" required="required"  col="col-md-12"  placeholder="Enter CommonTerm"/>
                  
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