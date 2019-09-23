import emptyState from "../../../../../public/assets/images/empty-state.svg";
import React, {useState} from "react";


function EmptyState({title}) {
 return (
     <div className="bg-grey-gradient">
         <div className="text-center m-4">
             <p className="text-grey">Your <span className="text-lowercase">{title} will appear here.</span>
             </p>
             <img src={'./' + emptyState} style={{height: '64px'}}/>
         </div>
     </div>
 );
}

export default EmptyState;

