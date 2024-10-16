import { Grid } from "../../components/_Grid";
import { isMobile, owlCarousel, select2 } from "../../util/util";
export const DownloadScreen = {
    render(){
        var bsOffcanvas;
        if (isMobile()) {
            var myOffcanvas = document.getElementById('offcanvasFilter')
            var bsOffcanvas = new bootstrap.Offcanvas(myOffcanvas);
            owlCarousel('.info-card-slide',{
                items: 1,
                margin: 15,
                stagePadding: 20,
                dots: !1,
                nav: !1,
                responsive: { 0: { items: 1 }, 1200: { items: 3 } },
                navText: [
                    "<i class='d-block icon fs-4 icon-arrow-right-14'></i>",
                    "<i class='d-block icon fs-4 icon-arrow-left4'></i>",
                ],
            }); 
        }
        select2(); 

        select2(0,'filter-search-template').on('select2:select', function (e) {
            switch (e.target.id) {
                case 'inp_cid':
                    grid.changeFilters({ cid: this.value });
                    if (isMobile()) {
                        bsOffcanvas.hide();
                    }
                    break;
                case 'inp_send_type':
                    grid.changeFilters({ send_type: this.value });
                    if (isMobile()) {
                        bsOffcanvas.hide();
                    }
                    break;
            }
        });
        const grid = new Grid({
        });
        grid.render();
        const a = new mds.MdsPersianDateTimePicker(document.getElementById('inp_date'), {
            disableBeforeDate:new Date(2023, 9, 1),
            disableAfterToday:true,
            targetTextSelector: '[data-name="inp-date"]',
            targetDateSelector: '[name="inp_date"]',
            yearOffset:2,
            onDayClick:function(event){
                grid.changeFilters({ date: document.querySelector('[name="inp_date"]').value});
            }
        });
        document.querySelector('.btn-clear').addEventListener('click',function (e) {
            a.clearDate()
            grid.changeFilters({ date: 'delete'});
        }); 
    }
};