import React, {useState} from "react";


function Error({error}) {
    if (Array.isArray(error)) {
        return (
            <div className="alert alert-danger mt-3" role="alert">
                {error.map((err,indx) => <li key={indx}>{err}</li>)}
            </div>
        );
    }
    return (
        <div className="alert alert-danger mt-3" role="alert">
            {error}
        </div>
    );
}

export default Error;
