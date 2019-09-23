import React, {useState} from "react";

import DatePicker from 'react-datepicker';
import "react-datepicker/dist/react-datepicker.css";
import "react-virtualized-select/styles.css";
import Select from "react-virtualized-select";
import {Creatable} from "react-virtualized-select/node_modules/react-select"

const customStyles = {

    container: () => ({
        width: '100%',
    }),
    control: (provided) => ({
        ...provided,
        border: 'none',
        fontFamily: "Ubuntu",
        fontSize: "0.7rem",
        color: "#343a40 !important",
        background: "transparent",
    }),
    indicatorsContainer: () => ({
        display: 'none',
    }),
}
const Input = ({name, type, refValue, value, onChange, selectData, info}) => {
        let defaultValue = '';
        if (type === "textarea") {
            return (
                <textarea
                    rows={5}
                    name={name}
                    ref={refValue}
                    value={value}
                    onChange={onChange}
                    className="text-dark overflow-hidden font-small mb-0 py-2 border-0 w-100 bg-transparent"
                />
            );
        } else if (type === "select") {

            const optionsCb = (inputValue, cb) => {
                if (Promise.resolve(selectData) !== selectData) {

                    return Promise.resolve({
                        options: selectData.map((data, indx) => {
                            return {value: data.name, label: data.title !== undefined ? data.title : data.name}
                        }).filter(i =>
                            i.label.toLowerCase().includes(inputValue.toLowerCase())
                        )
                    });
                } else {
                    return selectData.then((data) => {
                        return {
                            options: data.map((data, indx) => {
                                return {value: data.name, label: data.title !== undefined ? data.title : data.name}
                            }).filter(i =>
                                i.label.toLowerCase().includes(inputValue.toLowerCase())
                            )
                        };
                    });

                }
            }
            const options = Array.isArray(selectData) ? selectData.map((data, indx) => {
                return {value: data.name, label: data.title !== undefined ? data.title : data.name}
            }) : [];
            if (Promise.resolve(selectData) !== selectData) {
                defaultValue = typeof value === 'object' ? options.filter(option => option.value == value.value)[0] : options.filter(option => option.value == value)[0];
            } else {
                defaultValue = {value: value, label: value};
            }

            return <Select async={true} options={options} styles={customStyles} loadOptions={optionsCb}
                           onChange={(val) => {
                               onChange({target: {name: {name}, value: val.value}})
                           }}
                           name={name}
                           key={(Math.random() * 10000) + Math.random().toString(36).substring(7)}
                           ref={refValue}
                           value={defaultValue}
                           defaultOptions={true}/>;
            /*
const optionsCb = (inputValue) => {

 if (Promise.resolve(selectData) !== selectData) {
     return new Promise((resolve, reject) => {

         resolve(selectData.map((data, indx) => {
             return {value: data.name, label: data.title !== undefined ? data.title : data.name}
         }).filter(i =>
             i.label.toLowerCase().includes(inputValue.toLowerCase())
         ));
     });

 } else {
     return selectData.then((data) => {
         return data.map((data, indx) => {
             return {value: data.name, label: data.title !== undefined ? data.title : data.name}
         }).filter(i =>
             i.label.toLowerCase().includes(inputValue.toLowerCase())
         );
     });
 }
}
const options = Array.isArray(selectData) ? selectData.map((data, indx) => {
 return {value: data.name, label: data.title !== undefined ? data.title : data.name}
}) : [];
if (Promise.resolve(selectData) !== selectData) {
 defaultValue = typeof value === 'object' ? options.filter(option => option.value == value.value) : options.filter(option => option.value == value);
} else {
 defaultValue = {value: value, label: value};
}


return <Async styles={customStyles}
           onChange={(val) => {
               onChange({target: {name: {name}, value: val.value}})
           }}
           name={name}
           key={(Math.random() * 10000) + Math.random().toString(36).substring(7)}
           ref={refValue}
           defaultValue={defaultValue}
           defaultOptions={true}
           loadOptions={optionsCb}/>
           */

        } else if (type === "select-multiple") {
            const optionsCb = (inputValue, cb) => {
                if (Promise.resolve(selectData) !== selectData) {

                    return Promise.resolve({
                        options: selectData.map((data, indx) => {
                            return {value: data.name, label: data.title !== undefined ? data.title : data.name}
                        }).filter(i =>
                            i.label.toLowerCase().includes(inputValue.toLowerCase())
                        )
                    });
                } else {
                    return selectData.then((data) => {
                        return {
                            options: data.map((data, indx) => {
                                return {value: data.name, label: data.title !== undefined ? data.title : data.name}
                            }).filter(i =>
                                i.label.toLowerCase().includes(inputValue.toLowerCase())
                            )
                        };
                    });

                }
            }
            const options = Array.isArray(selectData) ? selectData.map((data, indx) => {
                return {value: data.name, label: data.title !== undefined ? data.title : data.name}
            }) : [];
            if (Promise.resolve(selectData) !== selectData) {
                defaultValue = typeof value === 'object' ? options.filter(option => option.value == value.value) : options.filter(option => option.value == value);
            } else {
                defaultValue = Array.isArray(value) ? value.map((data, index) => {
                    return {label: data, value: data}
                }) : {value: value, label: value};
            }
            const [inputVal, setInputVal] = useState(value !== '' ? defaultValue :  false);


            return <Select async={true}
                           styles={customStyles}
                           loadOptions={optionsCb}
                           onChange={(val) => {
                               console.log(val);

                               setInputVal(val);
                               onChange({target: {name: {name}, value: val.value}})
                           }}
                           multi={true}
                           value={inputVal}
                           multiValue={inputVal}
                           name={name + '[]'}
                           key={(Math.random() * 10000) + Math.random().toString(36).substring(7)}
                           ref={refValue}
                           defaultValue={inputVal}
                           defaultOptions={true}/>;
                /*
            const optionsCb = (inputValue) => {

                if (Promise.resolve(selectData) !== selectData) {
                    return new Promise((resolve, reject) => {

                        resolve(selectData.map((data, indx) => {
                            return {value: data.name, label: data.title !== undefined ? data.title : data.name}
                        }).filter(i =>
                            i.label.toLowerCase().includes(inputValue.toLowerCase())
                        ));
                    });

                } else {
                    return selectData.then((data) => {
                        return data.map((data, indx) => {
                            return {value: data.name, label: data.title !== undefined ? data.title : data.name}
                        }).filter(i =>
                            i.label.toLowerCase().includes(inputValue.toLowerCase())
                        );
                    });
                }
            }
            const options = Array.isArray(selectData) ? selectData.map((data, indx) => {
                return {value: data.name, label: data.title !== undefined ? data.title : data.name}
            }) : [];
            console.log(value);
            if (Promise.resolve(selectData) !== selectData) {
                defaultValue = typeof value === 'object' ? options.filter(option => option.value == value.value) : options.filter(option => option.value == value);
            } else {
                defaultValue = Array.isArray(value) ? value.map((data, index) => {
                    return {label: data, value: data}
                }) : {value: value, label: value};
            }
            const [inputVal, setInputVal] = useState(value !== '' ? defaultValue :  false);

            return <Async styles={customStyles}
                          onChange={(val) => {
                              setInputVal(val);
                              onChange({target: {name: {name}, value: val.value}})
                          }}
                          isMulti
                          name={name + '[]'}
                          key={(Math.random() * 10000) + Math.random().toString(36).substring(7)}
                          ref={refValue}
                          defaultValue={inputVal}
                          defaultOptions={true}
                          loadOptions={optionsCb}/>
                          */
        } else if (type === "create-select") {
            const options = Array.isArray(value) ? value.map((data, index) => {
                return {label: data, value: data}
            }) : [];
            const [inputVal, setInputVal] = useState(options);
            return <Select async={true}
                           //options={options}
                           onChange={(val) => {
                               console.log(val);
                               setInputVal(val);
                               onChange({target: {name: {name}, value: val.value}})
                           }}
                           promptTextCreator={userInput => (<button className="btn btn-primary-2 w-100 m-0" style={{position:"absolute",display: "block",left:"-10px"}}>ADD</button>)}
                           multi={true}
                           value={inputVal}
                           selectComponent={Creatable}
                           name={name + '[]'}
                           key={(Math.random() * 10000) + Math.random().toString(36).substring(7)}
                           placeholder={info}/>;
        //    return (<CreatableInputOnly onChange={onChange} name={name} placeholder={info} defaultOptions={options}/>)
        } else if (type === "input-calender") {
            return (
                <DatePicker
                    name={name}
                    ref={refValue}
                    peekNextMonth
                    showMonthDropdown
                    showYearDropdown
                    dropdownMode="select"
                    dateFormat="yyyy-MM-dd"
                    selected={Date.parse(value)} onChange={(val) => onChange({target: {name: {name}, value: val}})}
                    className="text-dark font-small mb-0 py-2 border-0 w-100 bg-transparent"
                />
            );
        } else {

            return (
                <input
                    type={type}
                    name={name}
                    ref={refValue}
                    placeholder={info}
                    value={value}
                    onChange={onChange}
                    className="text-dark font-small mb-0 py-2 border-0 w-100 bg-transparent"
                />
            );
        }

    }
;

export default Input;
