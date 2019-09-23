import React, {useState} from "react";


function Success({message}) {
    if (Array.isArray(message)) {
        return (
            <div className="alert alert-success mt-3" role="alert">
                {message.map((mgs,indx) => <li key={indx}>{mgs}</li>)}
            </div>
        );
    }
    return (
        <div className="alert alert-success mt-3" role="alert">

            {message}
        </div>
    );
}

export default Success;
