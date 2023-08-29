<div class="col-md-8">
    <div class="table-responsive">
        <table class="table table-borderless">
            <tbody>
                <tr>
                    <td><b>Department Code </b></td>
                    <td><b>:</b></td>
                    <td>
                        @if(isset($RefDepartment[0]->DepartmentCode))
                            {{$RefDepartment[0]->DepartmentCode??""}}
                        @endif
                    </td>
                </tr>
                
                <tr>
                    <td><b>WorkPlace Name</b></td>
                    <td><b>:</b></td>
                    <td>
                        @if(isset($RefDepartment[0]->WorkPlaceName))
                            {{$RefDepartment[0]->WorkPlaceName??""}}
                        @endif
                    </td>
                </tr>

                <tr>
                    <td><b>Description</b></td>
                    <td><b>:</b></td>
                    <td>
                        @if(isset($RefDepartment[0]->Description))
                            {{$RefDepartment[0]->Description??""}}
                        @endif
                    </td>
                </tr>
                
            </tbody>
        </table>
    </div>
</div>