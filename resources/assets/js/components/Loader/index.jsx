import React, {useState} from "react";


function Loader() {
    return (
        <div className="flex text-center" style={
            {
                marginTop: "5em",
                fontSize: "40px",
                color: "#8d87d9"
            }}>
            <i className="fa fa-circle-o-notch fa-spin"></i>
        </div>
    );
}

export default Loader;
