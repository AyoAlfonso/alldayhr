import React, {useEffect, useState} from "react";
import ReactDOM from "react-dom";
import Tabs from "../components/Tab";
import CandidateInfoSection from "./CandidateInfoSection";
import {skills, defaultValues, generateKeyValue} from "./mockData";
import resumeIcon from "../../../../public/assets/images/icon/icon_brief.png";
import profile_image from "../../../../public/assets/images/profile_placeholder2.png";
import {useDataApi} from "../utils/fetch";
import Modal from "../components/Modal";
import Loader from "../components/Loader";
import Error from "../components/Error";
import Success from "../components/Success";
import Select from 'react-select';
import {formatDocTypes} from "../utils/helpers";
import {ToastContainer, toast} from 'react-toastify';
import 'react-toastify/dist/ReactToastify.css';
import emptyState from "../../../../public/assets/images/empty-state.svg";
import EmptyState from "../components/EmptyState";
import CandidateProfileDetail from "./CandidateProfileDetail";


const CandidateProfile = () => {
    let profileInput;
    const initialModalState = {show: false, url: null, tag: null, defaultValues: null, title: null}
    const [fileName, setFileName] = useState("");
    const [InputChange, setInputChange] = useState(false);
    const [alertChanges, setAlertChanges] = useState("");
    const [docFileName, setDocFileName] = useState("");
    const [activeTabMain, setActiveTabMain] = useState("");
    const [activeTabUrlMain, setActiveTabUrlMain] = useState("");
    const [activeTab, setActiveTab] = useState(activeTabMain ? activeTabMain : 'Resume');
    const [modalFetch, setModalFetch] = useState(false);
    const [modalContent, setModalContent] = useState(initialModalState);
    const urlParams = new URLSearchParams(window.location.search);
    const {isLoading, error, data, refresh, fetchData, success, message} = useDataApi(
        "api/v1/candidate/profile" + (!urlParams.has('slug') ? "" : "?job_slug=" + urlParams.get('slug')),
        null,
        null,
        {showSuccess: false},
    );

    if (alertChanges) {
        toast.error('Click save to continue!', {
            position: "bottom-right",
            autoClose: 5000,
            hideProgressBar: true,
            closeOnClick: true,
            pauseOnHover: true,
            draggable: true,
        });
    }
    const onDocFileChange = e => {
        setInputChange(true);
        const {name, files, value} = e.target;
        if (name === "doc_file" && files.length > 0) {
            setDocFileName(files[0].name);
        } else {
            setDocFileName(value);
        }
    };
    const onFileChange = e => {
        setInputChange(true);

        const {name, files, value} = e.target;
        if (name === "cv_file" && files.length > 0) {
            setFileName(files[0].name);
        } else {
            setFileName(value);
        }
    };

    const generateInfo = (data, type) => {
        const result = [];
        const arrayOfData = !Array.isArray(data) ? [data] : data;
        for (const value of arrayOfData) {
            result.push(generateKeyValue(value, type));
        }
        return result;
    };

    const addNewModal = (url, tag, defaultValues, title) => {
        setModalFetch(false);
        this.error = null;
        setModalContent({show: true, url: url, tag: tag, defaultValues: defaultValues, title: title});
    }

    const closeModal = e => {
        setModalContent(initialModalState);
    }
    const handleModalSubmit = async (url, event) => {
        event.preventDefault();
        setFileName(null);
        setModalFetch(true);
        setInputChange(false);
        setAlertChanges(false);
        const form = event.target;
        const mdata = new FormData(form);
        fetchData(url + (!urlParams.has('slug') ? "" : "?job_slug=" + urlParams.get('slug')), {method: "post", data: mdata});
    }

    const footerSubmitCallback = (redirect, response) => {

        if (redirect) {
            const urlParams = new URLSearchParams(window.location.search);
            if (urlParams.has('slug')) {
                window.location = 'job/' + urlParams.get('slug') + '/apply';
            } else {
                window.location = 'candidate/dashboard';
            }

        }
    }

    const handleFooterSubmit = async (url, _redirect) => {
        let form = document.getElementById('tabSubmit');

        event.preventDefault();
        setFileName(null);
        setModalFetch(false);
        setInputChange(false);
        setAlertChanges(false);
        setDocFileName(null);

        const mdata = new FormData(form);
        await fetchData(url + (!urlParams.has('slug') ? "" : "?job_slug=" + urlParams.get('slug')), {method: "post", data: mdata}, {
            callback: footerSubmitCallback,
            redirect: _redirect
        });
        /*
        for (let name of mdata.keys()) {
            data.set(name, data.get(name));
        }
        console.log(data);
        */


        //alert(`Submitting  form`);
    }

    const handleDeleteRequest = (url) => {

        if (confirm('Are you sure you want to perform action?')) {
            setFileName(null);
            setDocFileName(null);
            setModalFetch(false);
            fetchData(url+(!urlParams.has('slug') ? "" : "?job_slug=" + urlParams.get('slug')), {method: "delete"});
        }
    }
    const handleProfileImage = (e, url) => {
        //profile_image
        const profile = new FormData();

        profile.append('profile_image', e.target.files[0]);
        fetchData(url, {method: "post", data: profile});
    }
    const changeActiveTab = (tab)=>{
        setActiveTab(tab);
        setActiveTabMain(tab);
        //setActiveTabUrlMain(tab);
    }
    if (refresh) {
        return <Loader/>;
    }
    if (data && !isLoading && !activeTabMain && data.default_tab) {
        //alert(data.default_tab);
        setActiveTabMain(data.default_tab);
    }


    return (
        <div className="px-xs-1">
            <div style={{display: !isLoading ? 'none' : 'block'}}><Loader/></div>
            <ToastContainer/>

            <div className="container profile-container " style={{display: isLoading ? 'none' : 'block'}}>
                {success && !modalFetch ? <Success message={message}/> : ''}
                {error && !modalFetch ? <Error error={error}/> : ''}
                {modalContent.show ?
                    <Modal show={modalContent.show} onCloseEvent={closeModal} title={modalContent.title}>
                        {success && modalFetch ? <Success message={message}/> : ''}
                        {error && modalFetch ? <Error error={error}/> : ''}
                        <form onSubmit={(e) => handleModalSubmit(modalContent.url, e)} method="post">
                            <CandidateInfoSection
                                tag={modalContent.tag}
                                info={modalContent.defaultValues}
                            />
                            <div
                                className="position-relative overflow-hidden d-inline-block w-100 text-right">

                                <button type="submit"
                                        className="btn text-capitalize rounded border cursor-pointer font-small font-weight-bold">
                                    Add
                                </button>
                            </div>
                        </form>
                    </Modal>
                    : null}
                <div className="row pt-4 flex-column align-items-center justify-content-center">
                    <h5 className="ubuntu-medium">Edit your profile</h5>
                    <h6 className="ubuntu-light">Manage your account information here</h6>
                    <div className="profile-img-border rounded-circle bg-dark  border border-white">
                        <button onClick={() => profileInput.click()}
                                className="btn change-img-btn p-1 font-small border  ubuntu-bold">Change
                        </button>

                        <img
                            className="img-responsive profile-img-main rounded-circle object-fit-cover"
                            width="68"
                            height="68"
                            src={data && data.user.profile_image_url ? data.user.profile_image_url : "./" + profile_image}
                            alt="profile image"
                        />

                    </div>
                    <input
                        onChange={(e) => handleProfileImage(e, 'api/v1/candidate/profile/update/image')}
                        ref={input => profileInput = input}
                        type="file"
                        className="d-none"
                    />
                    {urlParams.has('slug') && data && data.job_requirements ?
                        <div className="w-60 ubuntu-bold w-sm-100">

                            <p className="text-primary2 font-small text-center mb-0">Please note that the following
                                information or documents must</p>
                            <p className="text-primary2 font-small text-center ">
                                be provided before you can submit your application.
                            </p>
                            <div className="row justify-content-center">
                            {data.job_requirements.map((row, index) => {
                                return (
                                    <div onClick={()=>changeActiveTab(row.type)} key={index} className="col-4 col-sm-3 cursor-pointer px-1 text-center">
                                        <p className={(!row.value ? "border-danger glow-danger": "border-success")+" w-100 rounded font-small  px-2 border py-2 text-truncate"}>{row.label}</p>
                                    </div>
                                )

                            })}
                            </div>
                        </div>

                        : null}
                    <form action="" id="tabSubmit">
                        <Tabs submitCallback={handleFooterSubmit} setActiveTabMain={setActiveTabMain}
                              activeTabMain={activeTabMain} activeTabUrlMain={activeTabUrlMain}
                              setActiveTabUrlMain={setActiveTabUrlMain} InputChange={InputChange}
                              setAlertChanges={setAlertChanges} activeTab={activeTab} setActiveTab={setActiveTab}
                              data={data}
                        >
                            <div label="Resume"
                                 url="api/v1/candidate/profile/update/cv">
                                <div className="rounded-container bg-white py-5">
                                    <div className="d-flex flex-column align-items-center justify-content-center">
                                        <img src={"./" + resumeIcon} alt="Resume Icon"/>
                                        <h6 className="text-grey ubuntu-bold font-weight-bold py-2">
                                            Upload your resume
                                        </h6>
                                        <p className="mx-md-5 ubuntu-medium font-small text-center w-50">
                                            It will be very helpful if the information in your profile
                                            correlates with you resume
                                        </p>
                                        <div className="text-center">
                                            {data && data.user.cv_name !== null ? <p><a href={data.user.cv_url}
                                                                                        className="cursor-pointer">{data.user.cv_name}</a>
                                            </p> : null}

                                            <div className="position-relative overflow-hidden d-inline-block">
                                                <a className="btn text-capitalize rounded border text-truncate cursor-pointer font-small font-weight-bold cursor-pointer">
                                                    {!fileName && data && data.user.cv_name !== null ? "Change" : fileName ? fileName : "Select File"}
                                                </a>
                                                <input
                                                    type="file"
                                                    name="cv_file"
                                                    onChange={onFileChange}
                                                    className="position-absolute opacity-0 left-0 top-0 bottom-0 cursor-pointer"
                                                />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div label="Personal Information" url="api/v1/candidate/profile/update/info">
                                <div className="rounded-container d-flex flex-column mb-3">
                                    <div className="rounded-top bg-white py-3">
                                        <h6 className="text-uppercase text-primary font-weight-bold font-x-small pl-4 mb-0">
                                            Add Personal Info
                                        </h6>
                                    </div>
                                    <CandidateInfoSection
                                        setInputChange={setInputChange}
                                        tag="personalInfo"
                                        info={data && generateInfo(data.user, 'personal')}
                                    />
                                </div>
                            </div>
                            <div label="Education" url="api/v1/candidate/profile/update/education">
                                <div className="rounded-container d-flex flex-column mb-3">
                                    <div className="rounded-top bg-white py-3">
                                        <h6 className="text-uppercase text-primary font-weight-bold font-x-small pl-4 mb-0">
                                            Education
                                        </h6>
                                    </div>
                                    {data && data.user.education.length === 0 ?
                                        <EmptyState title="Education"/>
                                        : null}


                                    <CandidateInfoSection
                                        setInputChange={setInputChange}
                                        tag="education"
                                        info={data && generateInfo(data.user.education, 'education')}//{defaultValues.education}//
                                        canAdd={true}
                                        canAddEvent={() => addNewModal("api/v1/candidate/profile/create/education", "education", defaultValues.education, "Add Education")}
                                        deleteUrl="api/v1/candidate/profile/delete/education/"
                                        canDeleteEvent={handleDeleteRequest}

                                    />
                                </div>
                            </div>
                            <div label="Employment History" url="api/v1/candidate/profile/update/work">
                                <div className="rounded-container d-flex flex-column mb-3">
                                    <div className="rounded-top bg-white py-3">
                                        <h6 className="text-uppercase text-primary font-weight-bold font-x-small pl-4 mb-0">
                                            Employment History
                                        </h6>
                                    </div>
                                    {data && data.user.work.length === 0 ?
                                        <EmptyState title="Employment History"/>
                                        : null}

                                    <CandidateInfoSection
                                        setInputChange={setInputChange}
                                        tag="employment"
                                        info={data && generateInfo(data.user.work, 'employment')}
                                        canAdd={true}
                                        deleteUrl="api/v1/candidate/profile/delete/work/"
                                        canAddEvent={() => addNewModal("api/v1/candidate/profile/create/work", "employment", defaultValues.employment, "Add Employment History")}
                                        canDeleteEvent={handleDeleteRequest}
                                    />
                                </div>

                            </div>
                            <div label="O'Levels" url="api/v1/candidate/profile/update/olevel">
                                <div className="rounded-container d-flex flex-column mb-3">
                                    <div className="rounded-top bg-white py-3">
                                        <h6 className="text-uppercase text-primary font-weight-bold font-x-small pl-4 mb-0">
                                            O'Levels
                                        </h6>
                                    </div>
                                    <div className="bg-grey-gradient rounded-bottom">
                                        {data && data.user.olevels.length === 0 ?
                                            <EmptyState title="O'Level Result"/>
                                            : null}
                                        <CandidateInfoSection
                                            setInputChange={setInputChange}
                                            tag="O'Level"
                                            info={data && generateInfo(data.user.olevels, 'olevels')}//{defaultValues.olevels}//
                                            canAdd={true}
                                            canAddEvent={() => addNewModal("api/v1/candidate/profile/create/olevel", "olevels", defaultValues.olevels, "Add Olevel Result")}
                                            deleteUrl="api/v1/candidate/profile/delete/olevel/"
                                            canDeleteEvent={handleDeleteRequest}
                                        />

                                    </div>
                                </div>
                            </div>
                            <div label="Skills & Other" url="api/v1/candidate/profile/update/other">
                                <div className="rounded-container d-flex flex-column  mb-3">
                                    <div className="rounded-top bg-white py-3">
                                        <h6 className="text-uppercase text-primary font-weight-bold font-x-small pl-4 mb-0">
                                            Other Qualifications
                                        </h6>
                                    </div>
                                    <CandidateInfoSection setInputChange={setInputChange} tag="skills"
                                                          info={data && generateInfo(data.user, 'others')}//{defaultValues.education}//
                                    />
                                </div>


                                <div className="rounded-container d-flex flex-column mb-3">
                                    <div className="rounded-top bg-white py-3">
                                        <h6 className="text-uppercase text-primary font-weight-bold font-x-small pl-4 mb-0">
                                            NYSC
                                        </h6>
                                    </div>
                                    <CandidateInfoSection
                                        setInputChange={setInputChange}
                                        tag="nysc"
                                        info={data && generateInfo(data.user, 'nysc')}//{data && generateInfo(data.user.work)}
                                    />
                                </div>
                            </div>
                            <div label="Other Documents" url="api/v1/candidate/profile/create/document">
                                <div className="rounded-container d-flex flex-column  mb-3">
                                    <div className="rounded-top bg-white py-3">
                                        <h6 className="text-uppercase text-primary font-weight-bold font-x-small pl-4 mb-0">
                                            ADD DOCUMENTS
                                        </h6>
                                    </div>
                                    <div className="bg-grey-gradient rounded-bottom">
                                        {data ?
                                            <div>
                                                {data.user.documents.map((doc, indx) => {
                                                    return (
                                                        <div className="row row mx-2 my-2" key={indx}>
                                                            <div
                                                                className="col-5 font-small d-flex align-items-start text-left">
                                                                <a href={doc.doc_url} key={indx}>{doc.doc_name}</a>
                                                            </div>
                                                            <div
                                                                className="col-4 font-small d-flex align-items-start text-left">
                                                                <p className="text-uppercase">{doc.type.name}</p>
                                                            </div>
                                                            <div className="col-3 d-flex align-items-start text-right">
                                                                <div className="w-100">
                                                                    <a onClick={() => handleDeleteRequest('api/v1/candidate/profile/delete/document/' + doc.uuid)}
                                                                       className=" text-danger cursor-pointer font-small font-weight-bold"
                                                                       key={indx}><i
                                                                        className="fa fa-trash-o"></i> Delete file</a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    )
                                                })}
                                            </div>
                                            : null}
                                        <div className="border-top"></div>
                                        <div className="row row mx-2 my-3">
                                            <div className="col-12 d-flex align-items-start"><span>Add a certificate or other
                                            credentials to boost your profile (pdf,docx only).</span></div>

                                        </div>
                                        <div className="row row mx-2 my-3">
                                            <div className="col-6 d-flex align-items-start">
                                                <Select name="doc_type"
                                                        options={formatDocTypes(data && data.bootstrap.document_types)}
                                                        placeholder="Select document type" styles={{
                                                    container: () => ({
                                                        width: '100%',
                                                    })
                                                }}/>
                                            </div>
                                            <div className="col-6 d-flex align-items-start">
                                                <a className="btn text-capitalize rounded border text-truncate font-weight-bold  font-small  cursor-pointer">
                                                    {docFileName ? docFileName : "Select File"}
                                                </a>
                                                <input
                                                    type="file"
                                                    name="doc_file"
                                                    onChange={onDocFileChange}
                                                    className="position-absolute w-100 opacity-0 left-0 top-0 bottom-0 cursor-pointer"
                                                />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </Tabs>
                    </form>
                </div>
            </div>
        </div>

    );
};

if (document.getElementById("candidate-profile")) {
    ReactDOM.render(
        <CandidateProfile/>,
        document.getElementById("candidate-profile")
    );
}
