import {useState, useEffect, useReducer} from "react";
import axios from "axios";

const actions = {
    FETCH_INIT: "FETCH_INIT",
    FETCH_SUCCESS: "FETCH_SUCCESS",
    FETCH_FAILURE: "FETCH_FAILURE",
    FETCH_REFRESH_START: "FETCH_REFRESH_START",
    FETCH_REFRESH_END: "FETCH_REFRESH_END",
};

const dataFetchReducer = (state, action) => {
    switch (action.type) {

        case actions.FETCH_INIT:
            return {
                ...state,
                isLoading: true,
                error: "",
                success: false,
                message: undefined,
            };
        case actions.FETCH_SUCCESS:
            return {
                ...state,
                isLoading: false,
                success: action.settings !== undefined && action.settings.showSuccess !== undefined ? action.settings.showSuccess : true,
                message: action.payload.message !== undefined && action.payload.message !== null ? action.payload.message : 'Operation completed successfully.',
                error: "",
                data: action.payload
            };
        case actions.FETCH_REFRESH_START:
            return {
                ...state,
                refresh: true,
            };
        case actions.FETCH_REFRESH_END:
            return {
                ...state,
                refresh: false,
            };
        case actions.FETCH_FAILURE:
            return {
                ...state,
                isLoading: false,
                success: false,
                message: undefined,
                error: formatError(action.payload)
            };
        default:
            throw new Error();
    }
};

export const useDataApi = (initialUrl, initialData, initialConfig, InitialSettings) => {
    const [url, setUrl] = useState(initialUrl);
    const [config, setConfig] = useState(initialConfig);
    const [settings, setSettings] = useState(InitialSettings);

    const [state, dispatch] = useReducer(dataFetchReducer, {
        isLoading: true,
        error: "",
        success: false,
        message: undefined,
        data: initialData,
        refresh: false,
    });

    useEffect(() => {

        const fetchData = async () => {
            if (url) {
                dispatch({type: actions.FETCH_INIT});
                try {
                    const result = await axios(url, config);
                    dispatch({type: actions.FETCH_SUCCESS, payload: result.data, settings: settings});
                    if(settings !== undefined && settings.callback){
                        settings.callback(settings.redirect,result.data);
                    }
                    dispatch({type: actions.FETCH_REFRESH_START });
                    dispatch({type: actions.FETCH_REFRESH_END });

                } catch (error) {
                    dispatch({type: actions.FETCH_FAILURE, payload: error});
                }
            }
        };
        fetchData();
    }, [url, config, settings]);

    const fetchData = (url, config, setting) => {
        setUrl(url);
        setConfig(config);
        setSettings(setting)
    };

    return {...state, fetchData};
};

const formatError = error => {
    let message = "";
    if (error.response && error.response.data && error.response.data.error) {
        return error.response.data.error;
    }
    const status = error.response ? error.response.status : "";
    if (status === 404) {
        message = "404 Not found";
    } else if (status === 403) {
        message = "Forbidden from performing this action";
    } else {
        message = error.message;
    }
    return message;
};
