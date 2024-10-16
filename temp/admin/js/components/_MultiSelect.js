import { fetchApi } from '../api'; 
function MultiSelect(options) {
    const _this = this;
    let searchResult = [];
    let selectors = {};
    let menuItems = {}; 
    let typingTimer;
    let selectBoxEl;
    let defaultOptions = {
        type: '',
        max:100,
        title:'تگ ها',
        api:true,
        url:`util/multi_select`,
        required: true,
        selector: '.multi-select',
        searchStartLen: 2, //char
        searchDelay: 500, //ms
    }; 

    options = { ...defaultOptions, ...options };

    this.createTemplate = function () {

        const labelEl = document.createElement('label');
        labelEl.setAttribute('class', 'control-label');
        labelEl.setAttribute('for', 'multi-select__q'); 
        if (options.required) {
            labelEl.innerHTML = `${options.title } (حداکثر ${options.max } گزینه)<span class="text-danger">*</span>`;
        }else{
            labelEl.innerHTML = `${options.title } (حداکثر ${options.max } گزینه)`;
        }
        
        const containerEl = document.createElement('div');
        containerEl.setAttribute('class', 'multi-select__container');
        containerEl.innerHTML = `
            <div class="multi-select__box">
                <div class="multi-select__inner-box">
                    <div class="multi-select__items"></div>
                    <input type="text" class="form-control multi-select__q" placeholder=" جستجو و انتخاب..." id="multi-select__q"/>
                    <div class="multi-select__loading">
                        <span class="spinner-border spinner-border-sm text-primary" role="status" aria-hidden="true"></span> 
                    </div>
                </div>
                <ul class="list-group multi-select__result"> 
                </ul>
            </div>
            `;
 
        selectBoxEl.appendChild(labelEl);
        selectBoxEl.appendChild(containerEl); 
    };

    this.createSearchResult = function (fetchedData) { 
        let menuListItems = '';  
        if (fetchedData.status  == 1) {
            const keys = Object.keys(fetchedData.data);
            keys.map((index) => {
                const itm = fetchedData.data[index]; 
                menuListItems += `
                    <li data-id="${itm.id}" data-title="${itm.title}"  class="list-group-item multi-select__result-item">${itm.title}</li>
                `; 
            });
            selectors.resultEl.innerHTML = menuListItems;
            _this.handleOnItemClick();
        } else {
            selectors.resultEl.innerHTML = `
                <li class="list-group-item ">نتیجه ای یافت نشد</li>
            `; 
        }
        // selectBoxEl.classList.add('is-active')
        this.showLoading(false);

    };

    this.handleOnItemClick = function () {
        menuItems = selectors.resultEl.getElementsByClassName(
            'multi-select__result-item'
        );
        for (let index = 0; index < menuItems.length; index++) {
            const el = menuItems[index];
            el.addEventListener('click', function (e) {
                e.preventDefault();
                const data = e.target.dataset; 
                _this.addBadge(data);
                
                if (options.api) {
                    this.remove(); 
                }
            });
        }
    };

    this.handleBadgeClick = function () {
        const badgeEls = selectors.itemsEl.querySelectorAll(
            '.multi-select__item'
        );
        for (let index = 0; index < badgeEls.length; index++) {
            const el = badgeEls[index];
            el.addEventListener('click', function (e) {
                e.preventDefault();
                this.remove(); 
                if (options.max === 1) {
                    selectors.q.classList.remove('d-none');
                }
            });
        }
    };

    this.addBadge = function (data,intiData) {  
         
        intiData = intiData || 0;
        const badgeEls = selectors.itemsEl.querySelectorAll(
            '.multi-select__item'
        ); 
        if (intiData) {
            const keys = Object.keys(data);
            let els = '';
            for (let index = 0; index < keys.length; index++) {
                const element = data[keys[index]];  
                els += `
                    <span class="mb-1 badge multi-select__item bg-primary" id="badge_${keys[index]}" data-id="${keys[index]}" data-title="${element.title}" >
                        <i class="ti-close text-white"></i>
                        ${element.title}
                    </span> 
                `;
            }
            selectors.itemsEl.innerHTML = els;
            _this.handleBadgeClick(); 
        }else{
            
            const isExist = Array.from(badgeEls).find(item=>{ 
                return item.id === 'badge_'+data.id
            });
            if (!isExist) {
                selectors.itemsEl.innerHTML += `
                    <span class="mb-1 badge multi-select__item bg-primary" id="badge_${data.id}" data-id="${data.id}" data-title="${data.title}" >
                        <i class="ti-close text-white"></i>
                        ${data.title} 
                    </span> 
                `; 
                _this.handleBadgeClick();
            } 
        }
        if (options.max === 1) {
            selectors.q.classList.add('d-none');
        }
    };

    this.init = function (tags) { 
        tags = tags|| {}
        selectBoxEl = document.querySelector(options.selector); 
        selectBoxEl.setAttribute('id', options.selector); 
        const inpEl = selectBoxEl.querySelectorAll('input');
        let data = {};
        let values = {};
        if (inpEl.length > 0) { 
            for (let index = 0; index < inpEl.length; index++) {
                const inp = inpEl[index]; 
                if(inp.dataset.selected){
                    values[inp.id] = {'title':inp.value}; 
                } 
                data[inp.id] = {'title':inp.value,'id':inp.id}; 
            } 
        } else if(Object.keys(tags).length>0){
            data = tags ;
            values = tags ;
        }
        this.createTemplate();
        selectors.q = selectBoxEl.querySelector('.multi-select__q');
        selectors.itemsEl = selectBoxEl.querySelector('.multi-select__items'); 
        
        selectors.resultEl = selectBoxEl.querySelector(
            '.multi-select__result'
        );
        selectors.loadingEl = selectBoxEl.querySelector(
            '.multi-select__loading'
        );
        
        if (values && Object.keys(values).length > 0) {
            this.addBadge(values,1);
        } 
        if (!options.api) {  
            this.createSearchResult({
                status:true,
                data:data
            });
        }
        this.events();
        // this.fetchData(0);
    }; 

    this.events = function () {
        selectors.q.addEventListener('keyup', function (event) {
            if (options.api) { 
                clearTimeout(typingTimer); 
                typingTimer = setTimeout(() => {
                    const value = event.target.value;
                    const len = value.trim().length;
                    if (len >= options.searchStartLen) {
                        _this.fetchData(value, event);
                    }
                }, options.searchDelay);
            }else{
                const value = event.target.value;
                if (value.length > 0) {
                    for (let index = 0; index < menuItems.length; index++) {
                        const el = menuItems[index];
                        if(el.dataset.title.search(value) >= 0){
                            el.classList.remove('d-none')
                        }else{
                            el.classList.add('d-none')
                        }
                    }
                }else{
                    for (let index = 0; index < menuItems.length; index++) {
                        const el = menuItems[index];
                        el.classList.remove('d-none');
                    }
                }
            }
            
            // abortFetch();
        });  
        selectors.q.addEventListener('focus', (e) => {
            selectBoxEl.classList.add('is-active');
        }); 
        this.handleOnClickOutside();
        return; 

    };

    this.showLoading = function (show) {
        show
            ? selectors.loadingEl.classList.add('multi-select__loading--show')
            : selectors.loadingEl.classList.remove(
                  'multi-select__loading--show'
              );
    };

    this.fetchData =async function (q, e) {
        this.showLoading(1); 
        searchResult.length = 0; 
        const res = await fetchApi(options.url, 'GET', {
            params: { q,type:options.type },
        })
        this.createSearchResult(res);
    };  

    this.handleOnClickOutside = function () {
        document.addEventListener('click', (e) => {
            selectBoxEl.classList.remove('is-active'); 
        });
        selectBoxEl.addEventListener('click', (e) => {
            e.stopPropagation();
        });
    };

    this.value = function () {
        const badgeEls = selectors.itemsEl.querySelectorAll(
            '.multi-select__item'
        ); 
        let res = {};
        if (badgeEls.length > 0) {
            for (let index = 0; index < badgeEls.length; index++) {
                const id = badgeEls[index].getAttribute('data-id');
                const title = badgeEls[index].getAttribute('data-title'); 
                res[id] = { 
                    title:title
                };
            }
        }
        return res; 
    }; 
    this.reset = function () {
        selectors.itemsEl.innerHTML = '';
        selectors.q.value = '';

    }
}

export { MultiSelect };
