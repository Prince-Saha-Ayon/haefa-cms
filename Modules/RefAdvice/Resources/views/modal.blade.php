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
                    <input type="hidden" name="AdviceId" value="" id="AdviceId"/>
                     <input type="hidden" name="SortOrder" value="1" />
                  

                    <x-form.textbox labelName="Advice Code" name="AdviceCode" required="required" id="AdviceCode"  col="col-md-12"  placeholder="Enter Advice Code"/>
                    <x-form.textbox labelName="Advice In English" name="AdviceInEnglish"  id="AdviceInEnglish"  col="col-md-12"  placeholder="Enter Advice In English"/>
                    <x-form.textbox labelName="Advice In Bangla" name="AdviceInBangla" id="AdviceInBangla"  col="col-md-12"  placeholder="Enter Advice In Bangla"/>

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