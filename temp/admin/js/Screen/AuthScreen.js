import { delToken, fetchApi, setToken } from '../api';
import { loading, toast } from '../util/util';
import {
    LNG_ERROR,
    LNG_MSG_CONNECTION_ERROR,
    LNG_MSG_SUCCESS,
    LNG_SUCCESS,
} from '../util/consts';
import { Validator } from '../util/Validator';

export const AuthScreen = {
    render: () => {
        if (document.getElementById('submit_login')) {
            document
                .getElementById('submit_login')
                .addEventListener('click', function (e) {
                    e.preventDefault()
                    var response = grecaptcha.getResponse();
                    if (!response) {
                        toast('e','please check google recaptcha....',LNG_ERROR)
                        document.getElementById('alert-login-err-txt').innerText = 'please check google recaptcha....';
                        document.getElementById('alert-login-err').classList.remove('d-none');
                        setTimeout(() => {
                            document.getElementById('alert-login-err').classList.add('d-none');
                        }, 3000);
                        return
                    }
                    const form = document.getElementById('form-login')
                    var valid = Validator('form-login').validate();
                    if (valid) {
                        const formObj = new FormData(form);
                        const formData = Object.fromEntries(formObj);
                        loading(1, e);
                        fetchApi('auth/login', 'POST', { body: formData })
                            .then((res) => {
                                if (res.status == '3') {
                                    $("#loginform").slideUp();
                                    $("#recoverform").slideUp();
                                    $("#verifyform").fadeIn();
                                    grecaptcha.reset();
                                }else  if (res.status) {
                                    window.location.href = $_Burl+'home';
                                    setToken(res.data);
                                    toast('s', LNG_MSG_SUCCESS, LNG_SUCCESS);
                                } else {
                                    toast('e', LNG_MSG_CONNECTION_ERROR, LNG_ERROR);
                                    document.getElementById('alert-login-err-txt').innerText = res.data;
                                    document.getElementById('alert-login-err').classList.remove('d-none');
                                    grecaptcha.reset();
                                }
                            })
                            .catch((err) => {
                                document.getElementById('alert-login-err-txt').innerText = err.message;
                                document.getElementById('alert-login-err').classList.remove('d-none');
                                grecaptcha.reset();
                            })
                            .finally(() => {
                                loading(0, e);
                                setTimeout(() => {
                                    document.getElementById('alert-login-err').classList.add('d-none');
                                }, 5000);
                            });
                    }
                });
            document
                .getElementById('submit_forgot_pass')
                .addEventListener('click', function (e) {
                    e.preventDefault()
                    document.getElementById('alert-success').classList.add('d-none')
                    document.getElementById('alert-err').classList.add('d-none')
                    const form = document.getElementById('form-forgot-pass')
                    var valid = Validator('form-forgot-pass').validate();
                    if (valid) {
                        const formObj = new FormData(form);
                        const formData = Object.fromEntries(formObj);
                        loading(1, e);
                        fetchApi('auth/forgot_pass', 'POST', { body: formData })
                            .then((res) => {
                                form.reset();
                                if (res.status) {
                                    document.getElementById('alert-success').classList.remove('d-none')
                                } else {
                                    document.getElementById('alert-err').classList.remove('d-none')
                                    toast('e', LNG_MSG_CONNECTION_ERROR, LNG_ERROR);
                                }
                            })
                            .catch((err) => {
                                console.log(err);
                                toast('e', err.message, LNG_ERROR);
                            })
                            .finally(() => {
                                loading(0, e);
                            });
                    }
                });

        }
        $(".preloader").fadeOut();
        $("#to-recover").on("click", function() {
            $("#verifyform").slideUp();
            $("#loginform").slideUp();
            $("#recoverform").fadeIn();
        });

        $("#back-login").on("click", function() {
            $("#verifyform").slideUp();
            $("#loginform").fadeIn();
            $("#recoverform").slideUp();
        });
        if (document.getElementById('submit_change_password')) {
            document
            .getElementById('submit_change_password')
            .addEventListener('click', function (e) {
                e.preventDefault()
                const form = document.getElementById('form-change-password')
                var valid = Validator('form-change-password').validate();
                if (valid) {
                    const formObj = new FormData(form);
                    const formData = Object.fromEntries(formObj);
                    loading(1, e);
                    fetchApi('auth/change_password', 'POST', { body: formData })
                        .then((res) => {
                            form.reset();
                            if (res.status) {
                                document.getElementById('alert-success').classList.remove('d-none')
                                setTimeout(() => {
                                    window.location.href = $_Burl+'login'
                                }, 2000);
                            } else {
                                document.getElementById('alert-err-txt').innerText = res.data
                                document.getElementById('alert-err').classList.remove('d-none')
                            }
                        })
                        .catch((err) => {
                            console.log(err);
                            toast('e', err.message, LNG_ERROR);
                        })
                        .finally(() => {
                            loading(0, e);
                        });
                }
            });
        }
        if (document.getElementById('submit_code') != null) {
            document.getElementById('submit_code').addEventListener('click', submit_code);
            function submit_code(e) { 
                var valid = Validator('form-verify').validate();
                if (valid) {
                    let data = new FormData(document.getElementById('form-verify'));
                    loading(1,e);
                    fetchApi('auth/verify', 'POST', { body:  Object.fromEntries(data)}).then(async (res) => {
                        if (res.status) {
                            document.location.href = $_url+'admin/login/index/'+res.data;
                        } else {
                            throw new Error(res.msg);
                        }
                        loading(0, e);
                    }).catch(err=>{
                        loading(0, e)
                        console.log(err);
                        // alertBox('e', err.message,'form-verify');
                    })
                }
            }
        }
        delToken();
    },
};