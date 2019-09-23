import React, {useState} from "react";
import moment from "moment";
import emptyState from "../../../../public/assets/images/empty-state.svg";

function CandidateApplication({title,data}) {

    return (
        <div>

            <div className="card mt-4">
                <div className="px-4 px-xs-4 mx-0 py-3 border-bottom row">
                    <div className="font-weight-bold text-primary2 text-uppercase font-small">{title}
                    </div>
                </div>
                <div className="bg-grey-gradient  mb-3 application-container"
                     style={{maxHeight: '200px', 'overflowY': 'scroll'}}>
                    {data.length === 0 ?
                        <div className="text-center m-4">
                            <p className="text-grey">Your <span
                                className="text-lowercase">{title} will appear here.</span></p>
                            <img src={'./' + emptyState} style={{height: '64px'}}/>
                        </div>
                    : null}
                    {data.map((row, index) => {
                        return (
                            <div className="row mx-0 px-3 py-4 border-bottom" key={index}>
                                <div className="col-4 text-left">
                                    <a href={"job/"+row.job.slug}>
                                    <span className="text-dark font-medium ubuntu-medium">{row.job.title}</span>
                                    </a>
                                </div>
                                <div className="col-4 text-center">
                                    <span className="text-dark ubuntu-medium text-center">{moment(row.created_at).fromNow()}</span>
                                </div>
                                <div className="col-4 text-right">
                                    <a className="font-medium ubuntu-bold badge badge-success text-white font-small-xs">Applied</a>
                                </div>
                            </div>
                        )
                    })
                    }
                </div>
            </div>
            <div>
                <a href="" className="btn w-100 btn-primary-2 mt-4 ubuntu-bold text-capitalize">Find Jobs</a>

            </div>
        </div>

    );
}

export default CandidateApplication;
