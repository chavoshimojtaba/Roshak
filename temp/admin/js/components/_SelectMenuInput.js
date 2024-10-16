
function SelectMenuInput(options) {

	const _this = this;
	let searchResult = [];
    let selectors = {} ;
    let typingTimer;
    let selectBoxEl;
    let defaultSelected = 0;
	let defaultOptions = {
		id:'select-menu',
        fetch:{},
        searchStartLen: 2,//char
        searchDelay:500//ms
	}

    const assets = {
        'closeSvg':`
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" width="18" height="18" fill="#b1b0b0">
                <path d="M175 175C184.4 165.7 199.6 165.7 208.1 175L255.1 222.1L303 175C312.4 165.7 327.6 165.7 336.1 175C346.3 184.4 346.3 199.6 336.1 208.1L289.9 255.1L336.1 303C346.3 312.4 346.3 327.6 336.1 336.1C327.6 346.3 312.4 346.3 303 336.1L255.1 289.9L208.1 336.1C199.6 346.3 184.4 346.3 175 336.1C165.7 327.6 165.7 312.4 175 303L222.1 255.1L175 208.1C165.7 199.6 165.7 184.4 175 175V175zM512 256C512 397.4 397.4 512 256 512C114.6 512 0 397.4 0 256C0 114.6 114.6 0 256 0C397.4 0 512 114.6 512 256zM256 48C141.1 48 48 141.1 48 256C48 370.9 141.1 464 256 464C370.9 464 464 370.9 464 256C464 141.1 370.9 48 256 48z"/>
            </svg>
            `,
        'loadingSvg':`
            <svg version="1.1" id="loader-1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="36px" height="36px" viewBox="0 0 50 50" style="enable-background:new 0 0 50 50;" xml:space="preserve">
                <path fill="#fff" d="M25.251,6.461c-10.318,0-18.683,8.365-18.683,18.683h4.068c0-8.071,6.543-14.615,14.615-14.615V6.461z">
                <animateTransform attributeType="xml" attributeName="transform" type="rotate" from="0 25 25" to="360 25 25" dur="0.9s" repeatCount="indefinite"></animateTransform>
                </path>
            </svg>
        `
    };

	options = {...defaultOptions,...options};

    this.init = function () {
        
		selectBoxEl = document.querySelector('.'+options.id);
        
		selectBoxEl.setAttribute('id' , options.id);
        if (selectBoxEl.getAttribute('value') != 'undefined') {
            defaultSelected = selectBoxEl.getAttribute('value');
        }
        if (document.querySelector('#menuobject') != null) {
            document.querySelector('#menuobject').remove();
        }
		this.createTemplate();
		selectors.menuListEl     = selectBoxEl.querySelector('.select-menu__list');
		selectors.inpEl          = selectBoxEl.querySelector('.select-menu__input');
		selectors.closeBtn       = selectBoxEl.querySelector('.select-menu__header-close-btn');
		selectors.selectedItemEl = selectBoxEl.querySelector('.select-menu__selected-item');
		selectors.loadingEl      = selectBoxEl.querySelector('.select-menu__loading');
		selectors.resultInp      = selectBoxEl.querySelector('#select-menu__result');
		this.events();
        if (parseInt(defaultSelected)) { 
            const searchedObj = _this.objDeepSearch(options.tree,defaultSelected); 
            this.setSelected(searchedObj,1);
            return;
        }
        this.fetchMenuItems(0);

	};

	this.createTemplate = function () {
		const headerDiv = document.createElement('div');
		headerDiv.setAttribute('class','select-menu__header');
		headerDiv.innerHTML = `
			<div class="select-menu__header-input">
				<input type="text" class="form-control select-menu__input" placeholder="انتخاب کنید...">
				<input type="hidden" value="0" id="select-menu__result">
				<span class="select-menu__header-close-btn">
					${assets.closeSvg}
				</span>
			</div>
		`;
		const containerDiv = document.createElement('div');
		containerDiv.setAttribute('class','select-menu__container')
		containerDiv.innerHTML = `
			<div class="select-menu__loading">
				${assets.loadingSvg}
			</div>
			<div class="select-menu__selected-item">
			</div>
			<ul class="select-menu__list">
			</ul>
		`;
		selectBoxEl.appendChild(headerDiv);
		selectBoxEl.appendChild(containerDiv);
	};

    this.createMenuItems = function(fetchedData) { 
        let menuListItemsWithSub = '';
        let menuListItems = '';
        const keys = Object.keys(fetchedData);
        if (keys.length > 0) {
            keys.map((index) => {
                const itm = fetchedData[index];
                if (itm.hasSub) {
                    menuListItemsWithSub += `
                        <li>
                            <div
                                class="select-menu__list-item select-menu__list-item--has-sub"
                                data-id="${itm.id}"
                                data-has-sub="${itm.hasSub}"
                                data-pid="${itm.pid}"
                                data-title='${itm.title}'>
                                ${itm.title}
                            </div>
                        </li>
                    `;
                } else {
                    menuListItems += `
                        <li>
                            <div
                                class="select-menu__list-item"
                                data-id="${itm.id}"
                                data-has-sub="${itm.hasSub}"
                                data-pid="${itm.pid}"
                                data-title='${itm.title}'>
                                ${itm.title}
                            </div>
                        </li>
                    `;
                }
            });
            selectors.menuListEl.innerHTML = menuListItemsWithSub + menuListItems ;
            _this.handleOnItemClick();
        }else{
            selectors.menuListEl.innerHTML = `
                <li>
                    <div
                        class="select-menu__list-item" >
                        داده ای یافت نشد
                    </div>
                </li> 
            `;
            _this.setSelected({id:'no-result'});
        }
    };

    this.handleOnItemClick = function () {
        const menuItems = selectors.menuListEl.getElementsByClassName(
            'select-menu__list-item'
        );
        for (let index = 0; index < menuItems.length; index++) {
            menuItems[index].addEventListener('click', function (e) {
                e.preventDefault();
                const data = e.target.dataset;
                if (data.hasSub == 'true') {
                    _this.setSelected(data);
                    _this.fetchMenuItems(data.id);
                } else {
                    _this.setSelected(data,1);
                    selectBoxEl.classList.remove('is-active');
                }
            });
        }
    };

	this.events = function () {

		selectors.selectedItemEl.addEventListener('click', (e) => {
			e.preventDefault();
			const data = _this.fetchMenuItems(e.target.dataset.pid);
			_this.setSelected(data);
		});

		selectors.closeBtn.addEventListener('click', (e) => {
			selectBoxEl.classList.remove('is-active');
			if (selectors.resultInp.value == 0) {
				_this.setSelected({id:0})
			}
		});

		selectors.inpEl.addEventListener('focus', (e) => {

			selectBoxEl.classList.add('is-active');
			if (selectors.resultInp.value == 0) {
				_this.setSelected({id:0})
				_this.fetchMenuItems(0);
			}
		});

		selectors.inpEl.addEventListener('keyup',function (event) {
			clearTimeout(typingTimer);
			typingTimer = setTimeout(() => {
				const value = event.target.value;
				const len = value.length;
				if (len >= options.searchStartLen) {
					_this.fetchMenuItems(value,1);
				}
			}, options.searchDelay);
		})

		selectors.inpEl.addEventListener('keydown',function (event) {
			clearTimeout(typingTimer);
			if (selectors.resultInp.value != 0) {
				_this.setSelected({id:'no-result'});
			}
		})

		this.handleOnClickOutside();

	};

	this.showLoading = function (show) {
		show
            ? selectors.loadingEl.classList.add('select-menu__loading--show')
            : selectors.loadingEl.classList.remove('select-menu__loading--show');
	};

	this.fetchMenuItems = function(q,isSearch) {
        isSearch = isSearch || 0;
        _this.showLoading(true);
        let fetchedData = {};
        if (isSearch) {
            searchResult.length = 0;
            q = q.toLowerCase();
            _this.searchInTitles(options.tree,q);
            _this.createMenuItems(searchResult);
        }else{
            fetchedData = _this.objDeepSearch(options.tree, q);
            _this.createMenuItems(fetchedData.subItems);
        }
        _this.showLoading(false);
        return fetchedData;
    };

    this.objDeepSearch = function (obj, id) { 
        if (obj.hasOwnProperty('id') && obj['id'] == id) { 
            return obj;
        }
        const keys = Object.keys(obj); 
        for (let index = 0; index < keys.length; index++) {
            if (typeof obj[keys[index]] === 'object' && obj[keys[index]] != null) {  
                const newObj = _this.objDeepSearch(obj[keys[index]], id); 
                if (newObj != null) {
                    return newObj;
                }
            } 
        }
        return null;
    }
	this.setSelected = function(data, isFinalSelected) {
        // console.log(data);
        isFinalSelected = isFinalSelected || 0;
        selectors.resultInp.value = data.id == 'no-result' ? 0 : data.id;
        if (data.id == 0 || data.id == 'no-result') {
            if(data.id == 0) selectors.inpEl.value = '';
            selectors.selectedItemEl.innerText = '';
            selectors.selectedItemEl.classList.remove(
                'select-menu__selected-item--active'
            );
            selectors.selectedItemEl.dataset.pid = 0;
        } else if (isFinalSelected != 0) {
            selectors.inpEl.value = data.title;
        } else {
            selectors.inpEl.value = data.title;
            selectors.selectedItemEl.innerText = data.title;
            selectors.selectedItemEl.classList.add('select-menu__selected-item--active');
            selectors.selectedItemEl.dataset.pid = data.pid;
        }
    }

	this.searchInTitles = function(object,q){
        if(object.hasOwnProperty('title') && object.title.toLowerCase().indexOf(q) > -1)
            searchResult.push(object);
        for(var i=0; i<Object.keys(object).length; i++){
            if(typeof object[Object.keys(object)[i]] == "object"){
                _this.searchInTitles(object[Object.keys(object)[i]], q);
            }
        }
    }

	this.handleOnClickOutside = function () {
		document.addEventListener('click', (e) => {
			selectBoxEl.classList.remove('is-active');
			if (selectors.resultInp.value == 0) {
				_this.setSelected({id:0})
				selectors.menuListEl.innerHTML = '';
			}
		});
		selectBoxEl.addEventListener('click', (e) => {
			e.stopPropagation();
		});
	}

	this.value = function () {
		return selectors.resultInp.value;
	}

	this.init();
}

export { SelectMenuInput };
