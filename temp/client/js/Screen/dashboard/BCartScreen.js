
import { fetchApi } from '../../api';
import { loading, toast, toman } from '../../util/util';
export const BCartScreen = {
	render() {
		const inpCode = document.querySelector('#inp_code');
		if (inpCode != null) {
			document.querySelector('#submit-code').addEventListener('click', function (e) {
				if (inpCode.value.length > 3) {
					loading(e,1)
					fetchApi('order/discount_code', 'GET', { params:{data: {code:inpCode.value}} }).then(async (res) => {
						if (res.status) {
							const mainPrice = parseInt(document.querySelector('#final-price').value);
							if ((res.data.percent * mainPrice) > 0) { 
								const percentToman = (res.data.percent * mainPrice );
								const finalP = mainPrice - percentToman ; 
								document.querySelector('#final-price-el').innerText = toman(finalP)+' تومان';
								document.querySelector('.off-text').innerText = toman(percentToman)+ ' تومان';
								document.querySelector('.off-el').classList.remove('d-none');
							}else{
								inpCode.value = '';
								document.querySelector('.off-el').classList.add('d-none');
								toast('e','کد وارد شده صحیح نمیباشد');
							}
						} else {
							inpCode.value = '';
							document.querySelector('.off-el').classList.add('d-none');
							toast('e','کد وارد شده صحیح نمیباشد');
						}
						loading(e,0);
					}).catch(err => {
						toast('e','کد وارد شده صحیح نمیباشد');
					})
				}else{
					toast('e','کد صحیح را وارد کنید')
				}
			});
		}
		var _els = document.getElementsByClassName('del-cart-item'); 
		if (_els.length > 0) {
			for (var index = 0; index < _els.length; index++) {
				_els[index].addEventListener('click',function (e) {
					e.preventDefault(); 
					document.location.href = HOST+'bcart/remove_'+this.getAttribute('data-id');
				});
			}
		}
	}
};