import React, {useState, useEffect} from "react";
import {defaultValues} from "./mockData";
import CandidateProfileDetail from "./CandidateProfileDetail";
import CandidateProfileFooter from "./CandidateProfileFooter";

function CandidateInfoSection({info, tag, canAdd, canAddEvent, canDeleteEvent, deleteUrl, setInputChange}) {
    const [index, setIndex] = useState(0);
    const [profileInfo, setProfileInfo] = useState(info);
    const addNew = e => {
        setProfileInfo(defaultValues[tag]);
    };


    return (
        <div className={(profileInfo && profileInfo.length > 0 ? "bg-grey-gradient" : "bg-white")+" rounded-bottom"}>
            {profileInfo && profileInfo.length > 0 ? (
                profileInfo[index].map(data => {
                    const {name, key, value, labelEditable, labelReadOnly,labelInfo, changeCallback, type, selectData, info} = data;
                    //console.log(changeCallback())
                    return (
                        <CandidateProfileDetail
                            name={name}
                            key={key}
                            dataKey={key}
                            value={value}
                            selectData={selectData}
                            info={info}
                            changeCallback={changeCallback}
                            type={type}
                            labelEditable={labelEditable}
                            labelReadOnly={labelReadOnly}
                            labelInfo={labelInfo}
                            setInputChange={setInputChange}
                        />
                    );
                })
            ) : null

                /*
                (
              <div className="d-flex align-items-center justify-content-center py-5">
                <button onClick={addNew}> Add New</button>
              </div>
            )*/}
            <CandidateProfileFooter info={profileInfo} tag={tag} index={index} setIndex={setIndex} canAdd={canAdd}
                                    canAddEvent={canAddEvent} canDeleteEvent={canDeleteEvent}
                                    deleteUrl={deleteUrl}/>
        </div>
    );

}

export default CandidateInfoSection;
