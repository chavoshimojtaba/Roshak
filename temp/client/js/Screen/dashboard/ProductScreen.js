import { isMobile, loading, owlCarousel, select2, toast,slugValidation, toman,currency, owlCarouselGallery } from "../../util/util";
import { productActionBtns } from "../../util/util_product";
import { Grid } from "../../components/_Grid"; 
import { MultiSelect } from "../../components/_MultiSelect";
import { editorValidation } from "../../../../dev/js/util/Validator";
import { Validator } from "../../../../util/util";
import { fetchApi } from "../../api";
import { StepWizard } from "../../components/_StepWizard";
import { Uploader } from "../../components/_Uploader";
import { SelectMenuInput } from "../../components/_SelectMenuInput";  
import { UploaderFTP } from "../../components/_UploaderFTP";
export const ProductScreen = {
    render(){
        if (isMobile()) { 
            var myOffcanvas = document.getElementById('offcanvasFilter')
            var bsOffcanvas = new bootstrap.Offcanvas(myOffcanvas);
            owlCarousel('.info-card-slide',{
                items: 2,
                margin: 15,
                stagePadding: 20,
                loop: 1,
                dots: !1,
                nav: 1,
                responsive: { 0: { items: 2 }, 1200: { items: 3 } }, 
            }); 
        }
        select2(0,'filter-dashboard-template').on('select2:select', function (e) {
            switch (e.target.id) {
                case 'inp_status':
                    grid.changeFilters({ status: this.value });
                    if (isMobile()) {
                        bsOffcanvas.hide();
                    }
                    break;
                case 'inp_cid':
                    grid.changeFilters({ cid: this.value });
                    if (isMobile()) {
                        bsOffcanvas.hide();
                    }
                    break;
                default:
                    break;
            }
        });
        const grid = new Grid({
            filters:{
                status:'all',
                designer:true,
            },
            emptyHtml:`<div class="d-flex flex-column align-items-center  justify-content-center w-100">
                <img width="200px" class="rounded-2" src="http://localhost/tarhpich/file/client/images/no_result.svg">
                <div class="fs-4 fw-bold text-secondary">
                    نتیجه ای یافت نشد!
                </div>
            </div>`,
            afterRender:function(data){
                productActionBtns();
                document.querySelector('#inp_grid_q').value = (data && data.q)?data.q:'';
            }
        });
        grid.render();
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
        if (document.getElementById('inp_date').getAttribute('data-value').length>0) {
            a.setDate(new Date(document.getElementById('inp_date').getAttribute('data-value')))
        }
        document.querySelector('.btn-clear').addEventListener('click',function (e) {
            a.clearDate()
            grid.changeFilters({ date: 'delete'});
        })
    },
    add(){   
        const send_type = document.querySelector('#file_send_type_other');
        send_type.addEventListener('click',function (e) {
            if (this.checked) { 
                document.querySelector('.send-by-other').classList.remove('d-none'); 
            }else{
                document.querySelector('.send-by-other').classList.add('d-none'); 
            }
        });
        let categorySelctbox; 
        const uploadfile = new Uploader({
			type:'product',
			maxFilesize:.15,
			FileTterms:'d-block',
			cls:'upload-file--gallery',
			formats: '.jpeg , .jpg , .png , .webp',
			title: 'آپلود تصاویر گالری',
            onAction:function (params) { 
                if (uploadfile.getFiles().length<=0) {
                    document.querySelector('#continue-btn').classList.remove('d-flex');
                    document.querySelector('#continue-btn').classList.add('d-none');
                }else{
                    document.querySelector('#continue-btn').classList.remove('d-none');
                    document.querySelector('#continue-btn').classList.add('d-flex');
                }
            },
            onSend:function () { 
                return {cid:categorySelctbox.value()}
            }
		}); 
        new StepWizard  ({
			steps: 4,
			onNext: function (currentStep) {
				let isValid = false; 
				switch (currentStep) {
					case 2: 
                        isValid = true;
						if (uploadfile.getFiles().length <= 0) {
                            toast('e', 'لطفا تصاویر گالری را بارگذاری کنید.');
							isValid = false;
						} else{
                            if (document.querySelector('.upload-file--gallery [name="main-file"]:checked') == null) {
                                toast('e', 'لطفا تصاویر اصلی را انتخاب کنید.');
                                isValid = false;
                            }   
                        }
						break;
					case 1: 
                        if (Validator('form-step-1').validate()) {
                            isValid = true;
                        } 
                        const editorContent =  tinymce.get('inp_desc').getContent(); 
						if (editorContent.length === 0) {
                            editorValidation('form-group_desc');
                            isValid =  false;
                        } 
						if (categorySelctbox.value() <= 0) { 
							toast('e', 'دسته بندی را مشخص کنید.');
                            isValid =  false;
                        } 
						break;
					case 3:
                        isValid = true;
                        const format_value = selectInp_formats.value(); 
                        if (Object.keys(format_value).length>0) {
                            isValid = true;
                        }else{
                            isValid = false;
                            selectInp_formats.isRequired()  
                        }
						if (uploadMainFile.getFiles().length <= 0 && send_type.checked == false) {
							toast('e', 'لطفا فایل را بارگذاری کنید.');
							isValid = false; 
						}
                       
						break;
				}  
                return isValid;
			},
			onInitilized: function (currentStep) {
                document.querySelector('.initialize-loading-box').classList.add('d-none');
			}
		})
        select2();  
        if (menuObject != 'undefined') {
            categorySelctbox = new SelectMenuInput({
                tree: menuObject,
                id: 'select-menu',
                searchStartLen: 3,//char
                searchDelay: 600//ms
            });
        }
        const uploadMainFile = new UploaderFTP({
			preview_element:'upload-file--main_file',
			dest:'ftp',
			max:'1',
			cls:'upload-file--main_file',
			formats: '.zip',
			title: 'بارگزاری فایل',
            onSend:function () { 
                return {cid:categorySelctbox.value()}
            }
		});  
        const selectInp_formats   =  new MultiSelect({
			selector: '.format-select-box',
			required: false,
			title:'',
			api:false,
			max:1,
            type:'formats'
        }); 
        const selectInp_tags   =  new MultiSelect({
			selector: '.tags-select-box',
			title:'',
            type:'product_tags'
        }); 
        document.getElementById('inp_title').addEventListener('blur',function (e) {
            if (document.querySelector('#inp_seo_title').value.length <= 0) {
                document.querySelector('#inp_seo_title').value = this.value
            }
        }); 
        document.querySelector('.submit-info').addEventListener('click',function (e) {
            if (document.querySelector('#termsInput').checked) {
                
                submitInfo();
            }else{
                toast('e', 'برای ادامه فرایند نیاز است که با قوانین و اساسنامه موافقت نمایید.')
            }
        }); 
        selectInp_formats.init();
		selectInp_tags.init();
        currency();
        window.onbeforeunload = function(){
            return 'آیا مطمئن هستید برای ترک این صفحه؟';
        };
       

        slugValidation('product','checked_slug'); 
        function submitInfo() { 
            const format_value = selectInp_formats.value();
            const format_keys =Object.keys(format_value);
            const format = [];
            const data = Object.assign(Object.fromEntries(new FormData(document.getElementById('form-step-2'))),{formats:''}); 
            if (send_type.checked) { 
                data.send_type = document.querySelector('[name="send_by_social_media"]:checked').value;
			    data.file = 'test.zip';
            }else{
                data.send_type = 'direct';
			    data.file = uploadMainFile.getFilesDetails()[0].file;
            }
			data.price_temp = toman(data.price_temp , 1);
			data.price_off = toman(data.price_off , 1); 
			// data.gallery = uploadfile.getFiles(true);
			data.main_pic = document.querySelector('[name="main-file"]:checked').value;
            const pics  =   uploadfile.getFilesDetails();
			data.pic = uploadfile.getFilesDetails()[0].file; 
            const galleryPics = [];
            if (pics.length>0) {
                for (let index = 0; index < pics.length; index++) {
                    if(data.main_pic == pics[index].id){
                        data.pic = pics[index].file;    
                    } else{
                        galleryPics.push( pics[index].id);
                    }
                }
            }
            data.gallery = JSON.stringify(galleryPics);   
            data.desc = tinymce.get('inp_desc').getContent(); 
            data.cid = categorySelctbox.value(); 
            for (let index = 0; index < format_keys.length; index++) {
                format.push(format_keys[index]);
            }
            data.formats = format.join('-');
            const b = selectInp_tags.value();
            const bkeys = Object.keys(b);
            if (Object.keys(bkeys).length>0) {
                const tags = {};
                for (let index = 0; index < bkeys.length; index++) {
                    tags[bkeys[index]] = bkeys[index];
                }
                data.tags = JSON.stringify(tags);
            }   
            if (parseInt(data.price_temp) > 0 && parseInt(data.price_off) <= parseInt(data.price_temp)) {
                data.price =parseInt(data.price_temp) - parseInt(data.price_off) ;
            }else if (parseInt(data.price_temp) > 0) {
                data.price = parseInt(data.price_temp);
            } else{
                data.price = 0;
            }
            let method  = 'POST';
            let url     = `product`;
            if (data.id && parseInt(data.id) > 0) {
                method = 'PUT'; 
            } else {
                delete data.id;
            }  
            window.onbeforeunload = null;
            document.querySelector('.terms-box').classList.add('d-none');
            document.querySelector('.loading-box').classList.remove('d-none'); 
            fetchApi(url, method, { body: data }).then((res) => {  
				document.querySelector('.loading-box').classList.add('d-none');
				if (res.status) {
					document.querySelector('.alert-box').classList.remove('d-none'); 
                    location.href = HOST+'dashboard/product/'+data.slug;
				} else {
                    throw new Error((res.msg)?res.msg:'خطا در برقراری ارتباط با سرور...لطفا لحظاتی دیگر مجددا تلاش نمایید.');
				}
			}).catch(err => {
                document.querySelector('#error-text').innerHTML =err.message ; 
                document.querySelector('.last-action-box').classList.add('d-none');
                document.querySelector('.alert-box--danger').classList.remove('d-none');
                document.querySelector('#back-dashboard').classList.remove('d-none');
			})  
        } 
    },
    slug(){
        let padd = 0;
        if (isMobile()) {
            padd = '20px'; 
            owlCarousel('.info-card-slide',{
                items: 1,
                margin: 15,
                stagePadding: 20,
                dots: !1,
                nav: !1,
                responsive: { 0: { items: 1 }, 1200: { items: 3 } },
                navText: [
                    "<i class='d-block icon fs-4 icon-arrow-right-14'></i>",
                    "<i class='d-block icon fs-4 icon-arrow-left4'></i>",
                ],
            });  
        }
      
        const ctx = document.getElementById('myChart');
        const days_p = Object.keys(days);
        let labels = [];
        let data = [];
        let chart = [];

        if (days_p.length>0) {
            for (let index = 0; index < days_p.length; index++) {
                labels.push(days[days_p[index]].p)
                data.push(days[days_p[index]].total_price)
            }
        }
        createChart(labels,data,30)
        select2(0,'filter-dashboard-template').on('select2:select', function (e) {
            loading('#chart-box', 1);
            const _this = this;
            fetchApi('financial/designer_sell', 'GET', { params: { data: { days:this.value,pid:document.querySelector('#inp_id').value } } }).then(async (res) => {
                if (res.status) {
                      labels = [];
                      data = [];
                    const days_item = Object.keys(res.data); 
                    if (days_item.length>0) {
                        for (let index = 0; index < days_item.length; index++) {
                            labels.push(res.data[days_item[index]].p)
                            data.push(res.data[days_item[index]].total_price)
                        }
                    }
                    chart.destroy()
                    createChart(labels,data,_this.value)

                }
            }).catch(err => {
                console.log('e',err.message);
            }).finally(res => {
                loading('#chart-box', 0);

            })
        });
        function createChart(labels,data,days) {
            if (document.body.classList.contains('mobile-view')) {
                chart = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels ,
                        datasets: [{
                            label: days+' روز اخیر',
                            data,
                            borderWidth: 1
                        }]
                    },
                    options: {
                        backgroundColor: '#33d1e7',
                        borderRadius: 8,
                        scales: {
                            y: {
                                display: false
                            },
                            x: {
                                display: false
                            }
                        },
                        plugins: {
                            tooltip: {
                                cornerRadius: 8
                            },
                            legend: {
                                display: false,
                                labels: {
                                    color: 'rgb(255, 99, 132)'
                                }
                            }
                        }
                    }
                });
            }else{
                chart = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels ,
                        datasets: [{
                            label: '# هزار تومان',
                            data,
                            borderWidth: 1
                        }]
                    },
                    options: {
                        backgroundColor: '#33d1e7',
                        borderRadius: 8,
                        scales: {
                            y: {
                                beginAtZero: true
                            }
                        },
                        plugins: {
                            tooltip: {
                                cornerRadius: 8
                            }
                        }
                    }
                });
            }
        } 
        owlCarouselGallery();
        $('.toggle-content').click(function () {
            var i = $(this).closest('.more-wrapper');
            i.toggleClass('overlay-bg'),
                $(this).children('.icon-arrow-down-14').toggleClass('rotate'),
                i.parent().find('.content-hidden').toggleClass('show');
        });
    }
};