import React from "react";
import PropTypes from "prop-types";

function Tab({ label, url, onClick, activeTab }) {

  const onTabClick = () => {
    onClick(label,url);
  };

  let className = "tab-list-item font-small ubuntu-light";

  if (activeTab === label) {
    className = "tab-list-item font-small tab-list-active ubuntu-medium";
  }
  return (
    <li className={className} onClick={onTabClick}>
      {label}
    </li>
  );
}
Tab.propTypes = {
  activeTab: PropTypes.string.isRequired,
  label: PropTypes.string.isRequired,
  onClick: PropTypes.func.isRequired
};

export default Tab;
