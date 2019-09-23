 <span class="modal-labels tt-delivery-footer">
    <span id="tt-delivery-pginfo"> Showing Page {{$tts->currentPage()}} of {{$tts->total()}} test takers </span>
               <div id="tt-delivery-pagination" class="show tt-delivery-pagination" style="display: inline-block;">
                  {{$tts->links('vendor.pagination.tt-delivery')}}
               </div>
 </span>