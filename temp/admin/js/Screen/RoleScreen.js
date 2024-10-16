import { fetchApi } from '../api';
import { DataTable } from '../components/_DataTable';
import {
    LNG_ERROR,
    LNG_MSG_CONNECTION_ERROR,
    LNG_MSG_SUCCESS,
    LNG_SUCCESS,
} from '../util/consts';
import { loading, toast } from '../util/util';
import { Validator } from '../util/Validator';
export const RoleScreen = {
    render: () => {
        const editModal = document.getElementById('editModal');
        const modal = new bootstrap.Modal(editModal);
        const permModal = document.getElementById('permModal');
        const formPerm = document.getElementById('form-permissions-submit');
        const form = document.getElementById('form-role-submit');
        const inp_role_id = document.getElementById("inp_role_id")
        const resource_inp = document.getElementsByClassName("resource_inp")
        const checkbox_selectors = {};
        const permModalInstance = new bootstrap.Modal(permModal); 
        const gridData = new DataTable({
            cell: [
                {
                    key: 'name',
                    title: 'نقش',
                    editable: 'text',
                }, 
                {
                    key: 'desc',
                    editable: 'text',
                    title: 'توضیحات',
                },
                {
                    key: 'createAt',
                    title: 'تاریخ',
                    type:'date'
                },
                {
                    key: 'permission',
                    title: 'مجوز ها',
                    type: 'btn',
                    options: {
                        class: 'success role-permissions d-flex align-items-center',
                        value: '<i class="ti-settings"></i>&nbsp;مجوز ها',
                    },
                },
                {
                    key: 'edit',
                    title: 'ویرایش',
                    type: 'public-btn',
                    options: {
                        class: 'btn-light-info text-info edit-role',
                        value: '<i class="far fa-edit"></i>' 
                    },
                },
                {
                    key: 'del',
                    title: 'حذف',
                    type: 'public-btn',
                    attr: [
                        'data-resource="role"'
                    ],
                    options: {
                        class: 'btn-light-danger text-danger del-role',
                        value: '<i class="far fa-trash-alt"></i>',
                    },
                },
            ], 
            filter:false,
            api: (data) => { 
                return fetchApi(`role/list`, 'GET', {
                    params: { data },
                });
            },
            actions: {
                permission(data,e) { 
                    formPerm.reset();  
                    loading(1,e)
                    fetchApi(`role/permission_list/${data.id}`, 'GET', {
                        params: {},
                    }).then((res) => {
                        loading(0,e); 
                        if (res.status) {
                            Object.keys(res.data).map((resource)=>{ 
                                const data = res.data[resource]; 
                                const id = `inp_perm_${data.reid}_${data.perm}`
                                if (checkbox_selectors[id]) {
                                    checkbox_selectors[id].checked = true;
                                }
                            })
                        } 
                        permModalInstance.show();
                        permModal.querySelector('.modal-title').innerText =`${data.name} : مدیریت مجوز ها`
                        inp_role_id.value = data.id
                    }).catch(err=>{ 
                        toast('e', LNG_MSG_CONNECTION_ERROR, LNG_ERROR);
                    })
                },
                edit(data) {
                    document.querySelector('#inp_id').value = data.id;
                    document.querySelector('#inp_desc').value = data.desc;
                    document.querySelector('#inp_name').value = data.name;
                    console.log(data);
                    modal.show();
                },
            },
        });
        document
            .getElementById('submit_role')
            .addEventListener('click', function (e) {
                var valid = Validator('form-role-submit').validate();
                if (valid) {
                    const formData = Object.fromEntries(new FormData(form));
                    let method = 'POST';
                    let url = `role`;
                    if (formData.id && parseInt(formData.id) > 0) {
                        method = 'PUT';
                        url = `role/${formData.id}`;
                    } else {
                        delete formData.id;
                    }
                    loading(1,e)
                    fetchApi(url, method, { body: formData }).then((res) => {
                        modal.hide();
                        form.reset();
                        if (res.status) {
                            toast('s', LNG_MSG_SUCCESS, LNG_SUCCESS);
                            gridData.fetchHandler();
                        } else {
                            toast('e', LNG_MSG_CONNECTION_ERROR, LNG_ERROR);
                        }
                        loading(0,e) 
                    }).catch(err=>{ 
                        loading(0,e) 
                        toast('e', LNG_MSG_CONNECTION_ERROR, LNG_ERROR);
                    })
                }
            });
        document
            .getElementById('submit_permissons')
            .addEventListener('click', function (e) {
                const rid = inp_role_id.value;
                const rows = {
                    data:{},
                    rid
                };
                for (let index = 0; index < resource_inp.length; index++) {
                    const element = resource_inp[index];
                    if (!element.checked) {
                        continue;
                    }
                    rows.data[index] = {
                        rid,
                        reid:element.name,
                        perm:element.value
                    } 
                } 
                fetchApi('role/permissions', 'POST', { body: rows }).then((res) => {
                    permModalInstance.hide();
                    form.reset();
                    if (res.status) {
                        toast('s', LNG_MSG_SUCCESS, LNG_SUCCESS);
                        gridData.fetchHandler();
                    } else {
                        toast('e', LNG_MSG_CONNECTION_ERROR, LNG_ERROR);
                    }
                }).catch(err=>{ 
                    toast('e', LNG_MSG_CONNECTION_ERROR, LNG_ERROR);
                })
            });
        editModal.addEventListener('hide.bs.modal', function (e) {
            form.reset(); 
        });
         
        for (let index = 0; index < resource_inp.length; index++) {
            const element = resource_inp[index];
            checkbox_selectors[element.id] = element;
            element.addEventListener('change',function (e) {
                e.preventDefault();
                if (this.value == 2 && this.checked === false) {
                    checkbox_selectors[`inp_perm_${this.name}_1`].checked=false;
                    checkbox_selectors[`inp_perm_${this.name}_2`].checked=false;
                    checkbox_selectors[`inp_perm_${this.name}_3`].checked=false;
                    checkbox_selectors[`inp_perm_${this.name}_4`].checked=false;
                }else if (this.value !== 2 && this.checked == true) {
                    checkbox_selectors[`inp_perm_${this.name}_2`].checked=true;
                }
            })
        } 
    },
};
