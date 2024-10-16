import { E_404 } from "./Router";
import { LNG_ERROR, LNG_MSG_CONNECTION_ERROR } from "./util/consts";
import { toast } from "./util/util";
let controller = '';
const API_VER = 'v1';
export const BASE_URL = `${$_url}api/${API_VER}/`;

const writeOptions = async (options) => {
    const token = await getToken() ;
    const headers = new Headers({
        'Content-Type': 'application/json',
        'Authorization':`Bearer ${token}`
    });
    return {
        credentials: 'same-origin',
        headers,
        ...options,
    };
};

export const fetchApi = async (_url,method,options) => {
    let ApiRes = {};
    try {
        controller = new AbortController();
        const signal = controller.signal;
        let url = `${BASE_URL}${_url}`;
        const customOptions = {
            method,
            signal
        };
        switch (method) {
            case 'POST':
                customOptions.body=JSON.stringify(options.body);
                break;
            case 'PUT':
                customOptions.body=JSON.stringify(options.body);
                break;
            case 'DELETE':
                url += `/${options.params.id}`;
                break;
            default:
                let params='';
                if (options.params.data && Object.keys(options.params.data).length>0) {
                    params = new URLSearchParams(options.params.data).toString();
                    url +=`?${params}`;
                }else if (Object.keys(options.params).length>0) {
                    params = new URLSearchParams(options.params).toString();
                    url +=`?${params}`;
                }
                break;

        }
        const finalOptions = await writeOptions(customOptions);
        ApiRes     = await fetch(url,finalOptions); 
        if (ApiRes.status !== 200) {
            throw new Error( `An error has occured: ${ApiRes.status} - ${ApiRes.statusText}` );
        }
        const data = await ApiRes.json();
        return data;
    } catch (err) { 
        if (ApiRes.status == 401) {
            E_404();
        }else if (ApiRes.status == 403) {
            Unauthorized();
        }else{
            // toast('e', LNG_MSG_CONNECTION_ERROR, LNG_ERROR);
            return { error: err.message,code };
        }
    }
};

const Unauthorized =async () => {
    await getToken();
    window.location.href = $_url+'admin/login';
    return;
};
export const abortFetch = async () => {
    if (controller){
        console.log('controller',controller);
        controller.abort();
    }
};

export async function getToken() {
    return await localStorage.getItem("tkn")
}

export async function setToken(token) {
    await localStorage.setItem("tkn", token)
}
export async function delToken() {
    await localStorage.removeItem("tkn")
}

// Longer duration refresh token (30-60 min)
export function getRefreshToken() {
    return localStorage.getItem("refreshToken")
}

export function setRefreshToken(token) {
    localStorage.setItem("refreshToken", token)
}
