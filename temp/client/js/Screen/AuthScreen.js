import { delToken, fetchApi, setToken } from '../api';
import { Validator } from '../../../util/util';
import { alertBox, isMobile, loading, parseRequestUrl, toast } from '../util/util';

export const AuthScreen = {
	render() {
		let backUrl = '';
		const request = parseRequestUrl(); 
		
		if (request.params != 'undefined' && request.params.callback != 'undefined') {
			backUrl = request.params.callback;
		} 

		delToken();
		backUrl = backUrl || '';
		let mobile;
		const mobileForm = document.getElementById('form-login');
		const passwordForm = document.getElementById('form-password');
		const verifyForm = document.getElementById('form-verify');
		const loginBox = document.getElementById('box-login');
		const passwordBox = document.getElementById('box-password');
		const verifyBox = document.getElementById('box-verify');
		
		function Timer(defTime) {
			let timeouts = [];
			let secondsRemaining;
			let time;
			let timer_new = document.querySelector('.timer_new');
			let timerContainer = document.querySelector('.timer_timer');
			const timerSelector = document.getElementById('timer-alert');
			let timerId;
			this.start = function () {
				time = defTime;
				secondsRemaining = defTime * 60;
				timer_new.classList.add('d-none');
				timerContainer.classList.remove('d-none');
				timerId = setInterval(this.countdown, 1000);
				timeouts.push(timerId);
			}
			this.countdown = function () {
				var min = Math.floor(secondsRemaining / 60);
				var sec = secondsRemaining - (min * 60);
				if (sec < 10) {
					sec = "0" + sec;
				}
				var message = min.toString() + ":" + sec;
				if (secondsRemaining === 0) {
					clearTimeout(timerId);
					timer_new.classList.remove('d-none');
					timerContainer.classList.add('d-none');
				} else {
					timerSelector.innerHTML = message;
				}
				secondsRemaining--;
			}
			this.clear_timer = function () {
				timerSelector.classList.remove('hide')
				for (var i = 0; i < timeouts.length; i++) {
					clearTimeout(timeouts[i]);
				}
				timerSelector.innerHTML = 0;
			}

		}
		
		const timer = new Timer(2);//2 min
		document.querySelector('.back-btn').addEventListener('click', function (e) {
			passwordBox.classList.add('d-none');
			verifyBox.classList.add('d-none');
			loginBox.classList.remove('d-none');
		})
		
		const inpCodes = document.getElementsByName('code[]');
		for (var index = 0; index < inpCodes.length; index++) {
			inpCodes[index].addEventListener('input', function (e) {
				e.preventDefault(); 
				if (e.currentTarget.value.length >= 1) {
					if (e.currentTarget.getAttribute('index') == 3) {
						document.getElementById('submit_code').focus()
					} else if (e.currentTarget.getAttribute('index') != 3) {
						inpCodes[parseInt(e.currentTarget.getAttribute('index')) + 1].focus();
						
					}
				}
			});
			inpCodes[index].addEventListener('focus', function (e) {
				e.preventDefault();
				e.currentTarget.value = '';
			});
		}

		document.getElementsByName('code[]')[3].addEventListener('keyup', function (e) {
			e.preventDefault();
			if (!isMobile()) { 
				let code = '';
				let _els = document.getElementsByName('code[]');
				for (let index = 0; index < _els.length; index++) {
					code += _els[index].value;
				}
				if (code.length === 4 && document.querySelector('.register-inputs').classList.contains('d-none')) {
					submit_code(document.getElementById('submit_code'));
				}
			}
		});
		
		document.getElementById('submit_code').addEventListener('click', submit_code);
		function submit_code(e) {
			var _Pristine = Validator('form-verify');
			const valid = _Pristine.validate();
			if (valid) {
				loading('#submit_code', 1);
				let code = '';
				var _els = document.getElementsByName('code[]');
				for (var index = 0; index < _els.length; index++) {
					code += _els[index].value;
				}
				let data = new FormData(verifyForm);
				data.append('code', code); 
				data.append('mobile', mobile);
				data.append('designer', document.getElementById('inp_designer').value);
				fetchApi('auth/verify', 'POST', { body: Object.fromEntries(data) }).then(async (res) => {  
					if (res.status) {
						await setToken(res.data.token, res.data);
						document.location.href = HOST + backUrl;
					} else {
						document.getElementsByName('code[]')[0].focus()
						throw new Error(res.msg);
					}
				}).catch(err => {	
					verifyForm.reset();
					_Pristine.reset();
					// grecaptcha.reset();
					loading('#submit_code', 0)
					toast('e', err.message); 
				}) 
			}
			else {
				console.log(262); 
				verifyForm.reset();
				toast('e', 'کد معتبر نمیباشد');
				loading(e, 0);
			}
		}
		
		document.getElementById('submit_password').addEventListener('click', submit_password);
		function submit_password(e) {
			var valid = Validator('form-password').validate();
			if (valid) {
				let data = new FormData(passwordForm);
				data.append('mobile', mobile);
				loading('#submit_password', 1);
				fetchApi('auth/password', 'POST', { body: Object.fromEntries(data) }).then(async (res) => {
					verifyForm.reset();
					if (res.status) {
						await setToken(res.data.token, res.data);
						document.location.href = HOST + backUrl;
					} else {
						throw new Error(res.msg);
					}
					loading('#submit_password', 0);
				}).catch(err => {
					loading('#submit_password', 0)
					alertBox('e', err.message, 'form-password');
				})
			} else {
				toast('e', 'رمز عبور معتبر نمیباشد');
			}
		}

		document.querySelector('.timer_new-btn').addEventListener('click', function (e) {
			e.preventDefault();
			timer.clear_timer();
			if (!mobile) {
				verifyBox.classList.add('d-none');
				passwordBox.classList.add('d-none');
				loginBox.classList.remove('d-none');
				toast(langs.invalidMobile, 'e');
				return
			}
			loading(e, 1);
			fetchApi('auth/resend_code', 'POST', { body: { mobile } }).then((res) => {
				if (res.status) {
					timer.start();
				} else {
					throw new Error(res.msg);
				}
				loading(e, 0);
			}).catch(err => {
				console.log(err);
				loading(e, 0)
				toast(err.message, 'e');
			})
		});

		document.querySelector('.timer_new-edit').addEventListener('click', function (e) {
			e.preventDefault();
			timer.clear_timer();
			passwordBox.classList.add('d-none');
			verifyBox.classList.add('d-none');
			loginBox.classList.remove('d-none');
		});

		document.querySelector('#login-by-pass').addEventListener('click', function (e) {
			e.preventDefault();
			// timer.clear_timer();
			passwordBox.classList.remove('d-none');
			verifyBox.classList.add('d-none');
			loginBox.classList.add('d-none');
		});

		document.querySelector('#login-by-code').addEventListener('click', function (e) {
			e.preventDefault();
			// timer.clear_timer();
			passwordBox.classList.add('d-none');
			verifyBox.classList.remove('d-none');
			loginBox.classList.add('d-none');
		});

		function submit_mobile(e) {
			e.preventDefault();
			var valid = Validator('form-login').validate();
			if (valid) {
				let data = new FormData(mobileForm);
				loading('#submit_mobile', 1);
				fetchApi('auth/login', 'POST', { body: Object.fromEntries(data) }).then((res) => {
					if (res.status) {
						mobile = document.getElementById('inp_mobile').value;
						mobileForm.reset();
						if (res.data === 'deactive') {
							toast('e', res.msg);
						}else{
							if (res.data == 'incomplete') {
								document.getElementById('inp_name').setAttribute('required', 'required');
								document.getElementById('inp_family').setAttribute('required', 'required');
								document.querySelector('.register-inputs').classList.remove('d-none');
							} else {
								document.getElementById('inp_name').removeAttribute('required');
								document.getElementById('inp_family').removeAttribute('required');
								document.querySelector('.register-inputs').classList.add('d-none');
							}
							if (res.has_pass) {
								document.querySelector('#login-by-pass').classList.remove('d-none');
							} else {
								document.querySelector('#login-by-pass').classList.add('d-none');
							}
							timer.start();
							passwordBox.classList.add('d-none');
							loginBox.classList.add('d-none');
							verifyBox.classList.remove('d-none');
							document.getElementsByName('code[]')[0].focus();
						}
					} else {
						throw new Error('');
					}
					loading('#submit_mobile', 0);
				}).catch(err => { 
					loading('#submit_mobile', 0) 
				})
			}
		}
		
		document.getElementById('submit_mobile').addEventListener('click', submit_mobile);
		document.getElementById('inp_mobile').addEventListener('keyup', function (e) {
			e.preventDefault(); 
			if (e.key === 'Enter' && !isMobile()) {
				submit_mobile(e);
			}
		});

	}
};