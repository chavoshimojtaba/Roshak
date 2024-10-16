import { Authorization, isMobile, loading, owlCarousel, toast } from "../util/util";

export const PlanScreen = {
    render(){
       var _els = document.getElementsByClassName('plan-order');
	   for (var index = 0; index < _els.length; index++) {
		_els[index].addEventListener('click',function (e) {
			e.preventDefault();
				loading(e,1)
				if(document.querySelector('input[name="plans-checkbox"]:checked')  != null ){
					Authorization().then(res=>{
						const url = 'plan/bank/'+document.querySelector('input[name="plans-checkbox"]:checked').value;
						window.location.href = res.s ?HOST+url:HOST+'auth?callback='+url;
					});
				}else{
					toast('e', 'لطفا طرح مورد نظر را انتخاب کنید.')
					loading(e,0)
				}
			});
	   }
		if (isMobile()) {
			owlCarousel('.plans-slide',{
				items: 1,
				margin: 15,
				stagePadding: 100,
				loop: true,
				dots: !1,
				nav: !1,
				responsive: { 0: { items: 1 }, 1200: { items: 3 } },
				navText: [
					"<i class='d-block icon fs-4 icon-arrow-right-14'></i>",
					"<i class='d-block icon fs-4 icon-arrow-left4'></i>",
				],
			});
		}
    }
};