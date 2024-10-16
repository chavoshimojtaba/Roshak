import { LNG_WARNING } from '../util/consts';
import {   toast, openFM, fileType } from '../util/util';
function UploadForm(options) {
    const _this = this;
    let selectors = {
        inputs: {},
    };

    let fileItems;
    let containerEl;
    let defaultFiles = [];
    let defaultOptions = {
        cls: 'upload-file',
        formats: 'webp , .jpeg , .jpg , .png , .gif',
        title: 'انتخاب فایل',
        filesType: 'image',
        btnTitle: 'افزودن تصویر',
        perPage: 10,
    };

    options = { ...defaultOptions, ...options };

    this.render = function () {
        containerEl = document.querySelector(`.${options.cls}`);
		const inpEl = containerEl.querySelectorAll('input');
        if (inpEl.length > 0) {
            for (let index = 0; index < inpEl.length; index++) {
                const inp = inpEl[index];
                defaultFiles[index] = {'id':inp.id,file:inp.value};
            }
        }else if(options.files && options.files.length>0){
			defaultFiles = options.files;
		}
        this.createTemplate();
    };

    this.createTemplate = function () {
		containerEl.innerHTML = `
			<i class="fas fa-cloud-upload-alt mb-2"></i>
			<h4>${options.title}</h4>
			<span class="text-info  mb-2 d-block">
				فرمت های مجاز :
				<span class="upload-file__formats ">
					${options.formats}
				</span>
			</span>
			<div class="upload-file__items">
			</div>
			<button type="button" class="add_file btn btn-light-info text-info  d-inline-flex align-items-center justify-content-center">
				<i class="mdi mdi-database-plus"></i>&nbsp;
				${options.btnTitle}
			</button>
		`;
		this.addFiles(defaultFiles);
    };

    this.addFiles = function (files) {
		if (typeof files == 'string') {
			files = [{file:files,id:1}]
		}else if (!files instanceof Array) {
			files = [files]
		}
		let items = '';
		if (files && files.length > 0) {
			for (let index = 0; index < files.length; index++) {
				const item = files[index];
				let fileEl = '';
				const type = getFileType(item.file);
				switch (type) {
					case 'image':
						fileEl = `<img src="${$_url}${item.file}" data-key="${item.id}" data-dir="${item.file}">`;
						break;
					case 'video':
						fileEl = `
							<video width="100%" height="240" controls  data-key="${item.id}" data-dir="${item.file}">
								<source src="${$_url}${item.file}" type="video/mp4">
								<source src="movie.ogg" type="video/ogg">
								Your browser does not support the video tag.
							</video>
						`;
						break;
					default:
						fileEl = `<a class="file_box doc bg-info p-3 text-white"  data-key="${item.id}" data-dir="${item.file}" href="${$_url}${item.file}" target="_blank"><i class="mdi mdi-file-document text-white mb-1"></i>document</a>`;
						break;
				}
				items += `
					<div class="upload-file__item mb-3" id="upload-file__item-${item.id}">
						${fileEl}
						<button
							type="button"
							class="
							btn btn-light-danger btn-circle btn-sm
							d-inline-flex
							align-items-center
							justify-content-center
							m-0
							del-item
							upload-file__action-del">
							<i class="del-item ti-trash text-danger"></i>
						</button>
					</div>
				`;
			}
			containerEl.querySelector('.upload-file__items').innerHTML = items;
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
			element.addEventListener('click',function (e) {
				e.preventDefault();
				element.closest('.upload-file__item').remove();
			})
		}
    };

    this.getFiles = function (jsonOutput) {
		jsonOutput = jsonOutput||false;
		const files = containerEl.querySelectorAll('.upload-file__items img,.upload-file__items video,.upload-file__items .doc');
		let single = '';
		let res = {};
		if (files.length > 0) {
			for (let index = 0; index < files.length; index++) {
				const id = files[index].getAttribute('data-key');
				const dir = files[index].getAttribute('data-dir');
				res[id] = {
					file:dir,
					id:id
				};
				single=dir;
			}
		}
		if (jsonOutput == 'single') {
			return single;
		}
		return jsonOutput?JSON.stringify(res):res;
    };

    this.clickHandlers = function () {
		containerEl.querySelector('.add_file').addEventListener('click',function (e) {
			e.preventDefault();
			if (!selectors.containers) {
				selectors.containers = document.getElementsByClassName('upload-file__items');
			}
			for (let index = 0; index < selectors.containers.length; index++) {
				selectors.containers[index].classList.remove('active');
			}
			containerEl.querySelector('.upload-file__items').classList.add('active');
			fileItems = containerEl.querySelectorAll('.upload-file__item');
			if (fileItems && (options.max === fileItems.length)) {
				toast('w','تعداد مجاز فایل انتخابی 1 میباشد',LNG_WARNING);
			}else{
				openFM(options.filesType,(options.max>1?'multi':'single'));
			}
		})
    };
    this.render();
}

export { UploadForm };
