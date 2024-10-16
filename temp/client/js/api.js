import { loading, toast } from "./util/util";
let controller = '';
const API_VER = 'cv1';
export const BASE_URL = `${HOST}api/${API_VER}/`;
 
const writeOptions = async (options) => { 
    const token = await  getToken() ;  
    const headers = new Headers({
        'Content-Type': 'application/json',
        'Authorization':`Bearer ${token}`
    });
    if (options.contentType) {
        return {
            credentials: 'same-origin',
            ...options,
        }; 
    } 
    return {
        credentials: 'same-origin',
        headers,
        ...options,
    };

};

export const fetchApi = async (_url,method,options) => {
    let code = 200;
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
                /* customOptions.body=options.body;
                if (options.contentType) {
                    customOptions.contentType=options.contentType;
                } */
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
        const response =  await fetch(url, finalOptions);
        code = response.status;
        if (code !== 200) {     
            if(code == 401){
                delToken();
                throw new Error("ابتدا وارد حساب کاربری خود شوید");
            }
            throw new Error( response.msg );
        }
        const data = await response.json();
        return data;
    } catch (err) {     
        if (err.message) {
            toast('e',err.message); 
            return { error: err.message,code };
        }else{ 
            toast(langs.msgConnectionError,langs.error);
            return { error: langs.error,code };
        }
    }
};

export const abortFetch = async () => {
    if (controller){
        console.log('controller',controller);
        controller.abort();
    }
};

export const delAlert = async (url,id,title) => {
    title = title || '';
    var removeModal = new bootstrap.Modal(document.getElementById('removeModal'));
    removeModal.show();
    document.getElementById('submit_delete').addEventListener('click',function (e) {
        e.preventDefault();
        loading(1,e);
        fetchApi(url, 'DELETE', { params: {id}}).then((res) => {
            removeModal.hide();
            if (res.status) {
                document.getElementById('del_'+id).remove();
            } else {
                throw new Error('');
            }
            loading(0,e);
        }).catch(err=>{
            loading(0,e)
        })
    });
};



export async function getToken() {
    return await localStorage.getItem("auth_tkn")
}
export function get_Token() {
    return localStorage.getItem("auth_tkn")
}

export async function setToken(token,data) {
    await localStorage.setItem('_auth', JSON.stringify(data));
    await localStorage.setItem("auth_tkn", token)
}
export async function delToken() {
    await localStorage.removeItem("_auth")
    await localStorage.removeItem("auth_tkn")
}

// Longer duration refresh token (30-60 min)
export function getRefreshToken() {
    return localStorage.getItem("auth_refreshToken")
}

export function setRefreshToken(token) {
    localStorage.setItem("auth_refreshToken", token)
}
