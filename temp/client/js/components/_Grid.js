import { fetchApi } from '../api';
import { loading, scrollTop } from '../util/util';
import { Pagination } from './_Pagination';

function Grid(options) {

    const _this = this;
    let selectors = {
        inputs: {}
    };
    let dataRows = {};
    let apiQueries;
    let containerEl;
    let filters = {
        q: '',
        sort: '0'
    };
    let q_type = '';
    let htmlTemplate = '';
    let sort_type = 'DESC';
    let defaultOptions = {
        page: 1,
        searchStartLen: 0,
        searchDelay: 700,
        totalCount: 0,
        id: '.content-grid',
        afterRender: '',
        perPage: 10
    };

    options = { ...defaultOptions, ...options };

    this.render = function () {
        // return
        containerEl = document.querySelector(`${options.id}`);
        const data = JSON.parse(containerEl.getAttribute('data-jsn'));
        if (containerEl.tagName == 'TBODY') {
            htmlTemplate = containerEl.nextElementSibling.innerHTML;
            containerEl.nextElementSibling.remove();
            containerEl.parentElement.insertAdjacentHTML('afterend', '<div class="d-flex w-100 justify-content-center"><div id="pager"  class="d-flex justify-content-center bg-white shadow-custom border-opacity-10 p-3 rounded-4 justify-content-center"></div></div>');
        } else {
            htmlTemplate = containerEl.querySelector('.ssr_grid_template').innerHTML;
            containerEl.querySelector('.ssr_grid_template').remove();
            containerEl.insertAdjacentHTML('afterend', '<div class="d-flex  justify-content-center"><div id="pager"  class="d-flex justify-content-center   bg-white shadow-custom border-opacity-10 p-3 rounded-4 justify-content-center"></div></div>');
        }
        if (options.filters != 'undefined') {
            filters = { ...filters, ...options.filters };
        }
        options.totalCount = data.tt;
        options.perPage = data.lm;
        options.page = data.p;
        options.api = data.ap;
        this.resetPagination();
        this.createUri(0, 1);
        this.searchInput();
        if (typeof options.OnInitail === 'function') {
            options.OnInitail(this.fetch)
        } else if (typeof options.afterRender === 'function') { 
            options.afterRender(filters)
        }

    };

    this.clearSortClass = function () {
        for (let index = 0; index < selectors.th.length; index++) {
            selectors.th[index].classList.remove('asc');
            selectors.th[index].setAttribute('data-type', 'desc');
        }
    };

    this.createRows = function () {
        if (document.querySelector('.grid-data--empty') != null) {
            document.querySelector('.grid-data--empty').remove()
        }
        let htmlElements = '';
        dataRows.map((item) => {
            item.HOST = HOST;
            item.a = 'a';
            item.img_tag = 'img';
            item.href = 'href'; 
            let htmlItem = htmlTemplate;
            for (const property in item) {
                htmlItem = htmlItem.replaceAll('[' + property + ']', item[property]);
                htmlItem = htmlItem.replace('<!--', '');
                htmlItem = htmlItem.replace('-->', '');
                // console.log(htmlItem);
            }
            return htmlElements += htmlItem
        });
        containerEl.innerHTML = htmlElements;
    };

    this.emptyRow = function () {
        let trRows
        if (typeof(options.emptyHtml) != 'undefined') {
            trRows = options.emptyHtml;
        }else{
            trRows = '<div class="alert alert-danger w-100 my-2 border-0 py-2 d-flex align-items-center" role="alert"> <span class="pe-2">' + langs.noResultsFound + '  </span></div> ';
        }
        
        if (containerEl.tagName == 'TBODY') {
            trRows = '<tr><td colspan="100%" class="border-0 px-0">'+trRows+'</td></tr> ';
        }else if(containerEl.classList.contains('row')){

            trRows = '<div  class="col-12">'+trRows+'</div> ';
        }
        containerEl.innerHTML = trRows;
    };

    this.tableFilterClickHandler = function () {
        const sortable = document.getElementsByClassName('sortable')
        for (let index = 0; index < sortable.length; index++) {
            const sortableBtn = sortable[index];
            sortableBtn.addEventListener('click', async function (e) {
                e.preventDefault();
                sort = this.getAttribute('data-key');
                sort_type = (this.getAttribute('data-type') === 'asc') ? 'desc' : 'asc';
                await _this.clearSortClass();
                this.setAttribute('data-type', sort_type);
                if (sort_type == 'asc') {
                    this.classList.add('asc')
                }
                _this.fetch(1);
            });
        }
    };

    this.createUri = function (resetPage, initial) {
        apiQueries = {};
        const queryString = window.location.search;
        const urlParams = new URLSearchParams(queryString);
        const keysParams = urlParams.keys();
        if (initial) {
            for (const key of keysParams) { 
                filters[key] = urlParams.get(key)
            }
            return;
        }
        for (const key in filters) {
            if (typeof filters[key] == 'object') {
                if (filters[key].length > 0) {
                    urlParams.set(key, filters[key].join(','));
                } else {
                    urlParams.delete(key);
                }
            } else if (key === 'mid' || key == 'designer') {
                urlParams.delete(key);
            }  else if (filters[key] === 'delete' ) {
                urlParams.delete(key);
                delete filters[key];
            } else if (filters[key] != '0' && filters[key]) {
                urlParams.set(key, filters[key]);
            } else {
                urlParams.delete(key);
            }
        }
        if (resetPage == 'yes' || urlParams.get('page') <= 1) {
            urlParams.delete('page');
            delete filters.page;
        }

        urlParams.delete('platform_view');
        apiQueries = urlParams.toString();
        if (apiQueries.length > 3) {
            history.pushState({}, '', window.location.origin + window.location.pathname + '?' + apiQueries);
        } else {
            history.pushState({}, '', window.location.origin + window.location.pathname);
        }
    }

    this.resetPagination = function () {
        if (parseInt(options.totalCount) > parseInt(options.perPage)) {
            Pagination.Init(document.getElementById('pager'), {
                size: options.totalCount,
                limit: options.perPage,
                page: options.page,
                clickHandler: (p) => {
                    this.fetch(p);
                },
                step: 3
            });
        } else {
            document.getElementById('pager').classList.add('d-none')
        }
    };

    this.searchFilterHandler = function () {
        selectors.q.addEventListener('submit', async function (e) {
            e.preventDefault();
            const table_q = document.getElementById('table_q');
            if (table_q.value.length >= 3) {
                q = table_q.value;
                q_type = selectors.q_type.value;
                page = 1;
                selectors.qClearBtn.classList.add('d-flex');
                _this.fetch();
            } else {
                selectors.qClearBtn.classList.remove('d-flex');
            }
        });
        selectors.qClearBtn.addEventListener('click', async function (e) {
            e.preventDefault();
            _this.resetQ();
            this.classList.remove('d-flex');
            _this.fetch();
        });
    };

    this.searchInput = function () {
        if (document.querySelector('#inp_grid_q') != null) {
            let typingTimer;
            selectors.q = document.querySelector('#inp_grid_q');
            selectors.q.addEventListener('keyup', function (event) {
                const value = event.target.value; 
                clearTimeout(typingTimer);
                typingTimer = setTimeout(() => { 
                    loading(selectors.q.closest('div'),1);
                    const len = value.trim().length;
                    if (len >= options.searchStartLen) {
                        filters.q = value;
                        _this.fetch(1); 
                        loading(selectors.q.closest('div'),0);

                    }
                }, options.searchDelay);
            });
        }
    };

    /* this.resetQ = function () {
        q = '';
        q_type = '';
        options.totalCount = 0;
        document.getElementById('table_q').value = '';
    }; */
    this.changeFilters = function (data) {
        data = data || {};
        const keys = Object.keys(data);
        for (let index = 0; index < keys.length; index++) {
            if (!data[keys[index]]) {
                delete filters[keys[index]];
            } else {
                filters[keys[index]] = data[keys[index]];
            }
        }
        this.fetch(1);
    }
    this.fetch = function (pageOrCallback) {
        const oldTotalCount = options.totalCount;
        if (typeof pageOrCallback === 'number') {
            if (pageOrCallback <= 1) {
                delete filters.page;
            } else {
                filters.page = pageOrCallback;
            }
            options.page = pageOrCallback;
        }
        this.createUri((pageOrCallback <= 1) ? 'yes' : '');
        containerEl.innerHTML = '';
        scrollTop();
        let url = options.api;
        if (document.querySelector('#page-slug') !== null) {
            url = options.api +'/'+document.querySelector('#page-slug').value;
        } 
        // loading(selectors.innerContainerEl,1,true);
        fetchApi(url, 'GET', {
            params: { data: { page: options.page, limit: options.perPage, total: options.totalCount, sort: filters.sort, sort_type, q: filters.q, q_type, ...filters } },
        }).then((res) => {
            options.totalCount = res.total ? res.total : 0;
            if (!res.status) {
                _this.emptyRow();
            } else {
                dataRows = res.result;
                _this.createRows();
                if (typeof pageOrCallback === 'function') {
                    pageOrCallback()
                }
            }

            if (oldTotalCount != options.totalCount) {
                _this.resetPagination();
            }
            // loading(selectors.innerContainerEl,0,true);
            return res;
        }).then(res => {
            if (typeof options.afterRender === 'function') {
                options.afterRender({ q: filters.q, total: options.totalCount })
            }
        })
    };

}

export { Grid };
