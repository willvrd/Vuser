export default [
    {
        path: '',
        name: 'admin.user.users.index',
        component: require('./pages/user-spa.vue').default,
    },
    {
        path: '/backend/user/users/spa/form',
        name: 'admin.user.users.spa.form',
        component: require('./pages/user-form.vue').default,
    },

];
