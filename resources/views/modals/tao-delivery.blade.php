
<div class="modal fade " id="deliverTestModal" tabindex="-1" role="dialog" aria-labelledby="deliverTestModal">
   <div class="modal-dialog" role="document">
      <div class="modal-content" style="
        margin-top: 35%;width: 517px;height: auto;border-radius: 6px;box-shadow: 0 3px 6px 2px rgba(0, 0, 0, 0.11);background-color: #fffefe;padding: 0 20px;">
         <div class="modal-header"> 
            <span class="modal-title content-body" style="width: 100%;font-size: 16px;margin-bottom: 1%;text-align: center;color: #0e0e0e;">Test Delivery</span>
         </div>
         <div class="modal-body">
            <form id="deliveryTestForm">
               <div class="form-group row" style="padding-left: 2%;padding-right: 2%;margin-top: -5%;">
                  <label class="modal-labels" style="color:rgba(40, 40, 40, 0.74);height: 18px;">Create a new Delivery</label>
                  <select id="available_tao_tests" class="col-md-12 modal-input-sm " name="available_tao_tests" style="font-size: 14px;height: 32px;border-radius: 4px;border: solid 1px #97979785;background-color: #e0ebff;color: #606060;">
                   {{-- @foreach($default_org_section_role as $default_org_role)
                                <option class="" value="{{  $default_org_role["id"] }}"> {{  $default_org_role["title"]}}</option>
                            @endforeach --}}
                            <option value="" > Select the Test to be delivered </option>
                </select>
               </div>
               <div class="form-group row" style="padding-left: 2%;padding-right: 2%;margin-top: 10%;border-top: 1px solid #e9ecef;">
                  <label class="modal-labels" style="color:   rgba(40, 40, 40, 0.74);height: 18px;">Test Title</label>
                  <input id="tao_delivery_title" class="col-md-12 modal-input-sm " placeholder="Name Test for Candidates" name="tao_delivery_title" style="font-size: 14px;height: 32px;border-radius: 4px;border: solid 1px #97979785;background-color: var(--white);">
                
               </div>
               <div class="row">
                  <div class="col-md-6">
                        <div style="padding-left: 2%;padding-right: 2%;margin-top: 10%;" class="form-group row tt-sd-container">
                        <label class="modal-labels" style="color: rgba(40, 40, 40, 0.74);height: 18px;">Start Date</label>
                        <input id="start_date" type="text" class="col-md-12 modal-input-sm" name="start_date" placeholder="Select Date" style="font-size: 14px;
                        height: 32px;border-radius: 4px;
                        border: solid 1px #97979785;
                        background-color: var(--white);
                        padding-right: 25px;">
                        <span style="position: absolute;right: 3px;width: 25px;height: 25px;pointer-events: none;margin-top: 40px;">
                        <button type="button">
                           <img
                             style="height:15px;"
                             src="{{asset('/auth_assets/images/calendar.svg')}}"
                            alt="calendar">

                        </button>
                        </span>
                     </div>
                  </div>
                  <div class="col-md-6">
                     <div style="padding-left: 2%;padding-right: 2%;margin-top: 10%;" class="form-group row tt-sd-container">
                        <label class="modal-labels" style="color:   rgba(40, 40, 40, 0.74);height: 18px;">End Date</label>
                        <input type="text" id="end_date" class="col-md-12 modal-input-sm " name="end_date" placeholder="Select Date" style="font-size: 14px;height: 32px;border-radius: 4px;border: solid 1px #97979785;background-color: var(--white);padding-right: 25px;">
                        <span style="position: absolute;right: 3px;width: 25px;height: 25px;pointer-events: none;margin-top: 40px;">
                        <button type="button">
                             <img
                             style="height:15px;"
                             src="{{asset('/auth_assets/images/calendar.svg')}}"
                            alt="calendar">
                        </button>
                        </span>
                     </div>
                  </div>
                  {{-- <div style="display:none" class="col-md-6">
                     <div class="form-group row" style="padding-left: 2%;padding-right: 2%;margin-top: 10%;">
                        <label class="modal-labels" style="color:   rgba(40, 40, 40, 0.74);height: 18px;">Allowed Test Attempts</label>
                        <input type="number" id="delivery_test_attempts" class="col-md-12 modal-input-sm" placeholder="Enter Number" name="delivery_test_attempts" style="font-size: 14px;height: 32px;border-radius: 4px;border: solid 1px #97979785;background-color: var(--white);">
                       
                     </div>
                  </div> --}}
               </div>
            </form>
         </div>
         <div class="row" style="margin: 3%;display: flex;align-items: center;justify-content: center;">
            <button type="button" onclick="return onDeliverTest()" style="width: 150px;height: 32px;" class="btn btn bl-underb-btn bl-underb-txt">Save and Continue</button>
         </div>
      </div>
   </div>
</div>