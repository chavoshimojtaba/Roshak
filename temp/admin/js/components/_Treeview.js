import { fetchApi } from '../api';
import {
    LNG_ERROR,
    LNG_MSG_CONNECTION_ERROR,
    LNG_MSG_SUCCESS,
    LNG_SUCCESS,
} from '../util/consts';
import { createNestedObject, delAlert, loading, swalBs, toast } from '../util/util';
import { Validator } from '../util/Validator';

function Treeview(options,maxLevel) {
	maxLevel = maxLevel || 100;
	const editModal = document.getElementById('editModal');
	const modal = new bootstrap.Modal(editModal,{
		backdrop:'static'
	});
	const form = document.getElementById('form-treeview-submit');
	const treeviewEl = document.getElementById('treeview');
	let selectedId = 0;
	let fetchedData={};
	let rows,keys = {};
	let allData = {};
	let defaultOptions = {
		inputs:{
			file:{},
			multi_select:{}
		},
		onBtnAction :(data,type)=>{ 
		},
        fetch:{},
        actions: true,
        searchStartLen: 2,//char
        searchDelay:500//ms
	}
	options = {...defaultOptions,...options};
	
	const callApi = async () =>{
		loading(1);
		const res = await fetchApi(options.api.list, 'GET', {
			params: {},
		}); 
		
		if (res.status == 1) {
			fetchedData = {};
			rows = res.data;
			keys = Object.keys(rows);
			for (let index = 0; index < keys.length; index++) {
				const el = res.data[keys[index]];
				fetchedData[el.id] = el;
			} 
			const json = createNestedObject('0',rows,keys); 
			if (treeviewEl.querySelector('ul')) {
				treeviewEl.querySelector('ul').remove();
			} 
			treeviewEl.innerHTML += createCategory(json,0); 
			selected(0);
		}else{
			modal.show();
		}
		loading(0);
	}
	const selected = ()=>{ 
		if (selectedId > 0 && document.querySelector('#actions-'+selectedId) !== null) {
			let currentLevel = document.querySelector('#actions-'+selectedId);
			const path = currentLevel.getAttribute('data-path').split('-'); 
			for (let index = 0; index < path.length; index++) { 
				const id = '#actions-'+path[index].replaceAll('_','');
				if (document.querySelector(id)) {
					document.querySelector(id).classList.remove('collapsed-level');
				}
			}
			setTimeout(() => { 
				currentLevel.setAttribute('tabindex', '-10')
				currentLevel.focus()
				currentLevel.removeAttribute('tabindex')  
			}, 200);
		}
	} 
	$('.js-treeview').on('click', '.level-add', function () {
		$(this).find('span').toggleClass('fa-plus').toggleClass('fa-times');
		$(this).siblings().toggleClass('in');
	});
	$('.js-treeview').on('click', '.level-remove',async function () {
		const id = $(this).closest('.treeview__level-btns').attr('data-id');
		const element = fetchedData[id];
		document.getElementById('pub_id').value = id;
		options.onBtnAction(element,'remove'); 
		const res =await delAlert(options.api.delete,'دسته '+element.title); 
		if (res != 'undefined' && res.status == '-1') {
			toast('e', 'آیتم  مورد نظر دارای زیر مجموعه میباشد و امکان حذف وجود ندارد  ', LNG_ERROR);
		}else if (res && !res.err) {
			callApi();
		} 
	});
	$('.js-treeview').on('click', '.level-same', function () { 
		const id = $(this).closest('.treeview__level-btns').attr('data-id');
		const element = fetchedData[id];
		form.querySelector('#inp_pid').value = element.pid;
		form.querySelector('#inp_id').value = 0;
		options.onBtnAction(element,'add');
		modal.show() 
	});
	$('.js-treeview').on('click', '.level-sub', function () {
		const id = $(this).closest('.treeview__level-btns').attr('data-id');
		const element = fetchedData[id];
		
		form.querySelector('#inp_pid').value = element.id;
		form.querySelector('#inp_id').value = 0;
		options.onBtnAction(element,'addSub');
		modal.show(); 
	});
	$('.js-treeview').on('click', '.level-order',async function (e) {
		const id = $(this).closest('.treeview__level-btns').attr('data-id');  
		const element = fetchedData[id]; 
		swalBs.fire({
			title: `ترتیب نمایش`,
			html: `<input type="number" class="form-control" id="inp_order" required min="0" step="1" value="${element.order}" max="100000" data-pristine-required-message="لطفا عدد را وارد کنید">`, 
			width: 200, 
			showCancelButton: true,
			confirmButtonText: 'ثبت',
			cancelButtonText: 'انصراف',
			preConfirm: () => { 
				loading(1,e);
				fetchApi(`content/order_loc/${id}`, 'PUT', { body: {id,order:document.getElementById('inp_order').value} }).then((res) => { 
					if (res.status) {
						if (e.currentTarget.closest('li')!= null) { 
							e.currentTarget.closest('li').style.setProperty('order', document.getElementById('inp_order').value);
						}
					} else {
						toast('e', LNG_MSG_CONNECTION_ERROR, LNG_ERROR);
					}
				}).catch(err=>{
					toast('e', LNG_MSG_CONNECTION_ERROR, LNG_ERROR);
				}).finally(()=>{ 
					loading(0,e);
				});

			},
		});   
	});
	$('.js-treeview').on('click', '.level-edit',async function (e) {
		const id = $(this).closest('.treeview__level-btns').attr('data-id');  
		options.onBtnAction(fetchedData[id],'edit');
		modal.show(); 
	});
	$('.js-treeview').on('click', '.level-expand',async function (e) {
		if ($(e.target).closest('.treeview__level').attr('data-level') === '*') {
			$('[data-level="*"]+ul .treeview__level').toggleClass('collapsed-level');
		}else{
			$(e.target).closest('.treeview__level').toggleClass('collapsed-level');
		}
	});
	editModal.addEventListener('hide.bs.modal', function (e) {
		form.reset();
		if (options.onModalHide) {
			options.onModalHide();
		}
	});
	function createCategory(data,levelNumber) { 
		let orderBtn = ''; 
		let actionBtns = '';
		if ( options.actions == false){
			actionBtns='treeview__level-btns--none';
		} 
		if ( options.api.order){
			orderBtn='<div class="btn btn-secondary btn-sm level-order"><span class="fas fa-sort-amount-down"></span></div>';
		} 
		var html = ` <ul class="${actionBtns} flex-column"> `;
		levelNumber++;
		$.each(data,function(key,val){ 
			
			let order = 10000;
			let expandBtn = '';
			let addBtn = '<div class="btn text-white btn-success btn-sm level-sub"><span>زیر سطح</span></div>';
			if (val.children){
				expandBtn = '<div class="btn btn-primary btn-sm level-expand"><span class="ti-"></span></div>';
			}
			if (val.type == 'product'){
				expandBtn += '<a href="'+HOST+'search/'+val.slug+'" target="_blank" class="btn btn-warning btn-sm level-open"><span class="ti-eye"></span></a>';
			}  
			if (val.order){
				order = val.order;
			} 
			if ((maxLevel+1) == levelNumber){
				// return false;
			}
			if (maxLevel  == levelNumber){
				addBtn='';
			}     
			////// data-json=\'${JSON.stringify(val)}\'
			html += `
				<li style="order:${order}">
					<div class="treeview__level collapsed-level" id="actions-${val.id}" data-path="${val.path}" data-level="${levelNumber}" >
						<span class="level-title fw-bold">
							${val.title}
						</span>
						<div class="treeview__level-btns "   data-id="${val.id}">
							<div class="btn btn-info btn-sm level-edit"><span class="ti-pencil"></span></div> 
							<div class="btn btn-danger btn-sm level-remove"><span class="ti-trash"></span></div>
							<div class="btn btn-success btn-sm level-add"><span class="ti-plus"></span></div>
							${orderBtn}
							${expandBtn}
							${addBtn}
							<div class="btn text-white btn-success btn-sm level-same"><span>هم سطح</span></div>
						</div>
					</div>
			`;

			if (val.children){ 
				html += createCategory(val.children,levelNumber);
				 
			}
			html += "</li>";
		});
		html += "</ul>";
		return html;
	};
	document.getElementById('submit_treeview').addEventListener('click', function (e) {
		var valid = Validator('form-treeview-submit').validate(); 
		if (valid) {
			const formData = new FormData(form);
			formData.append('type',options.type)
			let method = 'POST';
			let url = options.api.add;
			if (document.querySelector('.editor') != null) {
				var _els = document.getElementsByClassName('editor');
				for (var index = 0; index < _els.length; index++) {
					const id = _els[index].id;
					formData.append(id.split('inp_')[1],tinymce.get(id).getContent());
				}
			}
			if (options.inputs.file && options.inputs.file != "undefined") {
				const _files = Object.keys(options.inputs.file);
				for (let index = 0; index < _files.length; index++) {
					const itm = options.inputs.file[_files[index]];
					formData.append(_files[index] , itm.getFiles('single'));
				}
			}
			if (options.inputs.multi_select && options.inputs.multi_select != "undefined") {
				const _multi_select = Object.keys(options.inputs.multi_select);
				for (let index = 0; index < _multi_select.length; index++) {
					const itm = options.inputs.multi_select[_multi_select[index]];
					formData.append(_multi_select[index] , JSON.stringify(itm.value()));
				}
			}
			if (form.querySelector('#inp_publish') != null) {
				formData.append('publish',form.querySelector('#inp_publish').checked?'yes':'no')//en
			}
			let formDataEntries = Object.fromEntries(formData);

			if (options.onSubmit) {
				const onSubmitRes = options.onSubmit(formDataEntries);
				if (typeof onSubmitRes != 'object') {
					return false;
				}
			}
			if (formDataEntries.id && parseInt(formDataEntries.id) > 0) {
				method = 'PUT';
				url = options.api.update;
			} else {
				delete formDataEntries.id;
			}
			loading(1,e)
			fetchApi(url, method, { body: formDataEntries }).then((res) => {
				modal.hide();
				form.reset();
				if (res.status) {
					if (method == 'POST') {
						formDataEntries.id = res.data;
					}
					selectedId=formDataEntries.id;
					toast('s', LNG_MSG_SUCCESS, LNG_SUCCESS);
					callApi();
				} else {
					toast('e', LNG_MSG_CONNECTION_ERROR, LNG_ERROR);
				}
				loading(0,e)
			}).catch(err=>{
				loading(0,e)
				toast('e', LNG_MSG_CONNECTION_ERROR, LNG_ERROR);
			})
		}
	});
	callApi();
}
const selected = (id)=>{ 
}
export default Treeview;