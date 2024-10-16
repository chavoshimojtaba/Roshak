import { fetchApi } from '../api';
import { DataTable } from '../components/_DataTable';
import {
    LNG_ERROR,
    LNG_MSG_CONNECTION_ERROR,
    LNG_MSG_SUCCESS,
    LNG_SUCCESS,
} from '../util/consts';
import { loading, redirect, slugValidation, toast } from '../util/util';
import { editorValidation, Validator } from '../util/Validator';
export const TagScreen = {
    render: () => { 
        new DataTable({
            cell: [
                {
                    key: 'title',
                    title: 'عنوان',
                    type:'link',
                    options: { 
                        url:"explorer/|slug"
                    },
                    search:true,
                    sort:true
                }, 
                {
                    key: 'meta',
                    title: 'Meta(Desc)',
                },
                {
                    key: 'createAt',
                    title: 'تاریخ',
                    sort:true,
                    type:'date'
                },
                {
                    key: 'edit_tag',
                    title: 'ویرایش',
                    type: 'public-btn',
                    options: {
                        class: 'btn-light-info text-info edit-tag',
                        value: '<i class="far fa-edit"></i>',
                    },
                },
                {
                    key: 'del',
                    title: 'حذف',
                    type: 'public-btn',
                    attr: [
                        'data-resource="tag"',
                        `data-ext-url="delete"`,
                    ],
                    options: {
                        class: 'btn-light-danger text-danger del-tag',
                        value: '<i class="far fa-trash-alt"></i>',
                    },
                },
            ],
            api: (data) => { 
                return fetchApi(`tag/list`, 'GET', {
                    params: { data },
                });
            },
            actions: {
                edit_tag(data) {
                    redirect('tag/add/'+data.id,true);
                },
            },
        });
    },
    add: () => {  
        const form = document.getElementById('form-tag-submit'); 
        document
            .getElementById('submit_tag')
            .addEventListener('click', function (e) {
                const editorContent =  tinymce.get('inp_desc').getContent();
                var valid = Validator('form-tag-submit').validate(); 
                if (editorContent.length === 0) {
                    editorValidation('form-group_desc');
                    valid = false;
                }
                
                if (valid) {
                    const formData = new FormData(form); 
                    formData.append('desc',tinymce.get('inp_desc').getContent());
                    const formDataEntries = Object.fromEntries(formData);
                    let method  = 'POST';
                    let url     = `tag`;
                    if (formDataEntries.id && parseInt(formDataEntries.id) > 0) {
                        method = 'PUT';
                        url = `tag/${formDataEntries.id}`;
                    } else {
                        delete formDataEntries.id;
                    }
                    loading(1,e)
                    fetchApi(url, method, { body: formDataEntries }).then((res) => {
                        // modal.hide();
                        form.reset();
                        if (res.status) {
                            toast('s', LNG_MSG_SUCCESS, LNG_SUCCESS);
                            if (method == 'PUT') {
                                window.location.href = $_Curl;
                            }
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
            slugValidation('tags','submit_tag');

         

    },
};
