import { fetchApi } from '../api';
import { Validator } from '../../../util/util';
import { loading, toast } from '../util/util';

export const ContactScreen = {
    render(){
		const verifyForm = document.querySelector('#form-contact-submit');
		verifyForm.querySelector('#submit-contact').addEventListener('click', function  (e) {
			e.preventDefault();
			var valid = Validator('form-contact-submit').validate();
			if (valid) {
				let data = new FormData(verifyForm);
				loading('#submit-contact',1);
				fetchApi('util/member_message', 'POST', { body:  Object.fromEntries(data)}).then(async (res) => {
					if (res.status) {
						toast('s','پیام شما با موفقیت ثبت شد')
						verifyForm.reset();
					} else {
						throw new Error(res.msg);
					}
					loading('#submit-contact',0);

				}).catch(err=>{
					toast('e',err.message)

				})
			}
		})
    }
};