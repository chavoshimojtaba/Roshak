import { fetchApi } from "../api";
import { UploadForm } from "../components/UploadForm";
import { CommentWidget } from "../components/_CommentWidget";
import { DataTable } from "../components/_DataTable";
import { MultiSelect } from "../components/_MultiSelect";
import { SelectMenuInput } from "../components/_SelectMenuInput";
import Treeview from "../components/_Treeview";
import {
    LNG_ERROR,
    LNG_MSG_CONNECTION_ERROR,
    LNG_MSG_SUCCESS,
    LNG_SUCCESS,
} from "../util/consts";
import { loading, redirect, slugValidation, toast } from "../util/util";
import { Validator, editorValidation } from "../util/Validator";
export const ProductScreen = {
    render: async (id) => {
        id = id || 0;
        new DataTable({
            cell: [
                {
                    key: "img",
                    title: "Thumbnail",
                    type: "file",
                    filetype: "image",
                }, 
                {
                    key: "title",
                    title: "عنوان",
                    type: "link",
                    options: {
                        url: "p/|slug",
                    },
                    search: true,
                    sort: true,
                },
                {
                    key: "city",
                    title: "شهر"
                }, 
                {
                    key: "category",
                    type: "link",
                    options: {
                        url: "search/|cat_slug",
                        target: "_blank",
                    },
                    sort: true,
                    search: true,
                    title: "دسته بندی",
                }, 
                {
                    key: "createAt",
                    sort: true,
                    title: "تاریخ",
                    type: "date",
                }, 
                {
                    key: "edit_product",
                    title: "ویرایش",
                    type: "public-btn",
                    options: {
                        class: "btn-light-info text-info edit-product",
                        value: '<i class="ti-pencil"></i>',
                    },
                },
                {
                    key: "del",
                    title: "حذف",
                    type: "public-btn",
                    attr: ['data-resource="product"'],
                    options: {
                        class: "btn-light-danger text-danger del-role",
                        value: '<i class="far fa-trash-alt"></i>',
                    },
                },
            ],
            api: (data) => {
                data.mid = id;
                return fetchApi(`product/list`, "GET", {
                    params: { data },
                });
            },
            actions: {
                edit_product(data) {
                    redirect("product/add/" + data.id);
                },
                more(data) {
                    window.open($_Curl + "view/" + data.id, "_blank");
                },
            },
        });
    },
    category() {
        document.addEventListener("focusin", (e) => {
            if ($(e.target).closest(".mce-window") !== null) {
                e.stopImmediatePropagation();
            }
        });
        const uploadImage = new UploadForm({
            cls: "icon-file",
            formats: "jpg",
            title: "آیکن",
            max: 1,
            filesType: "image",
            btnTitle: "افزودن تصویر",
        });
        slugValidation("category", "submit_treeview");
        Treeview(
            {
                type: "product",
                inputs: {
                    file: { icon: uploadImage },
                },
                api: {
                    add: "category/add",
                    list: "category/list",
                    delete: "category/delete",
                    update: "category/update",
                },
                onEdit: function (data) {
                    document.querySelector("#inp_title").value = data.title;
                    document.querySelector("#inp_path").value = data.path;
                    document.querySelector("#inp_slug").value = data.slug;
                    document.querySelector("#inp_filetype").value = data.filetype;
                    document.querySelector("#inp_seo_title").value = data.seo_title;
                    document.querySelector("#inp_meta").value = data.meta;
                    document.querySelector("#inp_short_desc").value = data.short_desc;
                    document.querySelector("#inp_id").value = data.id;
                    document.querySelector("#inp_pid").value = data.pid;
                    tinymce.get("inp_desc").setContent(data.desc);
                    if (data.icon.length > 0) {
                        uploadImage.addFiles([{ file: data.icon, id: 1 }]);
                    }
                },
                onModalHide: function () {
                    uploadImage.reset();
                },
                onSubmit: function (data) {
                    if (data.pid == 0 && data.filetype.length <= 0) {
                        toast("e", "انتخاب نوع فایل برای سطح یک الزامی میباشد.");
                        return false;
                    }
                    return data;
                },
            },
            10
        );
    },
    async add(id) {
        id = id || 0;
        let locationSelectBox = 0;
        let categorySelctbox = 0;
        if (id > 0) {
            document.getElementById("inp_id").value = id;
        } else {
            id = 0;
        }

        const submitBtn = document.getElementById("submit_product");
        const form = document.getElementById("form-product-submit");
       
        const selectInp = new MultiSelect({
            required: false,
            selector: ".tag-select-box",
            type: "tags",
        });

        slugValidation("product", "submit_product");

        await selectInp.init();

        if (menuObject != "undefined") {
            locationSelectBox = new SelectMenuInput({
                tree: locationObject,
                id: "select-menu-city",
                searchStartLen: 3, //char
                searchDelay: 600, //ms
            });
            categorySelctbox = new SelectMenuInput({
                tree: menuObject,
                id: "select-menu-cat",
                searchStartLen: 3, //char
                searchDelay: 600, //ms
            });
        }
        
        const uploadImage = new UploadForm({
            cls: "pic-file",
            formats: "jpg",
            title: "تصویر اصلی",
            max: 1,
            filesType: "image",
            btnTitle: "افزودن تصویر",
        });

        const uploadGallery = new UploadForm({
            cls: "gallery-file",
            formats: "jpg,jepg,png",
            title: "تصاویر گالری",
            max: 10,
            filesType: "image",
            btnTitle: "افزودن تصویر",
        });
        submitBtn.addEventListener("click", function (e) {
            e.preventDefault();
            console.log(111);
            submitForm(e, "");
        });
        /* document.getElementById('submit_draft').addEventListener('click', function (e) {
                e.preventDefault();
                submitForm(e, 'draft');
            }) */
        function submitForm(e, draft) {
            let tags = selectInp.value();
            
            
            const editorContent = tinymce.get("inp_desc").getContent();
            tinymce.DOM.addClass(".mce-container-body", "myclass");
            const images = uploadImage.getFiles();
            const imagesKeys = Object.keys(images); 
            const gallery = uploadGallery.getFiles(true);
            var valid = Validator("form-product-submit").validate() 

            if (imagesKeys.length <= 0) {
                toast("e", "لطفا تصویر را انتخاب کنید", LNG_ERROR);
                valid = false;
            }
            if (Object.keys(tags).length === 0) {
                tags = {};
            } 
            if (editorContent.length === 0) {
                
                editorValidation("form-group_desc");
                valid = false;
            } 
            if (valid) {
                const formData = new FormData(form);
                formData.append("tags", JSON.stringify(tags)); 
                formData.append(
                    "follow",
                    document.querySelector("#inp_follow").checked ? "yes" : "no"
                );
                formData.append(
                    "index",
                    document.querySelector("#inp_index").checked ? "yes" : "no"
                );
                formData.append("gallery", gallery);
                formData.append("lid", locationSelectBox.value());
                formData.append("cid", categorySelctbox.value());
                formData.append("pic", images[imagesKeys[0]].file);
                formData.append("desc", tinymce.get("inp_desc").getContent());
                const formDataEntries = Object.fromEntries(formData);
                let method = "POST";
                let url = `product`;
                if (formDataEntries.id && parseInt(formDataEntries.id) > 0) {
                    method = "PUT";
                    url = `product/${formDataEntries.id}`;
                } else {
                    delete formDataEntries.id;
                }
                fetchApi(url, method, { body: formDataEntries })
                    .then((res) => {
                        if (res.status) {
                            toast("s", LNG_MSG_SUCCESS, LNG_SUCCESS);
                            window.location.href = $_Burl + "product/";
                        } else {
                            toast("e", LNG_MSG_CONNECTION_ERROR, LNG_ERROR);
                        }
                        loading(0, e);
                    })
                    .catch((err) => {
                        loading(0, e);
                        toast("e", LNG_MSG_CONNECTION_ERROR, LNG_ERROR);
                    });
            }
        }
        const mapModal = document.getElementById("mapModal"); 
        var app = {};

        mapModal.addEventListener("show.bs.modal", function (e) {
                        console.log(e);
                        setTimeout(() => {
                if(!app.map) { 
                    app = new Mapp({
                        element: "#app",
                        presets: {
                            latlng: {
                                lat: 36.287918924166085,
                                lng: 59.615614414215095,
                            },
                            zoom: 12,
                        },
                        
                        apiKey:
                            "eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiIsImp0aSI6ImI3ZjEwYTg2MjExODE2NmM0MmIxNDlkYzdiNjgzZmVkYjJlZDU5NmUyMTRiYWEwODE4YWFkMjVhNzM5NjAwNTMyMzg4MTQ0Njg3MDBkMTEyIn0.eyJhdWQiOiIyNzY1MyIsImp0aSI6ImI3ZjEwYTg2MjExODE2NmM0MmIxNDlkYzdiNjgzZmVkYjJlZDU5NmUyMTRiYWEwODE4YWFkMjVhNzM5NjAwNTMyMzg4MTQ0Njg3MDBkMTEyIiwiaWF0IjoxNzE3NzcxODI2LCJuYmYiOjE3MTc3NzE4MjYsImV4cCI6MTcyMDM2MzgyNiwic3ViIjoiIiwic2NvcGVzIjpbImJhc2ljIl19.nyY13r_Uxo6XVt4WZ6Y8qy0mHifFhx40ZBgIgzTHMHp__fgEt0Um3V19KlIZBGAbgMNMJ6Slbhp_VOtNgMe7vl8e3DGBQqmpjVmVKwpHqBVbYvKksWGWlJUn7quErxDXReqLUxfIGua-wy3QCxXZpp8H5ZsMYzD64IXpe3KvI20ef8IvUc-sfYKABDO4YPOrUzIy4u8Ul8j8O6qwj7rQpiKOoz7djVJx8Ce8O_o0rLSk9UIBWze4Lamkmy820nz2AJiklNHiqC2uu0VcmEj1iqbNpJECsULl9b5fGdhPhMnBrNczM3gFO5e0skgcv6WiEtP3fTaJ1p54Ba1vb54QBg",
                    });
                    
                    app.addVectorLayers();
    
                    // Add in a crosshair for the map
                    var crosshairIcon = L.icon({
                        iconUrl: HOST + "file/global/map/pin.svg",
                        iconSize: [26, 26], // size of the icon
                        iconAnchor: [14, 14], // point of the icon which will correspond to marker's location
                    });
                    var crosshairMarker = new L.marker(app.map.getCenter(), {
                        icon: crosshairIcon,
                        clickable: false,
                    });
                    crosshairMarker.addTo(app.map);  
                    app.map.on('click', function(e) {
                        $.ajax({
                          url: `https://map.ir/reverse/?lat=${e.latlng.lat}&lon=${e.latlng.lng}&lang=${e.latlng.lng}`,
                          method: 'GET',
                          beforeSend: function(request) {
                            request.setRequestHeader('x-api-key', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiIsImp0aSI6ImI3ZjEwYTg2MjExODE2NmM0MmIxNDlkYzdiNjgzZmVkYjJlZDU5NmUyMTRiYWEwODE4YWFkMjVhNzM5NjAwNTMyMzg4MTQ0Njg3MDBkMTEyIn0.eyJhdWQiOiIyNzY1MyIsImp0aSI6ImI3ZjEwYTg2MjExODE2NmM0MmIxNDlkYzdiNjgzZmVkYjJlZDU5NmUyMTRiYWEwODE4YWFkMjVhNzM5NjAwNTMyMzg4MTQ0Njg3MDBkMTEyIiwiaWF0IjoxNzE3NzcxODI2LCJuYmYiOjE3MTc3NzE4MjYsImV4cCI6MTcyMDM2MzgyNiwic3ViIjoiIiwic2NvcGVzIjpbImJhc2ljIl19.nyY13r_Uxo6XVt4WZ6Y8qy0mHifFhx40ZBgIgzTHMHp__fgEt0Um3V19KlIZBGAbgMNMJ6Slbhp_VOtNgMe7vl8e3DGBQqmpjVmVKwpHqBVbYvKksWGWlJUn7quErxDXReqLUxfIGua-wy3QCxXZpp8H5ZsMYzD64IXpe3KvI20ef8IvUc-sfYKABDO4YPOrUzIy4u8Ul8j8O6qwj7rQpiKOoz7djVJx8Ce8O_o0rLSk9UIBWze4Lamkmy820nz2AJiklNHiqC2uu0VcmEj1iqbNpJECsULl9b5fGdhPhMnBrNczM3gFO5e0skgcv6WiEtP3fTaJ1p54Ba1vb54QBg');
                            request.setRequestHeader('content-type', 'application/json');
                          },
                          success: function(data, status) {
                            var lat = e.latlng.lat;
                            var lng = e.latlng.lng;
                            var newLatLng = new L.LatLng(lat, lng); 
                            crosshairMarker.setLatLng(newLatLng);

                            var popup = app.generatePopupHtml({
                              title: { i18n: 'آدرس' },
                              description: { i18n: data.address }
                            });
                            document.querySelector('#inp_address').value = data.address;
                            document.querySelector('#inp_modal_address').value = data.address;
                            document.querySelector('#inp_lat').value = lat;
                            document.querySelector('#inp_lng').value = lng;
                            document.querySelector('#inp_address_json').value = JSON.stringify(data);
                            crosshairMarker.bindPopup(popup).openPopup();
                          },
                          error: function(error) {
                            console.log(error);
                          }
                        });
                      });
                   
                } 
            }, 1000);
        });
    },
    view: async (id) => {
        new CommentWidget({
            showMoreBtn: false,
            type: "product",
            api: (page, limit) => {
                console.log(page);
                return fetchApi(`comment/content_comments`, "GET", {
                    params: { page, limit, type: "product", pbid: id },
                });
            },
            afterRender(res) {
                if (res == 404) {
                    E_404();
                }
            },
        });
        const modal = new bootstrap.Modal(document.getElementById("statusModal"));
        document
            .getElementById("inp_status")
            .addEventListener("change", function (e) {
                if (this.value == "reject") {
                    document.getElementById("reason_inp").classList.remove("d-none");
                } else {
                    document.getElementById("reason_inp").classList.add("d-none");
                }
            });
        document
            .getElementById("change-status")
            .addEventListener("click", function (e) {
                modal.show();
            });
        const statusList = {
            accept: '<span class="badge bg-primary status-badge">تایید شده</span>',
            reject: '<span class="badge bg-danger status-badge">رد شد</span>',
        };
        document
            .getElementById("submit_status")
            .addEventListener("click", function (e) {
                console.log(12);
                var valid = Validator("form-status-submit").validate();
                if (valid) {
                    const formObj = new FormData(
                        document.getElementById("form-status-submit")
                    );
                    const formData = Object.fromEntries(formObj);
                    loading(1, e);
                    fetchApi("product/status/" + id, "PUT", { body: formData })
                        .then((res) => {
                            if (res.status) {
                                modal.hide();
                                document.getElementById("status-row").innerHTML =
                                    statusList[formData.status];
                                toast("s", LNG_MSG_SUCCESS, LNG_SUCCESS);
                            } else {
                                toast("e", LNG_MSG_CONNECTION_ERROR, LNG_ERROR);
                            }
                            loading(0, e);
                        })
                        .catch((err) => {
                            loading(0, e);
                            toast("e", err.message, LNG_ERROR);
                        });
                }
            });
    },
};
