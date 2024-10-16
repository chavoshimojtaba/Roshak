import { fetchApi } from "../api";

const swalWithBootstrapButtons = Swal.mixin({
    customClass: {
        confirmButton: 'btn btn-success',
        cancelButton: 'btn btn-danger',
    },
    buttonsStyling: false,
});
export const fileType = (file)=> {
    const type =  file.split('/')[0];
    return ['image','video','application'].find(itm=> itm == type);
}
export function redirect(url,blank) {
    window.open($_Burl+url, (blank) ? '_blank':'_self');
    return;
} 
export const parseRequestUrl = () => {
    const url = document.location.href.toLowerCase().split('admin/')[1].split('/');
    let method = 'index';
    let params = [];
    let id = '';
    if (url.length > 1 ) {
        method = url[1];
    }
    if (url.length > 2 ) {
        const paramUrl = url.splice(2);
        for (let index = 0; index < paramUrl.length; index++) {
            params[index] = paramUrl[index];
        }
    }
    return {
        class: url[0],
        method,
        params
    };
};
export const openFM = (type,editor,cat) => {
    cat = cat || 0;
    window.open(
        $_Burl+ `file/index/${type}/${editor}/${cat}/fullscreen`,
        'گالری' ,
    ' left=5, top=200, width=1024,height=600,status=yes,scrollbars=yes,directories=no,menubar=no,resizable=yes,toolbar=no');
};
export const rerender = async (component) => {
    document.getElementById('main-container').innerHTML =
        await component.render();
        await component.after_render();
};
export const showLoading = () => {
    document.getElementById('loading-overlay').classList.add('active');
};
export const hideLoading = () => {
    document.getElementById('loading-overlay').classList.remove('active');
};
export const showMessage = (message, callback) => {
    document.getElementById('message-overlay').innerHTML = `
	<div>
	  <div id="message-overlay-content">${message}</div>
	  <button id="message-overlay-close-button">OK</button>
	</div>
	`;
    document.getElementById('message-overlay').classList.add('active');
    document
        .getElementById('message-overlay-close-button')
        .addEventListener('click', () => {
            document
                .getElementById('message-overlay')
                .classList.remove('active');
            if (callback) {
                callback();
            }
        });
};
export const toman = (x,reverse)=>{
    reverse = reverse || false; 
    if (x == 0 || x=='' || !x ) {
        return 0;
    }
    if (reverse) {  
        return Number(x.replace(/[^0-9.-]+/g,"")); 
    }else{ 
        var re = '\\d(?=(\\d{' + (  3) + '})+' + (  '$') + ')'  ;  
        return String(x).replace(new RegExp(re, 'g'), '$&' + ( ',')); 
    }
} 
export const currency = function () {
    $(".currency").on({
        keyup: function() {
            formatCurrency($(this));
        },
        blur: function() { 
            formatCurrency($(this), "blur");
        }
    });


    function formatNumber(n) {
    // format number 1000000 to 1,234,567
        return n.replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ",")
    }


    function formatCurrency(input, blur) {
        // appends $ to value, validates decimal side
        // and puts cursor back in right position.

        // get input value
        var input_val = input.val();

        // don't validate empty input
        if (input_val === "") { return; }

        // original length
        var original_len = input_val.length;

        // initial caret position 
        var caret_pos = input.prop("selectionStart");

        // check for decimal
        if (input_val.indexOf(".") >= 0) {

            // get position of first decimal
            // this prevents multiple decimals from
            // being entered
            var decimal_pos = input_val.indexOf(".");

            // split number by decimal point
            var left_side = input_val.substring(0, decimal_pos);
            var right_side = input_val.substring(decimal_pos);

            // add commas to left side of number
            left_side = formatNumber(left_side);

            // validate right side
            right_side = formatNumber(right_side);

            // On blur make sure 2 numbers after decimal
            if (blur === "blur") {
                right_side += "00";
            }

            // Limit decimal to only 2 digits
            right_side = right_side.substring(0, 2);

            // join number by .
            input_val = "$" + left_side + "." + right_side;

        } else {
            // no decimal entered
            // add commas to number
            // remove all non-digits
            input_val = formatNumber(input_val);
            input_val = input_val;

        }

        // send updated string to input
        input.val(input_val);

        // put caret back in the right position
        var updated_len = input_val.length;
        caret_pos = updated_len - original_len + caret_pos;
        input[0].setSelectionRange(caret_pos, caret_pos);
    }
}
export const delAlert =async (resource,title,params) => {
    title = title || 'داده';
    params = params || 0;
    const url = (params !== 0)?resource+'/'+params:resource;
    const {value} =await swalBs
        .fire({
            title: `حذف ${title}`,
            text: `آیا از حذف ${title}  اطمینان دارید؟`,
            type: 'error',
            showCancelButton: true,
            confirmButtonText: 'بله, حذف شود!',
            cancelButtonText: 'انصراف',
            preConfirm: () => {
                return fetchApi(url, 'DELETE', { params:{
                    id:document.getElementById('pub_id').value
                } }).then(res=>{
                    if (res.status == 1) {
                        return {success:true};
                    }else{
                        return {success:false,status:res.status};
                    }
                }).catch(err=>{
                    return {err:true};
                })
            },
        })
        return value
}; 
export const ratingStars =(num) => {
    let stars = '';
    const emptyStarNum = 5 - parseInt(num);
    for (let index = 0; index < num; index++) {
        stars += `<i class="fas fa-star"></i>`
    }
    for (let i = 0; i < emptyStarNum; i++) {
        stars += `<i class="far fa-star"></i>`
    }
    return `
        <div class="rating-box">${stars}</div>
    `
}; 
export const actionAlert =async (url,title,text,body,type) => {
    title = title || 'داده مورد نظر';
    type = type || 'error';
    const {value} =await swalWithBootstrapButtons
        .fire({
            title: `${title}`,
            text: `${text}`,
            type,
            showCancelButton: true,
            confirmButtonText: 'بله!',
            cancelButtonText: 'انصراف',
            preConfirm: () => {
                return fetchApi(url, 'PUT',{body}).then(res=>{
                    if (res.error) {
                        throw new Error( res.error);
                    }
                    return {success:true};
                }).catch(err=>{
                    return {err:true};
                })
            },
        })
    return value
};
export const toast = (type, message, title) => {
    const options = {
        progressBar: true,
        positionClass: 'toast-top-left',
        closeBtn: true,
    };
    
    switch (type) {
        case 'i': //info
            toastr.info(message, title, options);
            break;
        case 'w': //warning
            toastr.warning(message, title, options);
            break;
        case 's': //success
            toastr.success(message, title, options);
            break;
        case 'e': //error
        console.log(options);
            toastr.error(message, title, options);
            return
            break;
        default:
            toastr.success(message, title, options);
            break;
    }
};
export const loading = (show,e) => {
    e = e || 0;
    if (e !== 0 ) {
        if (!show ) {
            e.target.disabled=false; 
            (e.target.querySelector('.spinner-container'))?e.target.querySelector('.spinner-container').remove():false;
        }else{
            e.target.disabled=true
            e.target.innerHTML += '<div class="spinner-container"><span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span></div>';
        }
    }else{
        const selector = document.querySelector('.preloader').classList;
        (show)?selector.remove('d-none'):selector.add('d-none')
    }
};
export const createNestedObject = (pid,data,keys) => {
    var res = {};
    for (var i = 0; i < keys.length; i++) {
        if (data[keys[i]].pid === pid) {
            var search = createNestedObject(data[keys[i]].id,data,keys);
            var childs = Object.keys(search).length === 0 ? {} : { children: search };
            res[data[keys[i]].id] = Object.assign(
                childs,
                data[keys[i]]
            );
        }
    }
    return res;
} 
export const createCategoryTemplate = (data,levelNumber) => {
    var html = `
        <ul>
    `;
    levelNumber++;
    $.each(data,function(key,val){
        html += `
            <li>
                <div class="treeview__level" data-level="${levelNumber}" >
                    <span class="level-title">
                        ${val.title}
                    </span>
                    <div class="treeview__level-btns" data-json=\'${JSON.stringify(val)}\'>
                        <div class="btn btn-outline-info btn-sm level-edit"><span class="ti-settings"></span></div>
                        <div class="btn btn-outline-danger btn-sm level-remove"><span class="ti-trash"></span></div>
                        <div class="btn btn-outline-success btn-sm level-add"><span class="ti-plus"></span></div>
                        <div class="btn btn-outline-success btn-sm level-same"><span>هم سطح</span></div>
                    </div>
                </div>
        `;
        if (val.children){
            html += createCategoryTemplate(val.children,levelNumber);
        }
        html += "</li>";
    });
    html += "</ul>";
    return html;
};
export const betweenCharacters = (text,startCh,endCh) => {
    startCh = startCh || '{';
    endCh = endCh || '}';
    const matches = text.match(new RegExp(startCh + "(.*?)" + endCh, 'g'));
    console.log(matches);
    const result = [];
    if (matches) {
      for (let i = 0; i < matches.length; ++i) {
        const match = matches[i];
        result.push(match.substring(1, match.length - 1)); // brackets removing
      }
    }
    return result;
}; 
export function slugValidation(table,btn) {
    if (document.querySelector('#inp_slug') != null) {
        document.querySelector('#inp_slug').addEventListener('blur', function (e) {
            this.value = this.value.trim().toLowerCase().replaceAll(' ', '-').replaceAll(/[^a-z,-]+/g, '') ; 
            const _this = this;
            var postalRGEX = /^([a-z])([a-z]|[-]){1,150}$/;
            this.value = this.value.toLowerCase();
            if (postalRGEX.test( _this.value)) {
                fetchApi('util/slug_validation', 'GET', { params: { data: { slug: _this.value, table ,id:document.querySelector('#inp_id').value } } }).then(async (res) => {
                    if (res.status == 0) {
                        _this.focus();
                        document.querySelector('#'+btn).setAttribute('disabled',true);
                        toast('e','اسلاگ نامعتبر(تکراری)');
                        _this.closest('.form-group').classList.add('has-danger');
                        _this.closest('.form-group').classList.remove('has-success');
                        _this.classList.add('form-control-danger');
                        _this.classList.remove('form-control-success');
                    }else{
                        document.querySelector('#'+btn).removeAttribute('disabled');
                        _this.closest('.form-group').classList.remove('has-danger');
                        _this.closest('.form-group').classList.add('has-success');
                        _this.classList.remove('form-control-danger');
                        _this.classList.add('form-control-success');
                    }
                }).catch(err => {
                    toast('e');
                })
            }else{
                document.querySelector('#'+btn).setAttribute('disabled',true);
                toast('e','اسلاگ نامعتبر(کاراکتر غیر مجاز)');
                _this.closest('.form-group').classList.add('has-danger');
                _this.closest('.form-group').classList.remove('has-success');
                _this.classList.add('form-control-danger');
                _this.classList.remove('form-control-success');
            }
        })
    }
}
export const swalBs = Swal.mixin({
    customClass: {
        confirmButton: 'btn btn-success',
        cancelButton: 'btn btn-danger',
    },
    buttonsStyling: false,
});