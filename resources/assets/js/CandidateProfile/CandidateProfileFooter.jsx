import React, {useState} from "react";
import arrowLeft from "../../../../public/assets/images/icon/chevron-left.svg";
import arrowRight from "../../../../public/assets/images/icon/chevron-right.svg";

function CandidateProfileFooter({info, index, tag, setIndex, canAdd, canAddEvent, canDeleteEvent, deleteUrl}) {
    const onClickPrev = e => {
        e.preventDefault();
        const prevIndex = index - 1;
        setIndex(Math.max(0, prevIndex));
    };

    const onClickNext = e => {
        e.preventDefault();
        const endIndex = info.length - 1;
        const nextIndex = index + 1;
        setIndex(Math.min(endIndex, nextIndex));
    };
    if (info.length === 1 && !canAdd) {
        return null;
    }
    let uuid_data = info[index] ? info[index].filter((info) => info.name === 'uuid') : [];
    let uuid = uuid_data.length > 0 ? uuid_data[0].value : undefined;
    // console.log(info);
    // canDeleteEvent(deleteUrl);
//    console.log(,"deleteUrl");
    return (
        <div>

            {info.length > 1 || canAdd ? (
                <div className="d-flex justify-content-center align-items-center py-2 px-2">
                    <div className="col-4  d-flex align-items-center">
                        {canDeleteEvent && deleteUrl && uuid ?
                            <a onClick={() => canDeleteEvent ? canDeleteEvent(deleteUrl + uuid) : () => {
                            }} className="text-danger cursor-pointer font-small font-weight-bold">Delete</a>
                            : null}
                    </div>
                    <div className="col-4  d-flex align-items-center">
                        {info.length > 1 ? (
                            <div className="col-12  d-flex align-items-center">
                                <div onClick={onClickPrev} className="cursor-pointer w-25">
                                    <img src={"./" + arrowLeft} alt="Left"/>
                                </div>
                                < p className="d-flex align-items-center justify-content-center text-grey font-x-small mb-0 w-50">
                                    {`${index + 1} / ${info.length}`}
                                </p>
                                <div onClick={onClickNext} className="cursor-pointer w-25">
                                    <img src={"./" + arrowRight} alt="Left"/>
                                </div>
                            </div>
                        ) : null}
                    </div>
                    <div className="col-4  d-flex align-items-center text-right">
                        <div className="w-100">
                            <a onClick={canAddEvent}
                               className="text-primary cursor-pointer font-small font-weight-bold text-capitalize">Add</a>
                        </div>
                    </div>
                    {/*<button onClick={onClickNext}>Add Something</button>*/}
                </div>
            ) : null}

        </div>
    );
    /*
            {canAdd ? (
                <div className="position-relative overflow-hidden d-inline-block w-100 text-center mb-1 mt-3">

                    <a onClick={canAddEvent}
                       className="btn text-capitalize rounded border cursor-pointer font-small font-weight-bold">
                        Add New
                    </a>
                </div>
            ) : null}
            */
}

export default CandidateProfileFooter;
