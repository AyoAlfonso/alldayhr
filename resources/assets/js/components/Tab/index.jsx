import React, {useState, useEffect} from "react";
import PropTypes from "prop-types";
import Tab from "./Tab";
import CandidateProfileFooterSave from "../../CandidateProfile/CandidateProfileFooterSave";


function Tabs({children, submitCallback, hideFooterSave, activeTabMain, setActiveTabMain, activeTabUrlMain, setActiveTabUrlMain, InputChange, setAlertChanges, activeTab, setActiveTab,data}) {
//    const [activeTab, setActiveTab] = useState(activeTabMain ? activeTabMain : children[0].props.label);
    //const [activeUrl, setActiveUrl] = useState(activeTabUrlMain ? activeTabUrlMain : children[0].props.url);

    const activeTabProps = children.find(child => child.props.label === activeTab);
    //const [activeUrl, setActiveUrl] = useState(activeTabUrlMain ? activeTabUrlMain : activeTabProps ? activeTabProps.props.url : children[0].props.url);
    const [activeUrl, setActiveUrl] = useState(activeTabProps.props.url);
    const onClickTabItem = (tab, url) => {
        if (setAlertChanges) setAlertChanges(false);
        if (!InputChange) {
            setActiveTab(tab);
            if (setActiveTabMain) setActiveTabMain(tab);
            setActiveUrl(url);
            if (setActiveTabUrlMain) setActiveTabUrlMain(url);
        } else {
            if (setAlertChanges) setAlertChanges(true)
        }
    };
    if (activeTabMain) {
        setActiveTab(activeTabMain);
    }
    if (activeUrl !== activeTabProps.props.url) {
        setActiveUrl(activeTabProps.props.url);
    }

    return (
        <div className="tabs">
            <ol className="tab-list">
                {children.map(child => {
                    const {label, url} = child.props;
                    return (
                        <Tab
                            activeTab={activeTab}
                            url={url}
                            key={label}
                            label={label}
                            onClick={onClickTabItem}
                        />
                    );
                })}
            </ol>
            <div className="tab-content  mb-xs-4" style={hideFooterSave ? {paddingBottom: '20px'} : null}>
                {children.map(child => {
                    if (child.props.label !== activeTab) return undefined;
                    return child.props.children;
                })}
            </div>
            {!hideFooterSave ?
                <CandidateProfileFooterSave data={data} InputChange={InputChange} submitCallback={submitCallback}
                                            activeUrl={activeUrl}/>
                : null}
        </div>

    );
}

Tabs.propTypes = {
    children: PropTypes.instanceOf(Array).isRequired
};

export default Tabs;
