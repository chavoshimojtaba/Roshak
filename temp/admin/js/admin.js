import { Router } from "./Router";
import { globalActions } from "./util/global";
window.addEventListener('load', function (e) {
	Router();
	globalActions();
});

// window.addEventListener('hashchange', router);
