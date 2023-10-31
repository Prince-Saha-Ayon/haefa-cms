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
                    <input type="hidden" name="DrugId" value="" id="DrugId"/>
                     <input type="hidden" name="SortOrder" value="1" />

                    <x-form.textbox labelName="Drug Code" name="DrugCode" id="DrugCode" required="required" col="col-md-12"  placeholder="Enter Drug Code"/>
                   <x-form.selectbox labelName="Drug Group" required="required" name="DrugGroupId" id="DrugGroupId"
                                col="col-md-12" class="selectpicker">
                                @foreach($drug_group as $dg)
                                <option value="{{$dg->DrugGroupId??''}}">
                                    {{$dg->DrugGroupCode??""}}
                                </option>
                                @endforeach
                    </x-form.selectbox>
                     <x-form.selectbox labelName="Drug Form" required="required" name="DrugFormId" id="DrugFormId"
                                col="col-md-12" class="selectpicker">
                                @foreach($drug_form as $df)
                                <option value="{{$df->DrugFormId??''}}">
                                    {{$df->DrugFormCode??""}}
                                </option>
                                @endforeach
                    </x-form.selectbox>
                     <x-form.textbox labelName="Drug Dose" name="DrugDose" id="DrugDose"  col="col-md-12"  placeholder="Enter DrugDose"/>
                 
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