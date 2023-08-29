<div class="col-md-10">
    <div class="table-responsive">
        <table class="table table-borderless">
            <tbody>
                <tr>
                    <td><b>InstructionCode</b></td>
                    <td><b>:</b></td>
                    <td>{{$refinstruction->InstructionCode??""}}</td>
                </tr>
                
                <tr>
                    <td><b>InstructionInEnglish</b></td>
                    <td><b>:</b></td>
                    <td>{{$refinstruction->InstructionInEnglish??""}}</td>
                </tr>
                
                <tr>
                    <td><b>InstructionInBangla</b></td>
                    <td><b>:</b></td>
                    <td>{{$refinstruction->InstructionInBangla??""}}</td>
                </tr>

                <tr>
                    <td><b>Description</b></td>
                    <td><b>:</b></td>
                    <td>{{$refinstruction->Description??""}}</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>