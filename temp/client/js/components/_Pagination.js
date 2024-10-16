export const Pagination = {
    clickHandler:'',
    code: '',
    baseUrl:'',

    // --------------------
    // Utility
    // --------------------
    createUri : function () {
        const queryString = window.location.search;
        const urlParams = new URLSearchParams(queryString);
        urlParams.delete("page"); 
        Pagination.baseUrl =  window.location.origin + window.location.pathname + '?' + ((urlParams.toString().length>0)?urlParams.toString()+'&' : '');
        Pagination._baseUrl =  window.location.origin + window.location.pathname ;
    },

    // converting initialize data
    Extend: function(data) {
        data = data || {};
        Pagination.size =  Math.ceil(data.size / data.limit) ;
        Pagination.page = data.page;
        Pagination.step = data.step;
    },
    Add: function(from, to) {
        for (var i = from; i < to; i++) { 
            if (i == 1) {
                Pagination.code += '<li class="page-item"><a href="'+Pagination._baseUrl+'" value="'+i+'" class="page-link" >' + i + '</a></li>';
            }else{
                Pagination.code += '<li class="page-item"><a href="'+Pagination.baseUrl+'page='+i+'" value="'+i+'" class="page-link" >' + i + '</a></li>';

            }
        }
    },

    Last: function() {
        console.log(6);

        Pagination.code += '<li class="page-item"><a class="page-link"  href="'+Pagination.baseUrl+'page='+Pagination.size+'">' + Pagination.size + '</a></li>';
    },

    // add first page with separator
    First: function() {
        console.log(16);
        Pagination.code += '<li class="page-item"><a href="'+Pagination.baseUrl+'" class="page-link" >1</a></li>';
    },

    // --------------------
    // Handlers
    // --------------------

    // change page
    Click: function(e) {
        e.preventDefault();
        if (Pagination.page == +this.innerHTML) return;
        Pagination.page = +this.innerHTML;
        Pagination.Start();
        Pagination.clickHandler(Pagination.page)
    },

    // previous page
    Prev: function(e) {
        e.preventDefault();
        if (Pagination.page === 1) {
            return
        }
        Pagination.page--;
        if (Pagination.page < 1) {
            Pagination.page = 1;
        }
        Pagination.Start();
        Pagination.clickHandler(Pagination.page)
    },

    // next page
    Next: function(e) {
        e.preventDefault();
        if (Pagination.page === Pagination.size) {
            return
        }
        Pagination.page++;
        if (Pagination.page > Pagination.size) {
            Pagination.page = Pagination.size;
        }
        Pagination.Start();
        Pagination.clickHandler(Pagination.page)
    },

    // --------------------
    // Script
    // --------------------

    // binding pages
    Bind: function() {
        var btns = Pagination.e.getElementsByTagName('a');
        for (var i = 0; i < btns.length; i++) {
            if (+btns[i].innerHTML == Pagination.page){
                btns[i].className = 'page-link active';
                btns[i].removeAttribute('href');
            }
            btns[i].addEventListener('click', Pagination.Click, false);
        }
    },

    // write pagination
    Finish: function() {
        Pagination.e.innerHTML = Pagination.code;
        Pagination.code = '';
        Pagination.Bind();
    },

    // find pagination type
    Start: function() {
        if (Pagination.size < Pagination.step * 2 + 6) {
            Pagination.Add(1, Pagination.size + 1);
        }
        else if (Pagination.page < Pagination.step * 2 + 1) {
            Pagination.Add(1, Pagination.step * 2 + 4);
            Pagination.Last();
        }
        else if (Pagination.page > Pagination.size - Pagination.step * 2) {
            Pagination.First();
            Pagination.Add(Pagination.size - Pagination.step * 2 - 2, Pagination.size + 1);
        }
        else {
            Pagination.First();
            Pagination.Add(Pagination.page - Pagination.step, Pagination.page + Pagination.step + 1);
            Pagination.Last();
        }
        Pagination.Finish();
    },

    // --------------------
    // Initialization
    // --------------------

    // binding buttons
    Buttons: function(e) {
        e.querySelector('.pag-link--prev').addEventListener('click', Pagination.Prev, false);
        e.querySelector('.pag-link--next').addEventListener('click', Pagination.Next, false);
    },

    // create skeleton
    Create: function(e) {
        e.innerHTML = `
                <nav aria-label="Page navigation">
                    <ul class="pagination px-0 mb-0 justify-content-center">
                        <li class="page-item ms-5">
                            <a class="page-link  pag-link--prev" href="#prev"  aria-label="prev">
                                <i class="icon icon-arrow-right-14 fs-4 mt-0 ms-2 float-end"></i>
                                صفحه قبل
                            </a>
                        </li>
                        <div id="pager-items" class="d-flex"></div>
                        <li class="page-item me-5">
                            <a class="page-link  pag-link--next" href="#next"  aria-label="next">
                                <i class="icon icon-arrow-left4 fs-4 mt-0 me-2 float-start"></i>
                                صفحه بعد
                            </a>
                        </li>
                    </ul>
                </nav>
		`;
        Pagination.e = e.querySelector('#pager-items');
        Pagination.Buttons(e);
    },

    // init
    Init: function(e, data) { 
        if (!data.size || data.size == '0') {
            e.classList.add('d-none');
            return
        }else{
            e.classList.remove('d-none');
        }
        Pagination.createUri();
        Pagination.Extend(data);
        Pagination.Create(e);
        Pagination.Start();
        Pagination.clickHandler = data.clickHandler;
    }
};


