
import { CommentScreen } from './Screen/dashboard/CommentScreen';
    import { FavoriteScreen } from './Screen/dashboard/FavoriteScreen';
    import { HomeScreen } from './Screen/dashboard/HomeScreen';
    import { NotificationScreen } from './Screen/dashboard/NotificationScreen';
import { Settlement_requestsScreen } from './Screen/dashboard/Settlement_requestsScreen';
import { ProductScreen } from './Screen/dashboard/ProductScreen';
import {  ProfileScreen } from './Screen/dashboard/ProfileScreen';
import {  TicketScreen } from './Screen/dashboard/TicketScreen';
import { parseRequestUrl } from './util/util';
import { DownloadScreen } from './Screen/dashboard/DownloadScreen';
import { PlanScreen } from './Screen/dashboard/PlanScreen';

    const routes = {
        'favorite':FavoriteScreen,
        'comment':CommentScreen,
        'profile':ProfileScreen,
        'plan':PlanScreen,
        'product':ProductScreen,
        'notification':NotificationScreen,
        'settlement_requests':Settlement_requestsScreen,
        'download':DownloadScreen,
        'ticket':TicketScreen,
        'home':HomeScreen,
    };

    const request = parseRequestUrl();
    export const Router = async () => {
        if (routes[request.class]) {
            const screen = routes[request.class];
            if (screen[request.method] && request.method !== '' && request.method != 'index') {
                await screen[request.method](request.id);
            }else if(!isNaN(request.method)){
                await screen._render(request.method);
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
