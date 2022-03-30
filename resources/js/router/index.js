import {createWebHistory, createRouter} from "vue-router";

import Home from '../pages/Home';
import Notfound from '../pages/Notfound';
import Register from '../pages/Register';
import Login from '../pages/Login';
import Category from '../pages/home/Category';
import Form from '../pages/home/Form';
import Organizations from '../pages/home/Organizations';
import Files from '../pages/home/File';


export const routes = [
    {
        name: 'home',
        path: '/',
        component: Home,
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
                name:'files',
                path:'/files/:id/org/:org_id',
                component: Files,
                props:true

            },
        ]
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
    if(openRoutes.includes(to.name)){
        next();
    }else if(window.Laravel.isLoggedin) {
        next();
    }
});

export default router;