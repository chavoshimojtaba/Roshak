export const Pagination = {
    clickHandler:'',
    code: '',

    // --------------------
    // Utility
    // --------------------

    // converting initialize data
    Extend: function(data) {
        data = data || {};
        Pagination.size = Math.ceil(data.size / data.limit);
        Pagination.page = data.page;
        Pagination.step = data.step;
    },

    // add pages by number (from [s] to [f])
    Add: function(from, to) {
        for (var i = from; i < to; i++) {
            Pagination.code += '<li class="page-item"><a value="'+i+'" class="page-link" >' + i + '</a></li>';
        }
    },

    // add last page with separator
    Last: function() { 
        Pagination.code += '<li class="page-item"><a class="page-link" >' + Pagination.size + '</a></li>';
    },

    // add first page with separator
    First: function() {

        Pagination.code += '<li class="page-item"><a class="page-link" >1</a></li>';
    },



    // --------------------
    // Handlers
    // --------------------

    // change page
    Click: function() {
        if (Pagination.page === +this.innerHTML) {
            return
        }
        Pagination.page = +this.innerHTML;
        Pagination.Start(); 
        Pagination.clickHandler(Pagination.page)
    },

    // previous page
    Prev: function() {
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
    Next: function() {
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
            if (+btns[i].innerHTML === Pagination.page) btns[i].className = 'page-link active';
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
				<nav aria-label="Page navigation" class="d-flex align-items-center justify-content-center m-0">
					<ul class="pagination">
						<li class="page-item page-item--prev">
							<a class="page-link pag-link--prev" aria-label="Previous">
							<span aria-hidden="true">&laquo;</span>
							</a>
						</li>
						<div id="pager-items" class="d-flex"></div> 
						<li class="page-item">
							<a class="page-link pag-link--next" aria-label="Next">
							<span aria-hidden="true">&raquo;</span>
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
        console.log(e);
        console.log(data);
        Pagination.Extend(data);
        Pagination.Create(e);
        Pagination.Start();
        Pagination.clickHandler = data.clickHandler;
    }
};
 

