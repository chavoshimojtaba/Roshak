import { delAlert, fileType, loading } from '../util/util';
import { Pagination } from './_Pagination';
function FileTable(options) {
    const _this = this;
    let selectors = {
        inputs: {},
    };
    let dataRows = {};
    let containerEl;
    let page = 1;
    let q = '';
    let accept_formats = 0;
    let category = 0;
    let totalCount = 0;
    let defaultOptions = {
        id: 'cart-table',
        afterRender: '',
        perPage: 10,
    };

    options = { ...defaultOptions, ...options };

    this.render = function () {
        containerEl = document.getElementById(`${options.id}`);
        this.createTemplate();
    };

    this.rerender = function () {
        this.fetchHandler();
    };

    this.createTemplate = function () {
        selectors.cardTableTag = document.createElement('div');
        selectors.cardTableTag.setAttribute('class', 'row el-element-overlay');
        containerEl.appendChild(selectors.cardTableTag);
        const pagerEl = document.createElement('div');
        pagerEl.id = 'pager';
        containerEl.appendChild(pagerEl);
        selectors.inputs['pub_id'] = document.getElementById('pub_id');
        selectors.q = document.getElementById('q-form');
        selectors.qClearBtn = document.querySelector('.q-form__clear-btn');
        if (options.filetype == 0) {
            selectors.fileTypeBtn = document.getElementById('filter_filetype');
            selectors.fileTypeBtn.classList.remove('d-none')
        }
        this.fetchHandler(() => {
            Pagination.Init(document.getElementById('pager'), {
                size: totalCount, // pages size
                limit: options.perPage, // limit per page
                page, // selected page
                clickHandler: this.fetchHandler,
                step: 3, // pages before and after current
            });
        });
        this.filtersHandler()
    };

    this.createRows = function () {
        let trRows = '';
        dataRows.map((item, itemIndex) => {
            let itm = '';
            let type = fileType(item.type);
            switch (type) {
                case 'image':
                    itm = `<img src="${$_url}${item.dir}" class="d-block position-relative w-100 get-file-ff" />`;
                    break;
                case 'video':
                    itm = `<a class="file-type bg-danger get-file-ff"><i class=" ti-video-clapper text-white"></i></a>`;
                    break;
                default:
                    type='doc';
                    itm = `<a class="file-type bg-info get-file-ff"><i class="ti-file text-white"></i></a>`;
                    break;
            }
            const seletBtn = (options.is_iframe)?`
            <div class="el-card-content text-center">
                <button key="${itemIndex}" data-type="${type}" action="select" class="get-file-ff w-100 m-0 btn btn-light-secondary text-secondary  table-action-btn"><i class="mdi mdi-database-plus
                "></i>&nbsp;انتخاب</button>
            </div>
            `:``;
            let card = `
                        <div class="el-card-item ">
                            <div class=" el-card-avatar mb-1 el-overlay-1 w-100 overflow-hidden position-relative text-center ">
                                ${itm}
                                <div class="el-overlay w-100 overflow-hidden">
                                    <ul class=" list-style-none mt-1 el-info text-white text-uppercase d-inline-block p-0">
                                        <li class="el-item d-inline-block my-0 mx-1">
                                            <a key="${itemIndex}" action="more" class=" btn default btn-outline  el-link table-action-btn text-secondary border-secondary"><i class="ti-eye"></i></a>
                                        </li>
                                        <li class="el-item d-inline-block my-0 mx-1">
                                            <a key="${itemIndex}" action="del" class=" btn default btn-outline el-link table-action-btn text-secondary border-secondary" href="javascript:void(0);"><i class="ti-trash"></i></a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="el-card-content text-center">
                                <h6 class="mb-1 text-truncate">${item.name_cat}</h6>
                                <span class="text-muted">${item.createAt}</span>
                            </div>
                            ${seletBtn}
                        </div>
                `;
            return (trRows += `
                <div class="col-lg-2 col-md-6">
                    <div class="card">
                        ${card}
                    </div>
                </div>
			`);
        });
        const tbody = containerEl.querySelector('.el-element-overlay');
        tbody.innerHTML = trRows;
        this.handleOnItemClick();
    };

    this.emptyRow = function () {
        let trRows = `
            <div class="col-12 ">
                <div class="card">
                    <div class=" alert customize-alert m-2 p-2   border-danger text-danger fade show remove-close-icon" role="alert">
                        <div class=" d-flex align-items-center font-weight-medium me-3 me-md-0">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-info text-danger feather-sm me-2"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="16" x2="12" y2="12"></line><line x1="12" y1="8" x2="12" y2="8"></line></svg>
                            نتیجه ای یافت نشد
                        </div>
                    </div>
                </div>
            </div>
        `;
        containerEl.querySelector('.el-element-overlay').innerHTML = trRows;
    };

    this.handleOnItemClick = function () {
        const btns = document.getElementsByClassName('table-action-btn');
        for (let index = 0; index < btns.length; index++) {
            const btn = btns[index];
            btn.addEventListener('click', async function (e) {
                e.preventDefault();
                const action = this.getAttribute('action');
                const key = this.getAttribute('key');
                if (action === 'del') {
                    selectors.inputs['pub_id'].value = dataRows[key].id;
                    const res = await delAlert('file');
                    if (res && !res.err) {
                        _this.fetchHandler();
                    }
                } else if (action === 'select') {
                    const type = this.getAttribute('data-type');
                    if (window.opener && !this.classList.contains('disabled')) {
                        console.log(dataRows[key]);
                        dataRows[key].dir = dataRows[key].dir.replace('w_100/','');
                        console.log(dataRows[key]);
                        const res = window.opener.getFileFF(dataRows[key],options.is_editor,type);
                        if (options.is_editor=='editor' || options.is_editor=='single') {
                            window.open("", '_self').window.close();
                        }
                        if (res === true) {
                            this.innerHTML = '<i class="ti-check-box"></i>';
                            this.classList.add('disabled');
                        }
                        loading(0,e);
                    }
                } else if (action === 'more') {
                    options.actions[action](dataRows[key], e);
                }
            });
        }
    };
    this.filtersHandler = function () {
        if (selectors.fileTypeBtn) {
            selectors.fileTypeBtn.addEventListener('change', async function (e) {
                e.preventDefault();
                accept_formats = this.value;
                _this.fetchHandler();
            });
        }
        const sortBtn = document.getElementById('filter_category_type');
        sortBtn.addEventListener('change', async function (e) {
            e.preventDefault();
            category = this.value;
            _this.fetchHandler();
        });
        selectors.q.addEventListener('submit',async function (e) {
            e.preventDefault();
            const table_q = document.getElementById('table_q');
            if (table_q.value.length >= 2) {
                q = table_q.value;
                page = 1;
                selectors.qClearBtn.classList.add('d-flex');
                _this.fetchHandler();
            }else{
                selectors.qClearBtn.classList.remove('d-flex');
            }
        });
        selectors.qClearBtn.addEventListener('click',async function (e) {
            e.preventDefault();
            q = '';
            totalCount = 0;
            document.getElementById('table_q').value = '';
            this.classList.remove('d-flex');
            _this.fetchHandler();
        });
    };

    this.resetPagination = function () {
        Pagination.Init(document.getElementById('pager'), {
            size: totalCount, // pages size
            limit: options.perPage, // limit per page
            page, // selected page
            clickHandler: this.fetchHandler,
            step: 3, // pages before and after current
        });
    };

    this.fetchHandler = function (callback) {
        const oldTotalCount = totalCount;
        if (typeof callback === 'number') {
            page = callback;
        }
        loading(1);
        options
            .api({ page , limit : options.perPage,cat:category,q,accept_formats })
            .then((res) => {
                const { data, total } = res;
                totalCount = total;
                if (total == '0') {
                    console.log(res);
                    _this.emptyRow();
                } else {
                    dataRows = data;

                    _this.createRows();
                    if (typeof callback === 'function') {
                        callback();
                    }
                }
                if (oldTotalCount != totalCount) {
                    _this.resetPagination();
                }
                loading(0);
                return true;
            })
            .then((res) => {
                if (typeof options.afterRender === 'function') {
                    options.afterRender();
                }
            });
    };

    this.render();
}

export { FileTable };
