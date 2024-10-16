import { UploadForm } from '../components/UploadForm';
import { CommentWidget } from '../components/_CommentWidget';
import { DataTable } from '../components/_DataTable';
import { MultiSelect } from '../components/_MultiSelect';
import { SelectMenuInput } from '../components/_SelectMenuInput';
import Treeview from '../components/_Treeview';
import { LNG_ERROR, LNG_MSG_CONNECTION_ERROR, LNG_MSG_SUCCESS, LNG_SUCCESS } from '../util/consts';
import { loading, redirect, slugValidation, toast } from '../util/util';
import { Validator, editorValidation } from '../util/Validator';
export const ContentScreen = {
    render: async (id) => {
       
    }, 
    loc() {
        document.addEventListener('focusin', (e) => {
            if ($(e.target).closest(".mce-window") !== null) {
               e.stopImmediatePropagation();
            }
        }); 
        slugValidation('location_content','submit_treeview'); 
        Treeview({
            type: 'content',  
            api: {
                'add': 'content/add_loc',
                'list': 'content/list_loc',
                'delete': 'content/delete_loc',
                'update': 'content/update_loc',
                'order': 'content/order_loc',
            },
            onBtnAction: function (data,type) {
                document.querySelector('.tags-container').classList.add('d-none');
                if (type == 'edit') {
                    document.querySelector('#inp_title').value = data.title;
                    document.querySelector('#inp_path').value = data.path;
                    document.querySelector('#inp_slug').value = data.slug; 
                    document.querySelector('#inp_seo_title').value = data.seo_title;
                    document.querySelector('#inp_tags').value = data.tags;
                    document.querySelector('#inp_meta').value = data.meta;
                    document.querySelector('#inp_short_desc').value = data.short_desc;
                    document.querySelector('#inp_id').value = data.id;
                    document.querySelector('#inp_pid').value = data.pid; 
                    tinymce.get('inp_desc').setContent(data.desc); 
                }
                if (data.type == 'area') {
                    document.querySelector('.tags-container').classList.remove('d-none');
                } 
            },
            onModalHide: function () {   
            },
            onSubmit: function (data) {  
                data.type = 'city';
                console.log(2222);
                return data;
            },
        }, 3);

    }, 
    category() {
        document.addEventListener('focusin', (e) => {
            if ($(e.target).closest(".mce-window") !== null) {
               e.stopImmediatePropagation();
            }
        });
        const uploadImage = new UploadForm({
            cls: 'icon-file',
            formats: 'jpg',
            title: 'آیکن',
            max: 1,
            filesType: 'image',
            btnTitle: 'افزودن تصویر'
        });
        slugValidation('category','submit_treeview'); 
        Treeview({
            type: 'product',
            inputs: {
                file: { icon: uploadImage }
            },
            api: {
                'add': 'category/add',
                'list': 'category/list',
                'delete': 'category/delete',
                'update': 'category/update',
                'order': 'category/order',
            },
            onBtnAction: function (data,type) { 
                if (type=='edit') {
                    document.querySelector('#inp_title').value = data.title;
                    document.querySelector('#inp_path').value = data.path;
                    document.querySelector('#inp_slug').value = data.slug; 
                    document.querySelector('#inp_seo_title').value = data.seo_title;
                    document.querySelector('#inp_meta').value = data.meta;
                    document.querySelector('#inp_short_desc').value = data.short_desc;
                    document.querySelector('#inp_id').value = data.id;
                    document.querySelector('#inp_pid').value = data.pid; 
                    tinymce.get('inp_desc').setContent(data.desc)
                    if (data.icon.length > 0) {
                        uploadImage.addFiles([{ file: data.icon, id: 1 }]);
                    }
                }
            },
            onModalHide: function () { 
                uploadImage.reset(); 
            },
            onSubmit: function (data) {  
                return data;
            },
        }, 10); 
    },
    /* vacancie() {
        document.addEventListener('focusin', (e) => {
            if ($(e.target).closest(".mce-window") !== null) {
               e.stopImmediatePropagation();
            }
        }); 
        slugValidation('location_vacancies','submit_treeview'); 
        Treeview({
            type: 'content', 
            orderBtn:true, 
            api: {
                'add': 'content/add_vacancies',
                'list': 'content/list_vacancies',
                'delete': 'content/delete_vacancies',
                'update': 'content/update_vacancies',
                'order': 'content/order_vacancies',
            },
            onBtnAction: function (data,type) {
                if (type == 'edit') {
                    document.querySelector('#inp_title').value = data.title;
                    document.querySelector('#inp_path').value = data.path;
                    document.querySelector('#inp_slug').value = data.slug; 
                    document.querySelector('#inp_seo_title').value = data.seo_title;
                    document.querySelector('#inp_meta').value = data.meta;
                    document.querySelector('#inp_short_desc').value = data.short_desc;
                    document.querySelector('#inp_id').value = data.id;
                    document.querySelector('#inp_pid').value = data.pid; 
                    tinymce.get('inp_desc').setContent(data.desc);
                    console.log(data);
                }
                console.log(data);
            },
            onModalHide: function () {   
            },
            onSubmit: function (data) {   
                return data;
            },
        }, 4);

    },  */
};