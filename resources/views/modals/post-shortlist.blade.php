<div style="display:none" id="hiddenJobTitleFiltered"> </div>
<div style="display:none" id="hiddenIndustryFiltered"> </div>
<div style="display:none" id="hiddenCompaniesFiltered"> </div>
<div style="display:none" id="hiddenSkillsFiltered"> </div>
<div style="display:none" id="hiddenUniversityFiltered"> </div>
<div style="display:none" id="hiddenCandidateCourseFiltered"> </div>
<div style="display:none" id="hiddenCandidateDegreesFiltered"> </div>
<div style="display:none" id="hiddenCandidateQualificationsFiltered"> </div>
<div style="display:none" id="hiddenStateOfOriginFiltered"> </div>
<div style="display:none" id="hiddenCandidateResidentialStateFiltered"> </div>
<div style="display:none" id="hiddenCandidateCertificationsFiltered"> </div>

<div>
   <div style="display: block;">
   <div class="left-col-filter col-md-6">
   <div style="padding-bottom: 5%;">
      <label class="modal-labels"> JOB TITLES </label>
      <input type="text" autocomplete="nope" name="candidate_job_title_input" list="candidate_job_title" multiple class="flexdatalist col-md-12 shortlistInput modal-input-sm" placeholder="Product Designer" style="font-size: 14px;">
     
      {{-- <datalist id="candidate_job_title">
         @foreach($employee_job_titles as $jobtitle)
            <option style="border-radius: 5px;background-color: #ECF0FF;
            " value="{{$jobtitle["Occupation"] }}"> {{$jobtitle["Occupation"] }}  </option>
         @endforeach
      </datalist> --}}

      <label class="modal-labels"> COMPANIES </label>
      <input type="text" autocomplete="nope" name="candidate_companies_input" multiple class="flexdatalist col-md-12 shortlistInput modal-input-sm" list="candidate_companies" placeholder="Deloitte" style="font-size: 14px;">
    
      {{-- <datalist id="candidate_companies">
         @foreach($nigerian_employers as $nigerian_employer)
            <option style="border-radius: 5px;background-color: #ECF0FF;" value="{{$nigerian_employer["Name"] }}"> {{$nigerian_employer["Name"] }}  </option>
         @endforeach
      </datalist> --}}

      <label class="modal-labels"> SKILLS </label>
      <input type="text" autocomplete="nope" name="candidate_skills_input"  multiple class="flexdatalist col-md-12 shortlistInput modal-input-sm" list="candidate_skills"  placeholder="Sales and Marketing" style="font-size: 14px;">
      {{-- <datalist id="candidate_skills">
         @foreach($employee_skills as $employee_skill)
            <option style="border-radius: 5px;background-color: #ECF0FF;" value="{{$employee_skill["skill_name"] }}"> {{$employee_skill["skill_name"] }}  </option>
         @endforeach
      </datalist> --}}
   </div>
   </div>
   <div class="right-col-filter col-md-6">
      <div style="padding-bottom: 5%;">
         <label class="modal-labels"> INDUSTRY </label>
         <input type="text" autocomplete="nope" name="candidate_industry_input" multiple class="flexdatalist col-md-12 shortlistInput modal-input-sm" list="candidate_industry"  placeholder="Oil &amp; gas" style="font-size: 14px;">
        
         <datalist id="candidate_industry">
            <option style="border-radius: 5px;background-color: #ECF0FF;" value="finance"> Finance </option>
            <option style="border-radius: 5px;background-color: #ECF0FF;" value="consumer_services">  Consumer services </option>
            <option style="border-radius: 5px;background-color: #ECF0FF;" value="industrials"> Industrials </option>
            <option style="border-radius: 5px;background-color: #ECF0FF;" value="oil_and_gas"> Oil &amp; gas </option>
            <option style="border-radius: 5px;background-color: #ECF0FF;" value="conglomerates"> Conglomerates </option>
            <option style="border-radius: 5px;background-color: #ECF0FF;" value="airlines"> Airlines </option>
            <option style="border-radius: 5px;background-color: #ECF0FF;" value="media"> Media </option>
            <option style="border-radius: 5px;background-color: #ECF0FF;" value="banks"> Banks </option>
            <option style="border-radius: 5px;background-color: #ECF0FF;" value="insurance"> Insurance </option>
            <option style="border-radius: 5px;background-color: #ECF0FF;" value="publishing"> Publishing </option>
            <option style="border-radius: 5px;background-color: #ECF0FF;" value="investment_service "> Investment service </option>
            <option style="border-radius: 5px;background-color: #ECF0FF;" value="Accounting "> Accounting </option>
            @foreach($employee_job_industry as $industry)
               <option style="border-radius: 5px;background-color: #ECF0FF;" value="{{  $industry["industry"] }}"> {{ $industry["industry"] }}</option>
            @endforeach
         </datalist>
          <label class="modal-labels"> EXPERIENCE LEVEL </label>
        <div>
          <div class="left-col-filter col-md-5">
         <input type="number"  pattern="[0-9]{10}" class="form-control col-md-6 shortlistInput modal-input-sm r-input" id="candidate_experience_lower_bound" name="candidate_experience_lower_bound"  placeholder="" style="font-size: 14px;height: 35px;">
          </div>

           <div class="col-md-*" style="padding-top: 2.5%;display: inline-block;" >
             to
          </div>
           <div class="right-col-filter col-md-5">
         <input type="number"  pattern="[0-9]{10}" class="form-control col-md-6 shortlistInput modal-input-sm r-input" id="candidate_experience_higher_bound" name="candidate_experience_higher_bound"  placeholder="" style="font-size: 14px;height: 35px;">
          </div>

        
      </div>
   </div>
   </div>

   <div style="display: inline-block;">
      <div class="left-col-filter col-md-6">
         <div style="padding-bottom: 5%;">
            <label class="modal-labels"> UNIVERSITY </label>
            <input type="text" autocomplete="nope" name="candidate_university_input" list="candidate_university" multiple class="flexdatalist col-md-12 shortlistInput modal-input-sm" placeholder="Select University"  style="font-size: 14px;">
            <datalist id="candidate_university" style="font-size: 14px;" placeholder="Select University">
            @foreach($universities as $uni)
                <option class="" value="{{  $uni["university"] }}"> {{ $uni["university"] }}</option>
            @endforeach
        </datalist>

     <label class="modal-labels"> COURSE OF STUDY </label>
        <input type="text" autocomplete="nope" name="candidate_course_input"  multiple class="flexdatalist col-md-12 shortlistInput modal-input-sm" list="candidate_course_type" placeholder="Select Higher Education Course " style="font-size: 14px;">
          {{-- <datalist id="candidate_course_type"  style="font-size: 14px;" placeholder="Select Course of Study">
            @foreach($university_courses as $course)
            <option class="" value="{{  $course["Major"] }}"> {{ $course["Major"] }}</option>
            @endforeach
         </datalist> --}}

            <label class="modal-labels"> CLASS OF DEGREE  </label>
            <input type="text" autocomplete="nope" name="candidate_degree_input" multiple class="flexdatalist col-md-12 shortlistInput modal-input-sm" list="candidate_degree"  placeholder="Select University Distinction" style="font-size: 14px;">
             <datalist id="candidate_degree" style="font-size: 14px;" placeholder="Select Class of degree" >
                <option value="Distinction"> Distinction  </option>
                <option value="First Class">  First Class </option>
                 <option value="Second Class Upper"> Second Class Upper</option>
                 <option value="Upper Credit">Upper Credit</option>
                 <option value="Pass"> Pass</option>
                 <option value="Second Class Lower"> Second Class Lower</option>
                 <option value="Merit"> Merit </option>
                 <option value="Lower Credit"> Lower Credit</option>
                 <option value="Third Class"> Third Class</option>
                 <option value="Fail"> Fail </option>
                <option value="Others"> Others </option>
             </datalist>
         </div>
      </div>
      <div class="right-col-filter col-md-6">
         <div style="padding-bottom: 5%;">

            <label class="modal-labels"> QUALIFICATION </label>
            <input type="text" name="candidate_qualification_input" multiple class="flexdatalist col-md-12 shortlistInput modal-input-sm" list="candidate_qualification"  placeholder="Select Qualification Level" style="font-size: 14px;">
            <datalist id="candidate_qualification" style="font-size: 14px;">
               <option value="Diploma"> Diploma  </option>
               <option value="OND"> OND </option>
               <option value="HND"> HND </option>
               <option value="B.Sc"> B.Sc </option>
                <option value="P.gd"> P.gd </option>
                <option value="NCE"> NCE </option>
                <option value="B.A"> B.A </option>
                <option value="B.Ed"> B.Ed </option>
                <option value="M.Phil"> M.phil  </option>
                <option value="M.A"> M.A </option>
                <option value="M.Sc"> M.Sc </option>
                <option value="Ph.D"> Ph.D </option>
                <option value="Prof"> Professor  </option>
        </datalist>

           <label class="modal-labels">  AGE RANGE </label>
          <div>
          <div class="left-col-filter col-md-5">
             <input type="number"  pattern="[0-9]{10}" class="form-control col-md-6 shortlistInput modal-input-sm r-input" id="candidate_age_lower_bound" name="candidate_age_lower_bound" style="font-size: 14px;height: 35px;">
          </div>
          <div class="col-md-*" style="padding-top: 2.5%;display: inline-block;" >
             to
          </div>
          <div class="right-col-filter col-md-5">
             <input type="number"  pattern="[0-9]{10}" class="form-control col-md-6 shortlistInput modal-input-sm r-input" id="candidate_age_higher_bound" name="candidate_age_higher_bound" style="font-size: 14px;height: 35px;">
          </div>
       </div>

            <label class="modal-labels"> RESIDENTIAL STATE  </label>
            <input type="text" autocomplete="nope" name="candidate_state_input" list="candidate_state" list="candidate_state" multiple class="flexdatalist col-md-12 shortlistInput modal-input-sm" placeholder="Select Residence State" style="font-size: 14px;">
            <datalist id="candidate_state" style="font-size: 14px;">
              @foreach($states as $state)
                 <option class="" value="{{  $state["State"] }}"> {{ $state["State"] }}</option>
              @endforeach
           </datalist>
         </div>
      </div>
   </div>

       <div style="display: inline-block;">
           <div class="left-col-filter col-md-6">
               <div style="padding-bottom: 5%;">
                   <label class="modal-labels"> STATE OF ORIGIN </label>
                   <input type="text" autocomplete="nope" name="candidate_state_of_origin_input" list="candidate_state_of_origin" multiple class="flexdatalist col-md-12 shortlistInput modal-input-sm" placeholder="Select State of Origin"  style="font-size: 14px;">
                   <datalist id="candidate_state_of_origin" style="font-size: 14px;" placeholder="Select State of Origin">
                       @foreach($states as $state)
                         <option class="" value="{{  $state["State"] }}"> {{ $state["State"] }}</option>
                       @endforeach
                   </datalist>

                   <label class="modal-labels"> CERTIFICATIONS </label>
                   <input type="text" autocomplete="nope" name="candidate_certifications_input" multiple class="flexdatalist col-md-12 shortlistInput modal-input-sm" list="candidate_certifications" placeholder="Select Higher Education Course " style="font-size: 14px;">
                   {{-- <datalist id="candidate_certifications"  style="font-size: 14px;" placeholder="Select Course of Study">
                       @foreach($employee_certifications as $certification)
                           <option class="" value="{{   $certification["certifications"]}}"> {{ $certification["certifications"] }}</option>
                       @endforeach
                   </datalist> --}}
               </div>
           </div>
           <div class="right-col-filter col-md-6">
               <div style="padding-bottom: 5%;">
                  <label class="modal-labels"> O-LEVEL SCORE RANGE </label>
                   <div>
                       <div class="left-col-filter col-md-5">
                           <input type="number"  pattern="[0-9]{10}" class="form-control col-md-6 shortlistInput modal-input-sm r-input" id="olevel_lower_bound" name="olevel_lower_bound" style="font-size: 14px;height: 35px;">
                       </div>
                       <div class="col-md-*" style="padding-top: 2.5%;display: inline-block;" >
                           to
                       </div>
                       <div class="right-col-filter col-md-5">
                           <input type="number" pattern="[0-9]{10}" class="form-control col-md-6 shortlistInput modal-input-sm r-input" id="olevel_higher_bound" name="olevel_higher_bound" style="font-size: 14px;height: 35px;">
                       </div>
                   </div>

                   <label class="modal-labels"> RELEVANT YEARS OF EXPERIENCE </label>
                   <div>
                       <div class="left-col-filter col-md-5">
                           <input type="number"  pattern="[0-9]{10}" class="form-control col-md-6 shortlistInput modal-input-sm r-input" id="relevant_experience_lower_bound" name="relevant_experience_lower_bound" style="font-size: 14px;height: 35px;">
                       </div>
                       <div class="col-md-*" style="padding-top: 2.5%;display: inline-block;" >
                           to
                       </div>
                       <div class="right-col-filter col-md-5">
                           <input type="number"  pattern="[0-9]{10}" class="form-control col-md-6 shortlistInput modal-input-sm r-input" id="relevant_experience_higher_bound" name="relevant_experience_higher_bound" style="font-size: 14px;height: 35px;">
                       </div>
                   </div>
               </div>
           </div>
       </div>
</div>
 <div style="color: #999;" class="">
     <div style="margin-left:10%">
          <span> <input id="nysc_status"  class="shortlistInput" type="checkbox" name="nysc_strict_result" value=""> </span>
           <span> Show only candidates with completion/exemption certificates from NYSC </span>
     </div>
     @if($singleEntityId)
     <div class="" style="margin-top: 3%;">
        <span style="margin: 5%;padding: 5%;" class="col-md-6">
            <a id="saveShortListingFilterId" data-active="false" onclick="saveshortlistingfilter();return false;" style="color: white; padding: 10px 10px;padding-right: 50px;padding-left: 50px;" class="col-md-12 btn btn bl-underb-btn bl-underb-txt"> Save Shortlist Settings</a>
        </span>
        <span style="" class="col-md-6">
          <a onclick="applyShortlistingFilter();return false;" style="color: white; padding: 10px 10px;padding-right: 10px;padding-left: 10px;padding-right: 50px;padding-left: 50px;" class="col-md-12 btn btn bl-underb-btn bl-underb-txt"> Apply Shortlist Settings</a>
        </span>
      </div>
      @else
      <span style="" class="col-md-12">
        <a onclick="applyShortlistingFilter();return false;" style="color: white;margin-top: 2%;padding: 10px 10px;width: 100%;" class="col-md-12 btn btn bl-underb-btn bl-underb-txt"> Apply Shortlist Settings</a>
      </span>
      @endif
   
    </div>
</div>
