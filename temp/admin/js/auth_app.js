(()=>{"use strict";Swal.mixin({customClass:{confirmButton:"btn btn-success",cancelButton:"btn btn-danger"},buttonsStyling:!1}),Swal.mixin({customClass:{confirmButton:"btn btn-success",cancelButton:"btn btn-danger"},buttonsStyling:!1});const e=(e,t,n)=>{const o={progressBar:!0,positionClass:"toast-top-left",closeBtn:!0};switch(e){case"i":toastr.info(t,n,o);break;case"w":toastr.warning(t,n,o);break;case"s":default:toastr.success(t,n,o);break;case"e":toastr.error(t,n,o)}},t=(e,t)=>{if(0!==(t=t||0))e?(t.target.disabled=!0,t.target.innerHTML+='<div class="spinner-container"><span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span></div>'):(t.target.disabled=!1,t.target.querySelector(".spinner-container")&&t.target.querySelector(".spinner-container").remove());else{const t=document.querySelector(".preloader").classList;e?t.remove("d-none"):t.add("d-none")}},n=e=>{var t=document.getElementById(e);return new Pristine(t)},o="خطا",a="خطا در برقراری ارتباط با سرور";document.getElementById("form-user-submit"),(()=>{const e=document.location.href.toLowerCase().split("admin/")[1].split("/");let t="index",n=[];if(e.length>1&&e[1],e.length>2){const t=e.splice(2);for(let e=0;e<t.length;e++)n[e]=t[e]}e[0]})();let r="";const s=`${$_url}api/v1/`,c=async(e,t,n)=>{let o={};try{r=new AbortController;const a=r.signal;let c=`${s}${e}`;const i={method:t,signal:a};switch(t){case"POST":case"PUT":i.body=JSON.stringify(n.body);break;case"DELETE":c+=`/${n.params.id}`;break;default:let e="";n.params.data&&Object.keys(n.params.data).length>0?(e=new URLSearchParams(n.params.data).toString(),c+=`?${e}`):Object.keys(n.params).length>0&&(e=new URLSearchParams(n.params).toString(),c+=`?${e}`)}const d=await(async e=>{const t=await l();return{credentials:"same-origin",headers:new Headers({"Content-Type":"application/json",Authorization:`Bearer ${t}`}),...e}})(i);if(o=await fetch(c,d),200!==o.status)throw new Error(`An error has occured: ${o.status} - ${o.statusText}`);return await o.json()}catch(e){if(401==o.status)location.href=$_Burl+"err";else{if(403!=o.status)return{error:e.message,code};i()}}},i=async()=>{await l(),window.location.href=$_url+"admin/login"};async function l(){return await localStorage.getItem("tkn")}window.addEventListener("load",(async()=>{await(()=>{if(document.getElementById("submit_login")&&(document.getElementById("submit_login").addEventListener("click",(function(r){if(r.preventDefault(),!grecaptcha.getResponse())return e("e","please check google recaptcha....",o),document.getElementById("alert-login-err-txt").innerText="please check google recaptcha....",document.getElementById("alert-login-err").classList.remove("d-none"),void setTimeout((()=>{document.getElementById("alert-login-err").classList.add("d-none")}),3e3);const s=document.getElementById("form-login");if(n("form-login").validate()){const n=new FormData(s),i=Object.fromEntries(n);t(1,r),c("auth/login","POST",{body:i}).then((t=>{"3"==t.status?($("#loginform").slideUp(),$("#recoverform").slideUp(),$("#verifyform").fadeIn(),grecaptcha.reset()):t.status?(window.location.href=$_Burl+"home",async function(e){await localStorage.setItem("tkn",e)}(t.data),e("s","عملیات با موفقیت انجام شد","موفقیت آمیز")):(e("e",a,o),document.getElementById("alert-login-err-txt").innerText=t.data,document.getElementById("alert-login-err").classList.remove("d-none"),grecaptcha.reset())})).catch((e=>{document.getElementById("alert-login-err-txt").innerText=e.message,document.getElementById("alert-login-err").classList.remove("d-none"),grecaptcha.reset()})).finally((()=>{t(0,r),setTimeout((()=>{document.getElementById("alert-login-err").classList.add("d-none")}),5e3)}))}})),document.getElementById("submit_forgot_pass").addEventListener("click",(function(r){r.preventDefault(),document.getElementById("alert-success").classList.add("d-none"),document.getElementById("alert-err").classList.add("d-none");const s=document.getElementById("form-forgot-pass");if(n("form-forgot-pass").validate()){const n=new FormData(s),i=Object.fromEntries(n);t(1,r),c("auth/forgot_pass","POST",{body:i}).then((t=>{s.reset(),t.status?document.getElementById("alert-success").classList.remove("d-none"):(document.getElementById("alert-err").classList.remove("d-none"),e("e",a,o))})).catch((t=>{console.log(t),e("e",t.message,o)})).finally((()=>{t(0,r)}))}}))),$(".preloader").fadeOut(),$("#to-recover").on("click",(function(){$("#verifyform").slideUp(),$("#loginform").slideUp(),$("#recoverform").fadeIn()})),$("#back-login").on("click",(function(){$("#verifyform").slideUp(),$("#loginform").fadeIn(),$("#recoverform").slideUp()})),document.getElementById("submit_change_password")&&document.getElementById("submit_change_password").addEventListener("click",(function(a){a.preventDefault();const r=document.getElementById("form-change-password");if(n("form-change-password").validate()){const n=new FormData(r),s=Object.fromEntries(n);t(1,a),c("auth/change_password","POST",{body:s}).then((e=>{r.reset(),e.status?(document.getElementById("alert-success").classList.remove("d-none"),setTimeout((()=>{window.location.href=$_Burl+"login"}),2e3)):(document.getElementById("alert-err-txt").innerText=e.data,document.getElementById("alert-err").classList.remove("d-none"))})).catch((t=>{console.log(t),e("e",t.message,o)})).finally((()=>{t(0,a)}))}})),null!=document.getElementById("submit_code")){function r(e){if(n("form-verify").validate()){let n=new FormData(document.getElementById("form-verify"));t(1,e),c("auth/verify","POST",{body:Object.fromEntries(n)}).then((async n=>{if(!n.status)throw new Error(n.msg);document.location.href=$_url+"admin/login/index/"+n.data,t(0,e)})).catch((n=>{t(0,e),console.log(n)}))}}document.getElementById("submit_code").addEventListener("click",r)}!async function(){await localStorage.removeItem("tkn")}()})()}))})();