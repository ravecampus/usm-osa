import {createWebHistory, createRouter} from "vue-router";

import Home from '../pages/Home';
import Dashboard from '../pages/Dashboard';
import Notfound from '../pages/Notfound';
import Register from '../pages/Register';
import Report from '../pages/Report';
import Login from '../pages/Login';
import Category from '../pages/home/Category';
import Form from '../pages/home/Form';
import Organizations from '../pages/home/Organizations';
import ArchOrg from '../pages/home/ArchOrg';
import Profile from '../pages/home/Profile';
import Files from '../pages/home/File';
import Listfiles from '../pages/home/ListofFile';
import Archive from '../pages/home/Archive';
import Users from '../pages/home/Users';


export const routes = [
    {
        name: 'home',
        path: '/',
        component: Home
    },
    {
        name: 'dashboard',
        path: '/dashboard/',
        component: Dashboard,
        children:[
            {
                name:'category',
                path:'',
                component: Category
            },
            {
                name:'fillupform',
                path:'/fillup-form/:id',
                component: Form,
                params:true

            },
            {
                name:'orgs',
                path:'/orgs/:id',
                component: Organizations,
                params:true,

            },
            {
                name:'arcorg',
                path:'/orgs/:id/archive',
                component: ArchOrg,
                params:true,

            },
            {
                name:'files',
                path:'/files/:id/org/:org_id',
                component: Files,
                props:true

            },
            {
                name:'listfiles',
                path:'/files/:id/org/:org_id/list',
                component: Listfiles,
                props:true

            },
            {
                name:'archive',
                path:'/archive/',
                component: Archive,

            },
        ]
    },
    {
        name: 'profile',
        path: '/profile/:id',
        component: Profile
    },
    {
        name: 'users',
        path: '/users/',
        component: Users
    },
    {
        name: 'reports',
        path: '/reports/',
        component: Report
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
        path: '/:pathMatch(.*)*',
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
    if(openRoutes.includes(to.name) || window.Laravel.isLoggedin){
        next();
    }
});

export default router;