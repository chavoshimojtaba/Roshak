
import { SubmitForm } from "../../components/_Forms";
import { Authorization, loading, scrollTop, toast } from "../../util/util";
import { MultiSelect } from "../../components/_MultiSelect";
import { Uploader } from "../../components/_Uploader";
import { Validator } from "../../../../util/util";
import { fetchApi } from "../../api";
import { StepWizard } from "../../components/_StepWizard";

export const ProfileScreen = {
	async render() {
		
		var $modal = $('#modalAvatar');
		var $modalCover = $('#modalCover'); 
		var image = document.getElementById('sample_image');
		var cover = document.getElementById('sample_cover'); 
		var url;
		var reader;
		var canvas;
		var cropper; 

		$('#upload_image').change(function (event) {
			var files = event.target.files; 
			var done = function (url) {
				image.src = url;
				$modal.modal('show');
			};

			if (files && files.length > 0) {
				reader = new FileReader();
				reader.onload = function (event) {
					console.log(reader);

					done(reader.result);
				};
				reader.readAsDataURL(files[0]);
			}
		});
		$('#upload_cover').change(function (event) {
			var files = event.target.files; 
			var done = function (url) {
				cover.src = url;
				$modalCover.modal('show');
			}; 
			if (files && files.length > 0) {
				reader = new FileReader();
				reader.onload = function (event) { 
					done(reader.result);
				};
				reader.readAsDataURL(files[0]);
			}
		}); 

		$modal.on('shown.bs.modal', function () {
			cropper = new Cropper(image, {
				aspectRatio: 1,         
				autoCropArea: 1,   
				minContainerHeight: 300, 
				viewMode:2, 
				preview: '.preview'
			}); 

		}).on('hidden.bs.modal', function () { 
			cropper.destroy();
			cropper = null;
		});

		$modalCover.on('shown.bs.modal', function () { 
			cropper = new Cropper(cover, {  
				aspectRatio: 6 / 1,   	 
				initialAspectRatio: 6 / 1,   	 
				background:false, 
				responsive:true, 
				autoCropArea: 1,
				minContainerHeight: 300,   
				preview: '.preview-cover'
			});  
		}).on('hidden.bs.modal', function () { 
			cropper.destroy();
			cropper = null;
		});

		$('#submit-cover').click(function (e) {
			loading(e,1)
			canvas = cropper.getCroppedCanvas({
				width: 800,
				height: 300
			}); 
			canvas.toBlob(function (blob) {
				url = URL.createObjectURL(blob);
				var reader = new FileReader();
				reader.readAsDataURL(blob);
				reader.onloadend = function () {
					var base64data = reader.result; 
					fetchApi('member/cover','PUT',{body:{cover:base64data}}).then(res=> {
						if (res.status) {
							document.querySelector('.cover-image').src = HOST+res.data;
							$modalCover.modal('hide');
						}else{
							toast('e');
						}
					}).finally(()=>{
						loading(e,0)
					})
					 
				};
			});
		});

		$('#submit-avatar').click(function (e) {
			loading(e,1)
			canvas = cropper.getCroppedCanvas({
				width: 200,
				height: 200
			}); 
			canvas.toBlob(function (blob) {
				url = URL.createObjectURL(blob);
				var reader = new FileReader();
				reader.readAsDataURL(blob);
				reader.onloadend = function () {
					var base64data = reader.result; 
					fetchApi('member/avatar','PUT',{body:{avatar:base64data}}).then(res=> {
						if (res.status) {
							document.querySelector('.avatar-profile img').src = HOST+res.data;
							$modal.modal('hide');
						}else{
							toast('e');
						}
					}).finally(()=>{
						loading(e,0)
					})
					 
				};
			});
		});

		let selectInp;
		const member = Authorization();
		let authorization;;

		member.then(res => {
			authorization = res;
			if (res.s && res.t == 'designer') {
				selectInp = new MultiSelect({
					required: false,
					title: '',
					api: false,
					max: 1,
					type: 'expertise'
				});
				selectInp.init();
			}
		});
		const selectInp_cat = new MultiSelect({
			selector: '.cat-multi-select',
			required: false,
			title: '',
			api: false,
			max: 1,
			type: 'categories'
		});
		selectInp_cat.init();
		if (document.getElementById('inp_province') != null) {

			const citiesKey = Object.keys(cities);
			document.getElementById('inp_province').addEventListener('change', function (e) {
				let options = '';
				for (let m = 0; m < citiesKey.length; m++) {
					const city = cities[citiesKey[m]];
					if (city.pid == this.value) {
						options += '<option value="' + city.id + '">' + city.name + '</option>';
					}
				}
				document.getElementById('inp_city').innerHTML = options;
			});
		}
		document.getElementById('submit-password').addEventListener('click', function (e) {
			SubmitForm(e, {
				id: 'form-password',
				api: {
					POST: 'member/change_password'
				},
				onSuccess: function (res, data) {
					document.querySelector('#form-password').reset();
					document.querySelector('#alert-danger').classList.add('d-none');
					toast('s', 'رمز عبور با موفقیت تغییر کرد.');
					scrollTop();
				},
				
				onInvalid: function (res) { 
					document.querySelector('#alert-danger').classList.remove('d-none');
				},
				onFailed: function (res) {  
					toast('e', res.data);
				} 
			})
		}); 
		document.getElementById('submit-info').addEventListener('click', function (e) {
			SubmitForm(e, {
				id: 'form-info',
				api: {
					POST: 'member/update_profile'
				},
				onSend: function (res, data) {
					data = { favorite_categories: '' };
					if (authorization.s && authorization.t == 'designer') {
						data.expertise = '';
						const a = selectInp.value();
						const akeys = Object.keys(a);  
						if (akeys.length > 0) {
							const expertise = [];
							for (let index = 0; index < akeys.length; index++) {
								expertise.push('_' + akeys[index] + '_');
							}
							data.expertise = expertise.join('-');
						} else {
							selectInp.isRequired()
							return false;
						}
					} 
					const b = selectInp_cat.value();
					const bkeys = Object.keys(b);
					if (Object.keys(bkeys).length > 0) {
						const favorite_categories = [];
						for (let index = 0; index < bkeys.length; index++) {
							favorite_categories.push('_' + bkeys[index] + '_');
						}
						data.favorite_categories = favorite_categories.join('-');
					}
					return data;
				},
				onSuccess: function (res, data) {
					toast('s', 'اطلاعات با موفقیت ثبت شد');
					scrollTop();
					// window.location.href = HOST + 'dashboard/profile';
				},
				onError: function (res) {
					toast('e');
				},
			})
		});

	},
	upgrade_profile() {
		console.log(145);
		const uploadfile = new Uploader({
			formats: '.pdf ,  .jpeg , .jpg , .zip',
			title: 'بارگذاری فایل های رزومه و پرتفولیو',
			onAction:function (params) { 
                if (uploadfile.getFiles().length<=0) {
                    document.querySelector('#continue-btn').classList.remove('d-flex');
                    document.querySelector('#continue-btn').classList.add('d-none');
                }else{
                    document.querySelector('#continue-btn').classList.remove('d-none');
                    document.querySelector('#continue-btn').classList.add('d-flex');
                }
            }
		});
		new StepWizard({
			steps: 4,
			onNext: function (currentStep) {
				let isValid = false;
				switch (currentStep) {
					case 1:
						const expertise_list = selectInp.value();
						const expertise_keys = Object.keys(expertise_list);
						if (stepsForm[1].validate()) {

							isValid = true;
						}
						if (expertise_keys.length <= 0) {
							toast('e', 'لطفا تخصص خود را مشخص کنید.')
							isValid = false;
						}
						if (uploadfile.getFiles().length <= 0) {
							toast('e', 'لطفا فایل های مربوطه را بارگذاری کنید.')
							isValid = false;
						}
						break;
					case 2:
						if (stepsForm[2].validate()) {
							isValid = true;
						}
						break;
					case 3:
						if (stepsForm[3].validate()) {
							isValid = true;
							submitInfo();
						}
						break;
				}
				if (!isValid) {
					return false;
				}
				return isValid;
			}
		})
		// stepFormWizard(4);
		const selectInp = new MultiSelect({
			required: true,
			title: '',
			api: false,
			type: 'expertise'
		});
		let stepsForm = {
			1: Validator('form-step-1'),
			2: Validator('form-step-2'),
			3: Validator('form-step-3')
		}
		selectInp.init();
		const citiesKey = Object.keys(cities);
		document.getElementById('inp_province').addEventListener('change', function (e) {
			let options = '';
			for (let m = 0; m < citiesKey.length; m++) {
				const city = cities[citiesKey[m]];
				if (city.pid == this.value) {
					options += '<option value="' + city.id + '">' + city.name + '</option>';
				}
			}
			document.getElementById('inp_city').innerHTML = options;
		});
		function submitInfo() {
			const data = Object.assign(Object.fromEntries(new FormData(document.getElementById('form-step-1'))), Object.fromEntries(new FormData(document.getElementById('form-step-2'))), Object.fromEntries(new FormData(document.getElementById('form-step-3'))));
			const expertise_list = selectInp.value();
			const expertise_keys = Object.keys(expertise_list);
			if (expertise_keys.length > 0) {
				const expertise = [];
				for (let index = 0; index < expertise_keys.length; index++) {
					expertise.push('_' + expertise_keys[index] + '_');
				}
				data.expertise = expertise.join('-');
			}
			data.files = uploadfile.getFiles(true);
			fetchApi('member/upgrade_profile', 'POST', { body: data }).then((res) => {
				document.querySelector('.loading-box').classList.add('d-none')
				if (res.status) {
					document.querySelector('.alert-box').classList.remove('d-none')
				} else {
					document.querySelector('.alert-box').classList.remove('d-none')
				}
			}).catch(err => {
				console.log(err);

			})
		}
	},
};

/* 
var previewNode = document.querySelector('#template');
previewNode.id = '';
var previewTemplate = previewNode.parentNode.innerHTML;
previewNode.parentNode.removeChild(previewNode);
const cardCreview = document.querySelector('#card-preview');
const startBtn = document.querySelector('#actions .start');
const cancelBtn = document.querySelector('#actions .cancel');

const token = 111111;
var myDropzone = new Dropzone('div#previews', {
	url: 'file',
	method: 'POST',
	thumbnailWidth: 80,
	thumbnailHeight: 80,
	headers: {
		'Authorization': `Bearer ${token}`
	},
	createImageThumbnails: true,
	acceptedFiles: '*.png',
	autoQueue: false,
	maxFiles: 8,
	previewTemplate: previewTemplate,
	previewsContainer: '#previews',
	clickable: '.fileinput-button',
	success: function (file, response) {
		file.previewElement
			.querySelector('.upload-done')
			.classList.remove('d-none');
		setTimeout(() => {
			// file.previewElement.remove();
		}, 1000);
	},
	error: function (file, response) {
		if (file.previewElement) {
			// file.previewElement.remove();
		}
	},
	reset() {
		startBtn.classList.add('d-none');
		cardCreview.classList.add('d-none');
		cancelBtn.classList.add('d-none'); 
	},
});

myDropzone.on('addedfile', function (file) {
	startBtn.classList.remove('d-none');
	cardCreview.classList.remove('d-none');
	cancelBtn.classList.remove('d-none');
	file.previewElement.querySelector('.dz-type').innerHTML = file.type;
	const validImageTypes = ['image/gif', 'image/jpeg', 'image/jpg', 'image/png'];
	if (!validImageTypes.includes(file.type)) {
		file.previewElement.querySelector('img').src = '../images/icons/document.svg';
	}
	file.previewElement.querySelector('.start').onclick = function () {
		myDropzone.enqueueFile(file);
	};
	$('[data-toggle="tooltip"]').tooltip()

});

myDropzone.on('sending', function (file, xhr, formData) {

	formData.append(
		'title',
		file.previewElement.querySelector('.file_title').value
	);
	formData.append(
		'alt',
		file.previewElement.querySelector('.file_alt').value
	);
	file.previewElement
		.querySelector('.start')
		.setAttribute('disabled', 'disabled');
});
myDropzone.on('queuecomplete', function (progress) {
	startBtn.classList.add('d-none');
	cardCreview.classList.add('d-none');
	cancelBtn.classList.add('d-none');
});
startBtn.onclick = function () {
	myDropzone.enqueueFiles(
		myDropzone.getFilesWithStatus(Dropzone.ADDED)
	);
};
cancelBtn.onclick = function () {
	myDropzone.removeAllFiles(true);
}; */