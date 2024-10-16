
export const Validator = (id)=>{
	var form = document.getElementById(id); 
	return new Pristine(form);
}
export const editorValidation = (id)=>{
	var el = document.getElementById(id);
	el.classList.add('need_validation');
	setTimeout(() => {
		el.classList.remove('need_validation');
	}, 5000);
}