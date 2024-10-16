import { Grid } from "../../components/_Grid";
import { SubmitForm } from "../../components/_Forms";
import { isMobile, select2, toast } from "../../util/util";
import { Uploader } from "../../components/_Uploader";
export const TicketScreen = {
    render() {
         var bsOffcanvas;
        if (isMobile()) {
            var myOffcanvas = document.getElementById('offcanvasFilter')
            var bsOffcanvas = new bootstrap.Offcanvas(myOffcanvas)
        }  
        select2().on('select2:select', function (e) { 
            switch (e.target.id) {
                case 'inp_status_filter':
                    grid.changeFilters({ status: this.value });
                     if (isMobile()) {
                        bsOffcanvas.hide();
                    }
                    break;
                case 'inp_role_filter':
                    grid.changeFilters({ role: this.value });
                     if (isMobile()) {
                        bsOffcanvas.hide();
                    }
                    break;
            }
        });
        const grid = new Grid({});
        grid.render();
        const uploadfile = new Uploader({
			formats: '.pdf ,  .jpeg , .jpg , .zip',
			title: 'آپلود فایل'
		});
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
        const modal = new bootstrap.Modal(document.querySelector('#exampleModal'));
        document.querySelector('#exampleModal').addEventListener('shown.bs.modal', function () {  
            $('.modal select').select2({
                dir: 'rtl',
                dropdownParent: $("#exampleModal"),
                language: 'fa',
                minimumResultsForSearch: -1,
                theme: 'filter-search-template',
            })
          })
        document.getElementById('submit-new-request').addEventListener('click', function (e) {
            SubmitForm(e, {
                hasFile: false,
                id: 'form-new-request',
                api: {
                    POST: 'ticket/add'
                },
                onSend:function (e) { 
			       return  {files:uploadfile.getFiles(true)};
                },
                onSuccess: function (res,data) {
                    toast('s','تیکت با موفقیت ثبت شد.') 
                    modal.hide();
                    grid.changeFilters({})
                },
                onFailed: function (res) {
                    console.log(res);
                },
            })
        });
        document.querySelector('#exampleModal-btn').addEventListener('click',function (e) {
            e.preventDefault();
            modal.show()
        }) 
    },
    slug() { 
        if (document.querySelector('#form-reply-submit') != null) {
            const form = document.querySelector('#form-reply-submit');
            document.getElementById('submit-reply').addEventListener('click', function (e) {

                SubmitForm(e, {
                    hasFile: true,
                    validation:false,
                    id: 'form-reply-submit',
                    api: {
                        PUT: 'ticket/reply'
                    }, 
                    onSuccess: function (res,data) {
                        const full_name = document.querySelector('#member_full_name').value;
                        const img       = document.querySelector('#member_img').value;
                        form.reset();
                        document.querySelector('.comments').innerHTML += `
                                <div class="d-flex comment-item mt-4">
                                <div class="comment-avatar">
                                    <img width="50px" height="50px" class="rounded-pill" src="${HOST}${img}" alt="">
                                </div>
                                <div class="comment-user pe-2">
                                <span class="fs-6 fw-bold pe-2">${full_name}</span>
                                    <span class="date pe-2 me-2 border-end fs-6 text-secondary">
                                        همین الان
                                    </span>
                                    <div class="comment-text rounded-start rounded-bottom px-4 py-3 bg-light text-secondary bg-opacity-25">
                                        ${data.text}
                                    </div>
                                </div>
                            </div>
                        `;

                    },
                })
            });
            
            const uploadfile = new Uploader({
                formats: '.pdf ,  .jpeg , .jpg , .zip',
                title: 'آپلود فایل'
            });
            const modal = new bootstrap.Modal(document.querySelector('#exampleModal')); 
            document.querySelector('#exampleModal-btn').addEventListener('click',function (e) {
                e.preventDefault();
                modal.show()
            }) 
            document.getElementById('submit-new-request').addEventListener('click', function (e) { 
                const files = uploadfile.getFilesDetails();
                const keys = Object.keys(uploadfile.getFilesDetails())
                SubmitForm(e, {
                    hasFile: false,
                    validation:false,
                    id: 'form-file-submit',
                    api: {
                        PUT: 'ticket/reply'
                    },
                    onSend:function (e) { 
                        return  {files:JSON.stringify(files),text:'فایل پیوست'};
                    },
                    onSuccess: function (res,data) {
                        form.reset();
                        const full_name = document.querySelector('#member_full_name').value;
                        const img       = document.querySelector('#member_img').value;
                        let rows = '';
                        for (let index = 0; index < keys.length; index++) {
                            console.log(files[index]);
                            rows += ' <a class="ticket-card__file btn btn-info px-2 mt-2 me-2" href="' + HOST + files[index].file+ '" target="_blank">دانلود فایل</a>'; 
                        }
                        document.querySelector('.comments').innerHTML += `
                            <div class="d-flex comment-item mt-4">
                                <div class="comment-avatar">
                                    <img width="50px" height="50px" class="rounded-pill" src="${HOST}${img}" alt="">
                                </div>
                                <div class="comment-user pe-2">
                                <span class="fs-6 fw-bold pe-2">${full_name}</span>
                                    <span class="date pe-2 me-2 border-end fs-6 text-secondary">
                                        لحظاتی پیش
                                    </span>
                                    <div class="comment-text rounded-start rounded-bottom px-4 py-3 bg-light text-secondary bg-opacity-25">
                                        فایل های پیوست
                                        <div class="d-flex align-items-center text-white">
                                            ${rows}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        `;
                        modal.hide();
                    },
                })
            });
        }
    }

};