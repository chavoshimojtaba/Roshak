import { fetchApi } from '../api';
import { DataTable } from '../components/_DataTable';
import { LNG_ERROR, LNG_MSG_CONNECTION_ERROR, LNG_MSG_SUCCESS, LNG_SUCCESS } from '../util/consts';
import { loading, swalBs, toast } from '../util/util';
import { Validator } from '../util/Validator';

export const MemberScreen = {
    render: (arg) => {
        arg = arg || 0;
        new DataTable({
            cell: [
                {
                    key: 'img',
                    title: 'تصویر',
                    type: 'file',
                    filetype: 'image',
                },
                {
                    key: 'full_name',
                    title: 'نام و نام خانوادگی',
                    type:'link',
                    options: {
                        url:"designers/|slug",
                        if:'slug'
                    },
                    search:true,
                    sort:true
                },
                {
                    key: 'mobile',
                    title: 'شماره همراه',
                    search:true,
                    sort:true,
                    editable: 'text',
                },
                {
                    key: 'email',
                    search:true,
                    title: 'ایمیل',
                    sort:true
                },
                {
                    key: 'type',
                    type: 'option',
                    sort:true,
                    options: {
                        option_types: {
                            'designer': `<span class="mb-1 badge font-weight-medium bg-light-primary text-primary">طراح</span>`,
                            'common': `<span class="mb-1 badge font-weight-medium bg-light-secondary text-secondary">عادی</span>`
                        },
                    },
                    title: 'نوع کاربری'
                },
                {
                    key: 'status',
                    type: 'option',
                    sort:true,
                    title: 'وضعیت',
                    options: {
                        option_types: {
                            'active': `<span class="mb-1 badge font-weight-medium bg-light-success text-success">فعال</span>`,
                            'deactive': `<span class="mb-1 badge font-weight-medium bg-light-danger text-danger">غیر فعال</span>`
                        },
                    },
                },
                {
                    key: 'createAt',
                    title: 'تاریخ',
                    type:'date'
                },
                {
                    key: 'more',
                    title: 'بیشتر',
                    type: 'public-btn',
                    options: {
                        class: 'btn-light-primary text-primary more-file',
                        value: '<i class="ti-eye"></i>',
                        if:'has_page'
                    },
                }
            ],
            api: (data) => {
                if (arg != 0) {
                    if (arg === 'designer' || arg === 'common' ) {
                        data.type = arg;
                    }else{
                        data.change_type_request = arg;
                    }
                }
                return fetchApi(`member/list`, 'GET', {
                    params: { data },
                });
            },
            actions: {
                more(data) {
                    window.open($_Curl + 'view/' + data.id, '_blank');
                },
            },
        });
    },
    view: async (id) => {
        const form = document.getElementById('form-member-submit');
        const formPassword = document.getElementById('form-password-submit');
        let pristine = new Pristine(formPassword);
        const dtp1Instance =new mds.MdsPersianDateTimePicker(document.getElementById('inp_birthdate'), {
            targetTextSelector: '[data-name="dtp1-date"]',
            targetDateSelector: '[name="birthdate"]',
        });

        if (document.querySelector('[name="birthdate"]').value.length > 5) {
            dtp1Instance.setDate(new Date(document.querySelector('[name="birthdate"]').value));
        } 
        document
        .getElementById('submit_member')
        .addEventListener('click', function (e) {
            var valid = Validator('form-member-submit').validate();
            const formObj = new FormData(form);
            if (valid) {
                const formData = Object.fromEntries(formObj);
                loading(1,e);
                fetchApi(`member/update_profile/${id}`, 'PUT', { body: formData }).then((res) => {
                    if (res.status) {
                        toast('s', LNG_MSG_SUCCESS, LNG_SUCCESS);
                    } else {
                        toast('e', LNG_MSG_CONNECTION_ERROR, LNG_ERROR);
                    }
                }).catch(err=>{
                    toast('e', LNG_MSG_CONNECTION_ERROR, LNG_ERROR);
                }).finally(()=>{
                    loading(0,e);
                });
            }
        }); 
        if (document.querySelector('#submit_designer') != null) { 
            document.getElementById('submit_designer')
            .addEventListener('click', function (e) {
                var valid = Validator('form-designer-submit').validate();
                const formObj = new FormData(document.getElementById('form-designer-submit'));
                const formData = Object.fromEntries(formObj);
                formData.as_company = document.querySelector('#inp_as_company').checked?'yes':'no';
                formData.show = document.querySelector('#inp_show').checked?'no':'yes';
                if (valid) {
                    loading(1,e);
                    fetchApi(`member/update_profile_designer/${id}`, 'PUT', { body: formData }).then((res) => {
                        if (res.status) {
                            toast('s', LNG_MSG_SUCCESS, LNG_SUCCESS);
                        } else {
                            toast('e', LNG_MSG_CONNECTION_ERROR, LNG_ERROR);
                        }
                    }).catch(err=>{
                        toast('e', LNG_MSG_CONNECTION_ERROR, LNG_ERROR);
                    }).finally(()=>{
                        loading(0,e);
                    });
                }
            });
        }
        document
        .getElementById('submit_password')
        .addEventListener('click', function (e) {
            var valid = pristine.validate();
            const formData = new FormData(formPassword);
            formData.append('mid',id);
            const formObj = Object.fromEntries(formData);
            if (formObj.new_password !== formObj.rep_new_password) {
                toast('w','رمز عبورجدید با تکرار آن مطابقت ندارد',LNG_WARNING)
                valid=false;
            }
            if (valid) {
                loading(1,e);
                fetchApi(`member/change_password`, 'PUT', { body: formObj }).then((res) => {
                    if (res.status) {
                        toast('s', LNG_MSG_SUCCESS, LNG_SUCCESS);
                    } else {
                        toast('e', 'اطلاعات وارد شده صحیح نمیباشد', LNG_ERROR);
                    }
                }).catch(err=>{
                    toast('e', LNG_MSG_CONNECTION_ERROR, LNG_ERROR);
                }).finally(()=>{
                    formPassword.reset();
                    loading(0,e);
                });
            }
        });
        if (document.querySelector('#inp_downgrade')) {
            document
            .getElementById('inp_downgrade')
            .addEventListener('change', function (e) {
                swalBs.fire({
                    title: `تنزل نوع کاربری`,
                    html: `<h5>آیا از تنزل نوع کاربری اطمینان دارید؟</h5>`,
                    type: 'error',
                    showCancelButton: true,
                    confirmButtonText: 'ثبت',
                    cancelButtonText: 'انصراف',
                    preConfirm: () => {
                        loading(1,e);
                        fetchApi(`member/downgrade_to_common/${id}`, 'PUT', { body: {mid:id} }).then((res) => {
                            if (res.status) {
                                toast('s', 'درخواست با موفقیت رد شد', LNG_SUCCESS);
                                location.reload();
                            } else {
                                toast('e', LNG_MSG_CONNECTION_ERROR, LNG_ERROR);
                            }
                        }).catch(err=>{
                            toast('e', LNG_MSG_CONNECTION_ERROR, LNG_ERROR);
                        }).finally(()=>{
                            formPassword.reset();
                            loading(0,e);
                        });

                    },
                });
            });
        }
        if (document.querySelector('.change_type_request')) {
            document
            .getElementById('confirm_change_type')
            .addEventListener('click', function (e) {
                loading(1,e);
                fetchApi(`member/confirm_change_type/${id}`, 'PUT', { body: {mid:id} }).then((res) => {
                    if (res.status) {
                        toast('s', LNG_MSG_SUCCESS, LNG_SUCCESS);
                        location.reload()
                    } else {
                        toast('e', 'اطلاعات وارد شده صحیح نمیباشد', LNG_ERROR);
                    }
                }).catch(err=>{
                    toast('e', LNG_MSG_CONNECTION_ERROR, LNG_ERROR);
                }).finally(()=>{
                    formPassword.reset();
                    loading(0,e);
                });
            });
            document
            .getElementById('reject_change_type')
            .addEventListener('click', function (e) {
                swalBs.fire({
                    title: `رد درخواست`,
                    html: `<textarea class="form-control"   name="reject_exp"   required id="inp_reject_exp" rows="3"></textarea>`,
                    type: 'error',
                    showCancelButton: true,
                    confirmButtonText: 'رد',
                    cancelButtonText: 'انصراف',
                    preConfirm: () => {
                        loading(1,e);
                        fetchApi(`member/reject_change_type/${id}`, 'PUT', { body: {mid:id,exp:document.getElementById('inp_reject_exp').value} }).then((res) => {
                            if (res.status) {
                                toast('s', 'درخواست با موفقیت رد شد', LNG_SUCCESS);
                                document.querySelector('.change_type_request').remove()
                            } else {
                                toast('e', LNG_MSG_CONNECTION_ERROR, LNG_ERROR);
                            }
                        }).catch(err=>{
                            toast('e', LNG_MSG_CONNECTION_ERROR, LNG_ERROR);
                        }).finally(()=>{
                            formPassword.reset();
                            loading(0,e);
                        });

                    },
                });
            });
        }
    },
    coopration_requests: ( ) => {
        const editModal = document.getElementById('editModal');
        const modal = new bootstrap.Modal(editModal);
        new DataTable({
            cell: [
                {
                    key: 'full_name',
                    title: 'نام و نام خانوادگی',
                    search:true,
                    sort:true
                },
                {
                    key: 'mobile',
                    title: 'شماره همراه',
                    search:true,
                    sort:true,
                    editable: 'text',
                },
                {
                    key: 'status',
                    type: 'option',
                    sort:true,
                    options: {
                        option_types: {
                            'yes': `<span class="mb-1 badge font-weight-medium bg-light-secondary text-secondary">بررسی شده</span>`,
                            'no': `<span class="mb-1 badge font-weight-medium bg-light-danger text-danger">درخواست جدید</span>`
                        },
                    },
                    title: 'وضعیت'
                },
                {
                    key: 'createAt',
                    title: 'تاریخ',
                    type:'date'
                },
                {
                    key: 'more',
                    title: 'رزومه',
                    type: 'public-btn',
                    options: {
                        class: 'btn-light-primary text-primary more-file',
                        value: '<i class="ti-link"></i>'
                    },
                }
            ],
            api: (data) => {
                return fetchApi(`member/coopration_requests`, 'GET', {
                    params: { data },
                });
            },
            actions: {
                more(data) {
                    modal.show();
                    fetchApi(`member/coopration_request_files`, 'GET', { params: {data: {
                        cid:data.id
                    } }}).then((res) => {
                        if (res.status) {
                            let html   = '';
                            const keys = Object.keys(res.data);
                            for (let index = 0; index < keys.length; index++) {
                                const itm = res.data[keys[index]];
                                html +=`
                                    <div class="col-12">
                                        <a href="${$_url+itm.attachment}" class="justify-content-center w-100 btn  btn-danger d-flex align-items-center m-0">
                                        <i class="ti-link"></i>
                                        دانلود فایل
                                        </a>
                                    </div>
                                `;
                            }
                            document.getElementById('file-rows').innerHTML = html;
                        } else {
                            toast('e', LNG_MSG_CONNECTION_ERROR, LNG_ERROR);
                        }
                        loading(0,e);
                    }).catch(err=>{
                        loading(0,e);
                        toast('e', LNG_MSG_CONNECTION_ERROR, LNG_ERROR);
                    })
                },
            },
        });
    },
    settlement_requests: ( ) => {
        const editModal = document.getElementById('editModal');
        const modal = new bootstrap.Modal(editModal);
        const dataGrid = new DataTable({
            cell: [
                {
                    key: 'full_name',
                    title: 'نام و نام خانوادگی',
                    search:true,
                    sort:true
                },
                {
                    key: 'mobile',
                    title: 'شماره همراه',
                    search:true,
                    sort:true,
                },
                {
                    key: 'bank',
                    title: 'بانک'
                },
                {
                    key: 'sheba',
                    title: '  شبا'
                },
                {
                    key: 'status',
                    type: 'option',
                    sort:true,
                    options: {
                        option_types: {
                            'pend': `<span class="mb-1 badge font-weight-medium bg-light-secondary text-secondary">در حال بررسی</span>`,
                            'reject': `<span class="mb-1 badge font-weight-medium bg-light-danger text-danger">رد شده</span>`,
                            'accept': `<span class="mb-1 badge font-weight-medium bg-light-success text-success">تسویه شد</span>`
                        },
                    },
                    title: 'وضعیت'
                },
                {
                    key: 'createAt',
                    title: 'تاریخ',
                    type:'date'
                },
                {
                    key: 'member_credit',
                    title: 'میزان اعتبار',
                    type: 'public-btn',
                    options: {
                        class: 'btn-light-info text-info ',
                        value: '<i class="ti-money"></i>'
                    },
                },
                {
                    key: 'more',
                    title: 'بیشتر',
                    type: 'public-btn',
                    options: {
                        class: 'btn-light-primary text-primary more-file',
                        value: '<i class="ti-eye"></i>'
                    },
                }
            ],
            api: (data) => {
                return fetchApi(`member/settlement_requests`, 'GET', {
                    params: { data },
                });
            },
            actions: {
                more(data) {
                    document.getElementById('el_desc').innerText = data.desc;
                    document.getElementById('inp_reply').value = data.reply;
                    document.getElementById('inp_id').value = data.id;
                    document.getElementById('inp_mid').value = data.mid;
                    modal.show();
                },
                member_credit(data,e) {
                    console.log('00000000000000000000   ');
                    console.log(data);
                    loading(1,e);
                    fetchApi(`member/member_credit`, 'GET', {
                        params: { data:{mid:data.mid} },
                    }).then((res) => {
                        if (res.status) {
                            submit_treeview.fire({
                                title: `میزان اعتبار حال حاضر`,
                                html: `<h1 class="text-success fw-bold">${res.data.unsettled}</h1>`,
                                type: 'success',
                                confirmButtonText: 'بستن',

                            });
                        } else {
                            toast('e', LNG_MSG_CONNECTION_ERROR, LNG_ERROR);
                        }
                        loading(0,e);
                    }).catch(err=>{
                        console.log(err);
                        loading(0,e);
                        toast('e', LNG_MSG_CONNECTION_ERROR, LNG_ERROR);
                    })
                },
            },
        });
        const form = document.getElementById('form-reply-submit');
        new mds.MdsPersianDateTimePicker(document.getElementById('table-date-to-g'), {
			targetTextSelector: '[data-name="table_to-date"]',
			targetDateSelector: '[name="table-date-to"]',
		});
		new mds.MdsPersianDateTimePicker(document.getElementById('table-date-from-g'), {
			targetTextSelector: '[data-name="table_from-date"]',
			targetDateSelector: '[name="table-date-from"]',
		});
        document.getElementById('btn-status-reject').addEventListener('click',function (e) {
            e.preventDefault();
            changeStatus('reject',e)
        }) ;
        document.getElementById('btn-status-accept').addEventListener('click',function (e) {
            e.preventDefault();
            changeStatus('accept',e)
        }) ;
        function changeStatus(status,e) {
            var valid = Validator('form-reply-submit').validate();
            if (valid) {
                const formData = new FormData(form);
                formData.append('status',status);
                const formDataEntries = Object.fromEntries(formData);
                let url = `member/settlement_requests_reply/${formDataEntries.id}`;
                let method = 'PUT';
                loading(1,e);
                fetchApi(url, method, { body: formDataEntries }).then((res) => {
                    if (res.status) {
                        toast('s', LNG_MSG_SUCCESS, LNG_SUCCESS);
                        modal.hide();
                        dataGrid.rerender();
                    } else {
                        toast('e', LNG_MSG_CONNECTION_ERROR, LNG_ERROR);
                    }
                    loading(0,e);
                }).catch(err=>{
                    console.log(err);
                    loading(0,e);
                    toast('e', LNG_MSG_CONNECTION_ERROR, LNG_ERROR);
                })
            }
        }
    },
    add_expertise: async (id) => {
        id = id || 0;
        const submitBtn = document.getElementById('submit_page');
        const slug = document.getElementById('inp_slug');
        const form = document.getElementById('form-pages-submit');
        submitBtn.addEventListener('click',function (e) {
            e.preventDefault();
            const editorContent =  tinymce.activeEditor.getContent();
            tinymce.DOM.addClass('.mce-container-body', 'myclass');
            var valid = Validator('form-pages-submit').validate();

            if (editorContent.length === 0) {
                editorValidation('form-group_desc');
                valid = false;
            }
            if (valid) {
                const formData = new FormData(form);
                formData.append('desc',editorContent)//en
                const formDataEntries = Object.fromEntries(formData);
                let method = 'POST';
                let url = `member/add_expertise`;
                if (formDataEntries.id && parseInt(formDataEntries.id) > 0) {
                    method = 'PUT';
                    url = `member/update_expertise/${formDataEntries.id}`; 

                } else {
                    delete formDataEntries.id;
                }
                loading(1,e);
                fetchApi(url, method, { body: formDataEntries }).then((res) => {
                    // form.reset();
                    if (res.status) {
                        toast('s', LNG_MSG_SUCCESS, LNG_SUCCESS);
                        window.location.href = $_Burl+'member/expertise'
                    } else {
                        toast('e', LNG_MSG_CONNECTION_ERROR, LNG_ERROR);
                    }
                    loading(0,e)
                }).catch(err=>{
                    loading(0,e)
                    toast('e', LNG_MSG_CONNECTION_ERROR, LNG_ERROR);
                })
            }

        })

       
        
        slugValidation('pages','submit_page');

    },
    expertise: async() => {
        new DataTable({
            filter:false, 
            cell: [
                {
                    key: 'title',
                    title: 'عنوان',
                    editable: 'text',
                    type:'link',
                    options: { 
                        url:"designers/|slug"
                    },
                },
                {
                    key: 'createAt',
                    title: 'تاریخ',
                    type:'date'
                },
                {
                    key: 'edit_pages',
                    title: 'ویرایش',
                    type: 'public-btn',
                    options: {
                        class: 'btn-light-info text-info edit-pages',
                        value: '<i class="ti-pencil"></i>',
                    },
                },
                {
                    key: 'del',
                    title: 'حذف',
                    type: 'public-btn',
                    attr: [
                        'data-resource="member/del_expertise"'
                    ],
                    options: {
                        class: 'btn-light-danger text-danger del-role',
                        value: '<i class="far fa-trash-alt"></i>',
                    },
                },
            ],
            api: (data) => {
                return fetchApi(`member/expertise`, 'GET', {
                    params: { data },
                });
            },
            actions: {
                edit_pages(data) {
                    window.open($_Curl+'add_expertise/'+data.id, '_blank');
                }
            },
        });
    },
};
