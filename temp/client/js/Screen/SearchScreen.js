import { Grid } from "../components/_Grid"; 
import { isMobile } from "../util/util";
import { productActionBtns } from "../util/util_product";
export const SearchScreen = {
    _render(){
        var _els = document.querySelectorAll('.category-childs-element .bg-more');
        for (var index = 0; index < _els.length; index++) {
            _els[index].addEventListener('click',function (e) {
                e.preventDefault(); 
                console.log(1112);
                document.querySelector('.category-childs-elements').classList.add('flex-wrap')
            });
        }
         
        let filters = {};
        const defaultFilters = { 
            dimensions  : [],
            color_mode  : [],
            format      : [],
            layer       : [],
            resulation  : [],
        };
        const inpformat_q = document.querySelector('[name="format_q"]');
        const inpdimensions_q = document.querySelector('[name="dimensions_q"]');
        const filter_formats_checkboxs = document.querySelectorAll('.filter_formats_checkboxs .form-check');
        var _sortBtn = document.querySelectorAll('.sort-btn');
        const filter_dimensions_checkboxs = document.querySelectorAll('.filter_dimensions_checkboxs .form-check');
        var _tagsContainerEl = document.querySelector('.filter-tags-container');
        var _els = document.querySelectorAll('.filter-widget .filter-checkbox');
        var _tagsEl = document.querySelector('.filter-tags');
        var _clearFilters = document.getElementById('clear-all-filters');
        inpdimensions_q.addEventListener('input',function (e) {
            e.preventDefault()
            for (let index = 0; index < filter_dimensions_checkboxs.length; index++) {
                const element = filter_dimensions_checkboxs[index];
                if (element.getAttribute('data-title').search(this.value) >= 0) {
                    element.classList.remove('d-none')
                }else{
                    element.classList.add('d-none')
                }

            }
        }) 
        inpformat_q.addEventListener('input',function (e) {
            e.preventDefault()
            for (let index = 0; index < filter_formats_checkboxs.length; index++) {
                const element = filter_formats_checkboxs[index];
                if (element.getAttribute('data-title').search(this.value) >= 0) {
                    element.classList.remove('d-none')
                }else{
                    element.classList.add('d-none')
                }
            }
        }) 
        if (isMobile()) { 
            const canvasFilter = document.getElementById('offcanvasFilter');
            const canvasFilterBtn = document.querySelector('#offcanvasFilter .offcanvas-footer');
            canvasFilter.addEventListener('hidden.bs.offcanvas', function () {
                canvasFilterBtn.classList.add('d-none');
            })
            canvasFilter.addEventListener('show.bs.offcanvas', function () { 
                canvasFilterBtn.classList.add('d-none');
            })
        } 
        const grid = new Grid({
            filters:defaultFilters,
            emptyHtml:`<div class="d-flex flex-column align-items-center  justify-content-center w-100">
                <img width="200px" class="rounded-2" src="${HOST}file/client/images/no_result.svg" />
                <div class="fs-4 fw-bold text-secondary">
                    نتیجه ای یافت نشد!
                </div>
            </div>`,
            afterRender:function(data){  
                productActionBtns(); 
                console.log('data');
                console.log(data);
                if (data && data.q != 'undefined') {  
                    setQtoFilters(data.q);
                }
                if (isMobile()) {
                    document.querySelector('#offcanvasFilter .offcanvas-footer').classList.remove('d-none');
                }
            }
        });
        grid.render(); 
        for (let index = 0; index < _sortBtn.length; index++) {
            _sortBtn[index].addEventListener('click',function (e) {
                for (let m = 0; m < _sortBtn.length; m++) {
                    _sortBtn[m].classList.remove('active');
                }
                this.classList.add('active');
                grid.changeFilters({sort:this.getAttribute('value')});
            });
        }
        
       
        _clearFilters.addEventListener('click',function (e) {
            for (var index = 0; index < _els.length; index++) {
                const el = _els[index];
                el.checked = false;
            }
            _tagsEl.innerHTML = '';
            _tagsContainerEl.classList.add('d-none');
            defaultFilters.is_premium = 'delete';

            grid.changeFilters(defaultFilters);
        }); 
        for (var index = 0; index < _els.length; index++) {
            const el = _els[index];
            el.addEventListener('change',function (e) {
                const type = this.getAttribute('data-type');
                const value = this.value;
                filters[type] = filters[type] || [];
                if (this.checked) {
                    filters[type].push(value);
                    const title = this.getAttribute('data-title');
                    _tagsEl.insertAdjacentHTML(
                        'beforeend',
                        '<div class="bg-secondary filter-tag pointer text-secondary bg-opacity-10 rounded-3 px-2 py-2 mb-2 ms-2" data-id="'+value+'" id="filter-tag_'+value+'">'+title+'<i class="icon-svg close xsmall gray float-start me-2 fs-5"></i></div>',
                      );
                    filterTags();
                    _tagsContainerEl.classList.remove('d-none');
                }else{
                    document.querySelector('#filter-tag_'+value).remove();
                    if(_tagsEl.innerText.length<=1)_tagsContainerEl.classList.add('d-none');
                    filters[type].remove(value);
                }
                if (type == 'free') {
                    createFilterObject();
                }else{
                } 
                grid.changeFilters(filters);
            });
        } 
        function setQtoFilters(txt) {  
            var _els = document.querySelectorAll('#filter-tag_q');  
            for (var index = 0; index < _els.length; index++) {
                _els[index].remove()
            } 
            if (typeof txt != 'undefined' &&  txt.length>0) {
                _tagsEl.insertAdjacentHTML(
                    'beforeend',
                    '<div class="bg-secondary filter-tag pointer text-secondary bg-opacity-10 rounded-3 px-2 py-2 mb-2 ms-2" data-id="q" id="filter-tag_q">'+txt+'<i class="icon-svg close xsmall gray float-start me-2 fs-5"></i></div>',
                    );
                    _tagsContainerEl.classList.remove('d-none');
                } 
                filterTags();
        }
        document.querySelector('#filter_free').addEventListener('change',function (e) {
            createFilterObject();
        });
        if (document.getElementsByClassName('widget-tags').length>0) {
            document.getElementsByClassName('widget-tags--btn')[0].addEventListener('click',function (e) {
                this.classList.add('d-none');
                document.getElementsByClassName('widget-tags')[0].classList.add('widget-tags--open');
            });
            if (document.getElementsByClassName('widget-tags')[0].offsetHeight == 80) {
                document.getElementsByClassName('widget-tags--btn')[0].classList.remove('d-none');
            }
        }
        function filterTags() {
            var _els = document.getElementsByClassName('filter-tag'); 
            if (_els.length <= 0) {
                _tagsContainerEl.classList.add('d-none');
                return;
            };
            for (var index = 0; index < _els.length; index++) {
                _els[index].addEventListener('click',function (e) {
                    e.preventDefault();
                    if (this.id == 'filter-tag_q') { 
                        this.remove();
                        if(_tagsEl.innerText.length<=1)_tagsContainerEl.classList.add('d-none');
                        createFilterObject(1);
                    }else{
                        document.querySelector('.filter-checkbox[value="'+this.getAttribute('data-id')+'"]').checked = false;
                        this.remove();
                        if(_tagsEl.innerText.length<=1)_tagsContainerEl.classList.add('d-none');
                    }
                    createFilterObject();
                });
            }
        }
        function createFilterObject(remove_q) {
            remove_q = remove_q || 0;
            filters = {};
            for (let index = 0; index < _els.length; index++) {
                const element = _els[index];
                const type = element.getAttribute('data-type');
                filters[type] = filters[type] || [];
                if (element.checked) {
                    filters[type] = filters[type] || [];
                    filters[type].push(element.value);
                }
            }
            if (document.querySelector('#filter_free').checked) {
                filters.is_premium = 'free';
            }else{
                filters.is_premium = 'delete';
            } 
            if (remove_q == 1) {
                filters.q = 'delete';
            } 
            // console.log(filters);
            grid.changeFilters(filters);
        }
        filterTags();
    }
};