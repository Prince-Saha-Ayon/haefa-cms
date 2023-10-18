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
                    <input type="hidden" name="RefProvisionalDiagnosisId" value="" id="RefProvisionalDiagnosisId"/>
                     <input type="hidden" name="SortOrder" value="1" />
                     <input type="hidden" name="GroupSortOrder" value="20000" />

                    <x-form.textbox labelName="ProvisionalDiagnosis Code" name="ProvisionalDiagnosisCode" id="ProvisionalDiagnosisCode" required col="col-md-12"  placeholder="Enter ProvisionalDiagnosisCode"/>
                    <x-form.textbox labelName="ProvisionalDiagnosis Name" name="ProvisionalDiagnosisName" id="ProvisionalDiagnosisName" required col="col-md-12"  placeholder="Enter ProvisionalDiagnosis Name"/>
                   <x-form.selectbox labelName="ProvisionalDiagnosis Group" name="RefProvisionalDiagnosisGroupId" id="RefProvisionalDiagnosisGroupId"
                                col="col-md-12" class="selectpicker">
                                @foreach($dx_group as $dx)
                                <option value="{{$dx->RefProvisionalDiagnosisGroupId??''}}">
                                    {{$dx->RefProvisionalDiagnosisGroupCode??""}}
                                </option>
                                @endforeach
                    </x-form.selectbox>
                    
                
                 
                    <x-form.textbox labelName="Description" name="Description" id="Description"  col="col-md-12"  placeholder="Enter Description"/>
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