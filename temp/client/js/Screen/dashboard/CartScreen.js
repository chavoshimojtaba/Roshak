
import { fetchApi } from '../../api';
import { loading, toast, toman } from '../../util/util';
export const CartScreen = {
	render() {
		console.log(561);
		const inpCode = document.querySelector('#inp_code');
		if (inpCode != null) {
			document.querySelector('#submit-code').addEventListener('click', function (e) {
				if (inpCode.value.length > 3) {
					loading(e,1)
					fetchApi('order/discount_code', 'GET', { params:{data: {code:inpCode.value}} }).then(async (res) => {
						if (res.status) {
							document.querySelector('#final-price-el').innerText = toman(document.querySelector('#final-price').value +  res.data.percent )+' تومان';
							document.querySelector('.off-text').innerText = res.data.percentToman;
							document.querySelector('.off-el').classList.remove('d-none');
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
					toast('e','کد وارد شده صحیح نمیباشد')
				}
			});
		}
	}
};