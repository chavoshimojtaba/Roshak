import { BASE_URL, fetchApi, getToken } from '../api';
import {
    LNG_ERROR
} from '../util/consts';
import { fileType,   loading,   toast } from '../util/util';
import { FileTable } from '../components/_FileTable';
import Treeview from '../components/_Treeview';
export const FilesScreen = {
    render:async () => {

        Dropzone.autoDiscover = false;
        const moreModal = document.getElementById('moreModal');
        const modal = new bootstrap.Modal(moreModal);
        var previewNode = document.querySelector('#template');
        previewNode.id = '';
        var previewTemplate = previewNode.parentNode.innerHTML;
        previewNode.parentNode.removeChild(previewNode);
        const cardCreview = document.querySelector('#card-preview');
        const startBtn = document.querySelector('#actions .start');
        const cancelBtn = document.querySelector('#actions .cancel');
        const categoryInp = document.querySelector('#category_type');
        const qCategoryInp = document.querySelector('#filter_category_type');
        const is_editor = document.querySelector('#is_editor').value ;
        const is_cat = document.querySelector('#is_cat').value ;
        const acceptFormats = document.querySelector('#accept-formats').getAttribute('data-formats') ;
        let filetype = document.querySelector('#filetype').value ;
        const modalEls = document.getElementsByClassName('file_info');
        let categories = {};
        const createCategoriesList = () => {
            let optionsEl = '';
            const keys = Object.keys(categories);
            for (let index = 0; index < keys.length; index++) {
                const element = categories[keys[index]];
                optionsEl += `<option value="${element.id}">${element.title}(${element.original_size})</option>`;
            }
            categoryInp.innerHTML = optionsEl;
            qCategoryInp.innerHTML = `<option value="0">مرتب سازی بر اساس</option>`+optionsEl;
        };
        const token = await getToken() ;

        var myDropzone = new Dropzone('div#previews', {
            url: BASE_URL + 'file',
            method: 'POST',
            thumbnailWidth: 80,
            thumbnailHeight: 80,
            headers: {
                'Authorization':`Bearer ${token}`
            },
            createImageThumbnails: true,
            acceptedFiles:acceptFormats,
            autoQueue: false,
            maxFiles: 50,
            previewTemplate: previewTemplate,
            previewsContainer: '#previews',
            clickable: '.fileinput-button',
            success: function (file, response) {
                file.previewElement
                    .querySelector('.upload-done')
                    .classList.remove('d-none');
                setTimeout(() => {
                    file.previewElement.remove();
                }, 1000);
            },
            error: function (file, response) {
                if (file.previewElement) {
                    file.previewElement.remove();
                }
                toast('e', response.data, LNG_ERROR);
            },
            reset() {
                startBtn.classList.add('d-none');
                cardCreview.classList.add('d-none');
                cancelBtn.classList.add('d-none');

            },
        });

        categoryInp.addEventListener('change',function (e) {
            e.preventDefault()
            const size = categories[e.target.value].thumbnail_size;
            if (size) {
                var _els = document.querySelectorAll('#card-preview .file_thumbnail');
                if (_els) {
                    for (var index = 0; index < _els.length; index++) {
                        _els[index].value = size
                    }
                }
            }
        })
        myDropzone.on('addedfile', function (file) {
            startBtn.classList.remove('d-none');
            cardCreview.classList.remove('d-none');
            cancelBtn.classList.remove('d-none');
            file.previewElement.querySelector('.dz-type').innerHTML = file.type;
            const validImageTypes = ['image/gif', 'image/jpeg', 'image/jpg', 'image/png'];
            if (!validImageTypes.includes(file.type)) {
                file.previewElement.querySelector('.file_thumbnail').disabled = 'disabled';
                file.previewElement.querySelector('.watermark-inp').disabled = 'disabled';
            }else{
                const keys = Object.keys(categories);
                for (let index = 0; index < keys.length; index++) {
                    const element = categories[keys[index]];
                    if (categoryInp.value == element.id) {
                        file.previewElement.querySelector('.file_thumbnail').value = element.thumbnail_size;
                    }
                }
            }
            file.previewElement.querySelector('.start').onclick = function () {
                myDropzone.enqueueFile(file);
            };
        });
        myDropzone.on('sending', function (file, xhr, formData) {
            formData.append('type', categoryInp.value);
            formData.append('cat', categories[categoryInp.value].alias);
            formData.append('cid', categoryInp.value);
            formData.append('watermark', file.previewElement.querySelector('#watermark-inp').checked);
            formData.append(
                'alias',
                file.previewElement.querySelector('.file_name').value
            );
            formData.append(
                'thumbnail',
                file.previewElement.querySelector('.file_thumbnail').value
            );
            formData.append(
                'title',
                file.previewElement.querySelector('.file_title').value
            );
            formData.append(
                'alt',
                file.previewElement.querySelector('.file_alt').value
            );
            file.previewElement
                .querySelector('.start')
                .setAttribute('disabled', 'disabled');
        });
        myDropzone.on('queuecomplete', function (progress) {
            Table.rerender();
            startBtn.classList.add('d-none');
            cardCreview.classList.add('d-none');
            cancelBtn.classList.add('d-none');
        });
        startBtn.onclick = function () {
            myDropzone.enqueueFiles(
                myDropzone.getFilesWithStatus(Dropzone.ADDED)
            );
        };
        cancelBtn.onclick = function () {
            myDropzone.removeAllFiles(true);
        };
        const Table = new FileTable({
            cell: [
                {
                    key: 'dir',
                    title: 'فایل',
                    type: 'file',
                },
                {
                    key: 'name_cat',
                    title: 'نام(دسته بندی)',
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
                    },
                },
                {
                    key: 'del',
                    title: 'حذف',
                    type: 'public-btn',
                    options: {
                        class: 'btn-light-danger text-danger del-tag',
                        value: '<i class="far fa-trash-alt"></i>',
                    },
                },
            ],
            id: 'card-table',
            perPage: '30',
            filetype,
            is_iframe:(window.opener)?true:false,
            is_editor,
            api: async (data) => {
                let query_cat=0;
                if (data.cat != 0) {
                    query_cat = data.cat;
                }else if (is_cat != 0){
                    query_cat = is_cat;
                }
                if (filetype == 0 || data.accept_formats != 0) {
                    filetype = data.accept_formats;
                }
                const res = await fetchApi(`file/list`, 'GET', {
                    params: { data:{page:data.page, limit:data.limit,filetype,is_editor ,cat:query_cat,q:data.q}},
                });
                if (Object.keys(categories).length === 0) {
                    categories = res.category;
                    await createCategoriesList();
                }
                return res;
            },
            actions: {
                async more (data) {
                    const res = await fetchApi(`file/detail/` + data.id, 'GET', { params: {} });
                    if (!res.status) {
                        return false;
                    }
                    document.querySelector('#form-update-submit #inp_update_title').value = res.data.title;
                    document.querySelector('#form-update-submit #inp_update_alt').value = res.data.alt;
                    document.querySelector('#form-update-submit #inp_update_id').value = data.id;
                    for (let index = 0; index < modalEls.length; index++) {
                        const element = modalEls[index];
                        const id = element.getAttribute('data-id');
                        if (id === 'file') {
                            const type = fileType(data.type);
                            switch (type) {
                                case 'image':
                                    const pic = data.dir.replace('thumbnail/thumbnail_','')
                                    element.innerHTML = `<img src="${
                                        $_url + pic
                                    }" class="card-img-top file_info" />`;
                                    break;
                                case 'video':
                                    element.innerHTML = `
                                        <video width="100%" height="240" controls>
                                            <source src="${
                                                $_url + data.dir
                                            }" type="video/mp4">
                                            <source src="movie.ogg" type="video/ogg">
                                            Your browser does not support the video tag.
                                        </video>
                                    `;
                                    break;
                                default:
                                    element.innerHTML = `<a class="file_box bg-info p-3 text-white" href="${
                                        $_url + data.dir
                                    }" target="_blank"><i class="mdi mdi-file-document text-white mb-2"></i>دانلود فایل</a>`;
                                    break;
                            }
                        } else {
                            element.innerText = data[id];
                        }
                    }
                    modal.show();
                },
            }
        });
        document
        .getElementById('submit_update')
        .addEventListener('click', function (e) {
            const formData = new FormData(document.querySelector('#form-update-submit'));
            const formObj = Object.fromEntries(formData);
            loading(1,e);
            fetchApi(`file/update`, 'PUT', { body: formObj }).then((res) => {
                if (res.status) {
                    toast('s', LNG_MSG_SUCCESS, LNG_SUCCESS);
                } else {
                    toast('e', 'خطا در بارگذاری فایل', LNG_ERROR);
                }
            }).catch(err=>{
                toast('e', LNG_MSG_CONNECTION_ERROR, LNG_ERROR);
            }).finally(()=>{
                document.querySelector('#form-update-submit').reset();
                loading(0,e);
            });
        });
    },
    category:  () => {
        Treeview({
            type:'file',
            api:{
                'add':'file/add_cat',
                'list':'file/category',
                'delete':'file/del_cat',
                'update':'file/up_cat',
            },
            onEdit :function(data){
                document.getElementById('inp_alias').value = data.alias;
                document.getElementById('inp_title').value = data.title;
                document.getElementById('inp_original_size').value = data.original_size;
                document.getElementById('inp_thumbnail_size').value = data.thumbnail_size;
                document.getElementById('inp_id').value = data.id;
            },
        },1);
    }
};