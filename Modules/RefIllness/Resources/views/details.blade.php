<div class="col-md-8">
    <div class="table-responsive">
        <table class="table table-borderless">
            <tbody>
                <tr>
                    <td><b>Illness Code</b></td>
                    <td><b>:</b></td>
                    <td>{{$Refillnesss->IllnessCode??""}}</td>
                </tr>

                <tr>
                    <td><b>HO Illness</b></td>
                    <td><b>:</b></td>
                    <td>{{$Refillnesss->HOIllness??""}}</td>
                </tr>

                <tr>
                    <td><b>Family HO</b></td>
                    <td><b>:</b></td>
                    <td>{{$Refillnesss->FamilyHO??""}}</td>
                </tr>

                <tr>
                    <td><b>Description</b></td>
                    <td><b>:</b></td>
                    <td>{{$Refillnesss->Description??""}}</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>