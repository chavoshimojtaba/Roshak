(()=>{var e={504:()=>{}},t={};function n(o){var r=t[o];if(void 0!==r)return r.exports;var a=t[o]={exports:{}};return e[o](a,a.exports,n),a.exports}(()=>{"use strict";const e=e=>{var t=document.getElementById(e);return new Pristine(t)};Swal.mixin({customClass:{confirmButton:"btn btn-success",cancelButton:"btn btn-danger"},buttonsStyling:!1}),Swal.mixin({customClass:{confirmButton:"btn btn-success",cancelButton:"btn btn-danger"},buttonsStyling:!1});const t=(e,t,n)=>{const o={progressBar:!0,positionClass:"toast-top-left",closeBtn:!0};switch(e){case"i":toastr.info(t,n,o);break;case"w":toastr.warning(t,n,o);break;case"s":default:toastr.success(t,n,o);break;case"e":toastr.error(t,n,o)}},o=(e,t)=>{if(0!==(t=t||0))e?(t.target.disabled=!0,t.target.innerHTML+='<div class="spinner-container"><span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span></div>'):(t.target.disabled=!1,t.target.querySelector(".spinner-container")&&t.target.querySelector(".spinner-container").remove());else{const t=document.querySelector(".preloader").classList;e?t.remove("d-none"):t.add("d-none")}},r="خطا",a="خطا در برقراری ارتباط با سرور";n(504),document.getElementById("form-user-submit"),(()=>{const e=document.location.href.toLowerCase().split("admin/")[1].split("/");let t="index",n=[];if(e.length>1&&e[1],e.length>2){const t=e.splice(2);for(let e=0;e<t.length;e++)n[e]=t[e]}e[0]})();let s="";const c=`${$_url}api/v1/`,i=async(e,t,n)=>{let o={};try{s=new AbortController;const r=s.signal;let a=`${c}${e}`;const i={method:t,signal:r};switch(t){case"POST":case"PUT":i.body=JSON.stringify(n.body);break;case"DELETE":a+=`/${n.params.id}`;break;default:let e="";n.params.data&&Object.keys(n.params.data).length>0?(e=new URLSearchParams(n.params.data).toString(),a+=`?${e}`):Object.keys(n.params).length>0&&(e=new URLSearchParams(n.params).toString(),a+=`?${e}`)}const l=await(async e=>{const t=await d();return{credentials:"same-origin",headers:new Headers({"Content-Type":"application/json",Authorization:`Bearer ${t}`}),...e}})(i);if(o=await fetch(a,l),200!==o.status)throw new Error(`An error has occured: ${o.status} - ${o.statusText}`);return await o.json()}catch(e){if(401==o.status)location.href=$_Burl+"err";else{if(403!=o.status)return{error:e.message,code};l()}}},l=async()=>{await d(),window.location.href=$_url+"admin/login"};async function d(){return await localStorage.getItem("tkn")}window.addEventListener("load",(async()=>{await(()=>{if(document.getElementById("submit_login")&&(document.getElementById("submit_login").addEventListener("click",(function(n){if(n.preventDefault(),!grecaptcha.getResponse())return t("e","please check google recaptcha....",r),document.getElementById("alert-login-err-txt").innerText="please check google recaptcha....",document.getElementById("alert-login-err").classList.remove("d-none"),void setTimeout((()=>{document.getElementById("alert-login-err").classList.add("d-none")}),3e3);const s=document.getElementById("form-login");if(e("form-login").validate()){const e=new FormData(s),c=Object.fromEntries(e);o(1,n),i("auth/login","POST",{body:c}).then((e=>{"3"==e.status?($("#loginform").slideUp(),$("#recoverform").slideUp(),$("#verifyform").fadeIn()):e.status?(window.location.href=$_Burl+"home",async function(e){await localStorage.setItem("tkn",e)}(e.data),t("s","عملیات با موفقیت انجام شد","موفقیت آمیز")):(t("e",a,r),document.getElementById("alert-login-err-txt").innerText=e.data,document.getElementById("alert-login-err").classList.remove("d-none"))})).catch((e=>{document.getElementById("alert-login-err-txt").innerText=e.message,document.getElementById("alert-login-err").classList.remove("d-none")})).finally((()=>{o(0,n),setTimeout((()=>{document.getElementById("alert-login-err").classList.add("d-none")}),5e3)}))}})),document.getElementById("submit_forgot_pass").addEventListener("click",(function(n){n.preventDefault(),document.getElementById("alert-success").classList.add("d-none"),document.getElementById("alert-err").classList.add("d-none");const s=document.getElementById("form-forgot-pass");if(e("form-forgot-pass").validate()){const e=new FormData(s),c=Object.fromEntries(e);o(1,n),i("auth/forgot_pass","POST",{body:c}).then((e=>{s.reset(),e.status?document.getElementById("alert-success").classList.remove("d-none"):(document.getElementById("alert-err").classList.remove("d-none"),t("e",a,r))})).catch((e=>{console.log(e),t("e",e.message,r)})).finally((()=>{o(0,n)}))}}))),$(".preloader").fadeOut(),$("#to-recover").on("click",(function(){$("#verifyform").slideUp(),$("#loginform").slideUp(),$("#recoverform").fadeIn()})),$("#back-login").on("click",(function(){$("#verifyform").slideUp(),$("#loginform").fadeIn(),$("#recoverform").slideUp()})),document.getElementById("submit_change_password")&&document.getElementById("submit_change_password").addEventListener("click",(function(n){n.preventDefault();const a=document.getElementById("form-change-password");if(e("form-change-password").validate()){const e=new FormData(a),s=Object.fromEntries(e);o(1,n),i("auth/change_password","POST",{body:s}).then((e=>{a.reset(),e.status?(document.getElementById("alert-success").classList.remove("d-none"),setTimeout((()=>{window.location.href=$_Burl+"login"}),2e3)):(document.getElementById("alert-err-txt").innerText=e.data,document.getElementById("alert-err").classList.remove("d-none"))})).catch((e=>{console.log(e),t("e",e.message,r)})).finally((()=>{o(0,n)}))}})),null!=document.getElementById("submit_code")){function n(t){if(e("form-verify").validate()){let e=new FormData(document.getElementById("form-verify"));o(1,t),i("auth/verify","POST",{body:Object.fromEntries(e)}).then((async e=>{if(!e.status)throw new Error(e.msg);document.location.href=$_url+"admin/login/index/"+e.data,o(0,t)})).catch((e=>{o(0,t),console.log(e)}))}}document.getElementById("submit_code").addEventListener("click",n)}!async function(){await localStorage.removeItem("tkn")}()})()}))})()})();