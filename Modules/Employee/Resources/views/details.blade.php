<div class="col-md-8">
    <div class="table-responsive">
        <table class="table table-borderless">
            <tbody>
                <tr>
                    <td><b>Employee Code </b></td>
                    <td><b>:</b></td>
                    <td>
                        @if(isset($Employee[0]->EmployeeCode))
                            {{$Employee[0]->EmployeeCode??""}}
                        @endif
                    </td>
                </tr>
                
                <tr>
                    <td><b>Registration Number</b></td>
                    <td><b>:</b></td>
                    <td>
                        @if(isset($Employee[0]->RegistrationNumber))
                            {{$Employee[0]->RegistrationNumber??""}}
                        @endif
                    </td>
                </tr>

                <tr>
                    <td><b>First Name</b></td>
                    <td><b>:</b></td>
                    <td>
                        @if(isset($Employee[0]->FirstName))
                            {{$Employee[0]->FirstName??""}}
                        @endif
                    </td>
                </tr>
                
                <tr>
                    <td><b>Last Name</b></td>
                    <td><b>:</b></td>
                    <td>
                        @if(isset($Employee[0]->LastName))
                            {{$Employee[0]->LastName??""}}
                        @endif
                    </td>
                </tr>
                
                <tr>
                    <td><b>Birth Date</b></td>
                    <td><b>:</b></td>
                    <td>
                        @if(isset($Employee[0]->BirthDate))
                            {{$Employee[0]->BirthDate??""}}
                        @endif
                    </td>
                </tr>
                
                <tr>
                    <td><b>Joining Date</b></td>
                    <td><b>:</b></td>
                    <td>
                        @if(isset($Employee[0]->JoiningDate))
                            {{$Employee[0]->JoiningDate??""}}
                        @endif
                    </td>
                </tr>
                
                <tr>
                    <td><b>Gender</b></td>
                    <td><b>:</b></td>
                    <td>
                        @if(isset($Employee[0]->GenderCode))
                            {{$Employee[0]->GenderCode??""}}
                        @endif
                    </td>
                </tr>
                
                <tr>
                    <td><b>Marital Status</b></td>
                    <td><b>:</b></td>
                    <td>
                        @if(isset($Employee[0]->MaritalStatusCode))
                            {{$Employee[0]->MaritalStatusCode??""}}
                        @endif
                    </td>
                </tr>
                
                <tr>
                    <td><b>Designation</b></td>
                    <td><b>:</b></td>
                    <td>
                        @if(isset($Employee[0]->Designation))
                            {{$Employee[0]->Designation??""}}
                        @endif
                    </td>
                </tr>
                
                <tr>
                    <td><b>Religion</b></td>
                    <td><b>:</b></td>
                    <td>
                        @if(isset($Employee[0]->ReligionCode))
                            {{$Employee[0]->ReligionCode??""}}
                        @endif
                    </td>
                </tr>
                
                <tr>
                    <td><b>Phone</b></td>
                    <td><b>:</b></td>
                    <td>
                        @if(isset($Employee[0]->Phone))
                            {{$Employee[0]->Phone??""}}
                        @endif
                    </td>
                </tr>
                
                <tr>
                    <td><b>National Id Number</b></td>
                    <td><b>:</b></td>
                    <td>
                        @if(isset($Employee[0]->NationalIdNumber))
                            {{$Employee[0]->NationalIdNumber??""}}
                        @endif
                    </td>
                </tr>
                
                <tr>
                    <td><b>Email</b></td>
                    <td><b>:</b></td>
                    <td>
                        @if(isset($Employee[0]->Email))
                            {{$Employee[0]->Email??""}}
                        @endif
                    </td>
                </tr>
                
                <tr>
                    <td><b>Employee Image</b></td>
                    <td><b>:</b></td>
                    <td>
                        @if(isset($Employee[0]->EmployeeImage))
                            <img src="{{$Employee[0]->EmployeeImage??''}}" width="auto" height="70" alt='Employee Image'>
                        @endif
                    </td>
                </tr>
                
                <tr>
                    <td><b>Employee Signature</b></td>
                    <td><b>:</b></td>
                    <td>
                        @if(isset($Employee[0]->EmployeeSignature))
                            <img src="{{$Employee[0]->EmployeeSignature??''}}" width="auto" height="70" alt='Employee Signature'>
                        @endif
                    </td>
                </tr>
                
            </tbody>
        </table>
    </div>
</div>