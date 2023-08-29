<div class="col-md-12">
   <!-- slider start here -->
   <section id="prescription">
      <div class="container px-4">
        <header class="header">
          <p class="mb-0 pt-2 fs-"><b>Location :</b> Ukhia Upazila</p>
          @foreach($prescriptionCreation as $pc)
          <h4 class="mb-0 p-3 text-center">Prescription {{ $pc->PrescriptionId }}</h4>
          @endforeach
        </header>
        <div class="topHeading border border-start-0 border-end-0 py-2">
          @foreach($patientDetails as $patientDetail)
          <span class="me-3"><b>Name :</b> {{ $patientDetail->GivenName }} {{ $patientDetail->FamilyName }}</span>
          <span class="me-3"><b>Reg. No. :</b> {{ $patientDetail->RegistrationId }}</span>
          <span class="me-3"><b>Age :</b> {{ $patientDetail->Age }}</span>
          <span class="me-3"><b>Gender :</b> {{ $patientDetail->Gender->GenderCode }}</span>
          @endforeach
          @foreach($prescriptionCreation as $pc)
          <span class="me-3"><b>Date :</b> {{ date('d-m-Y', strtotime($pc->CreateDate)) }}</span>
          @endforeach
          
        </div>

        <div class="d-flex">
          <aside class="aside">
            <div class="item pt-3">
              <b class="d-block mb-0 py-2 border-bottom">Complaints</b>
              @foreach($Complaints as $Complaint)
              <p class="mb-0 mt-2 pe-2">{{ date('d-m-Y', strtotime($Complaint->CreateDate)) }}: {{ $Complaint->ChiefComplain }} for {{ $Complaint->CCDurationValue }} {{ $Complaint->DurationInEnglish }}</p>
              @endforeach
            </div>
            <div class="item pt-3">
              <b class="d-block mb-0 py-2 border-bottom">O/E</b>
              @foreach($HeightWeight as $hw)
              <p class="mb-0 mt-2 pe-2">Height: {{ $hw->Height }} cm</p>
              <p class="mb-0 mt-2 pe-2">Weight: {{ $hw->Weight }} kg</p>
              <p class="mb-0 mt-2 pe-2">BMI: {{ $hw->BMI }}</p>
              @endforeach
              @foreach($BP as $bps)
              <p class="mb-0 mt-2 pe-2">Pulse: {{$bps->HeartRate}}</p>
              <p class="mb-0 mt-2 pe-2">Blood Pressure: {{$bps->BPSystolic1}}/{{$bps->BPDiastolic1}} mmHg</p>
              @endforeach
              @foreach($GlucoseHb as $GHB)
              <p class="mb-0 mt-2 pe-2">RBG: {{$GHB->RBG}} mMol</p>
              <p class="mb-0 mt-2 pe-2">FBG: {{$GHB->FBG}} mMol</p>
              <p class="mb-0 mt-2 pe-2">Hemoglobin: {{$GHB->Hemoglobin}} g/dL</p>
              @endforeach
            </div>
            <div class="item pt-3">
              <b class="d-block mb-0 py-2 border-bottom">Provisional Dx</b>
              @foreach($ProvisionalDx as $key => $PDX)
              

              <p class="mb-0 mt-2  pe-2">Date: {{ date('d-m-Y', strtotime($PDX->CreateDate)) }}</p>
              <p class="mb-0 mt-2  pe-2">{{ ++$key }}.{{ $PDX->ProvisionalDiagnosis !="" ? $PDX->ProvisionalDiagnosis : $PDX->OtherProvisionalDiagnosis }} [
                <?php if($PDX->DiagnosisStatus == "P"){?>
                  Presumptive
                  <?php }elseif($PDX->DiagnosisStatus == "C"){?>
                    Confirmed
                  <?php }else{?>
                    Unspecified
                  <?php } ?>
                ]</p>
              @endforeach
            </div>
            
            <div class="item pt-3">
              <b class="d-block mb-0 py-2 border-bottom">Lab Investigations</b>
              @foreach($Investigation as $key => $IGS)
              <p class="mb-0 mt-2 pe-2">{{ ++$key }}. {{ $IGS->Investigation !="" ? $IGS->Investigation : $IGS->OtherInvestigation }}</p>
              @endforeach
            </div>
          </aside>
          <div class="rightSide position-relative w-100 py-3 px-4">
            <h2 class="mb-4">℞</h2>
            <div class="medicine mb-4">
              @foreach($Treatment as $key => $TMS)
              <p class="mb-0"><b>{{ ++$key }} .</b> {{ $TMS->Description }}</p>
              <i>{{ $TMS->Frequency }} - {{ $TMS->SpecialInstruction }}


<?php 

   $durationValue = $TMS->DrugDurationValue;
    if (stripos($durationValue, 'd') !== false || stripos($durationValue, 'D') !== false) {
        $durationValue = str_ireplace('d', ' দিন', $durationValue);
        echo $durationValue;
    } elseif (stripos($durationValue, 'm') !== false || stripos($durationValue, 'M') !== false) {
        $durationValue = str_ireplace('m', ' মাস', $durationValue);
    } elseif (stripos($durationValue, 'y') !== false || stripos($durationValue, 'Y') !== false) {
        $durationValue = str_ireplace('y', ' বছর', $durationValue);
    } elseif (stripos($durationValue, 'c') !== false || stripos($durationValue, 'C') !== false) {
        $durationValue = str_ireplace('c', ' চলবে', $durationValue);
    }


?>

              </i>
              @endforeach
            </div>

            <div class="nextinfo">
              <div class="medicine mb-4">
                <p class="mb-0"><b>Follow-up / পরবর্তী সাক্ষাৎ</b></p>
                @foreach($FollowUpDate as $key =>$FD)
                <p class="mb-0"><b>{{ ++$key }} . </b>{{ date('d-m-Y', strtotime($FD->FollowUpDate)) }}: {{ $FD->Comment }}</p>
                @endforeach
              </div>
              <div class="medicine mb-4">
                <p class="mb-0"><b>Advice / পরামর্শ</b></p>
                @foreach($Advice as $key =>$AS)
                <p class="mb-0"><b>{{ ++$key }} . </b>{{$AS->AdviceInBangla}}</p>
                @endforeach
               
              </div>
              <div class="medicine mb-4">
                <p class="mb-0"><b>Referral / রেফারেল</b></p>
                @foreach($PatientReferral as $key =>$PRF)
                <p class="mb-0"><b>{{ ++$key }} . </b>{{ date('d-m-Y', strtotime($PRF->CreateDate)) }}:{{ $PRF->Description }}, {{ $PRF->HealthCenterName }}</p>
                @endforeach
              </div>
            </div>

            <div class="signatureBox text-center">
            @foreach($prescriptionCreation as $pc)
              @if($pc->EmployeeSignature != null)
              <img
                src="{{ $pc->EmployeeSignature }}"
                alt="img"
                class="signatureImage"
              />
              @endif
              <p class="mb-0">{{ $pc->FirstName }} {{ $pc->LastName }}</p>
              <i class="my-0">{{ $pc->Designation }}</i>
          @endforeach
              
            </div>
          </div>
        </div>

        <footer class="footer d-flex justify-content-between">
          <address class="mb-0">
            <p class="mb-0">Haefa USA</p>
            <p class="mb-0">311 Bedford St, Lexington MA 07420, USA</p>
            <p class="mb-0">Email: healthonwheels.usa@gmail.com</p>
            <p class="mb-0">Website: www.healthonwheels.usa.org</p>
          </address>
          <address class="mb-0">
            <p class="mb-0">Haefa Bangladesh</p>
            <p class="mb-0">House: 31, Road: 16 Sector: 13 Uttara</p>
            <p class="mb-0">Email: healthonwheels.usa@gmail.com</p>
            <p class="mb-0">Website: www.healthonwheels.usa.org</p>
          </address>
        </footer>
        <p class="mb-0 text-center pb-4 logoText">
          Powered By:
          <img src="{{ asset('storage/logo/apilogo.png') }}" alt="img" class="apiLogo" />
        </p>
      </div>
    </section>
    <!-- slider end here -->
</div>