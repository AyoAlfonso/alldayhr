import React, {useState} from "react";


function CandidateProfileFooterSave({submitCallback, activeUrl, InputChange, data}) {
    const urlParams = new URLSearchParams(window.location.search);

    if (!InputChange) {
        return (
            <div>
                {
                    urlParams.has('slug') && data && data.job_requirements ?
                        <div className='row candidate-footer justify-content-end py-10 px-4'>
                            <a className="candidate-save-button-2 cursor-pointer col-12 col-sm-12 col-md-2 pr-20 px-0"
                               onClick={() => submitCallback(activeUrl)}>Save &
                                Continue</a>
                        </div>  :
                        <div className='row candidate-footer justify-content-end py-10 px-4'>
                            <a className="candidate-save-button-1 cursor-pointer col-12 col-sm-12 col-md-2 pr-20 px-0"
                               href={!urlParams.has('slug') ? "candidate/dashboard" : 'job/' + urlParams.get('slug') + '/apply'}>{!urlParams.has('slug') ? 'Back to Dashboard' : 'Continue Application'}</a>
                        </div>
                }
            </div>
        );
    }

    return (
        <div className='row candidate-footer justify-content-end py-10 px-4'>
            {urlParams.has('slug') && data && data.job_requirements ?
                null :
                <a className="candidate-save-button-2 cursor-pointer col-12 col-sm-12 col-md-2 pr-20 bg-transparent border text-white-force mb-xs-2"
                   onClick={() => submitCallback(activeUrl, true)}>
                    {urlParams.has('slug') ? 'Save & Continue Application' : 'Save & Continue Later'}</a>
            }

            {InputChange ?
                <a className="candidate-save-button-2 cursor-pointer col-12 col-sm-12 col-md-2 pr-20 px-0"
                   onClick={() => submitCallback(activeUrl)}>Save &
                    Continue</a>
                : null}
        </div>
    );
}

export default CandidateProfileFooterSave;




