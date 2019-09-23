<div class="modal fade" id="assignToJobModal" tabindex="-1" role="dialog" aria-labelledby="assignToJobModalLabel">
   <div class="modal-dialog" role="document">
      <div class="modal-content" style="border-radius: 5px;margin-top: 35%;">
         <div class="modal-header"> 
            <span class="modal-title content-body" style="width: 100%;font-size: 16px;margin-bottom: 1%;" id="assignToJobModalLabel">Assign Candidates to Job</span>
         </div>
         <div class="modal-body">
            <span class="header-txt " style="margin-bottom: 2%;display: inline-block;width: 100%;color: #333;font-weight: 600;">
            You are assigning the selected candidates to jobs.
            </span>
            <form>
               <div class="form-group row" style="padding-left: 2%;padding-right: 2%;margin-top: -5%;">
               <label class="modal-labels"> ORGANIZATION </label>
                    <select id="assign_candidate_to_org" class="col-md-12 modal-input-sm " name="assign_candidate_to_org" style="font-size: 14px;">
                            @foreach($companies as $company)
                                <option class="" value="{{  $company["id"] }}"> {{  $company["company_name"]}}</option>
                            @endforeach
                        </select>
               </div>
               <div class="form-group row" style="padding-left: 2%;padding-right: 2%;margin-top: -5%;">
                <label class="modal-labels"> ROLE </label>
                  <select id="assign_candidate_to_org_section_role" class="col-md-12  modal-input-sm " name="assign_candidate_to_org_section_role" style="font-size: 14px;">
                             @foreach($default_org_section_role as $default_org_role)
                                <option class="" value="{{  $default_org_role["id"] }}"> {{  $default_org_role["title"]}}</option>
                            @endforeach
                  </select>
               </div>
            </form>
         </div>
         <div class="row" style="margin: 3%;">
            <button type="button" onclick="return onAssignCandidate()" class="col-md-12 btn btn bl-underb-btn bl-underb-txt"> Assign Candidates to this Job</button>
         </div>
      </div>
   </div>
