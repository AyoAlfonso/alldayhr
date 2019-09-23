import React, {useState} from "react";
import ReactDOM from "react-dom";
import Loader from "../components/Loader";
import {ToastContainer} from "react-toastify";
import profile_image from "../../../../public/assets/images/profile_placeholder2.png";
import pen_icon from "../../../../public/assets/images/Icon_ Pen.png";
import Tabs from "../components/Tab";
import CandidateApplication from "./CandidateApplication";
import {useDataApi} from "../utils/fetch";

const CandidateDashboard = () => {

    const {isLoading, error, data, refresh, fetchData, success, message} = useDataApi(
        "api/v1/candidate/profile",
        null,
        null,
        {showSuccess: false},
    );
    const [activeTab, setActiveTab] = useState("Saved ("+(data ? data.user.all_job_applications.saved.length : '0')+")");
    if (isLoading) {
        return <Loader/>;
    }

    return (
        <div className="px-xs-1">
            <div className="container profile-container ">
                <div className="row pt-4 flex-column align-items-center justify-content-center">
                    <div className=" w-50 w-sm-100">
                        <div className="card mb-2">
                            <div className="px-4-half px-xs-4 py-3 row">
                                <div className="col-8">
                                    <div className="text-grey ubuntu-medium ">Hello,</div>
                                    <p className="m-0 text-primary2 font-weight-bold ubuntu-bold font-large">
                                        {data.user.user.firstname+' '+data.user.user.lastname}
                                    </p>
                                    <p className={(data.user.completion_rate >= 50 ? 'text-success-2' : 'text-danger')+"   ubuntu-bold"}>Your profile is {data.user.completion_rate}% complete</p>
                                </div>
                                <div className="col-4 p-0 text-right">
                                    <div className="profile-img-border rounded-circle  border border-white">
                                        <span className="image-cover rounded-circle">
                                        <img
                                            className="img-responsive  rounded-circle object-fit-cover"
                                            width="68"
                                            height="68"
                                            src={data && data.user.profile_image_url ? data.user.profile_image_url : "./" + profile_image}
                                            alt="profile image"
                                        />
                                        </span>

                                    </div>
                                </div>
                            </div>
                            <div className="row mx-0">
                                <div className="col-4 border-right pl-5 pl-4-half pl-xs-4 text-left">
                                    <span className="text-grey text-uppercase font-small-xs">Experience</span>
                                    <p className="font-small"><b>{data.user.experience_level !== null ? data.user.experience_level+' Year(s)' : 'N/A'}</b></p>
                                </div>

                                <div className="col-4 border-right pl-5 pl-xs-3 text-left" >
                                    <span className="text-grey text-uppercase font-small-xs">Qualification</span>
                                    <p className="font-small"><b>{data.user.education ? data.user.education.map((edu,index)=>{
                                        return edu.qualification+(data.user.education.length === index + 1 ? '' : ' | ')
                                    }): 'N/A'}</b></p>
                                </div>
                                <div className="col-4 pl-5 pl-4-half pl-xs-4 text-center">
                                    <span className="text-grey text-uppercase font-small-xs">Location</span>
                                    <p className="font-small"><b>{data.user.residence_state}</b></p>
                                </div>
                            </div>
                            <div className="px-5 border-top py-3 text-center">
                                <a href="candidate/profile" className="text-grey font-weight-bold"><img
                                    src={"./" + pen_icon}/> Complete your profile</a>
                            </div>
                        </div>

                        <Tabs hideFooterSave="false" activeTab={activeTab} data={data} setActiveTab={setActiveTab}>
                            <div label={"Saved ("+data.user.all_job_applications.saved.length+")"}>
                                <CandidateApplication title="Saved Job Applications" data={data.user && data.user.all_job_applications.saved}/>
                            </div>
                            <div label={"Applied ("+data.user.all_job_applications.applied.length+")"}>
                                <CandidateApplication title="Submitted Job Applications" data={data.user.all_job_applications.applied}/>
                            </div>

                            <div label={"Bookmarked ("+data.user.all_job_applications.bookmarked.length+")"} >
                                <CandidateApplication title="Bookmarked Jobs" data={data.user && data.user.all_job_applications.bookmarked}/>
                            </div>

                        </Tabs>

                    </div>

                </div>
            </div>

        </div>
    )
};

if (document.getElementById("candidate-dashboard")) {
    ReactDOM.render(<CandidateDashboard/>, document.getElementById("candidate-dashboard"));
}
