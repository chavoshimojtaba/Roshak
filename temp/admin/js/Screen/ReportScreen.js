
import { fetchApi } from '../api';
import { LNG_ERROR, LNG_MSG_CONNECTION_ERROR } from '../util/consts';
import { loading, toast } from '../util/util';
import { MultiSelect } from '../components/_MultiSelect';
import { SelectMenuInput } from '../components/_SelectMenuInput';
import Treeview from '../components/_Treeview';
export const ReportScreen = {
    render:  () => {
        let catSelectBox = {};
		new mds.MdsPersianDateTimePicker(document.getElementById('date-to'), {
			targetTextSelector: '[data-name="date_to-date"]',
			targetDateSelector: '[name="date_to"]',
		});
		new mds.MdsPersianDateTimePicker(document.getElementById('date-from'), {
			targetTextSelector: '[data-name="date_from-date"]',
			targetDateSelector: '[name="date_from"]',
		});
        document.getElementById('inp_type').addEventListener('change',function (e) {
            e.preventDefault();
            if (this.value == 'special')
                document.querySelector('#special-members').classList.remove('d-none')
            else
                document.querySelector('#special-members').classList.add('d-none');
       })
        const selectMembers =  new MultiSelect({
            required:false,
            type:'member',
            title:'انتخاب مشتریان',
            selector:'.member-select-box'
        });
        const selectproducts =  new MultiSelect({
            required:false,
            type:'product',
            title:'انتخاب محصولات',
            selector:'.product-select-box'
        });
        selectMembers.init()
        selectproducts.init()
        if (menuObject != 'undefined') {
            catSelectBox = new SelectMenuInput({
                tree:menuObject,
                id:'select-menu',
                searchStartLen: 3,//char
                searchDelay:600//ms
            });
        }

        document.getElementById('submit-form').addEventListener('click',function (e) {
            e.preventDefault();
            const cats =catSelectBox.value()
            if (document.getElementById('inp_type').value == 'special' && Object.keys(selectMembers.value())<= 0) {
                    toast('e', 'مشتری / طراح را انتخاب کنید', LNG_ERROR);
                    return
            }
            const formData = new FormData(document.getElementById('form-report-submit'));
            formData.append('cid',cats)
            const formDataEntries = Object.fromEntries(formData);
            formDataEntries.members=JSON.stringify(selectMembers.value());
            formDataEntries.pid=JSON.stringify(selectproducts.value());
            // loading(1,e)
            fetchApi(`financial/report`, 'GET', {params:{ data: formDataEntries }}).then((res) => {
                if (res.status) {
                    createTable(res.data);
                } else {
                    toast('e', LNG_MSG_CONNECTION_ERROR, LNG_ERROR);
                }
                loading(0,e)
            }).catch(err=>{
                loading(0,e)
                toast('e', LNG_MSG_CONNECTION_ERROR, LNG_ERROR);
            })
        })
        function createTable(data) {
            let html = '';
            const keys = Object.keys(data);
            if (!keys.length) {
                html = `
                <tr>
                    <td colspan="6">
                        نتیجه ای یافت نشد
                    </td>
                </tr>
                `;
            }else{
                for (let index = 0; index < keys.length; index++) {
                    const element = data[keys[index]];

                    html += `
                        <tr>
                            <td class="fw-bold">
                                ${element.type}
                            </td>
                            <td class="fw-bold">
                                ${element.serial}
                            </td>
                            <td class="fw-bold">
                                ${element.designer}
                            </td>
                            <td class="fw-bold">
                                ${element.member}
                            </td>
                            <td class="fw-bold">
                                ${element.product_title}
                            </td>
                            <td class="fw-bold">
                                ${element.product_price}
                            </td>
                            <td class="fw-bold">
                                ${element.status}
                            </td>
                            <td class="fw-bold">
                                ${element.createAt}
                            </td>
                            <td class="fw-bold">
                                ${element.total_price}
                            </td>
                        </tr>
                    `;
                }
            }
            document.getElementById('table-orders').innerHTML = html;
            setTimeout(() => {
                // createPdf()

            }, 1000);
        }

       document.getElementById('get-excel').addEventListener('click',function (e) {
            e.preventDefault();
            loading(1,e)
            openIframe('excel');
            setTimeout(() => {
                loading(0,e)

            }, 2000);
       })
       function openIframe(type) {
            const formData = new FormData(document.getElementById('form-report-submit'));
            formData.append('cid',catSelectBox.value())
            const formDataEntries = Object.fromEntries(formData);
            formDataEntries.members= selectMembers.value(true) ;
            formDataEntries.pid= selectproducts.value(true) ;
            const uri = JSON.stringify(formDataEntries) ;
            window.open($_url+"admin/report/"+type+"?filters="+uri,'_blank');
       }
    },
    plan:  () => {
		new mds.MdsPersianDateTimePicker(document.getElementById('date-to'), {
			targetTextSelector: '[data-name="date_to-date"]',
			targetDateSelector: '[name="date_to"]',
		});
		new mds.MdsPersianDateTimePicker(document.getElementById('date-from'), {
			targetTextSelector: '[data-name="date_from-date"]',
			targetDateSelector: '[name="date_from"]',
		});
        const selectMembers =  new MultiSelect({
            required:false,
            type:'member',
            title:'انتخاب مشتریان',
            selector:'.member-select-box'
        });
        selectMembers.init()

        document.getElementById('submit-form').addEventListener('click',function (e) {
            e.preventDefault();
            const formData = new FormData(document.getElementById('form-report-submit'));
            const formDataEntries = Object.fromEntries(formData);
            formDataEntries.members=JSON.stringify(selectMembers.value());
            // loading(1,e)
            fetchApi(`financial/plan_report`, 'GET', {params:{ data: formDataEntries }}).then((res) => {
                if (res.status) {
                    createTable(res.data);
                } else {
                    toast('e', LNG_MSG_CONNECTION_ERROR, LNG_ERROR);
                }
                loading(0,e)
            }).catch(err=>{
                loading(0,e)
                toast('e', LNG_MSG_CONNECTION_ERROR, LNG_ERROR);
            })
        })
        function createTable(data) {
            let html = '';
            const keys = Object.keys(data);
            if (!keys.length) {
                html = `
                <tr>
                    <td colspan="6">
                        نتیجه ای یافت نشد
                    </td>
                </tr>
                `;
            }else{
                for (let index = 0; index < keys.length; index++) {
                    const element = data[keys[index]];

                    html += `
                        <tr>
                            <td class="fw-bold">
                                ${element.status}
                            </td>
                            <td class="fw-bold">
                                ${element.member}
                            </td>
                            <td class="fw-bold">
                                ${element.plan}
                            </td>
                            <td class="fw-bold">
                                ${element.createAt}
                            </td>
                            <td class="fw-bold">
                                ${element.total_price}
                            </td>
                        </tr>
                    `;
                }
            }
            document.getElementById('table-orders').innerHTML = html;
            setTimeout(() => {
                // createPdf()

            }, 1000);
        }

       document.getElementById('get-excel').addEventListener('click',function (e) {
            e.preventDefault();
            loading(1,e)
            openIframe('excel');
            setTimeout(() => {
                loading(0,e)

            }, 2000);
       })
       function openIframe(type) {
            const formData = new FormData(document.getElementById('form-report-submit'));
            const formDataEntries = Object.fromEntries(formData);
            formDataEntries.members= selectMembers.value(true) ;
            const uri = JSON.stringify(formDataEntries) ;
            window.open($_url+"admin/report/plan_"+type+"?filters="+uri,'_blank');
       }
    },
    transaction:  () => {
		new mds.MdsPersianDateTimePicker(document.getElementById('date-to'), {
			targetTextSelector: '[data-name="date_to-date"]',
			targetDateSelector: '[name="date_to"]',
		});
		new mds.MdsPersianDateTimePicker(document.getElementById('date-from'), {
			targetTextSelector: '[data-name="date_from-date"]',
			targetDateSelector: '[name="date_from"]',
		});
        const selectMembers =  new MultiSelect({
            required:false,
            type:'member',
            title:'انتخاب مشتریان',
            selector:'.member-select-box'
        });
        selectMembers.init()

        document.getElementById('submit-form').addEventListener('click',function (e) {
            e.preventDefault();
            const formData = new FormData(document.getElementById('form-report-submit'));
            const formDataEntries = Object.fromEntries(formData);
            formDataEntries.members=JSON.stringify(selectMembers.value());
            // loading(1,e)
            fetchApi(`financial/transaction_report`, 'GET', {params:{ data: formDataEntries }}).then((res) => {
                if (res.status) {
                    createTable(res.data);
                } else {
                    toast('e', LNG_MSG_CONNECTION_ERROR, LNG_ERROR);
                }
                loading(0,e)
            }).catch(err=>{
                loading(0,e)
                toast('e', LNG_MSG_CONNECTION_ERROR, LNG_ERROR);
            })
        })
        function createTable(data) {
            let html = '';
            const keys = Object.keys(data);
            if (!keys.length) {
                html = `
                <tr>
                    <td colspan="6">
                        نتیجه ای یافت نشد
                    </td>
                </tr>
                `;
            }else{
                for (let index = 0; index < keys.length; index++) {
                    const element = data[keys[index]];

                    html += `
                        <tr>
                            <td class="fw-bold">
                                ${element.status}
                            </td>
                            <td class="fw-bold">
                                ${element.member}
                            </td>
                            <td class="fw-bold">
                                ${element.tracking_code}
                            </td>
                            <td class="fw-bold">
                                ${element.bank_code}
                            </td>
                            <td class="fw-bold">
                                ${element.bank_message}
                            </td>
                            <td class="fw-bold">
                                ${element.type}
                            </td>
                            <td class="fw-bold">
                                ${element.createAt}
                            </td>
                            <td class="fw-bold">
                                ${element.total_price}
                            </td>
                        </tr>
                    `;
                }
            }
            document.getElementById('table-orders').innerHTML = html;
            setTimeout(() => {
                // createPdf()

            }, 1000);
        }

       document.getElementById('get-excel').addEventListener('click',function (e) {
            e.preventDefault();
            loading(1,e)
            openIframe('excel');
            setTimeout(() => {
                loading(0,e)

            }, 2000);
       })
       function openIframe(type) {
            const formData = new FormData(document.getElementById('form-report-submit'));
            const formDataEntries = Object.fromEntries(formData);
            formDataEntries.members= selectMembers.value(true) ;
            const uri = JSON.stringify(formDataEntries) ;
            window.open($_url+"admin/report/transaction_"+type+"?filters="+uri,'_blank');
       }
    },
    category() {
 
        Treeview({
            type: 'product', 
            api: { 
                'list': 'report/product_statistics', 
            },
            actions:false
        }, 10);

    },
};
