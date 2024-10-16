import { fetchApi } from '../api';
import { UploadForm } from '../components/UploadForm';
import { DataTable } from '../components/_DataTable';
import {
    LNG_ERROR,
    LNG_MSG_CONNECTION_ERROR,
    LNG_MSG_SUCCESS,
    LNG_SUCCESS,
} from '../util/consts';
import {   delAlert, loading,  slugValidation,  toast } from '../util/util';
import { editorValidation, Validator } from '../util/Validator';

const formats = '.jpeg , .jpg , .png , .gif';

export const PagesScreen = {
    render: async() => {
        new DataTable({
            filter:false, 
            cell: [
                {
                    key: 'title',
                    title: 'عنوان',
                    editable: 'text'
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
                        'data-resource="pages"'
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
                    window.open($_Curl+'add/'+data.id, '_blank');
                }
            },
        });
    },
    add: async (id) => {
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
                let url = `pages`;
                if (formDataEntries.id && parseInt(formDataEntries.id) > 0) {
                    method = 'PUT';
                    url = `pages/${formDataEntries.id}`;
                } else {
                    delete formDataEntries.id;
                }
                loading(1,e);
                fetchApi(url, method, { body: formDataEntries }).then((res) => {
                    // form.reset();
                    if (res.status) {
                        toast('s', LNG_MSG_SUCCESS, LNG_SUCCESS);
                        window.location.href = $_Burl+'pages/'
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
    member_messages: () => {
        const moreModal = document.getElementById('moreModal');
        const modal     = new bootstrap.Modal(moreModal);
        const modalEls  = document.getElementsByClassName('message_info');

        new DataTable({
            cell: [
                {
                    key: 'full_name',
                    title: 'نام و نام خانوادگی',
                    editable: 'text'
                },
                {
                    key: 'subject',
                    title: 'موضوع',
                },
                {
                    key: 'email',
                    title: 'ایمیل',
                },
                {
                    key: 'read',
                    type: 'option',
                    title: 'پیام جدید',
                    options: {
                        option_types: {
                            'no': `<span class="badge-dot new"><i class="mdi mdi-email-open-outline"></i></span>`,
                            'yes': `<span class="badge-dot"><i class="mdi mdi-email-outline"></i></span>`,
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
                        class: 'btn-light-primary text-primary more-detail',
                        value: '<i class="ti-eye"></i>',
                    },
                }
            ],
            filter:false,
            api: (data) => {
                return fetchApi(`pages/member_messages`, 'GET', {
                    params: { data },
                });
            },
            actions: {
                more(data) {
                    for (let index = 0; index < modalEls.length; index++) {
                        const element = modalEls[index];
                        const id = element.getAttribute('data-id');
                        element.innerText = data[id];
                    }
                    modal.show();
                    fetchApi('pages/member_messages_read/' + data.id, 'PUT', { body: {} }).then((res) => {
                    })
                },
            },
        });

        document.getElementById('confirm_request').addEventListener('click',function (e) {
            e.preventDefault();
            const id = document.getElementById('pub_id').value;
            loading(1,e);
            fetchApi('pages/confirm_agency_request/' + id, 'PUT', { body: {} }).then((res) => {
                if (res.status) {
                    toast('s', LNG_MSG_SUCCESS, LNG_SUCCESS);
                    modal.hide();
                } else {
                    toast('e', LNG_MSG_CONNECTION_ERROR, LNG_ERROR);
                }
                loading(0,e);
            }).catch(err=>{
                loading(0,e);
                toast('e', LNG_MSG_CONNECTION_ERROR, LNG_ERROR);
            })
        });

        moreModal.addEventListener('hide.bs.modal', function (event) {
            document.getElementById('confirm_request').classList.add('d-none');
        });
    },
    search_page:()=>{
        otherPages(4);
    },
    image:()=>{
        otherPages(6);
    },
    video:()=>{
        otherPages(7);
    },
    font:()=>{
        otherPages(10);
    },
    vector:()=>{
        otherPages(9);
    },
    mockup:()=>{
        otherPages(11);
    },
    stock:()=>{
        otherPages(8);
    }, 
    designers_page:()=>{
        otherPages(5);
    },
    about_us:()=>{
        otherPages(1);
    },
    plan:()=>{
        otherPages(3);
    },
    policy:()=>{
        otherPages(2);
        faq_policy_action ()
    },
    faq: () => {
        faq_policy_action()
        otherPages(21);

    },
    add_faq: async (id) => {
        faq_terms('faq',id)
    },
    add_policy: async (id) => {
        faq_terms('policy',id)
    },
    team_members: async () => {
        new DataTable({
            filter:false,
            cell: [
                {
                    key: 'pic',
                    title: 'تصویر',
                    type: 'file',
                    filetype: 'image',
                },
                {
                    key: 'fullname',
                    title: 'نام و نام خ',
                    editable: 'text',
                },
                {
                    key: 'expert',
                    title: 'تخصص'
                },
                {
                    key: 'edit_team_members',
                    title: 'ویرایش',
                    type: 'public-btn',
                    options: {
                        class: 'btn-light-info text-info edit-team_members',
                        value: '<i class="ti-pencil"></i>',
                    },
                },
                {
                    key: 'del',
                    title: 'حذف',
                    type: 'public-btn',
                    attr: ['data-resource="pages"','data-ext-url="delete_team_members"'],
                    options: {
                        class: 'btn-light-danger text-danger del-team_members',
                        value: '<i class="far fa-trash-alt"></i>',
                    },
                },
            ],
            api: (data) => {
                return fetchApi(`pages/team_members`, 'GET', {
                    params: { data },
                });
            },
            actions: {
                edit_team_members(data) {
                    window.open($_Curl + 'add_team_members/' + data.id, '_blank');
                }
            },
        });
    },
    add_team_members: async (id) => {
        id = parseInt(id);
        if (id > 0) {
            document.getElementById('inp_id').value = id;
        } else {
            id = 0;
        }
        const team_membersFile = new UploadForm({
            formats: '.jpg,.png',
            title: 'تصویر  ',
            max: 1,
            files: {},
            filesType: 'image',
            btnTitle: 'افزودن تصویر',
        });
        const submitBtn = document.getElementById('submit_team_members');
        const form = document.getElementById('form-team_members-submit');

        submitBtn.addEventListener('click', function (e) {
            e.preventDefault();
            const images = team_membersFile.getFiles();
            const imagesKeys = Object.keys(images);
            var valid = Validator('form-team_members-submit').validate();
            if (imagesKeys.length <= 0) {
                toast('e', 'لطفا تصویر را انتخاب کنید', LNG_ERROR);
                valid = false;
            }
            if (valid) {
                const formData = new FormData(form);
                formData.append('pic', images[imagesKeys[0]].file);
                const formDataEntries = Object.fromEntries(formData);
                let method = 'POST';
                let url = `pages/add_team_members`;
                loading(1, e);
                if (formDataEntries.id && parseInt(formDataEntries.id) > 0) {
                    method = 'PUT';
                    url = `pages/update_team_members/${formDataEntries.id}`;
                } else delete formDataEntries.id;
                fetchApi(url, method, { body: formDataEntries })
                    .then((res) => {
                        form.reset();
                        if (res.status) {
                            toast('s', LNG_MSG_SUCCESS, LNG_SUCCESS);
                            window.location.href = $_Burl + 'pages/team_members';
                        } else {
                            toast('e', LNG_MSG_CONNECTION_ERROR, LNG_ERROR);
                        }
                        loading(0, e);
                    })
                    .catch((err) => {
                        loading(0, e);
                        toast('e', LNG_MSG_CONNECTION_ERROR, LNG_ERROR);
                    });
            }
        });

    },
};
async function faq_policy_action (){
    const delBtns = document.querySelectorAll('.faq-remove')
    if (delBtns.length > 0) {
        for (let index = 0; index < delBtns.length; index++) {
            const element = delBtns[index];
            element.addEventListener('click',async function (e) {
                document.getElementById('pub_id').value = this.dataset.id;
                const res =await delAlert('pages','حذف ایتم','del_faq');
                if (res && !res.err && document.querySelector('#faq_'+ this.dataset.id)) {
                    document.getElementById('faq_'+ this.dataset.id).remove();
                }
            })
        }
    }
}
async function  faq_terms(type,id) {
    id = parseInt(id)
        if (id > 0) {
            document.getElementById('inp_id').value = id;
        }else{
            id = 0;
        }
        const submitBtn = document.getElementById('submit_faq');
        const form = document.getElementById('form-faq-submit');
        if (id > 0 ) {
            loading(1);
            const res = await fetchApi(`pages/edit_faq`, 'GET', {
                params: { id },
            });
            const faq = res.data;
            document.getElementById('inp_title').value = faq.title;
            tinymce.get('inp_desc').setContent(faq.desc);
            loading(0);
        }

        submitBtn.addEventListener('click',function (e) {
            e.preventDefault();
            const editorContent =  tinymce.get('inp_desc').getContent();
            tinymce.DOM.addClass('.mce-container-body', 'myclass');
            var valid = Validator('form-faq-submit').validate();

            if (editorContent.length === 0) {
                editorValidation('form-group_desc');
                valid = false;
            }

            if (valid) {
                const formData = new FormData(form);
                formData.append('type',type);
                formData.append('desc',editorContent);
                const formDataEntries = Object.fromEntries(formData);
                let method = 'POST';
                let url = `pages/add_faq`;
                if (formDataEntries.id && parseInt(formDataEntries.id) > 0) {
                    method = 'PUT';
                    url = `pages/update_faq/${formDataEntries.id}`;
                } else {
                    delete formDataEntries.id;
                }
                loading(1,e);
                fetchApi(url, method, { body: formDataEntries }).then((res) => {
                    if (res.status) {
                        toast('s', LNG_MSG_SUCCESS, LNG_SUCCESS);
                        window.location.href = $_Burl+'pages/'+type;
                    } else {
                        toast('e', LNG_MSG_CONNECTION_ERROR, LNG_ERROR);
                    }
                    loading(0,e);
                }).catch(err=>{
                    loading(0,e);
                    toast('e', LNG_MSG_CONNECTION_ERROR, LNG_ERROR);
                })
            }

        })
}
function otherPages(type) { 
    let uploadCover; 
    const form = document.getElementById('form-about-us-submit');
    if (type==1) {

        uploadCover = new UploadForm({
            cls:'cover-file',
            formats,
            title:'تصویر کاور',
            max:1,
            files:[{
                id:1,
                file:document.getElementById('cover-file').value
            }],
            filesType:'image',
            btnTitle:'افزودن تصویر'
        });
    }

    document.getElementById('submit_about-us').addEventListener('click',function (e) {
        e.preventDefault();
        const editorContent =  tinymce.get('inp_desc').getContent();
        var valid = Validator('form-about-us-submit').validate();
        let cover= '';
        if (type==1) {
            const _file = uploadCover.getFiles();
            const _filesKeys = Object.keys(_file);
            if (_filesKeys.length <= 0) {
                toast('e','لطفا فایل را انتخاب کنید',LNG_ERROR);
                return
            }
            cover = _file[_filesKeys[0]].file;
        }
        if (editorContent.length === 0) {
            editorValidation('form-group_desc');
            valid = false;
        }
        if (valid) {
            const formData = new FormData(form);
            if (type==1) {
                formData.append('cover',cover);
            }else{
                formData.append('cover','');
            }
            formData.append('type',type);
            formData.append('desc',editorContent);
            const formDataEntries = Object.fromEntries(formData);
            loading(1,e);
            fetchApi('pages/update_about_us', 'PUT', { body: formDataEntries }).then((res) => {
                if (res.status) {
                    if (type>11) {
                        form.reset();
                    }
                    toast('s', LNG_MSG_SUCCESS, LNG_SUCCESS);
                    location.reload();
                } else {
                    location.reload();
                    // toast('e', LNG_MSG_CONNECTION_ERROR, LNG_ERROR);
                }
                loading(0,e);
            }).catch(err=>{
                loading(0,e);
                toast('e', LNG_MSG_CONNECTION_ERROR, LNG_ERROR);
            })
        }

    });
}