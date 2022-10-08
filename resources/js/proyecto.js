
require('./bootstrap');

window.Vue = require('vue').default;

//importar componentes
Vue.component('contenido-component', require('./components/Contenido.vue').default);
Vue.component('categoria-component', require('./components/Categoria.vue').default);
Vue.component('prueba-component', require('./components/Prueba.vue').default);
Vue.component('articulo-component', require('./components/Articulo.vue').default);
Vue.component('cliente-component', require('./components/Cliente.vue').default);
Vue.component('proveedor-component', require('./components/Proveedor.vue').default);
Vue.component('rol-component', require('./components/Rol.vue').default);
Vue.component('usuario-component', require('./components/Usuario.vue').default);
Vue.component('ingreso-component', require('./components/Ingreso.vue').default);
Vue.component('venta-component', require('./components/Venta.vue').default);
const app = new Vue({
    el: '#proyecto',
    data:{
        menu:0,
    },
    methods:{
       
 
    },

});
