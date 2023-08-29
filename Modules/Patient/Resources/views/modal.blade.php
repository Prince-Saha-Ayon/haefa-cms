<div class="modal fade" id="store_or_update_modal" tabindex="-1" role="dialog" aria-labelledby="model-1"
   aria-hidden="true">
   <div class="modal-dialog modal-xl" role="document">
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
                  <input type="hidden" name="update_id" id="update_id" />

                  <div class="col-md-12">
                     <div class="row">
                        <x-form.textbox labelName="Registration Number" name="registrationnumber" required="required" col="col-md-4"
                           placeholder="Enter Registration Number" />
                        <x-form.textbox labelName="First Name *" name="firstnname" required="required" col="col-md-4"
                           placeholder="Enter Registration Number" />
                           <x-form.textbox labelName="Registration Number" name="registrationnumber" required="required" col="col-md-4"
                           placeholder="Enter Registration Number" />
                           <x-form.textbox labelName="Registration Number" name="registrationnumber" required="required" col="col-md-4"
                           placeholder="Enter Registration Number" />
                        <x-form.textbox labelName="First Name *" name="firstnname" required="required" col="col-md-4"
                           placeholder="Enter Registration Number" />
                           <x-form.textbox labelName="Registration Number" name="registrationnumber" required="required" col="col-md-4"
                           placeholder="Enter Registration Number" />
                        <!-- <x-form.selectbox labelName="First Name *" name="firstnName" required="required" col="col-md-6"
                           class="selectpicker">
                           <option value="gggg">ddd</option>
                        </x-form.selectbox> -->
                     </div>
                  </div>
                  <!-- <div class="col-md-4">
                     <div class="form-group col-md-12 required">
                        <label for="image">Product Image</label>
                        <x-form.textbox labelName="Registration Number" name="registrationnumber" required="required" col="col-md-6"
                           placeholder="Enter Registration Number" />
                     </div>
                  </div> -->


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