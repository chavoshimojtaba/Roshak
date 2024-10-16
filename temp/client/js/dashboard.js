import { Router } from "./Router_dashboard";
import { changeViewOnResize } from "./util/util";
changeViewOnResize();
// if (Dropzone) {
// 	Dropzone.autoDiscover = false; 
// } 
window.addEventListener('load', function () {
	Router();
	if (document.querySelector('.logout-btn') !== null) { 
		var myModal = new bootstrap.Modal(document.getElementById('modalexit'), {
			keyboard: false
		}) 
		$('.logout-btn').on('click', function () { 
			myModal.show();
			$('.nav-offcanvas').removeClass('open');
			$('.offcanvas-overlay').removeClass('on');
		}); 
		$('.modal-logout-btn').on('click', function () { 
			document.location.href = HOST+'out';
		}); 
	}
	if (document.querySelector('.hamburger#offCanvas') !== null) { 
		$('.hamburger#offCanvas').on('click', function () {
			$('.nav-offcanvas').addClass('open');
			$('.offcanvas-overlay').addClass('on');
		});
		setTimeout(() => { 
			$('.hamburger #offCanvasClose,.offcanvas-overlay').on('click', function () { 
				$('.nav-offcanvas').removeClass('open');
				$('.offcanvas-overlay').removeClass('on');
			});
		}, 2000);
	}
	if (document.querySelectorAll('.input-date-clear-btn input') !== null) { 
		var _els = document.querySelectorAll('.input-date-clear-btn input');
		for (var index = 0; index < _els.length; index++) {
			_els[index].setAttribute('autocomplete','off');
		}
	}
	if (document.querySelector('.hamburger#offCanvas') !== null) { 
		$('.hamburger#offCanvas').on('click', function () {
			$('.nav-offcanvas').addClass('open');
			$('.offcanvas-overlay').addClass('on');
		});
		setTimeout(() => { 
			$('.hamburger #offCanvasClose,.offcanvas-overlay').on('click', function () { 
				$('.nav-offcanvas').removeClass('open');
				$('.offcanvas-overlay').removeClass('on');
			});
		}, 2000);
	}
});

