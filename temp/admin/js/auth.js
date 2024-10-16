import { AuthScreen } from './Screen/AuthScreen'; 


const Router = async () => { 
	await AuthScreen.render();
};

window.addEventListener('load', Router);
