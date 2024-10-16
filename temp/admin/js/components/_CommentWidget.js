import { actionAlert, delAlert, loading, ratingStars, toast } from '../util/util';
import {Pagination} from './_Pagination';
import { Validator } from '../util/Validator';
import { fetchApi } from '../api';
import { LNG_ERROR, LNG_MSG_CONNECTION_ERROR, LNG_MSG_SUCCESS, LNG_SUCCESS } from '../util/consts';

function CommentWidget(options) {
    const _this = this;
    let selectors = {
        inputs:{}
    };
    
    let dataRows = {};
    let widgetEl;
    let page = 1;
    let totalCount = 0;
    let editmodal = '';
    let modal = '';
    let defaultOptions = {
        id: 'widget-comments',
        afterRender:'',
        showReplyBtn:true,
		showMoreBtn:true,
        rateAvg:1,
        perPage:10
    };

    options = { ...defaultOptions, ...options };

    this.render = function () {
        widgetEl = document.getElementById(`${options.id}`);
        selectors.submit_comment = document.getElementById('submit_comment');
        selectors.submit_edit = document.getElementById('submit_edit');
        selectors.editmodal = document.getElementById('editcommentmodal');
        selectors.replyModal = document.getElementById('replyModal');
        editmodal = new bootstrap.Modal(selectors.editmodal);
        modal = new bootstrap.Modal(selectors.replyModal);
        selectors.form = document.getElementById('form-comment-submit');
        selectors.formedit = document.getElementById('form-edit-submit');
        this.createTemplate();
    };

    this.rerender = function () {
        this.fetchHandler(()=>{
            Pagination.Init(document.getElementById('pager'), {
                size: totalCount, // pages size
                limit: options.perPage, // limit per page
                page,  // selected page
                clickHandler:this.fetchHandler,
                step: 3   // pages before and after current
            });
        });
    };

    this.createTemplate = function () {
        selectors.widgetContainer = document.createElement('div');
        selectors.widgetContainer.setAttribute('class', 'comment-widgets');
        widgetEl.appendChild(selectors.widgetContainer);
		const pagerEl = document.createElement('div');
		pagerEl.id = 'pager';
        pagerEl.setAttribute('class', 'mt-3');
		widgetEl.appendChild(pagerEl);
        selectors.inputs['pub_id'] = document.getElementById('pub_id');
        selectors.inputs['inp_pbid'] = document.getElementById('inp_pbid');
        selectors.inputs['inp_text'] = document.getElementById('inp_text');
        this.fetchHandler(()=>{
            Pagination.Init(document.getElementById('pager'), {
                size: totalCount, // pages size
                limit: options.perPage, // limit per page
                page,  // selected page
                clickHandler:this.fetchHandler,
                step: 3   // pages before and after current
            });
        });
        _this.handleOnIinitialOnItemClick()

    };

    this.createRows = function () {
        let widtgetItems = '';
        dataRows.map((item, itemIndex) => {
			let status,rate='', ans='',btns='',parentClass='';
			if (options.showMoreBtn) {
				btns = `
					<a href="${$_Burl}comment/view/${options.type}/${item.id}" action="more" key="${itemIndex}" class="ps-3 d-flex "><i class="ti-eye text-primary"></i></a>
				`;
			}
            //console.log(item    );
            btns += `
            <a href="javascript:void(0)" action="edit" key="${itemIndex}" class="ps-3 d-flex table-action-btn "><i class="ti-pencil text-info"></i></a> `;
            if (item.publish == 'publish' || item.mid === '0') {
				status = '<span class=" badge bg-light-success text-success rounded-pill font-weight-medium fs-1 py-1">Approved</span>';
			}else if (item.publish == 'reject') {
				status = '<span class=" badge bg-light-danger text-danger rounded-pill font-weight-medium fs-1 py-1">Rejected</span>';
			}else {
				btns += `
				<a href="javascript:void(0)" action="approve" key="${itemIndex}" class="ps-3 d-flex table-action-btn "><i class="ti-check text-success"></i></a>
				<a href="javascript:void(0)" action="reject" key="${itemIndex}" class="ps-3 d-flex table-action-btn" data-resource="comment"><i class="ti-close text-danger" ></i></a>`;
				status = '<span class=" badge bg-light-warning text-warning rounded-pill font-weight-medium fs-1 py-1">Pending</span>';
			}
            if (item.pid == 0) {
                parentClass = 'comment-row--parent';
                if (item.publish == 1 && options.showReplyBtn) {
                    btns +=`<a href="javascript:void(0)" action="reply" class="ps-3 d-flex table-action-btn" key="${itemIndex}"><i class="fas fa-reply text-primary" ></i></a>`
                }
			}
            let bg = '';
            if (item.uid > 0) {
                bg = ' bg-light-primary';
			}

			if (item.ans) {
				ans = `
					<p class="mt-2 pt-2 fs-2 text-muted border-top">
						آخرین پاسخ : ${item.ans}
					</p>
				`;
			}

			if (parseInt(item.rate) > 0) {
                // rate = ratingStars(item.rate);
                rate ='';
			}

			return widtgetItems += `
				<div class="d-flex flex-row comment-row border-bottom ${parentClass+bg}">
						<div class="p-2">
							<span><img src="${$_url}${item.img}" class="rounded-circle" alt="user" width="50" height="50" /></span>
						</div>
						<div class="comment-text w-100 p-3">
							<h5 class="font-weight-medium d-flex justify-content-between align-items-center">
                                ${item.full_name}
                                ${rate}
                            </h5>
							<p class="mb-1 fs-3 text-muted">
								${item.text}
							</p>
							<div class="comment-footer mt-2">
								<div class="d-flex align-items-center">
									${status}
									<span class="action-icons d-flex">
										${btns}
										<a href="javascript:void(0)" action="del" key="${itemIndex}" class="ps-3 d-flex table-action-btn" data-resource="comment"><i class="ti-trash text-danger" ></i></a>
									</span>
                                    <span class=" text-muted ms-auto fw-normal fs-2 d-block   text-end">${item.createAt}</span>
								</div>
							</div>
							${ans}
						</div>
					</div>
			`;
        });
        const container = widgetEl.querySelector('.comment-widgets');
        container.innerHTML = widtgetItems;
        this.handleOnItemClick();
    };

    this.emptyRow = function () {
        let widtgetItems = `
            <div class=" alert customize-alert m-2 p-2   border-danger text-danger fade show remove-close-icon" role="alert">
                <div class=" d-flex align-items-center font-weight-medium me-3 me-md-0">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-info text-danger feather-sm me-2"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="16" x2="12" y2="12"></line><line x1="12" y1="8" x2="12" y2="8"></line></svg>
                    دیدگاهی یافت نشد
                </div>
            </div>
        `;
        widgetEl.querySelector('.comment-widgets').innerHTML = widtgetItems;
    };

    this.handleOnItemClick = function () {
        const btns = document.getElementsByClassName('table-action-btn')
        for (let index = 0; index < btns.length; index++) {
            const btn = btns[index];
            btn.addEventListener('click',async function (e) {
                e.preventDefault();
                const action = this.getAttribute('action');
                const key = this.getAttribute('key');
                if (action === 'approve') {
                    const res =await actionAlert('comment/'+dataRows[key].id,'تایید نظر','از تایید نظر اطمینان دارید؟',{
						publish:1,
                        type:options.type
					},'success');
                    if (res && !res.err) {
                        _this.fetchHandler();
                    }

                }else if (action === 'reject') {
                    const res =await actionAlert('comment/'+dataRows[key].id,'رد نظر','از رد کردن نظر اطمینان دارید؟',{
						publish:2,
                        type:options.type
					});
                    if (res && !res.err) {
                        _this.fetchHandler();
                    }

                }else if (action === 'del') {
					selectors.inputs['pub_id'].value = dataRows[key].id;
					const res =await delAlert('comment',' دیدگاه','del_comment_'+options.type);

                    if (res && !res.err) {
                        _this.fetchHandler();
                    }
                }else if (action === 'reply') {
                    console.log(dataRows[key]);
					selectors.inputs['pub_id'].value = dataRows[key].id;
					selectors.inputs['inp_pbid'].value = dataRows[key].pbid;
                    modal.show();
                }else if (action === 'edit') {
                    //console.log(dataRows[key]);
					selectors.inputs['pub_id'].value = dataRows[key].id;
					selectors.inputs['inp_text'].value = dataRows[key].text;
                    editmodal.show();
                }  else {
                    options.actions[action](dataRows[key],e);
                }
            });
        } 
        
    };
    this.handleOnIinitialOnItemClick = function () {
       
        if (document.querySelector('#replyModalBtn')) {
            document.querySelector('#replyModalBtn').addEventListener('click',function(e) {
                e.preventDefault()
                selectors.inputs['pub_id'].value = options.commentId;
                selectors.inputs['inp_pbid'].value = dataRows[0].pbid;
                modal.show();
            });
        }
        selectors.submit_comment.addEventListener('click', function (e) {
            var valid = Validator('form-comment-submit').validate();
            if (valid) {
                const formObj = new FormData(selectors.form);
                formObj.append('pid',selectors.inputs['pub_id'].value);
                formObj.append('type',options.type);

                formObj.append('pbid',selectors.inputs['inp_pbid'].value);
                const formData = Object.fromEntries(formObj);
                loading(1,e)
                fetchApi('comment/reply', 'POST', { body: formData }).then((res) => {
                    modal.hide();
                    selectors.form.reset();
                    if (res.status) {
                        toast('s', LNG_MSG_SUCCESS, LNG_SUCCESS);
                        _this.fetchHandler(()=>{
                            Pagination.Init(document.getElementById('pager'), {
                                size: totalCount, // pages size
                                limit: options.perPage, // limit per page
                                page,  // selected page
                                clickHandler:_this.fetchHandler,
                                step: 3   // pages before and after current
                            });
                        });
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
        if (!selectors.submit_edit) return;
        selectors.submit_edit.addEventListener('click', function (e) {
            var valid = Validator('form-edit-submit').validate();
            console.log(valid);
            if (valid) {
                const formObj = new FormData(selectors.formedit);

                const formData = Object.fromEntries(formObj);
                loading(1,e)
                fetchApi('comment/edit/'+selectors.inputs['pub_id'].value, 'PUT', { body: formData }).then((res) => {
                    modal.hide();
                    selectors.form.reset();
                    if (res.status) {
                        toast('s', LNG_MSG_SUCCESS, LNG_SUCCESS);
                        _this.fetchHandler(()=>{
                            Pagination.Init(document.getElementById('pager'), {
                                size: totalCount, // pages size
                                limit: options.perPage, // limit per page
                                page,  // selected page
                                clickHandler:_this.fetchHandler,
                                step: 3   // pages before and after current
                            });
                        });
                        editmodal.hide();

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
    };

    this.fetchHandler = function (callback) {
        if (typeof callback === 'number') {
            page=callback;
        }
        loading(1);
        options.api(page, options.perPage,totalCount).then((res) => {
			if (res.error) {
				// return res.code;
			}
            const { data, total } = res;
            totalCount = (total)?total:1;
            if (total == '0') {
                _this.emptyRow();
            }else{
                dataRows = data;
                _this.createRows();
                if (typeof callback === 'function') {
                    callback()
                }
            }
            loading(0);
            return res
        }).then(res=>{
            if (typeof options.afterRender === 'function') {
                options.afterRender(res)
            }
            loading(0);
        })
    };

    this.render();
}

export { CommentWidget };
