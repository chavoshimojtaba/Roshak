import { fetchApi } from '../../api';
import { Grid } from "../../components/_Grid";
import {   isMobile, select2 } from '../../util/util';
export const CommentScreen = {
    render() {
        var bsOffcanvas;
        if (isMobile()) {
            var myOffcanvas = document.getElementById('offcanvasFilter')
            var bsOffcanvas = new bootstrap.Offcanvas(myOffcanvas)
        }
        select2(0,'filter-dashboard-template').on('select2:select', function (e) {
            switch (e.target.id) {
                case 'inp_publish':
                    grid.changeFilters({ publish: this.value });
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
            afterRender:function (data) {
                const btns = document.querySelectorAll('.answer-btn')
                for (let index = 0; index < btns.length; index++) {
                    const element = btns[index];
                    element.addEventListener('click',function (e) {
                        e.preventDefault();
                        const pid = e.currentTarget.getAttribute('data-id');
                        const pbid = e.currentTarget.getAttribute('data-pbid');
                        console.log(pid);
                        const commentElement = e.currentTarget.closest('.item-comment');
                        commentElement.querySelector('.answer-form').innerHTML =`<hr class="border-secondary"><form novalidate id="form-answer-submit"><div class="d-flex bg-light shadow-sm py-2 mt-0 px-2 rounded-3 comment-message form-group">
                        <img width="45px" height="45px" class="rounded-pill border" src="${HOST}file/client/images/avatar.png">
                        <input type="text" name="text" required class="form-control bg-light border-0 mx-3" placeholder="متن پیام خود را وارد کنید">
                        <span class="btn btn-default rounded-3">
                            <i class="icon icon-grammerly fs-3 text-secondary"></i>
                        </span>
                        <a class="btn btn-primary submit-reply rounded-3 px-4">ارسال</a>
                    </div></form>`;
                        setTimeout(() => {
                            const form = commentElement.querySelector('#form-answer-submit');
                            commentElement.querySelector('.submit-reply').addEventListener('click',function(){
                                var valid = new Pristine(form).validate();
                                if (valid) {
                                    let data = new FormData(form);
                                    data.append('pbid',pbid);
                                    data.append('pid',pid);
                                    fetchApi('comment', 'POST', { body:  Object.fromEntries(data)}).then(async (res) => {
                                        if (res.status) {
                                            // toast('s','پیام شما با موفقیت ثبت شد')
                                            commentElement.querySelector('.answer-form').innerHTML = '';
                                        } else {
                                            throw new Error(res.msg);
                                        }
                                    }).catch(err=>{
                                    })
                                }
                            });
                        }, 1000);
                    })
                }
            }
        });
        grid.render();
 
 
        let date = document.getElementById('inp_date_g').value;
        const datePicker = new mds.MdsPersianDateTimePicker(document.getElementById('inp_date'), {
            disableBeforeDate:new Date(2023, 9, 1),
            disableAfterToday:true,
            targetTextSelector: '[data-name="inp-date"]',
            targetDateSelector: '[name="inp_date"]',
            yearOffset:2,
            onDayClick:function(event){
                grid.changeFilters({ date: document.querySelector('[name="inp_date"]').value});
            }
        });
        if (date.length > 5) {
            datePicker.setDate(new Date(date));
        }

        document.querySelector('.btn-clear').addEventListener('click',function (e) {
            datePicker.clearDate()
            grid.changeFilters({ date: 'delete'});
        })
    }
};