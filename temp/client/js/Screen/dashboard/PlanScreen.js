import { Grid } from "../../components/_Grid";
export const PlanScreen = {
    render(){
        const grid = new Grid({
            afterRender:function(data){
            }
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
        })
    }
};