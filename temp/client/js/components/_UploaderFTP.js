import { LNG_WARNING } from '../util/consts';
import { toast } from '../util/util';
import { DropzoneUploaderFtp } from './_DropzoneUploaderFtp';
var fileExts = { 
    'zip' : 'doc'
};
function UploaderFTP(options) { 
	const _this = this; 
	let fileItems;
	let dropzoneInstance = {};
	let containerEl;
	let defaultFiles = [];
	let defaultOptions = {
		preview_element: 'dropzone_preview',
		type: 'public', 
		dest:'server',
		onSend:null,
		cls: 'upload-ftp-file',
		max:'100',
		formats: '.zip',
		title: 'بارگذاری فایل',
		filesType: 'doc',  
	};

	options = { ...defaultOptions, ...options };

	this.render = function () {
		containerEl = document.querySelector(`.${options.cls}`); 
		const inpEl = containerEl.querySelectorAll('input');
		if (inpEl.length > 0) {
			for (let index = 0; index < inpEl.length; index++) {
				const inp = inpEl[index];
				defaultFiles[index] = { 'id': inp.id, file: inp.value };
			} 
		} else if (options.files && options.files.length > 0) {
			defaultFiles = options.files;
		}
		this.createTemplate();
		if (options.hasOwnProperty('onAction')) { 
			dropzoneInstance = new DropzoneUploaderFtp({dest:options.dest,cls:options.cls,formats:options.formats,id:options.preview_element,type : options.type  ,onComplete:options.onAction,onSend:options.onSend});
		}else{
			dropzoneInstance = new DropzoneUploaderFtp({dest:options.dest,cls:options.cls,formats:options.formats,id:options.preview_element,type : options.type,onSend:options.onSend });
		}
		dropzoneInstance.render()
	};

	this.createTemplate = function () { 
		containerEl.innerHTML = `
			<div class="upload-box border-warning px-md-4 my-md-3 py-md-3">
				<div id="actions" class="d-flex w-100 align-items-center justify-content-center ">
					<img src="${HOST}file/client/images/icons/squircle1.svg" alt="">
					<div class="flex-fill pe-2">
						<h5 class="text-black fw-bold">${options.title} </h5>
						<p class="m-0">فرمت های مجاز : ${options.formats}</p>
					</div>
					<div class="d-flex">
						<span class="btn rounded-2 	ms-0 btn-warning fileinput-button position-relative">
							برای انتخاب فایل کلیک کنید
							<span class="position-absolute top-0 w-100 h-100"></span>
						</span>
						<a class="btn btn-info mx-1 d-none start">
							<span>شروع بارگذاری</span>
						</a>
						<button type="reset" class="btn  me-1 btn-danger d-none cancel">
							<span>انصراف | حذف</span>
						</button>
					</div>
				</div>
				<div id="card-preview" class="upload-center mt-1 mt-md-3">
					<div class="files upload-file__items" id="${options.preview_element}"> 
					</div>
				</div>
			</div>
			
		`;
		this.addFiles(defaultFiles);
	};

	this.getFileType =function (file) {
		const extSplit = file.split('.');
		if (extSplit.length > 0) {
			let ext = extSplit[extSplit.length-1];
			if (fileExts[ext]) {
				return fileExts[ext];
			}
		}
		return 'undifined';
	}

	this.addFiles = function (files) { 
		if (typeof files == 'string') {
			files = [{ file: files, id: 1 }]
		} else if (!files instanceof Array) {
			files = [files]
		}
		let items = '';
		if (files && files.length > 0) {
			for (let index = 0; index < files.length; index++) {
				const item = files[index];
				let fileEl = '';
				const type = _this.getFileType(item.file); 
						fileEl = `<a class="file_box doc d-flex p-3 text-white"  data-key="${item.id}" data-dir="${item.file}" href="${HOST}${item.file}" target="_blank"><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="60px" height="60px" viewBox="0 0 20 20" version="1.1"><title>file_zip [#1735]</title><desc>Created with Sketch.</desc><defs></defs><g id="Page-1" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd"><g id="Dribbble-Light-Preview" transform="translate(-300.000000, -1359.000000)" fill="#000000"><g id="icons" transform="translate(56.000000, 160.000000)"><path d="M245.998401,1201.0005 L255.990408,1201.0005 L255.990408,1207.0005 L261.985612,1207.0005 L261.985612,1209.0005 L263.984013,1209.0005 L263.984013,1205.4385 L257.967826,1199.0005 L244,1199.0005 L244,1209.0005 L245.998401,1209.0005 L245.998401,1201.0005 Z M262.001599,1214.1055 C262.001599,1213.5525 261.553957,1213.1055 261.002398,1213.1055 L260.003197,1213.1055 L260.003197,1215.1055 L261.002398,1215.1055 C261.553957,1215.1055 262.001599,1214.6575 262.001599,1214.1055 L262.001599,1214.1055 Z M264,1214.0005 C264,1215.6575 262.657074,1217.0005 261.002398,1217.0005 L259.98721,1217.0005 L259.98721,1219.0005 L257.988809,1219.0005 L257.988809,1211.0005 L261.002398,1211.0005 C262.657074,1211.0005 264,1212.3435 264,1214.0005 L264,1214.0005 Z M249.995204,1211.0005 L244,1211.0005 L244,1213.0005 L246.997602,1213.0005 L244,1219.0005 L249.995204,1219.0005 L249.995204,1217.0005 L246.997602,1217.0005 C251.141287,1208.7065 248.200639,1214.5925 249.995204,1211.0005 L249.995204,1211.0005 Z M256.989608,1211.0005 L256.989608,1213.0005 L254.991207,1213.0005 L254.991207,1217.0005 L256.989608,1217.0005 L256.989608,1219.0005 L250.994404,1219.0005 L250.994404,1217.0005 L252.992806,1217.0005 L252.992806,1213.0005 L250.994404,1213.0005 L250.994404,1211.0005 L256.989608,1211.0005 Z" id="file_zip-[#1735]"></path></g></g></g></svg></a>`;
				
				items += `

					
						<div class="upload-file__item shadow-custom  position-relative mb-3 ms-3" id="upload-file__item-${item.id}">
							${fileEl}
							<input type="hidden" value="${item.id}"  data-id="${item.id}" data-dir="${item.file}" class="files"/>
							<button
								type="button"
								class=" btn btn-light-danger btn-circle btn-sm d-inline-flex align-items-center justify-content-center
								m-0 del-item upload-file__action-del">
								 <span class="shadow-custom d-flex">
								 <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="Layer_1" x="0px" y="0px" viewBox="0 0 10 11" width="13px" height="13px" style="enable-background:new 0 0 10 11;" xml:space="preserve"> <style type="text/css"> .st0{fill:none;stroke:#000;stroke-width:1;stroke-linecap:round;stroke-linejoin:round;} </style> <path class="st0" d="M9,1.5l-4,4 M5,5.5l-4,4 M5,5.5l4,4 M5,5.5l-4-4"/> </svg></span>
								</button>
						</div>
					
				`;
			}
			containerEl.querySelector('.upload-file__items').innerHTML ='<div class="file-row d-flex">'+ items+'</div>';
			this.handleOnItemClick();
		}
		this.clickHandlers();
	};

	this.reset = function () {
		containerEl.querySelector('.upload-file__items').innerHTML = '';
	};

	this.handleOnItemClick = function () {
		const delBtns = containerEl.querySelectorAll('.upload-file__items .upload-file__action-del');
		for (let index = 0; index < delBtns.length; index++) {
			const element = delBtns[index];
			element.addEventListener('click', function (e) {
				e.preventDefault();
				element.closest('.upload-file__item').remove();
				if (options.hasOwnProperty('onAction')) { 
					options.onAction(); 
				}
			})
		}
	};
 
	this.getFiles = function (jsonOutput) {
		jsonOutput = jsonOutput || false;
		const files = containerEl.querySelectorAll('.upload-file__items .files');
		let single = '';
		let res = [];
		if (files.length > 0) { 
			for (let index = 0; index < files.length; index++) {  
				res.push(files[index].value);
				if (index == 0) {
					single = files[index].value;
				}
			}
		}

		if (jsonOutput == 'single') {
			return single;
		}
		return jsonOutput ? JSON.stringify(res) : res;
	};
	
	this.getFilesDetails = function (jsonOutput) {
		jsonOutput = jsonOutput || false;
		const files = containerEl.querySelectorAll('.upload-file__items .files');
		let single = '' ;
		let res = []; 
		if (files.length > 0) { 
			for (let index = 0; index < files.length; index++) {  
				const id = files[index].getAttribute('data-id');
				const dir = files[index].getAttribute('data-dir');  
				res.push( {
					file:dir,
					id
				});
				if (index == 0) {
					single = files[index].value;
				}
			}
		}

		if (jsonOutput == 'single') {
			return single;
		}
		return jsonOutput ? JSON.stringify(res) : res;
	};

	this.clickHandlers = function () { 
		containerEl.querySelector('.fileinput-button span').addEventListener('click', function (e) {
			e.preventDefault();  
			fileItems = containerEl.querySelectorAll('.upload-file__items .files'); 
			if (fileItems && (options.max <= fileItems.length)) {
				e.stopPropagation(); 
				toast('w', 'تعداد مجاز فایل انتخابی 1 فایل میباشد.', LNG_WARNING);
			} 
		}) 
	};
	this.render();
}

export { UploaderFTP };
