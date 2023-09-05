<div class="col-md-8">
    <div class="table-responsive">
        <table class="table table-borderless">
            <tbody>
                <tr>
                    <td><b>Designation Title </b></td>
                    <td><b>:</b></td>
                    <td>
                        @if(isset($RefDesignation[0]->DesignationTitle))
                            {{$RefDesignation[0]->DesignationTitle??""}}
                        @endif
                    </td>
                </tr>
                
                <tr>
                    <td><b>WorkPlace Name</b></td>
                    <td><b>:</b></td>
                    <td>
                        @if(isset($RefDesignation[0]->WorkPlaceName))
                            {{$RefDesignation[0]->WorkPlaceName??""}}
                        @endif
                    </td>
                </tr>
                
                <tr>
                    <td><b>Department Code</b></td>
                    <td><b>:</b></td>
                    <td>
                        @if(isset($RefDesignation[0]->DepartmentCode))
                            {{$RefDesignation[0]->DepartmentCode??""}}
                        @endif
                    </td>
                </tr>

                <tr>
                    <td><b>Description</b></td>
                    <td><b>:</b></td>
                    <td>
                        @if(isset($RefDesignation[0]->Description))
                            {{$RefDesignation[0]->Description??""}}
                        @endif
                    </td>
                </tr>
                
            </tbody>
        </table>
    </div>
</div>