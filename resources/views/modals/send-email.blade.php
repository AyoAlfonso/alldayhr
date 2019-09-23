<div class="modal fade" id="sendEmailJobModal" tabindex="-1" role="dialog" aria-labelledby="sendEmailJobModalLabel">
   <div class="modal-dialog" role="document">
      <div class="modal-content" style="border-radius: 5px;margin-top: 35%;">
         <div class="modal-header"> 
            <span class="modal-title content-body" style="width: 100%;font-size: 16px;margin-bottom: 1%;" id="sendEmailJobModalLabel">Send bulk Email</span>
         </div>
         <div class="modal-body">
            <span class="header-txt " style="margin-bottom: 2%;display: inline-block;width: 100%;color: #333;font-weight: 600;">
            You are sending an email to the selected candidates.
            </span>
            <form>
               <div class="form-group row" style="padding: 2%;">
                  <input type="text" class="form-control col-md-12 modal-input-sm " id="candidate-subject" placeholder="Enter Subject" style="font-size: 14px;  color: #212529;">
               </div>
               <div class="form-group row" style="padding: 2%">
                  <textarea class="form-control modal-input-lg" id="candidate-message" placeholder="Type your message here" style="
                     font-size: 14px;
                     color: #212529;" spellcheck="false"></textarea>
               </div>
            </form>
         </div>
         <div class="row" style="margin: 3%;">
            <button type="button" style="width: 450px;" onclick="return onSendCandidateEmail()" class="col-md-12 col-sm-12 btn btn bl-underb-btn bl-underb-txt">Send Email</button>
         </div>
      </div>
   </div>
</div>