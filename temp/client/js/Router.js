import { parseRequestUrl } from './util/util';
import { AuthScreen } from './Screen/AuthScreen';
import { DesignersScreen } from './Screen/DesignersScreen';
import { SearchScreen } from './Screen/SearchScreen';
import { PScreen } from './Screen/PScreen';
import { PlanScreen } from './Screen/PlanScreen';
import { BCartScreen } from './Screen/dashboard/BCartScreen';
import { HomeScreen } from './Screen/HomeScreen';
import { AboutUsScreen } from './Screen/AboutUsScreen';
import { CooperationScreen } from './Screen/CooperationScreen';
import { ContactScreen } from './Screen/ContactScreen'; 
const routes = {
    'auth': AuthScreen ,
    '/':HomeScreen,
    'home':HomeScreen,
    'contact':ContactScreen,
    'about':AboutUsScreen,
    'p':PScreen,
    'cooperation':CooperationScreen,
    'search':SearchScreen,
    'bcart':BCartScreen,
    'plan':PlanScreen,
    'designers': DesignersScreen
};

const request = parseRequestUrl();

export const Router = async () => {
    if (routes[request.class]) {
        const screen = routes[request.class];
        if (screen[request.method] && request.method !== '' && request.method != 'index') {
            await screen[request.method](request.id);
        }else if(!isNaN(request.method)){
            await screen._render(request.method);
        }else if(screen._render){
            await screen._render();
        }else if(screen.render){
    		await screen.render(request.id);
        }
		if (screen.after_render) await screen.after_render();
	}
};

export const reouter_method = ()=>{
    return request.method;
}

export const E_404 = ()=>{
    location.href = HOST +'err'
}
