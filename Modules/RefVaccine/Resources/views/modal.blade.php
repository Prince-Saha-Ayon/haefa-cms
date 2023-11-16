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
                    <input type="hidden" name="VaccineId" value="" id="VaccineId"/>
                     <input type="hidden" name="SortOrder" value="1" />

                    <x-form.textbox labelName="Vaccine Code" name="VaccineCode" id="VaccineCode" required="required" col="col-md-12"  placeholder="Enter Vaccine Code"/>
                   <x-form.selectbox labelName="Vaccine Dose Group" required="required" name="VaccineDoseGroupId" id="VaccineDoseGroupId"
                                col="col-md-12" class="selectpicker">
                                @foreach($vaccine_group as $vg)
                                <option value="{{$vg->VaccineDoseGroupId??''}}">
                                    {{$vg->VaccineDoseTitle??""}}
                                </option>
                                @endforeach
                    </x-form.selectbox>
                    
                     <x-form.textbox labelName="Vaccine Dose" type="number" required="required" name="VaccineDoseNumber" id="VaccineDoseNumber"  col="col-md-12"  placeholder="Enter Vaccine Dose "/>

                     <x-form.textbox labelName="Instruction" name="Instruction" id="Instruction"  col="col-md-12"  placeholder="Enter Instruction"/>
                 
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