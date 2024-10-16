import { delAlert, loading } from '../util/util';
import {Pagination} from './_Pagination'
function DataTable(options) {

    const _this = this;
    let selectors = {
        inputs:{}
    };
    let dataRows = {};
    let tableEl;
    let page = 1;
    let sort_by = '0';
    let date_from = '';
    let date_to = '';
    let q = '';
    let q_type = '';
    let sort_type = 'DESC';
    let totalCount = 0;
    let defaultOptions = {
        id: '.table-card',
        filter:true,
        pagination:true,
        afterRender:'',
        perPage:10
    };

    options = { ...defaultOptions, ...options };

    this.render = function () {
        tableEl = document.querySelector(`${options.id}`);

        let formEl = ``;
        if (options.filter) {
            formEl = `
                <form id="q-form" action="#">
                    <div class="input-group">
                        <div class="position-relative table-search-filter-q">
                            <input type="text" class="form-control" placeholder="جستجو..." id="table_q"> 
                        </div>
                        <div class="table-search-filter-date d-none">
                            <div class="d-flex position-relative align-items-center">
                                <input type="text" class="form-control " style="width:100px" autocomplete="off" id="table-date-from-g" data-name="table_from-date" placeholder="از تاریخ" />
                                <input type="hidden" name="table-date-from" id="table-date-from">
                                <input type="text" class="form-control" style="width:100px;border-radius: 0  !important;" id="table-date-to-g"  autocomplete="off"  data-name="table_to-date" placeholder=" تا تاریخ"/>
                                <input type="hidden" name="table-date-to" id="table-date-to">
                            </div>
                        </div>
                        <select class="form-select" id="table_q_type">
                        </select>
                        <button type="submit" class="btn btn-primary d-flex align-items-center" type="button" id="table_q_btn">
                            <i class="ti-search"></i>
                        </button>
                        <button class="q-form__clear-btn btn btn-danger">
                            <i class="mdi text-white mdi-close-circle-outline d-flex"></i>
                        </button>
                    </div>
                </form>
            `;

        }
        tableEl.innerHTML = `
            <div class="border-bottom title-part-padding justify-content-between d-flex align-items-center	">
                <h4 class="card-title mb-0">لیست ${tableEl.getAttribute('data-title')}</h4>
                ${formEl}
                ${tableEl.innerHTML}
            </div>
        `;
        this.createTemplate();
    };

    this.rerender = function () {
        this.fetchHandler();
    };

    this.createTemplate = function () {
        const tableContainerEl = document.createElement('div');
        tableContainerEl.className = 'table-responsive';

        selectors.tableTag = document.createElement('TABLE');
        selectors.tableTag.setAttribute('class', 'table');

        let search_options = '';
        if (options.cell.length > 0) {
            let thEls = `<th scope="col">#</th>`;
            for (let index = 0; index < options.cell.length; index++) {
                const element = options.cell[index];
                if (element.search || element.type==='date') {
                    if (element.type == 'date') {
                        search_options += `<option value="date">${element.title}</option>`;
                    }else{
                        if (element.key == 'full_name' || element.key == 'fullname' ) {
                            element.title = 'نام خانوادگی';
                        }
                        search_options += `<option value="${element.key}">${element.title}</option>`;
                    }
                }
                if (element.editable) {
                    selectors.inputs['inp_'+element.key] = document.getElementById('inp_'+element.key);
                }
                if (element.sort || element.type==='date') {
                    thEls += `<th scope="col" class="sortable" data-key="${element.key}" data-type="${sort_type}">${element.title}</th>`;
                }else{
                    thEls += `<th scope="col">${element.title}</th>`;
                }
            }
            selectors.tableTag.innerHTML = `
				<thead class="table-light">
					<tr>
						${thEls}
					</tr>
				</thead>
			`;
        }
        tableContainerEl.appendChild(selectors.tableTag)
        tableEl.appendChild(tableContainerEl);
        
        const tbody = document.createElement('tbody');
        selectors.tableTag.appendChild(tbody);
		const pagerEl = document.createElement('div');
		pagerEl.id = 'pager';
		pagerEl.className = 'mt-2';
		tableEl.appendChild(pagerEl);
        selectors.th = selectors.tableTag.querySelectorAll('th');
        selectors.inputs['pub_id'] = document.getElementById('pub_id');
        this.fetchHandler();
        this.tableFilterClickHandler();
        if (document.getElementById('q-form')) {
            selectors.q = document.getElementById('q-form');
            selectors.qClearBtn = document.querySelector('.q-form__clear-btn');
            selectors.q_type = document.getElementById('table_q_type');
            selectors.date_from = document.getElementById('table-date-from');
            selectors.date_to = document.getElementById('table-date-to');
            selectors.q_type.innerHTML = search_options;
            this.searchFilterHandler();
        }
    };

    this.clearSortClass = function () {
        for (let index = 0; index <   selectors.th.length; index++) {
            selectors.th[index].classList.remove('asc');
            selectors.th[index].setAttribute('data-type','desc');
        }
    };

    this.createRows = function () {
        let trRows = '';
        const columnid = ((page-1)*options.perPage);
        dataRows.map((item, itemIndex) => {
            let tdColumns = `<td>${ columnid + (itemIndex + 1)}</td>`;
            for (let index = 0; index < options.cell.length; index++) {
                const element = options.cell[index];
                if (element.type) {
                    let attrs = '';
                    if (element.attr) {
                        attrs = element.attr.join(' ')
                    }
                    if (element.type === 'link') {
                        const field = element.options.url.split('|');
                        const val = (element.options.value)?element.options.value:item[element.key]; 
                        let href = ` target="_blank" href="${$_url}${field[0]}${item[field[1]]}"`; 
                        if (element.options?.if) { 
                            if (!item[element.options.if]) { 
                                href = '';
                            }
                        }
                        tdColumns += `<td>
										<a type="button" ${href}>
											${val}
										</a>
									</td>`;
                    }else if (element.type === 'public-btn') { 
                        if (element.options?.if) { 
                            console.log(element.options);
                            if (!item[element.options.if]) { 
                                attrs += ' disabled ';
                            }
                        }
                        tdColumns += `<td>
										<button type="button" action="${element.key}" ${attrs} key="${itemIndex}" class="table-action-btn
											btn btn-circle btn-sm
											d-inline-flex
											align-items-center
											justify-content-center
											${element.options.class}
										">
											${element.options.value}
										</button>
									</td>`;
                    }else if (element.type === 'btn') {
                        tdColumns += `<td>
                                    <button type="button" action="${element.key}" ${attrs} key="${itemIndex}" class="table-action-btn btn btn-${element.options.class}">
                                        ${element.options.value}
                                    </button>
                                </td>`;
                    }else if (element.type === 'date') {
                        tdColumns += `<td class="ltr">
                                    ${item[element.key]}
                                </td>`;
                    }  else if (element.type === 'option') {
                        tdColumns += `<td>${element.options.option_types[item[element.key]]}</td>`;
                    } else if (element.type === 'badge') {
                        tdColumns += `<td><span class="badge bg-${
                            element.options.class
                        }">${item[element.key]}</span></td>`;
                    } else if (element.type === 'file') {
                        let itm = '';
                        const type =element.filetype;
                        switch (type) {
                            case 'image':
                                if (!item[element.key] || item[element.key].length <= 0) {
                                    itm = `<svg width="40px" height="40px" viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg"><path fill="#2394BC" d="M50 0c27.614 0 50 22.386 50 50s-22.386 50-50 50-50-22.386-50-50 22.386-50 50-50z"/><path fill="#EDEDED" d="M15.592 31.141l51.129-13.699c1.613-.433 3.27.514 3.698 2.114l10.093 37.667c.43 1.601-.531 3.248-2.145 3.681l-51.129 13.7c-1.613.432-3.27-.515-3.698-2.115l-10.093-37.668c-.429-1.6.532-3.248 2.145-3.68z"/><path fill="#4681A0" d="M19.78 34.137l44.78-11.999c1.237-.331 2.508.4 2.838 1.634l8.382 31.28c.331 1.233-.403 2.503-1.641 2.834l-44.78 11.999c-1.236.332-2.508-.4-2.838-1.635l-8.381-31.279c-.33-1.235.404-2.503 1.64-2.834z"/><path fill="#F6F6F6" d="M25.038 31.014h52.933c1.67 0 3.024 1.343 3.024 3v38.996c0 1.656-1.354 2.999-3.024 2.999h-52.933c-1.671 0-3.024-1.343-3.024-2.999v-38.996c0-1.658 1.353-3 3.024-3z"/><path fill="#A3E0F5" d="M28.309 34.991h46.36c1.28 0 2.317 1.036 2.317 2.313v32.383c0 1.277-1.037 2.312-2.317 2.312h-46.36c-1.28 0-2.318-1.035-2.318-2.312v-32.382c-.001-1.278 1.037-2.314 2.318-2.314z"/><path fill="#3DB39E" d="M30.004 60.99c-1.389 0-2.729.116-4.014.313v8.384c0 1.277 1.038 2.312 2.318 2.312h17.482c.131-.49.219-.989.219-1.502.001-5.25-7.165-9.507-16.005-9.507z"/><path fill="#4BC2AD" d="M76.986 69.688v-12.452c-3.096-.796-6.497-1.236-10.064-1.236-14.402 0-26.077 7.164-26.077 16h33.824c1.28 0 2.317-1.035 2.317-2.312z"/><path fill="#EFC75E" d="M25.99 37.305v11.583c.492.059.99.098 1.498.098 6.902 0 12.498-5.593 12.498-12.493 0-.509-.04-1.009-.099-1.502h-11.578c-1.281 0-2.319 1.036-2.319 2.314z"/><path fill="#1F85A9" d="M77.971 76.009h-52.933c-1.671 0-3.024-1.343-3.024-2.999v-38.996c0-.299.058-.582.14-.854-1.236.377-2.14 1.505-2.14 2.854v38.996c0 1.656 1.354 2.999 3.024 2.999h52.933c1.369 0 2.513-.908 2.885-2.146-.282.086-.575.146-.885.146z"/><path fill="#D5D5D5" d="M22.014 66.792v-32.778c0-.299.058-.582.14-.854-1.236.377-2.14 1.505-2.14 2.854v23.314l2 7.464z"/><path fill="#3F7490" d="M21.014 33.806c-.611.545-1 1.327-1 2.208v7.948l2 7.464v-17.412c0-.168.021-.33.052-.489l-1.052.281z"/></svg>`;
                                }else{
                                    itm = `<img src="${$_url}${item[element.key]}" class="rounded-circle get-file-ff" width="40" height="40" />`;
                                }
                                break;
                            case 'video':
                                itm = `<a class="file-type bg-danger get-file-ff"><i class=" ti-video-clapper text-white"></i></a>`;
                                break;
                            default:
                                itm = `<a class="file-type bg-info get-file-ff"><i class="ti-file text-white"></i></a>`;
                                break;
                        }
                        tdColumns += `<td>${itm}</td>`;
                    }
                } else {
                    if (item[element.key] == null) {
                        item[element.key] = '-';
                    }
                    const cls = (element.options && element.options?.class)? `class="${element.options.class}"`:'';
                    tdColumns += `<td ${cls}>${item[element.key]}</td>`;
                }
            }
            return trRows += `
				<tr>
					${tdColumns}
				</tr>
			`;
        });
        const tbody = tableEl.querySelector('tbody');
        console.log(tableEl.querySelector('tbody'));
        tbody.innerHTML = trRows;
        this.handleOnItemClick();
    };

    this.emptyRow = function () {
        let trRows = `
            <td colspan="100%">
                <div class=" alert customize-alert m-2 p-2   border-danger text-danger fade show remove-close-icon" role="alert">
                    <div class=" d-flex align-items-center font-weight-medium me-3 me-md-0">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-info text-danger feather-sm me-2"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="16" x2="12" y2="12"></line><line x1="12" y1="8" x2="12" y2="8"></line></svg>
                        نتیجه ای یافت نشد
                    </div>
                </div>
            </td>
        `;
        tableEl.querySelector('tbody').innerHTML = trRows;
    };

    this.handleOnItemClick = function () {
        const btns = document.getElementsByClassName('table-action-btn')
        for (let index = 0; index < btns.length; index++) {
            const btn = btns[index];
            btn.addEventListener('click',async function (e) {
                e.preventDefault();
                const action = this.getAttribute('action');
                const key = this.getAttribute('key');
                if (action === 'edit') {
                    selectors.inputs.inp_id = document.getElementById('inp_id');
                    selectors.inputs.inp_id.value = dataRows[key].id;
                    if (options.actions.edit !== undefined) {
                        options.actions[action](dataRows[key]);
                    }else{
                        for (let index = 0; index < options.cell.length; index++) {
                            const el = options.cell[index];
                            if (el.editable) {
                                selectors.inputs['inp_'+el.key].value = dataRows[key][el.key]
                            }
                        }
                    }
                }else if (action === 'del') {
                    selectors.inputs['pub_id'].value = dataRows[key].id;
                    let extUrl = 0;
                    if (this.getAttribute('data-ext-url')) {
                        extUrl = this.getAttribute('data-ext-url');
                    }
                    const res =await delAlert(this.getAttribute('data-resource'),0,extUrl);
                    if (res && !res.err) {
                        _this.fetchHandler();
                    }
                } else {
                    options.actions[action](dataRows[key],e);
                }
            });
        }

    };

    this.tableFilterClickHandler = function () {
        const sortable = document.getElementsByClassName('sortable')
        for (let index = 0; index < sortable.length; index++) {
            const sortableBtn = sortable[index];
            sortableBtn.addEventListener('click',async function (e) {
                e.preventDefault();
                sort_by   = this.getAttribute('data-key');
                sort_type = (this.getAttribute('data-type') === 'asc')?'desc':'asc';
                await _this.clearSortClass();
                this.setAttribute('data-type',sort_type);
                if (sort_type == 'asc') {
                    this.classList.add('asc');
                }
                _this.fetchHandler(1);
            });
        }
    };

    this.resetPagination = function () {
        Pagination.Init(document.getElementById('pager'), {
            size: totalCount,
            limit: options.perPage,
            page,
            clickHandler:this.fetchHandler,
            step:3
        });
    };

    this.searchFilterHandler = function () {
        selectors.q.addEventListener('submit',async function (e) {
            e.preventDefault();
            const table_q = document.getElementById('table_q'); 
            if (selectors.q_type.value == 'date') {
                if (selectors.date_from.value.length > 6 && selectors.date_to.value.length > 6) {
                    q_type = selectors.q_type.value;
                    date_from = document.getElementById('table-date-from').value;
                    date_to = document.getElementById('table-date-to').value;
                    selectors.qClearBtn.classList.add('d-flex');
                    _this.fetchHandler();
                }
            }else if (table_q.value.length >= 1) {
                q = table_q.value;
                q_type = selectors.q_type.value;
                page = 1;
                    selectors.qClearBtn.classList.add('d-flex');
                    _this.fetchHandler();
            }else{
                selectors.qClearBtn.classList.remove('d-flex');
            }
        });
        selectors.qClearBtn.addEventListener('click',async function (e) {
            e.preventDefault();
            _this.resetQ();
            this.classList.remove('d-flex');
            _this.fetchHandler();
        });
        selectors.q_type.addEventListener('change',function(e) {
            e.preventDefault()
            console.log(e.currentTarget.value);
            if (e.currentTarget.value == 'date') {
                tableEl.querySelector('.table-search-filter-q').classList.add('d-none')
                tableEl.querySelector('.table-search-filter-date').classList.remove('d-none')
                new mds.MdsPersianDateTimePicker(document.getElementById('table-date-from-g'), {
                    targetTextSelector: '[data-name="table_from-date"]',
                    targetDateSelector: '[name="table-date-from"]',
                }); 
                new mds.MdsPersianDateTimePicker(document.getElementById('table-date-to-g'), {
                    targetTextSelector: '[data-name="table_to-date"]',
                    targetDateSelector: '[name="table-date-to"]',
                }); 
            }else{
                tableEl.querySelector('.table-search-filter-q').classList.remove('d-none')
                tableEl.querySelector('.table-search-filter-date').classList.add('d-none')
            }
        })
    };

    this.resetQ = function () {
        date_from = '';
        var _els = tableEl.querySelectorAll('input'); 
        for (var index = 0; index < _els.length; index++) {
            _els[index].value = '';
        }
        date_to = '';
        q = '';
        q_type = '';
        totalCount = 0;
        document.getElementById('table_q').value = '';
    };

    this.fetchHandler = function (callback) {

        const oldTotalCount = totalCount;
        if (typeof callback === 'number') {
            page = callback;
        }
        if (typeof callback === 'string' && callback === 'reset') {
            this.resetQ();
        }
        options.api({page,limit:options.perPage,total:totalCount,sort_by,sort_type,q,q_type,date_to,date_from}).then((res) => {
            const total  = res.total?res.total:0;
            const data  = res.data?res.data:{};
            if (total == '0') {
                _this.emptyRow();
            }else{
                dataRows = data;
                _this.createRows();
                if (typeof callback === 'function') {
                    callback()
                }
            }
            totalCount = total;
            if (oldTotalCount != totalCount && options.pagination) {
                _this.resetPagination();
            }
            loading(0);
            return true;
        }).then(res=>{
            if (typeof options.afterRender === 'function') {
                options.afterRender()
            }
        })
    };

    this.render();
}

export { DataTable };
