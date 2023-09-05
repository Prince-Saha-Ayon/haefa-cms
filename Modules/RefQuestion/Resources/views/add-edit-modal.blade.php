<div class="modal fade" id="store_or_update_modal" tabindex="-1" role="dialog" aria-labelledby="model-1"
    aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">

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
                        <input type="hidden" name="QuestionId" value="" id="QuestionId" />
                        <input type="hidden" name="SortOrder" value="8" />
                        <x-form.textbox labelName="QuestionModuleName" name="QuestionModuleName" id="QuestionModuleName"
                            required="required" col="col-md-12" placeholder="Enter Question Module Name" />
                    </div>

                    <div class="row">
                        <x-form.selectbox labelName="QuestionType" name="QuestionTypeId" id="QuestionType" col="col-md-12" class="selectpicker">
                          @if (!$types->isEmpty())
                          @foreach ($types as $type)
                              <option value="{{ $type->QuestionTypeId }}">{{ $type->QuestionTypeCode }}</option>
                          @endforeach
                          @endif
                          </x-form.selectbox>
                    </div>

                    <div class="row">
                        <x-form.textbox type="number" labelName="QuestionGroup" required name="QuestionGroupId" id="QuestionGroup"
                                 col="col-md-12" placeholder="Enter QuestionGroup" />
                    </div>

                    <div class="row">
                        <x-form.textbox labelName="QuestionTitle" name="QuestionTitle" id="QuestionTitle"
                                required="required" col="col-md-12" placeholder="Enter QuestionTitle" />
                    </div>

                    <div class="row">
                        <x-form.textbox labelName="Description" name="Description" id="Description" col="col-md-12"
                                placeholder="Enter description" />
                    </div>

                    <div class="row">
                        <div class="form-group col-md-4">
                            <label for="AnswerTitle-0">Answer</label>
                            <input type="text" name="AnswerTitle[]" id="AnswerTitle-0" class="form-control row-0" value="" placeholder="Enter Answer">
                        </div>

                        <div class="form-group col-md-4">
                            <label for="AnswerGroupId-0">Answer Group Id</label>
                            <input type="number" name="AnswerGroupId[]" id="AnswerGroupId-0" class="form-control AnswerGroupId-0" value="" placeholder="Enter AnswerGroupId">
                        </div>

                        <div class="form-group col-md-4">
                            <input class="mt-5" type="button" id="addnew" value="Add New" onclick="addRow()">
                        </div>
                    </div>

                    <div id="content">
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
