import { fetchApi } from '../api';
import { UploadForm } from '../components/UploadForm';
import { DataTable } from '../components/_DataTable';
import {
    LNG_ERROR,
    LNG_MSG_CONNECTION_ERROR,
    LNG_MSG_SUCCESS,
    LNG_SUCCESS,
    LNG_WARNING,
} from '../util/consts';
import { loading, openFM, toast } from '../util/util';
import { Validator } from '../util/Validator';
const form = document.getElementById('form-user-submit');
export const UserScreen = {
    render: () => {
        const editModal = document.getElementById('editModal');
        const fromInps = form.querySelectorAll('.form-control');
        const modal = new bootstrap.Modal(editModal);
        editModal.addEventListener('hide.bs.modal', function (e) {
            uploadImage.reset()
            document.getElementById('form-user-submit').reset();
        });
        const uploadImage = new UploadForm({
            cls: 'pic-file',
            formats: 'jpg',
            title: 'تصویر آواتار',
            max: 1,
            filesType: 'image',
            btnTitle: 'افزودن تصویر'
        });

        const gridData = new DataTable({
            cell: [
                {
                    key: 'img',
                    title: 'فایل',
                    type: 'file',
                    filetype: 'image',
                },
                {
                    key: 'full_name',
                    title: 'نام و نام خانوادگی',
                    search: true,
                    sort: true,
                },
                {
                    key: 'email',
                    editable: 'text',
                    title: 'ایمیل',
                    search: true,
                    sort: true,
                },
                {
                    key: 'role',
                    title: 'نقش',
                    sort: true,
                    editable: 'text'
                },
                {
                    key: 'expertise',
                    title: 'تخصص',
                    editable: 'text'
                },
                {
                    key: 'edit_user',
                    title: 'ویرایش',
                    type: 'public-btn',
                    options: {
                        class: 'btn-light-info text-info edit-user',
                        value: '<i class="far fa-edit"></i>'
                    },
                },
                {
                    key: 'del',
                    title: 'حذف',
                    type: 'public-btn',
                    attr: [
                        'data-resource="user"'
                    ],
                    options: {
                        class: 'btn-light-danger text-danger del-user',
                        value: '<i class="far fa-trash-alt"></i>',
                    },
                },
            ],
            api: (data) => {
                return fetchApi(`user/list`, 'GET', {
                    params: { data },
                });
            },
            actions: {
                edit_user(data) {
                    for (let index = 0; index < fromInps.length; index++) {
                        const element = fromInps[index];
                        if (element.type === 'checkbox') {
                            if (element.id === 'inp_as_team_member') {
                                element.checked = (data.as_team_member == 'yes') ? true : false
                            } else {
                                element.checked = (data.status == 'active') ? true : false
                            }
                        } else {
                            element.value = data[element.name];
                        }
                    }
                    uploadImage.addFiles([{ 'file': data.img, id: data.id }])
                    modal.show();
                },
            },
        });
        document
            .getElementById('submit_user')
            .addEventListener('click', function (e) {
                var valid = Validator('form-user-submit').validate();
                const formObj = new FormData(form);
                const images = uploadImage.getFiles();
                const imageKeys = Object.keys(images);
                if (imageKeys.length <= 0) {
                    toast('e', 'لطفا تصویر را انتخاب کنید', LNG_ERROR);
                    valid = false;
                }
                if (valid) {
                    formObj.append('pic', images[imageKeys[0]].file)//en
                    formObj.append('status', document.getElementById('inp_status').checked ? 'active' : 'deactive');//en
                    formObj.append('as_team_member', document.getElementById('inp_as_team_member').checked ? 'yes' : 'no');//en
                    const formData = Object.fromEntries(formObj);
                    let method = 'POST';
                    let url = `user`;
                    if (formData.id && parseInt(formData.id) > 0) {
                        method = 'PUT';
                        url = `user/${formData.id}`;
                    } else {
                        delete formData.id;
                    }
                    loading(1, e)
                    fetchApi(url, method, { body: formData }).then((res) => {
                        modal.hide();
                        resetFF();
                        form.reset();
                        if (res.status) {
                            toast('s', LNG_MSG_SUCCESS, LNG_SUCCESS);
                            gridData.fetchHandler();
                        } else {
                            toast('e', LNG_MSG_CONNECTION_ERROR, LNG_ERROR);
                        }
                        loading(0, e)
                    }).catch(err => {
                        loading(0, e)
                        toast('e', LNG_MSG_CONNECTION_ERROR, LNG_ERROR);
                    })
                }
            });
    },
    log_in_out: () => {
        const gridData = new DataTable({
            cell: [
                {
                    key: 'img',
                    title: 'کاربر',
                    type: 'file',
                    filetype: 'image',
                },
                {
                    key: 'full_name',
                    title: 'نام و نام خانوادگی',
                },
                {
                    key: 'ip',
                    title: 'IP Address',
                },
                {
                    key: 'agent',
                    title: 'Agent',
                },
                {
                    key: 'createAt',
                    title: 'تاریخ ورود'
                },
            ],
            api: (data) => {
                return fetchApi(`user/log_in_out`, 'GET', {
                    params: { data },
                });
            },
            actions: {
                edit_user(data) {
                    for (let index = 0; index < fromInps.length; index++) {
                        const element = fromInps[index];
                        if (element.type === 'checkbox') {
                            element.checked = (data.status == '1') ? true : false
                        } else {
                            element.value = data[element.name];
                        }
                    }
                    addDefaultFF(data.img, data.id);
                    modal.show();
                },
            },
        });
        document
            .getElementById('submit_user')
            .addEventListener('click', function (e) {
                var valid = Validator('form-user-submit').validate();
                const formObj = new FormData(form);
                const images = getFFItems();
                const imageKeys = Object.keys(images);
                if (imageKeys.length <= 0) {
                    toast('e', 'لطفا تصویر را انتخاب کنید', LNG_ERROR);
                    valid = false;
                }
                if (valid) {
                    formObj.append('pic', images[imageKeys[0]])//en
                    formObj.append('status', document.getElementById('inp_status').checked ? 1 : 0)//en
                    const formData = Object.fromEntries(formObj);
                    let method = 'POST';
                    let url = `user`;
                    if (formData.id && parseInt(formData.id) > 0) {
                        method = 'PUT';
                        url = `user/${formData.id}`;
                    } else {
                        delete formData.id;
                    }
                    loading(1, e)
                    fetchApi(url, method, { body: formData }).then((res) => {
                        modal.hide();
                        resetFF();
                        form.reset();
                        if (res.status) {
                            toast('s', LNG_MSG_SUCCESS, LNG_SUCCESS);
                            gridData.fetchHandler();
                        } else {
                            toast('e', LNG_MSG_CONNECTION_ERROR, LNG_ERROR);
                        }
                        loading(0, e)
                    }).catch(err => {
                        loading(0, e)
                        toast('e', LNG_MSG_CONNECTION_ERROR, LNG_ERROR);
                    })
                }
            });
    },
    profile: () => {
        const formPassword = document.getElementById('form-password-submit');
        let pristine = new Pristine(formPassword);
        document
            .getElementById('submit_user')
            .addEventListener('click', function (e) {
                var valid = Validator('form-user-submit').validate();
                const formObj = new FormData(form);
                const images = getFFItems();
                const imageKeys = Object.keys(images);
                if (valid) {
                    const pic = (imageKeys.length) ? images[imageKeys[0]] : '';
                    formObj.append('pic', pic);
                    const formData = Object.fromEntries(formObj);
                    loading(1, e);
                    fetchApi(`user/update_prfile`, 'PUT', { body: formData }).then((res) => {
                        if (res.status) {
                            resetFF();
                            document.getElementById('user_full_name').innerText = formData.name + ' ' + formData.family;
                            document.getElementById('user_address').innerText = formData.address;
                            if (pic !== '') {
                                document.getElementById('user_pic').src = $_url + formData.pic;
                            }
                            toast('s', LNG_MSG_SUCCESS, LNG_SUCCESS);
                        } else {
                            toast('e', LNG_MSG_CONNECTION_ERROR, LNG_ERROR);
                        }
                    }).catch(err => {
                        toast('e', LNG_MSG_CONNECTION_ERROR, LNG_ERROR);
                    }).finally(() => {
                        loading(0, e);
                    });
                }
            });
        document
            .getElementById('submit_password')
            .addEventListener('click', function (e) {
                var valid = pristine.validate();
                const formData = Object.fromEntries(new FormData(formPassword));
                if (formData.new_password !== formData.rep_new_password) {
                    toast('w', 'رمز عبورجدید با تکرار آن مطابقت ندارد', LNG_WARNING)
                    valid = false;
                }
                if (valid) {
                    loading(1, e);
                    fetchApi(`user/change_password`, 'PUT', { body: formData }).then((res) => {
                        if (res.status) {
                            toast('s', LNG_MSG_SUCCESS, LNG_SUCCESS);
                        } else {
                            toast('e', 'اطلاعات وارد شده صحیح نمیباشد', LNG_ERROR);
                        }
                    }).catch(err => {
                        toast('e', LNG_MSG_CONNECTION_ERROR, LNG_ERROR);
                    }).finally(() => {
                        formPassword.reset();
                        loading(0, e);
                    });
                }
            });
    },
    after_render: () => {
        document.querySelector('.add_file').addEventListener('click', function (e) {
            e.preventDefault();
            let fileItems = document.querySelector('.upload-file__item');
            if (fileItems === null || fileItems.length <= 1) {
                openFM('image', 'single', 4);
            } else {
                toast('w', 'تعداد مجاز فایل انتخابی 1 میباشد', LNG_WARNING);
            }
        });
    }
};
