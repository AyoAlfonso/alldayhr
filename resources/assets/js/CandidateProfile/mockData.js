import generalData from '../../data/json/general.json';
//import industryData from '../../data/json/industry.json';
//import stateData from '../../data/json/states.json';
//import nationalityData from '../../data/json/nationalities.json';
import axios from 'axios';

const university = axios.get('api/v1/candidate/general/universities').then(res=>res.data.map((uni) => {
    return {name:uni}
}));
const languages = axios.get('api/v1/candidate/general/languages').then(res=>res.data.map((language) => {
    return {name:language}
}));

const states = axios.get('api/v1/candidate/general/states').then(res=>res.data.map((state) => {
    return {name: state}
}));
const nationalities = axios.get('api/v1/candidate/general/nationalities').then(res=>res.data.map((nationality) => {
    return {name: nationality}
}));
const industryData = axios.get('api/v1/candidate/general/industries').then(res=>res.data.map((industry) => {
    return {name: industry}
}));


const monthNames = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
const months = monthNames.map((month, index) => {
    return {name: index + 1, title: month}
});

const oleveltypeNames = ["WASSCE", "NECO", "GCE", "NCE", "NABTEB", "Cambridge A'Level"];
const oleveltype = oleveltypeNames.map((name, index) => {
    return {name: name, title: name}
});

const olevelgradeNames = ["A1", "B2", "B3", "C4", "C5", "C6", "D7", "E8", "F9"];
const olevelgrade = olevelgradeNames.map((grade, index) => {
    return {name: grade, title: grade}
});

const callbackNysc = (value) => {

    if (value !== 'Completed') {
        let nysc_info = document.querySelector('input[name="nysc_other_info"]');
        if (nysc_info) {
            // nysc_info.value = '';
            nysc_info.style.display = 'none';
            let parent = nysc_info.closest('div[class="profile-input-section"]');
            if (parent) {
                parent.style.display = 'none';
            }
        }

        let nysc_completion_year = document.querySelector('input[name="nysc_completion_year"]');
        if (nysc_completion_year) {
            //nysc_completion_year.value = '';
            nysc_completion_year.style.display = 'none';
            nysc_completion_year.closest('div[class="profile-input-section"]').style.display = 'none';
        }

    }
    if (value === 'Completed') {
        let nysc_info = document.querySelector('input[name="nysc_other_info"]');
        if (nysc_info) {
            //nysc_info.value = '';
            nysc_info.style.display = 'block';
            let parent = nysc_info.closest('div[class="profile-input-section"]');
            parent.style.display = 'block';
        }

        let nysc_completion_year = document.querySelector('input[name="nysc_completion_year"]');
        if (nysc_completion_year) {
            //nysc_completion_year.value = '';
            nysc_completion_year.style.display = 'block';
            nysc_completion_year.closest('div[class="profile-input-section"]').style.display = 'block';
        }
    }
}
const jsonToString = (json) => {
    if (json) {
        return JSON.parse(json);
    }
    return null;
};
export const defaultValues = {

    education: [
        [
            {name: "institution", key: "Institution", value: "", type: "select", selectData: university},
            {name: "from_year", key: "Admission Year", value: "", type: "text"},
            {name: "to_year", key: "Graduation Year", value: "", type: "text"},
            {name: "field_of_study", key: "Course of Study", value: "", type: "text"},
            {
                name: "qualification",
                key: "Qualification",
                value: "",
                type: "select",
                selectData: generalData.qualifications
            },
            {name: "grade", key: "Grade", value: "", type: "select", selectData: generalData.grades}
        ]
    ],
    work: [
        [
            {name: "", key: "First Name", value: "", type: "text"},
            {name: "", key: "Last Name", value: "", type: "text"},
            {name: "", key: "Email Address", value: "", type: "text"},
            {name: "", key: "Phone Number", value: "", type: "text"},
            {name: "", key: "Gender", value: "", type: "text"},
            {name: "", key: "Date of Birth", value: "", type: "text"}
        ]
    ],
    employment: [
        [
            {name: "company", key: "Company", value: "", type: "text"},
            {name: "title", key: "Job Title", value: "", type: "text"},
            {
                name: "industry",
                key: "Industry",
                value: "",
                type: "select",
                selectData: industryData,
            },
            {name: "job_function", key: "Job Function", value: "", type: "text"},
            {
                name: "achievements",
                key: "Key Achievement",
                value: "",
                type: "textarea"
            },
            {name: "from_month", key: "From Month", value: "", type: "select", selectData: months},
            {name: "from_year", key: "From Year", value: "", type: "text"},
            {name: "to_month", key: "To Month", value: "", type: "select", selectData: months},
            {name: "to_year", key: "To Year", value: "", type: "text"},
            {
                name: "current",
                key: "I currently work here",
                value: "",
                type: "select",
                selectData: [{title: "No", name: '0'}, {title: "Yes", name: '1'}]
            },
        ]
    ],
    olevels: [
        [
            {
                name: "olevel_type",
                key: "O\'level Type",
                value: "",
                info: 'Type of O\'level',
                type: "select",
                selectData: oleveltype
            },
            {
                name: "subject_1",
                labelEditable: true,
                labelReadOnly: true,
                info: "Choose Grade",
                key: "Mathematics",
                value: {label: 'Mathematics', value: ''},
                type: "select",
                selectData: olevelgrade
            },
            {
                name: "subject_2",
                labelEditable: true,
                labelReadOnly: true,
                info: "Choose Grade",
                key: "English",
                value: {label: 'English', value: ''},
                type: "select",
                selectData: olevelgrade
            },
            {
                name: "subject_3",
                labelEditable: true,
                key: "subject_3",
                labelInfo: "Type Subject 3",
                info: "Choose Grade",
                value: {label: '', value: ''},
                type: "select",
                selectData: olevelgrade
            },
            {
                name: "subject_4",
                labelEditable: true,
                key: "subject_4",
                labelInfo: "Type Subject 4",
                info: "Choose Grade",
                value: {label: '', value: ''},
                type: "select",
                selectData: olevelgrade
            },
            {
                name: "subject_5",
                labelEditable: true,
                key: "subject_5",
                labelInfo: "Type Subject 5",
                info: "Choose Grade",
                value: {label: '', value: ''},
                type: "select",
                selectData: olevelgrade
            },
            {
                name: "subject_6",
                labelEditable: true,
                key: "subject_6",
                labelInfo: "Type Subject 6",
                info: "Choose Grade",
                value: {label: '', value: ''},
                type: "select",
                selectData: olevelgrade
            },
            {
                name: "subject_7",
                labelEditable: true,
                key: "subject_7",
                labelInfo: "Type Subject 7",
                info: "Choose Grade",
                value: {label: '', value: ''},
                type: "select",
                selectData: olevelgrade
            },
        ]
    ],
    experience: [
        [
            {
                name: "experience_level",
                key: "Experience Level (Years)",
                value: "",
                type: "text",
                info: "E.g 2"
            }
        ]
    ],
    skills: [
        [
            {name: "certifications", key: "Certification", value: "", type: "text"},
            {
                name: "skills",
                key: "Skills",
                value: "",
                type: "text"
            }
        ]
    ],
    nysc: [
        [
            {
                name: "nysc_status",
                key: "NYSC Status",
                value: "",
                type: "select",
                selectData: [{name: "Completed"}, {name: "Exempted"}, {name: "Ongoing"}, {name: "Not started"}]
            },
            {name: "nysc_completion_year", key: "Completion Year", value: "", type: "text"},
            {name: "nysc_other_info", key: "Other Information", value: "", type: "text"},
        ]
    ],
    tabSection: [
        {
            label: "Personal Information",
            heading: "Add Personal Info"
        }
    ]

};


const map = {
    personal: {
        firstname: {name: "", label: "First Name", nestedValue: "user", inputType: "text"},
        lastname: {name: "", label: "Last Name", nestedValue: "user", inputType: "text"},
        othername: {name: "", label: "Other Name", inputType: "text"},
        email: {name: "", label: "Email Address", nestedValue: "user", inputType: "text"},
        phone_number: {name: "", label: "Phone Number", inputType: "text"},
        gender: {
            name: "",
            label: "Gender",
            inputType: "select",
            selectData: [{name: "M", title: "Male"}, {name: "F", title: "Female"}]
        },
        date_of_birth: {name: "", label: "Date of Birth", inputType: "input-calender", info: "YYYY-mm-d"},
        marital_status: {
            name: "",
            label: "Marital Status",
            inputType: "select",
            selectData: [{name: "Single"}, {name: "Married"}, {name: "Divorced"}, {name: "Separated"}, {name: "Widowed"}]
        },
        state: {name: "", label: "State of origin", inputType: "select", selectData: states},
        lga: {name: "", label: "LGA of Origin", inputType: "text"},
        residence_state: {name: "", label: "State of Residence", inputType: "select", selectData: states},
        residence_lga: {name: "", label: "LGA of Residence", inputType: "text"},
        street: {name: "", label: "Residential Address", inputType: "text"},
        nationality: {name: "", label: "Nationality", inputType: "select", selectData: nationalities},
        languages: {
            label: "Languages",
            inputType: "select-multiple",
            info: "Type your language here and press comma(,) to add another.",
            selectData: languages,
            formatter: jsonToString
        },
        experience_level: {
            label: "Total years of experience",
            inputType: "text",
            info: "E.g 2"
        }


    },
    education: {
        uuid: {name: "id", label: "uuid", inputType: "hidden"},
        institution: {name: "institution", label: "Institution", inputType: "select", selectData: university},
        from_year: {name: "from_year", label: "Admission Year", inputType: "text"},
        to_year: {name: "to_year", label: "Graduation Year", inputType: "text"},
        field_of_study: {name: "field_of_study", label: "Course of Study", inputType: "text"},
        qualification: {
            name: "qualification",
            label: "Qualification",
            inputType: "select",
            selectData: generalData.qualifications
        },
        grade: {name: "grade", label: "Grade", inputType: "select", selectData: generalData.grades}

    },
    employment: {
        uuid: {name: "id", label: "uuid", inputType: "hidden"},
        company: {label: "Company", inputType: "text"},
        title: {label: "Job Title", inputType: "text"},
        industry: {label: "Industry", inputType: "select", selectData: industryData},
        job_function: {label: "Job Function", inputType: "text"},
        achievements: {label: "Key Achievement", inputType: "textarea"},
        from_month: {label: "From Month", inputType: "select", selectData: months},
        from_year: {label: "From Year", inputType: "text"},
        to_month: {label: "To Month", inputType: "select", selectData: months},
        to_year: {label: "To Year", inputType: "text"},
        current: {
            label: "I currently work here",
            inputType: "select",
            selectData: [{title: "No", name: 0}, {title: "Yes", name: '1'}]
        },
    },
    nysc: {
        nysc_status: {
            label: "NYSC Status",
            inputType: "select",
            changeCallback: callbackNysc,
            selectData: [{name: "Completed"}, {name: "Exempted"}, {name: "Ongoing"}, {name: "Not started"}]
        },
        nysc_completion_year: {label: "Completion Year", inputType: "text"},
        nysc_other_info: {label: "NYSC Number", inputType: "text"},
    },
    experience: {
        experience_level: {
            label: "Experience Level (Years)",
            inputType: "text",
            info: "E.g 2"
        },
    },
    olevels: {
        uuid: {name: "id", label: "uuid", inputType: "hidden"},
        olevel_type: {
            label: "O\'level Type",
            info: 'Type of O\'level',
            inputType: "select",
            selectData: oleveltype
        },
        subject_1: {
            labelEditable: true,
            labelReadOnly: true,
            info: "Choose Grade",
            label: "Mathematics",
            inputType: "select",
            selectData: olevelgrade
        },
        subject_2: {
            labelEditable: true,
            labelReadOnly: true,
            info: "Choose Grade",
            label: "English",
            inputType: "select",
            selectData: olevelgrade
        },
        subject_3: {
            labelEditable: true,
            label: "subject_3",
            labelInfo: "Type Subject 3",
            info: "Choose Grade",
            inputType: "select",
            selectData: olevelgrade
        },
        subject_4: {
            labelEditable: true,
            label: "subject_4",
            labelInfo: "Type Subject 4",
            info: "Choose Grade",
            inputType: "select",
            selectData: olevelgrade
        },
        subject_5: {
            labelEditable: true,
            label: "subject_5",
            labelInfo: "Type Subject 5",
            info: "Choose Grade",
            inputType: "select",
            selectData: olevelgrade
        },
        subject_6: {
            labelEditable: true,
            label: "subject_6",
            labelInfo: "Type Subject 6",
            info: "Choose Grade",
            inputType: "select",
            selectData: olevelgrade
        },
        subject_7: {
            labelEditable: true,
            label: "subject_7",
            labelInfo: "Type Subject 7",
            info: "Choose Grade",
            inputType: "select",
            selectData: olevelgrade
        },
    },

    others: {
        certifications: {
            label: "Certifications",
            inputType: "create-select",
            info: "Type your certification here and press comma(,) to add another.",
            formatter: jsonToString
        },
        skills: {
            label: "Skills",
            inputType: "create-select",
            info: "Type your skills here and press comma(,) to add another.",
            formatter: jsonToString
        },
    }

};

export const generateKeyValue = (data, type) => {

    return Object.keys(map[type]).reduce((array, key) => {
        let formatter = map[type][key].formatter;
        //console.log(formatter);
        if (map[type][key].nestedValue) {
            const dataKey = map[type][key].nestedValue;
            let value = data[dataKey] !== undefined ? data[dataKey][key] : '' || "";
            if (formatter !== undefined && value !== '') {
                value = formatter(value);
            }
            return array.concat({
                name: key,
                key: map[type][key].label,
                selectData: map[type][key].selectData,
                value: value,
                type: map[type][key].inputType,
                info: map[type][key].info,
                labelInfo: map[type][key].labelInfo,
                labelEditable: map[type][key].labelEditable,
                labelReadOnly: map[type][key].labelReadOnly,
                changeCallback: map[type][key].changeCallback,

            });
        }

        let value = data[key];
        if (formatter !== undefined && value !== '') {
            value = formatter(value);
        }
        return array.concat({
            name: key,
            key: map[type][key].label,
            selectData: map[type][key].selectData,
            value: value,
            type: map[type][key].inputType,
            info: map[type][key].info,
            labelInfo: map[type][key].labelInfo,
            labelEditable: map[type][key].labelEditable,
            labelReadOnly: map[type][key].labelReadOnly,
            changeCallback: map[type][key].changeCallback,

        });
    }, []);
};

export const skills = [
    [
        {name: "certifications", key: "Certification", value: "", type: "text"},
        {
            name: "skills",
            key: "Skills",
            value: "",
            type: "text"
        }
    ]
];
