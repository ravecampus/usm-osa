import {createWebHistory, createRouter} from "vue-router";

import Home from '../pages/Home';
import Notfound from '../pages/Notfound';
import Register from '../pages/Register';
import Login from '../pages/Login';
import OrgsList from '../pages/home/OrgsList';
import FileDocs from '../pages/home/FileDoc';
import Department from '../pages/Department';


export const routes = [
    {
        name: 'home',
        path: '/',
        component: Home,
        children:[
            {
                name:'orgs',
                path:'',
                component: OrgsList
            },
            {
                name:'filedocs',
                path:'/file-docs/',
                component: FileDocs

            }
        ]
    },
    {
        name: 'department',
        path: '/department',
        component: Department
    },
    {
        name: 'register',
        path: '/register',
        component: Register
    },
    {
        name: 'login',
        path: '/login',
        component: Login
    },
    // {
    //     name: 'dashboard',
    //     path: '/dashboard',
    //     component: Dashboard
    // },
    {
        path: '/*',
        name:'/notfound',
        component: Notfound
    },

];



const router = createRouter({
    history: createWebHistory(),
    routes: routes,
});

const openRoutes = [
    'home', 
    'login',
    'register',
    'notfound'
];

router.beforeEach((to, from, next)=>{
    if(openRoutes.includes(to.name)){
        next();
    }else if(window.Laravel.isLoggedin) {
        next();
    }
});

export default router;