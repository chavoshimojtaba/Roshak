import { fetchApi } from "../api";
import { Authorization, copyToClipboard, loading, toast } from "./util";

export function productActionBtns() {
	const items = document.querySelectorAll('.product-action-btn');
	if (items && items.length > 0) {
		for (let index = 0; index < items.length; index++) {
			items[index].addEventListener('click', function (e) {
				e.preventDefault();
				const action = this.getAttribute('data-action');
				const value = this.getAttribute('data-id');
				let url, method = 'POST', msg;
				const _this = this;
				switch (action) {
					case 'share':
						if (this.closest('a') != null) {
							copyToClipboard(this.closest('a').getAttribute('href'));
						}else{
							copyToClipboard(window.location.href);
						}
						toast('s', 'لینک محصول در حافظه کلیبورد ذخیر شد');
						break;
					case 'wishlist': 
						const member = Authorization(); 
						member.then(res => {
							if (res.s) {
								loading(e, 1)
								url = 'add_to_favorites';
								msg = langs.addedToFavorites;
								fetchApi('member/add_to_favorites', method, method == 'GET' ? { params: { id: value } } : { body: { id: value } }).then((res) => {  
									if ('status' in res ) {
										if (res.status) {
											if (action === 'wishlist' && _this.querySelector('i')) { 
												if (res.data == 'del') {
													_this.querySelector('i').setAttribute('class', 'icon icon-header-2 fs-4');
												}else{
													_this.querySelector('i').setAttribute('class', 'icon icon-heart-bold float-start text-danger fs-4'); 
												}
											} else if (action === 'detail') {
												toast('e')
											} 
										} else {
											throw new Error('');
										}
									}
									loading(e, 0);
								}).catch(err => { 
									toast('e');
									loading(e, 0)
								})
							} else {
								toast('n');
							}
						});
						return

						break;
				}
			}, false);
		}
	}
}


