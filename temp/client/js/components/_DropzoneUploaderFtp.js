import { BASE_URL, fetchApi } from '../api';
import {
	LNG_ERROR
} from '../util/consts';
import { toast } from '../util/util';
export function DropzoneUploaderFtp(options) {
	this.render = function () {
		const _this = this;
		let defaultOptions = {
			cls: 'upload-file',
			type: 'public',
			formats: 'image/jpg,image/jpeg'
		};

		options = { ...defaultOptions, ...options };

		var previewNode = document.querySelector('#template_ftp');
		previewNode.id = '';
		var previewTemplate = previewNode.parentNode.innerHTML;
		previewNode.parentNode.removeChild(previewNode);
		const fileinputButton = document.querySelector('.' + options.cls + ' .fileinput-button');
		const cardCreview = document.querySelector('.' + options.cls + ' #card-preview');
		const startBtn = document.querySelector('.' + options.cls + ' .start');
		const cancelBtn = document.querySelector('.' + options.cls + ' #actions .cancel');
		const acceptFormats = options.formats;
		let id = 'dropzone_preview';
		if (options.id) {
			id = options.id;
		}
		const clickable = '.' + options.cls + ' .fileinput-button';
		var myDropzone = new Dropzone('div#' + id, {
			url: BASE_URL + 'file/add_ftp',
			method: 'POST', 
			acceptedFiles: acceptFormats,
			autoQueue: false,     
			timeout: 180000,
			maxFiles: 1,
			previewTemplate: previewTemplate,
			previewsContainer: '#' + id,
			clickable,
			success: function (file, response) {
				file.previewElement.innerHTML += '<input type="hidden" value="' + response.data.id + '"  data-id="' + response.data.id + '" data-dir="' + response.data.dir + '" class="files"/>';
			},
			uploadprogress(file, progress, bytesSent) {
				if (file.previewElement) {
					for (let node of file.previewElement.querySelectorAll(
						"[data-dz-uploadprogress]"
					)) {
						node.nodeName === "PROGRESS"
							? (node.value = progress)
							: (node.style.width = `${progress}%`);
					}
				}
			},

			// Called whenever the total upload progress gets updated.
			// Called with totalUploadProgress (0-100), totalBytes and totalBytesSent
			totaluploadprogress(data) {  
			},
			uploadprogress: function(file, progress, bytesSent) {
				if (file.previewElement) {  
        			file.previewElement.querySelector("[data-dz-uploadprogress]").style.width = progress + "%";
					file.previewElement.querySelector(".dz-progress-percent").textContent = progress.toFixed(2) + "%";
				}
			},
			error: function (file, response) {  
				if (file.previewElement) {
					file.previewElement.remove();
					fileinputButton.classList.remove('d-none');
					setTimeout(() => {
						cancelBtn.classList.add('d-none'); 
					}, 100);
				}
				if(typeof response  === 'string'){
					toast('e', response, LNG_ERROR);

				}else{
					toast('e', response.data, LNG_ERROR);
				} 
				myDropzone.removeAllFiles() 
			},
			
			sending: function(file, xhr, formData) { 
				xhr.ontimeout = function(e) {
					toast('e', 'Server Timeout', LNG_ERROR);
					cancelBtn.classList.remove('d-none');
				};
			},
			reset() {
				startBtn.classList.add('d-none');
				cardCreview.classList.add('d-none');
				cancelBtn.classList.add('d-none'); 
			},
		});


		myDropzone.on('addedfile', function (file) {
			cancelBtn.classList.remove('d-none'); 
			fileinputButton.classList.add('d-none');
			startBtn.classList.remove('d-none');
			cardCreview.classList.remove('d-none');
			file.previewElement.querySelector('.dz-type').innerHTML = file.type;
			
			for (let node of file.previewElement.querySelectorAll(
				"[data-dz-uploadprogress]"
			)) {
				if(node.nodeName === "PROGRESS"){
					(node.value = progress)
				} else{
					(node.style.width = `0%`);
				}
					
			}
			file.previewElement.querySelector('.start').onclick = function () {
				console.log('stat');
				myDropzone.enqueueFile(file);
			};
		});
		myDropzone.on('sending', function (file, xhr, formData) { 
			formData.append('type', 1);
			if (options.hasOwnProperty('onSend') &&   options.onSend instanceof Function) {
				formData.append("more", JSON.stringify(options.onSend()));
			} 
			file.previewElement.querySelector('.start').setAttribute('disabled', 'disabled');
		});

		myDropzone.on('queuecomplete', function (progress) {
			startBtn.classList.add('d-none');
			cardCreview.classList.add('d-none'); 
			
			cancelBtn.classList.remove('d-none'); 
			if (options.hasOwnProperty('onComplete')) {
				options.onComplete();
			}
		});
		startBtn.onclick = function (e) {
			myDropzone.enqueueFiles(
				myDropzone.getFilesWithStatus(Dropzone.ADDED)
			);
		};
		cancelBtn.onclick = function () {
			myDropzone.removeAllFiles(true);
			fileinputButton.classList.remove('d-none');
		};
	}
} 