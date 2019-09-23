import React, {useState, useRef, useEffect} from "react";
import arrowRight from "../../../../public/assets/images/icon/chevron-right.svg";
import Input from "../components/Input/";

function CandidateProfileDetail({dataKey, changeCallback, labelInfo,labelEditable, labelReadOnly, value, type, name, selectData, info, setInputChange}) {
    const [profileValue, setProfileValue] = useState(value);
    const [labelValue, setLabelValue] = useState(value ? value.label : null);
    const profileInputElement = useRef(null);
    let [prevValue, setPrevVal] = useState(null);
    let [prevLabelValue, setLabelPrevVal] = useState(null);

    if (value !== prevValue) {
        setProfileValue(value);
        setPrevVal(value);
    }
    if (value && value.label !== prevLabelValue) {
        setLabelValue(value.label);
        setLabelPrevVal(value.label);
    }

    useEffect(() => {
        if (changeCallback) changeCallback(profileValue);

    })

    const onProfileValueChange = e => {
        if (e && e.target) setProfileValue(e.target.value);
        if (setInputChange) setInputChange(true);
    };

    const onLabelValueChange = e => {
        if (e && e.target) setLabelValue(e.target.value);
        if (setInputChange) setInputChange(true);
    };

    const onClickEdit = e => {
        profileInputElement.current.focus();
    };
    if (type === 'hidden') {
        return (
            <Input
                name={name}
                type={type}
                selectData={selectData}
                refValue={profileInputElement}
                value={profileValue || ""}
                onChange={onProfileValueChange}
            />
        )
    }
    return (
        <div className="profile-input-section">
            <div className="row mx-0" key={dataKey}>
                <div className="col-4 d-flex align-items-center pr-xs-0">
                    {labelEditable ?
                        <Input
                            name={name+'_label'}
                            type="text"
                            selectData={selectData}
                            info={labelInfo}
                            value={labelValue || ""}
                            onChange={labelReadOnly ?  ()=>{} : onLabelValueChange}

                        />

                        :
                        <p className="text-grey text-uppercase font-weight-bold font-x-small py-2 pl-2 mb-0"
                           onClick={onClickEdit}>
                            {dataKey}
                        </p>
                    }
                </div>
                <div className="col-8 col-sm-7 text-dark d-flex align-items-center ">
                    <Input
                        name={name}
                        type={type}
                        selectData={selectData}
                        info={info}
                        refValue={profileInputElement}
                        value={profileValue || ""}
                        onChange={onProfileValueChange}

                    />


                </div>
                <div className="col-1 d-flex align-items-center d-xs-none">
          <span className="cursor-pointer" onClick={onClickEdit}>
            <img src={"./" + arrowRight} width="20" height="20" alt="Right"/>
          </span>

                </div>

            </div>
        </div>
    );
}

export default CandidateProfileDetail;

