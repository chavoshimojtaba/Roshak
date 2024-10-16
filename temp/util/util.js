export const Validator = (id)=>{
	var form = document.getElementById(id);
	return new Pristine(form);
}
export function clearClass(className, elements) {
    for (const element of elements) {
        element.classList.remove(className);
    }
}