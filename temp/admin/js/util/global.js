export const globalActions = ()=>{
	if (document.querySelector('.price-input')) {
		var _els = document.getElementsByClassName('price-input');
		for (var index = 0; index < _els.length; index++) {
			_els[index].addEventListener('input',function (e) {
				e.preventDefault();
				if (this.value || this.value === 0) {
					this.value = _toman(this.value);
				}
			});
		}
	}
}