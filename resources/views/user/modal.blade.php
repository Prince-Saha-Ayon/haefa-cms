<div class="modal fade" id="store_or_update_modal" tabindex="-1" role="dialog" aria-labelledby="model-1"
    aria-hidden="true">
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
                        <input type="hidden" name="update_id" id="update_id" />
                        <input type="hidden" name="OrgId" value="73CA453C-5F08-4BE7-A8B8-A2FDDA006A2B"/>
                        <x-form.textbox labelName="Name" name="name" required="required" col="col-md-12"
                            placeholder="Enter name" />
                        <x-form.textbox labelName="Email" name="email" required="required" col="col-md-12"
                            placeholder="Enter email" />
                        <x-form.textbox labelName="Mobile No" name="mobile_no" required="required" col="col-md-12"
                            placeholder="Enter mobile_no" />
                        <x-form.selectbox labelName="Gender" name="gender" required="required" col="col-md-12"
                            class="selectpicker">
                            <option value="1">Male</option>
                            <option value="2">Female</option>
                        </x-form.selectbox>
                        <x-form.selectbox labelName="Role" name="role_id" required="required" col="col-md-12"
                            class="selectpicker">
                            @foreach ($roles as $role)
                            <option value="{{ $role->id }}">{{ $role->role_name }}</option>
                            @endforeach
                        </x-form.selectbox>
                        <x-form.selectbox labelName="Community Clinic" name="cc_id" required="required" col="col-md-12"
                            class="selectpicker">
                            @foreach ($barcodes as $barcode)
                            <option value="{{ $barcode->id }}">{{ $barcode->healthCenter->HealthCenterName }}</option>
                            @endforeach
                        </x-form.selectbox>
                        <x-form.selectbox labelName="Employee Name" name="EmployeeId" required="required" col="col-md-12"
                            class="selectpicker">
                            @foreach ($employees as $employee)
                            <option value="{{ $employee->EmployeeId }}">{{ $employee->FirstName }} {{ $employee->LastName }} </option>
                            @endforeach
                        </x-form.selectbox>

                        <div class="col-md-12 riMenuInputs">
                            <div class="ant-card ant-card-bordered gx-card">
                                <div class="ant-card-head">
                                    <div class="ant-card-head-wrapper">
                                        <div class="ant-card-head-title">Station Name</div>
                                    </div>
                                </div><br>
                                <div class="ant-card-body">
                                    <div class="ant-checkbox-group" id="StationName">
                                        @foreach ($stations as $station)
                                        <label class="ant-checkbox-wrapper ant-checkbox-group-item">
                                                <span class="ant-checkbox">
                                                    <input type="checkbox" name="station[]" class="ant-checkbox-input" value="{{ $station->deviceId }}">
                                                    <span class="ant-checkbox-inner"></span>
                                                </span>
                                            <span>{{ $station->name }}</span>
                                        </label>
                                        @endforeach
                                    </div>
                                </div>
                            </div>

                        </div>

                        <div class="form-group col-md-12">
                            <label for="password">Password</label>
                            <div class="input-group">
                                <input type="password" class="form-control" name="password" id="password">
                                <div class="input-group-prepend">
                                    <span class="input-group-text bg-warning" id="generate_password"
                                        data-toggle="tooltip" data-placement="top"
                                        data-original-title="Generate Password">
                                        <i class="fas fa-lock text-white" style="cursor: pointer;"></i>
                                    </span>
                                </div>
                                <div class="input-group-prepend">
                                    <span class="input-group-text bg-primary">
                                        <i class="fas fa-eye toggle-password text-white" toggle="#password"
                                            style="cursor: pointer;"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group col-md-12">
                            <label for="password_confirmation">Confirm Password</label>
                            <div class="input-group">
                                <input type="password" class="form-control" name="password_confirmation"
                                    id="password_confirmation">
                                <div class="input-group-prepend">
                                    <span class="input-group-text bg-primary">
                                        <i class="fas fa-eye toggle-password text-white" toggle="#password_confirmation"
                                            style="cursor: pointer;"></i>
                                    </span>
                                </div>
                            </div>
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
