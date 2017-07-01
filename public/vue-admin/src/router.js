const routers = [
    {
        path: '/',
        meta: {
            title: ''
        },
        component: (resolve) => require(['./views/index.vue'], resolve)
    },{
        path: '/login',
        component: (resolve) => require(['./views/login.vue'], resolve)
        },{
            path: '/home',
            component: (resolve) => require(['./views/home.vue'], resolve),
            children: [{
            // 当 /user/:id/profile 匹配成功，
            // UserProfile 会被渲染在 User 的 <router-view> 中
            path: 'admin/menu',
                component: (resolve) => require(['./views/admin/menu.vue'], resolve)
        },{
            path: 'admin/user',
                component: (resolve) => require(['./views/admin/user.vue'], resolve)
        },{
            path: 'admin/role',
                component: (resolve) => require(['./views/admin/role.vue'], resolve)
        },{
            path: 'admin/log',
                component: (resolve) => require(['./views/admin/log.vue'], resolve)
        },{
            path: 'blog/edit',
            component: (resolve) => require(['./views/blog/edit.vue'], resolve)
        },
    ]},
    {
        path: '*',
        component: (resolve) => require(['./views/common/404.vue'], resolve)
    },
];
export default routers;