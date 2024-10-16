import { Grid } from "../../components/_Grid";
import { SubmitForm } from "../../components/_Forms";
import { isMobile, owlCarousel, select2, toast } from "../../util/util";
export const Settlement_requestsScreen = {
    render() {
        var bsOffcanvas;
        if (isMobile()) {
            var myOffcanvas = document.getElementById('offcanvasFilter')
            var bsOffcanvas = new bootstrap.Offcanvas(myOffcanvas);
            owlCarousel('.info-card-slide',{
                items: 2,
                margin: 15, 
                dots: !1,
                nav: 1,
                responsive: { 0: { items: 2 }, 1200: { items: 3 } },
               
            });
        }
        select2(0,'filter-dashboard-template').on('select2:select', function (e) {
            switch (e.target.id) {
                case 'inp_status':
                    grid.changeFilters({ status: this.value });
                    if (isMobile()) {
                        bsOffcanvas.hide();
                    }
                    break; 
                default:
                    break;
            }
            if (isMobile()) {
                bsOffcanvas.hide();
            }
        });
        const grid = new Grid({});
        grid.render();
        const a = new mds.MdsPersianDateTimePicker(document.getElementById('inp_date'), {
            disableBeforeDate:new Date(2023, 9, 1),
            disableAfterToday:true,
            targetTextSelector: '[data-name="inp-date"]',
            targetDateSelector: '[name="inp_date"]',
            yearOffset:2,
            onDayClick:function(event){ 
                grid.changeFilters({ date: document.querySelector('[name="inp_date"]').value});
                if (isMobile()) {
                    bsOffcanvas.hide();
                }
            }
        });
      
        document.querySelector('.btn-clear').addEventListener('click',function (e) {
            a.clearDate()
            grid.changeFilters({ date: 'delete'});
        })
        
        if (document.querySelector('#submit-new-request') != null) {
            var myModal = new bootstrap.Modal(document.getElementById('exampleModal'), {})
            document.querySelector('#openModal').addEventListener('click',function (e) {
                a.clearDate()
                myModal.show()
            })
            document.getElementById('submit-new-request').addEventListener('click', function (e) {
                SubmitForm(e, {
                    id: 'form-new-request',
                    api: {
                        POST: 'financial/add_settlement_requests'
                    },
                    onSuccess: function (res,data) {
                        myModal.hide()
                        grid.changeFilters({})
                        toast('s','درخواست شما با وفقیت ثبت شد'); 
                        document.querySelector('#openModal').remove()
                    },
                    onFailed: function (res) {
                        toast('e');
                    },
                })
            });
        }
    },
};