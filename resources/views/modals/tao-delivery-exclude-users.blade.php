
<div class="modal fade " id="deliverTestModalExcludeUsers" tabindex="-1" role="dialog" aria-labelledby="deliverTestModalExcludeUsers" style="padding-top: 20px;">
   <div class="modal-dialog" role="document">
      <div class="modal-content" style="
        margin-top: 25%;width: 517px;height: auto;border-radius: 6px;box-shadow: 0 3px 6px 2px rgba(0, 0, 0, 0.11);background-color: #fffefe;padding: 0 20px;">
         <div class="modal-header"> 

            <span class="modal-title content-body" style="width: 100%;font-size: 16px;margin-bottom: 1%;text-align: center;color: #0e0e0e;">Test Delivery</span>
         </div>
         <div class="modal-body">
          
            <label class="modal-labels" style="color:rgba(40, 40, 40, 0.74);height: 18px;"> Select Test Takers to Exclude</label>

            <div class="tt-rectangle-filter" style="">
               <div style="text-align: center;padding: 10px;">
                   <span id="selectedCandidateCount" style="font-size: 12px;text-align:center; color:#333bb9; line-height: 0.83;letter-spacing: -0.17px;"> No Test Takers Selected for Exclusion </span>
               </div>
               <div style="border-top: solid 0.8px #97979785;padding-top: 15px;">
                  <span class="col-md-12" style="display: inline-block;"> <img style="height:30px;padding: 5px;" src="{{asset('/auth_assets/images/search-tt.svg')}}" alt=""> <input id="search-tt-excluded" type="text" style="font-size: 14px;
                           font-weight: 500;
                           color: rgba(40, 40, 40, 0.44);
                     width: 80%;
                     " placeholder="Search for a Candidate to Exclude">  </span>
               </div>

               <div id="excludeTestTakersDynamic">
                  @if(count($tts) > 0)
                     @foreach ($tts->chunk(2) as $chunk)
                                  <div class="row" style="margin-left: 10px;margin-top: 20px;margin-bottom: 2%;">
                           @foreach($chunk as $tt)
                             <div class="col-md-5" style="margin-left: 2.5%;margin-right: 2.5%;">
                                 
                              <span>
                                  <input class="tt-excluded-input" style="transform: scale(1.3)"  value="{{ count($tt['test_groups']) > 0 ? $tt['test_groups'][0]['test_taker_uri'] : null}}" type="checkbox"> <img src="{{$tt->profile_image_url?? asset('/auth_assets/images/avatar.png') }}" alt="" style="height: 15px;border-radius: 50%;margin-left: 5px;">  </span> <span style="display: inline-block;margin: 0px 5px;"> {{ $tt->full_name }} </span> </div>
                              @endforeach
                           </div>
                     @endforeach
                  @endif
               </div>
            </div>
            
            <span class="modal-labels tt-delivery-footer">
               Excluded Candidates
            </span>
            
            @include('sections.tt-delivery-pagination')

         </div>
         <div class="row" style="margin: 3%;display: flex;align-items: center;justify-content: center;">
            <button type="button" class="btn delivery-confirm-btn" style="width: 150px;height: 30px;padding: 0px;font-size: 14px;" data-toggle="modal" data-dismiss="modal" data-target="#deliverTestModal"> 
               Back
            </button>
            <button type="button" onclick="return excludeUsers()" style="width: 150px;height: 32px;" class="btn btn bl-underb-btn bl-underb-txt">Confirm</button>
         </div>
      </div>
   </div>
</div>