import { Validator } from '../../../util/util';
import { fetchApi } from '../api';
import { loading, toast } from '../util/util';

export function UploadFile(options) {

    const _this = this;
	let clearTimer,imgContainer,totalFiles;
    let selectors = {};
    let acceptFormats = {
        'jpg':'image/jpg',
        'pdf':'application/pdf',
        'jpeg':'image/jpeg',
    }
	let defaultOptions = {
        maxFiles: 5,
		accept:['jpg','jpeg','pdf'],
        inputName: 'files'
    };

    options = { ...defaultOptions, ...options };

    this.init = function () {
		selectors.imgUpload   = document.getElementById('upload_imgs');
        if (options.maxFiles <= 1) selectors.imgUpload.multiple = false;
        let formats =[];
        for (let index = 0; index < options.accept.length; index++) {
            formats[index] = acceptFormats[options.accept[index]];
        }
        selectors.imgUpload.setAttribute('accept',formats.join(','))
		selectors.hintElement = document.querySelector('.upload-form__hint');
		selectors.thumbsItems = document.querySelector('.upload-form__thumbs');
		selectors.chooseBtn   = document.querySelector('.upload-form__btn');
		document.querySelector('.upload-form__sub-title').innerText = `(${langs.allowedFormats} : ${options.accept.join(' , ')})`;
		this.eventHandlers();
    };

	this.eventHandlers = function () {
		selectors.imgUpload.addEventListener('change', function (e) {
			_this.previewImgs(e);
		}, false);
		selectors.thumbsItems.addEventListener('click', function (e) {
			if (e.target.classList.contains('upload-form__thumbs-remove')) {
				e.target.parentElement.remove();
                _this.updateImagesCount(0);
			}
		});
    };

    this.previewImgs = function (e) {
        const totalThumbsLength = selectors.thumbsItems.querySelectorAll('img').length;
        if (totalThumbsLength >= options.maxFiles) {
            _this.showHint();
            return;
        }
        totalFiles = selectors.imgUpload.files.length;
        if (totalFiles + totalThumbsLength > options.maxFiles) {
            selectors.imgUpload.files.length = options.maxFiles  - totalThumbsLength;
            totalFiles = selectors.imgUpload.files.length;
            _this.showHint();
        }
        const { totalFileInputs } = _this.updateImagesCount(totalFiles);

        if (!!totalFiles) {
            selectors.thumbsItems.classList.remove('d-none');
            selectors.chooseBtn.innerText = `${langs.chooseFile} (${
                totalFileInputs.length + totalFiles
            })`;
        } else {
            selectors.thumbsItems.classList.add('d-none');
            selectors.chooseBtn.innerText = langs.chooseFile;
        }

        for (var i = 0; i < totalFiles; i++) {
            imgContainer = document.createElement('div');
            const img = document.createElement('img');
            const removeSpan = document.createElement('span');
            removeSpan.classList.add('upload-form__thumbs-remove');
            imgContainer.appendChild(removeSpan);
            const inp = document.createElement('input');
            inp.setAttribute('type', 'file');
            inp.setAttribute('name', `${options.inputName}[]`);
            inp.files = selectors.imgUpload.files;
            inp.classList.add('d-none');
            imgContainer.appendChild(inp);
            img.src = URL.createObjectURL(e.target.files[i]);
            imgContainer.appendChild(img);
            imgContainer.classList.add('upload-form__thumbs-item');
            selectors.thumbsItems.appendChild(imgContainer);
        }
    }

    this.updateImagesCount = function (totalFiles = 0) {
        const totalFileInputs = selectors.thumbsItems.querySelectorAll('img');
        if (!!totalFileInputs.length) {
            selectors.thumbsItems.classList.remove('d-none');
            selectors.chooseBtn.innerText = `${langs.chooseFile} (${
                totalFileInputs.length + totalFiles
            })`;
        } else {
            selectors.thumbsItems.classList.add('d-none');
            selectors.chooseBtn.innerText = langs.chooseFile;
        }
        return {
            totalFileInputs
        };
    }

    this.showHint = function () {
        clearTimeout(clearTimer);
        selectors.hintElement.innerText = `تعداد مجاز فایلهای انتخابی ${options.maxFiles} می باشد.`;
        selectors.hintElement.classList.remove('d-none');
        clearTimer = setTimeout(() => {
            selectors.hintElement.classList.add('d-none');
        }, 3000);
    }
    this.reset = function () {
        console.log(selectors.thumbsItems);
        selectors.thumbsItems.innerHTML = '';
        _this.updateImagesCount(0);
    }
	this.init()
}
export function SubmitForm(e,params) {
	e.preventDefault();
    let _res;
	const formEl = document.getElementById(params.id);
	var valid =  Validator(params.id).validate();
	if (params.onSend){
        const sendF = params.onSend(e); 
        if (!sendF) {
            valid = false; 
        }else if (typeof sendF == 'object') {
            if (params.hasOwnProperty('values')) {
                params.values =  Object.assign(params.values,sendF) ;
            }else{
                params.values =   sendF;
            }
        }
    };
    if (valid) {
		let data = new FormData(formEl);
		let method = 'POST';
		let url = params.api.POST;
		if (formEl.querySelector('#inp_id') != null && formEl.querySelector('#inp_id').value > 0) {
			data.append('id',formEl.querySelector('#inp_id').value);
			method = 'PUT';
            url = params.api.PUT;
		}else if(params.api.PUT  && params.api.PUT.length>0){
            data.append('id',formEl.querySelector('#inp_id').value);
			method = 'PUT';
            url = params.api.PUT;
        }
        const formEnteris =   Object.assign(Object.fromEntries(data),params.values?params.values:{}); 
		const sendData = (params.hasFile)?{body:formEnteris ,contentType:'multipart/form-data'}:{body: formEnteris};
		loading(e,1);
		fetchApi(url,method,sendData).then((res) => {
            _res = res;
			if (res.status) {
                if (params.hasOwnProperty('onSuccess'))params.onSuccess(_res,formEnteris);
			} else {
				if (params.hasOwnProperty('onFailed')) params.onFailed(_res); else throw new Error('');
			}
		}).catch(err=>{ 
			if (params.hasOwnProperty('onError')) params.onError(_res);else toast(err.message,'e');
		}).finally(() => {
            loading(e,0);
			if (params.hasOwnProperty('onfinal')) params.onfinal(_res);
            // formEl.reset()
		});
	}else{ 
        if (params.hasOwnProperty('onInvalid')){params.onInvalid(_res)}else{
            toast('e','فیلدهای ضروری را پر کنید')
        }
    }
}
