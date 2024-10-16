import { parseRequestUrl } from './util/util'; 
import { FilesScreen }   from './Screen/FilesScreen'; 
import { MemberScreen }  from './Screen/MemberScreen';
import { PagesScreen }   from './Screen/PagesScreen';
import { RoleScreen }    from './Screen/RoleScreen';
import { SettingScreen } from './Screen/SettingScreen'; 
import { UserScreen }    from './Screen/UserScreen';
import { ProductScreen } from './Screen/ProductScreen'; 
import { TagScreen }     from './Screen/TagScreen'; 
import { ContentScreen } from './Screen/ContentScreen'; 

const routes = {
    //   '/': HomeScreen, 
    'content': ContentScreen, 
    'file': FilesScreen, 
    'tag': TagScreen, 
    'setting': SettingScreen,  
    'pages': PagesScreen,
    'role': RoleScreen, 
    'user': UserScreen,
    'member': MemberScreen, 
    'product': ProductScreen,
};
const request = parseRequestUrl();

export const Router = async () => {
	if (routes[request.class]) {
		const screen = routes[request.class];
        if (request.method !== '' && request.method != 'index') {
            await screen[request.method](...request.params);
        }else{
    		await screen.render(...request.params);
        }
		if (screen.after_render) await screen.after_render();
	}
};

export const reouter_method = ()=>{
    return request.method;
}
export const E_404 = ()=>{
    location.href = $_Burl +'err'
}