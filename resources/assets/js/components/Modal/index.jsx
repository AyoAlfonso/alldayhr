import React, {useState} from "react";


function Modal({children, onCloseEvent, show, title}) {
    const [activeModal, setActiveModal] = useState(show);

    const showCss = {
        display: 'block'
    };
    const onClose = (e) => {
        setActiveModal(false);
        onCloseEvent && onCloseEvent(e);
    };



    if (!activeModal) {
        return null;
    }

    return (
        <div>
            <div className="modal-backdrop show"></div>
            <div className="modal show " id="modal" style={showCss}>
                <div className="modal-dialog modal-dialog-centered">
                    <div className="modal-content">
                        <span className="position-absolute m-2 cursor-pointer text-grey" style={{"right": "0","fontSize": "15px"}} onClick={onClose}><i className="fa fa-remove"></i></span>
                        <div className="modal-header">
                            <div className="rounded bg-white py-3">
                                <h6 className="text-uppercase text-primary font-weight-bold font-x-small pl-4 mb-0">
                                    {title}
                                </h6>
                            </div>
                        </div>
                        <div className="modal-body p-xs-0">{children}</div>

                    </div>
                </div>
            </div>
        </div>
    );

}

export default Modal;
