<template>
      <main class="main">
    <!-- Breadcrumb -->
    <ol class="breadcrumb">
        <!-- <li class="breadcrumb-item">Home</li>
        <li class="breadcrumb-item"><a href="#">Admin</a></li>
        <li class="breadcrumb-item active">Dashboard</li> -->
        <li class="breadcrumb-item"><a href="/">Escritorio</a></li>
    </ol>
    <div class="container-fluid">
        <!-- Ejemplo de tabla Listado -->
        <div class="card">
            <div class="card-header">
                <i class="fa fa-align-justify"></i> Ingresos
                <!-- en el metodo abrir modal envio 2 elementos -->
                <button type="button" class="btn btn-secondary" v-on:click="motrarDetalle()">
                    <i class="icon-plus"></i>&nbsp;Nuevo
                </button>
            </div>
            <!-- listado -->
            <template v-if="listado==1">
            <div class="card-body">
                <div class="form-group row">
                    <div class="col-md-6">
                        <div class="input-group">
                            <select class="form-control col-md-3" v-model="criterio">
                              <option value="tipo_documento">Tipo comprobante</option>
                              <option value="num_comprobante">Num comprobante</option>
                              <option value="fecha_hora">Direccion</option>
                            </select>
                            <input type="text" class="form-control" placeholder="Texto a buscar" v-model="buscar" 
                            @keyup.enter="listarIngresos(1, criterio, buscar)">
                            <button type="button" @click="listarIngresos(1, criterio, buscar)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                        </div>
                    </div>
                </div>
                <div class="table-responsive">
                <table class="table table-bordered table-striped table-sm">
                    <thead>
                        <tr>
                            <th>Opciones</th>
                            <th>Usuario</th>
                            <th>Proveedor</th>
                            <th>Tipo comprobante</th>
                            <th>Serie comprobante</th>
                            <th>Num comprobante</th>
                            <th>Fecha_Hora</th>
                            <th>Total</th>
                            <th>Impuesto</th>
                            <th>Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- cnsidrar la :key  para identificar la id de la table-->
                        <tr v-for="ingreso in ArrayIngreso" :key="ingreso.id_ingreso">
                            <td>
                                <button type="button" class="btn btn-success btn-sm" v-on:click="verIngreso(ingreso.id_ingreso)" >
                                  <i class="icon-eye" ></i>
                                </button> &nbsp;

                                 <!-- //si la categoria.condicion es 1 -->
                                <template v-if="ingreso.estado=='Registrado'">
                                    <button type="button" class="btn btn-danger btn-sm" v-on:click="anularIngreso(ingreso.id_ingreso)">
                                        <i class="icon-trash"></i>
                                    </button>
                                </template>         
                            </td>
                            <td v-text="ingreso.usuario"></td>
                            <td v-text="ingreso.nombre"></td>
                            <td v-text="ingreso.tipo_documento"></td>
                            <td v-text="ingreso.serie_comprobante"></td>
                            <td v-text="ingreso.num_comprobante"></td>
                            <td v-text="ingreso.fecha_hora"></td>
                            <td v-text="ingreso.total"></td>
                            <!-- <td v-text="usuario.id_rol"></td> -->
                            <td v-text="ingreso.impuesto"></td>
                            <td>{{ingreso.estado}}</td>
                        </tr>
                      
                    </tbody>
                </table>
                </div>
                <nav>
                    <!-- aplicar paginacion -->
                    <ul class="pagination">
                        <li class="page-item" v-if="pagination.current_page > 1">
                            <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page - 1, criterio, buscar)">Ant</a>
                        </li>

                        <li class="page-item " v-for="page in pagesNumber" :key="page" :class="[page == isActived ? 'active' : '']">
                            <a class="page-link" href="#" @click.prevent="cambiarPagina(page,criterio, buscar)" v-text="page"></a>
                        </li>
                     
                        <li class="page-item" v-if="pagination.current_page < pagination.last_page">
                            <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page + 1, criterio, buscar)">Sig</a>
                        </li>
                    </ul>
                </nav>
            </div>
            </template>
            <!-- fin de listado -->
            <!-- detalles -->
            <template v-else-if="listado==0">
            <div class="card-body">
                <div class="form-group row border">
                     <!-- inicio contenido inputs -->
                    <div class="col-md-9">
                        <div class="form-group">
                            <label>Proveedor(*)</label>
                            <v-select
                            @search="selectProveedor"
                            label="nombre"
                            :options="arrayProveedor"
                            placeholder="Buscar proveedor"
                            v-on:Change="getDatosProveedor"
                            ></v-select>
                        </div>
                    </div>

                    <div class="col-md-3">
                         <label>Impuesto(*)</label>
                         <input type="text" class="form-control" v-model="impuesto">
                    </div>

                    <div class="col-md-4">
                        <label>Tipo comprobante(*)</label>
                         <select class="form-control" v-model="tipo_documento">
                             <option value="0">Selecione</option>
                             <option value="BOLETA">Boleta</option>
                             <option value="FACTURA">Factura</option>
                             <option value="TICKET">Ticket</option>
                        </select>
                    </div>

                    <div class="col-md-4">
                         <label>Serie comprobante</label>
                         <input type="text" class="form-control" v-model="serie_comprobante" placeholder="000X">
                    </div>

                    <div class="col-md-4">
                         <label>Num comprobante (*)</label>
                         <input type="text" class="form-control" v-model="num_comprobante" placeholder="000X">
                    </div>

                    <div class="col-md-12">
                        <!-- mensaje de validacion -->
                        <div v-show="errorIngreso" class="form-group row div-error">
                            <div class="text-center text-error">
                                <div v-for="error in errormensaje" :key="error" v-text="error">

                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- fin contenido inputs -->
                </div>

                <div class="form-group row border">
                    <div class="col-md-6">
                        <div class="form-group">
                             <label>Articulo  <span style="color:red" v-show="id_articulo==0">(*Selecione)</span> </label>
                             <div class="form-inline">
                                 <input type="text" class="form-control" v-model="codigo" @keyup.enter="buscarArticulo()" placeholder="Producto">
                                <button class="btn btn-primary" @click="abrirModal()">.....</button>
                                 <input type="text" readonly class="form-control" v-model="articulo">
                             </div>
                        </div>
                        
                    </div>

                    <div class="col-md-2">
                        <div class="form-group">
                            <label>Precio <span style="color:red" v-show="precio==0">(*ingrese)</span> </label>
                            <input type="number" value="0" class="form-control" step="any"  v-model="precio">
                        </div>
                    </div>

                     <div class="col-md-2">
                        <div class="form-group">
                            <label>Cantidad <span style="color:red" v-show="cantidad==0">(*ingrese)</span></label>
                            <input type="number" value="0" class="form-control"  v-model="cantidad">
                        </div>
                    </div>

                    <div class="col-md-2">
                        <div class="form-group">
                            <!-- btn agregar -->
                            <button class="btn btn-success form-control btnagregar" v-on:click="agregarDetalleI()">
                                <i  class="icon-plus"></i>
                            </button>
                        </div>
                    </div>


                </div>

                <div class="form-group row border">
                    <div class="table-responsive col-md-12">
                        <table class="table table-bordered table-striped table-sm">
                            <thead>
                                <th>Opciones</th>
                                <th>Articulos</th>
                                <th>Precio</th>
                                <th>Cantidad</th>
                                <th>Subtotal</th>
                            </thead>
                            <tbody v-if="ArrayDetalle.length">
                                <tr v-for="(detalle,index) in ArrayDetalle" :key="detalle.id_detalleI">
                                    <td>
                                        <button type="button" class="btn btn-danger btn-sm" v-on:click="eliminarDetalle(index)">
                                            <i class="icon-close"></i>
                                        </button>
                                    </td>
                                    <td>
                                        {{detalle.articulo}}
                                    </td>
                                    <td>
                                        <input type="number" value="3" class="form-control" v-model="detalle.precio">
                                    </td>
                                    <td>
                                        <input type="number" value="1" class="form-control" v-model="detalle.cantidad">
                                    </td>
                                    <td>
                                       $ {{detalle.precio * detalle.cantidad}}
                                    </td>
                                </tr>
                                <tr style="background-color:#CEECFS;">
                                    <td colspan="4" align="right">Total parcial</td>
                                    <td>$  {{totalparcial=(total-totalimpuesto).toFixed(2)}}</td>
                                </tr>
                                <tr style="background-color:#CEECFS;">
                                    <td colspan="4" align="right">Total impuesto</td>
                                    <td>$ {{totalimpuesto=((total * impuesto)/(1+impuesto)).toFixed(2)}}</td>
                                </tr>
                                <tr style="background-color:#CEECFS;">
                                    <td colspan="4" align="right">Total neto</td>
                                    <td>$ {{total = calculartotal}}</td>
                                </tr>
                            </tbody>
                            <tbody v-else>
                                <tr colspan="5">
                                    <td>No hay articulos</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="form-group row ">
                    <div class="col-ms-12">
                        <button type="button" class="btn btn-secondary" v-on:click="ocultarDetalle()">Cerrar</button>
                        <button type="button" class="btn btn-primary" v-on:click="registrarIngreso()">Registrar ingreso</button>
                    </div>
                </div>
                
            </div>
             </template>
            <!-- fin detallles  -->

            <!-- ver detallest  -->

            <template v-else-if="listado==2">
            <div class="card-body">
                <div class="form-group row border">
                     <!-- inicio contenido inputs -->
                    <div class="col-md-9">
                        <div class="form-group">
                            <label>Proveedor</label>
                            <p v-text="proveedor"></p>
                        </div>
                    </div>

                    <div class="col-md-3">
                         <label>Impuesto</label>
                          <p v-text="impuesto"></p>
                    </div>

                    <div class="col-md-4">
                        <label>Tipo comprobante</label>
                          <p v-text="tipo_documento"></p>
                    </div>

                    <div class="col-md-4">
                         <label>Serie comprobante</label>
                          <p v-text="serie_comprobante"></p>
                    </div>

                    <div class="col-md-4">
                         <label>Num comprobante </label>
                          <p v-text="num_comprobante"></p>
                    </div>

                    <!-- fin contenido inputs -->
                </div>

             <div class="form-group row border">
                    <div class="table-responsive col-md-12">
                        <table class="table table-bordered table-striped table-sm">
                            <thead>
                                <th>Articulos</th>
                                <th>Precio</th>
                                <th>Cantidad</th>
                                <th>Subtotal</th>
                            </thead>
                            <tbody v-if="ArrayDetalle.length">
                                <tr v-for="detalle in ArrayDetalle" :key="detalle.id_detalleI">
                                    
                                    <td>
                                        {{detalle.articulo}}
                                    </td>
                                    <td>
                                       {{detalle.precio}}
                                    </td>
                                    <td>
                                      {{detalle.cantidad}}
                                    </td>
                                    <td>
                                       $ {{detalle.precio * detalle.cantidad}}
                                    </td>
                                </tr>
                                <tr style="background-color:#CEECFS;">
                                    <td colspan="3" align="right">Total parcial</td>
                                    <td>$  {{totalparcial=(total-totalimpuesto).toFixed(2)}}</td>
                                </tr>
                                <tr style="background-color:#CEECFS;">
                                    <td colspan="3" align="right">Total impuesto</td>
                                    <td>$ {{totalimpuesto=((total * impuesto)).toFixed(2)}}</td>
                                </tr>
                                <tr style="background-color:#CEECFS;">
                                    <td colspan="3" align="right">Total neto</td>
                                    <td>$ {{total}}</td>
                                </tr>
                            </tbody>
                            <tbody v-else>
                                <tr colspan="5">
                                    <td>No hay articulos</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="form-group row ">
                    <div class="col-ms-12">
                        <button type="button" class="btn btn-secondary" v-on:click="ocultarDetalle()">Cerrar</button>
                    
                    </div>
                </div>
                
            </div>
             </template>

            <!-- fin ver detallles -->
        </div>
        <!-- Fin ejemplo de tabla Listado -->
    </div>


    <!--Inicio del modal agregar/actualizar-->
    <!-- :class="{'mostrar':modal}" con esto se le inyecta la clase -->
    <div class="modal fade" tabindex="-1" :class="{'mostrar':modal}" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-primary modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" v-text="tituloModal">{{tituloModal}}</h4>
                    <button type="button" class="close"  v-on:click="cerrarModal()">
                      <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group row">
                        <div class="col-md-6">
                            <div class="input-group">
                                <select class="form-control col-md-3" v-model="criterioA">
                                <option value="nombre">Nombre</option>
                                <option value="descripcion">Descripción</option>
                                <option value="codigo">Codigo</option>
                                </select>
                                <input type="text" class="form-control" placeholder="Texto a buscar" v-model="buscarA" 
                                @keyup.enter="listarArticulos(criterioA, buscarA)">
                                <button type="button" @click="listarArticulos(criterioA, buscarA)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-sm">
                            <thead>
                                <tr>
                                    <th>Opciones</th>
                                    <th>Codigo</th>
                                    <th>Nombre</th>
                                    <th>Categoria</th>
                                    <th>Precio</th>
                                    <th>Stock</th>
                                    <th>Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- cnsidrar la :key  para identificar la id de la table-->
                                <tr v-for="articulo in ModalArticulos" :key="articulo.id_articulo">
                                    <td>
                                        <button type="button" class="btn btn-success btn-sm" v-on:click="agregarDetalleModal(articulo)" >
                                        <i class="icon-check" ></i>
                                        </button> &nbsp;
                                    </td>
                                    <td v-text="articulo.codigo"></td>
                                    <td v-text="articulo.nombre"></td>
                                    <td v-text="articulo.categoria.nombre"></td>
                                    <td v-text="articulo.precio_venta"></td>
                                    <td v-text="articulo.stock"></td>
                                    <td>
                                        <div v-if="articulo.condicion">
                                            <span class="badge badge-success">Activo</span>
                                        </div>
                                        <div v-else>
                                            <span class="badge badge-danger">Inactivo</span>
                                        </div>
                                    </td>
                                </tr>
                            
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" v-on:click="cerrarModal()">Cerrar</button>
                    <!-- <button type="button" v-if="tipoAccion==1" class="btn btn-primary" v-on:click="registrarIngreso()">Registrar</button>
                     <button type="button" v-if="tipoAccion==2" class="btn btn-primary" v-on:click="actualizarUsuario()">Actualizar</button> -->
                </div>

            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!--Fin del modal-->
    
</main>  
</template>

<script>
    import vSelect from 'vue-select';
    import 'vue-select/dist/vue-select.css';
    
    export default {
        data(){
            return{
                 id_ingreso:0,
                 id_proveedor:0,
                 //datos del ingreso 
                 proveedor:'',

                 tipo_documento:'BOLETA',
                 serie_comprobante:'',
                 num_comprobante:'',
                 impuesto:0.18,
                 total:0.0,
                 totalimpuesto:0.0,
                 totalparcial:0.0,
                 ArrayIngreso:[],
                 ArrayDetalle:[], 
                 arrayProveedor:[],
                 arrayArticulo:[],
                 ModalArticulos:[],
                 id_articulo:0,
                 codigo:'',
                 articulo:'',
                 precio:0,
                 cantidad:0,
                 //v if
                 listado:1,
                //variable o idemficador del modal
                 modal: 0,
                 //titulo dle modal
                 tituloModal : '',
                 //logica del boton
                 tipoAccion: 0,
                 ///validacion
                 errorIngreso: 0,
                 errormensaje:[],
                 //variablesp para la paginacion
                 pagination : {
                     'total' :0,
                     'current_page' : 0,
                     'per_page' : 0,
                     'last_page' : 0,
                     'from' : 0,
                     'to' : 0
                 },
                 offset:3,
                 //variables busqueda
                 criterio:'num_comprobante',
                 buscar:'',
                 criterioA:'nombre',
                 buscarA:''

            } 
        },

        components:{

            vSelect

        },

        computed:{
            //metodo para la paginacion 
            isActived: function(){
                return this.pagination.current_page;
            },
            //calculos de los elmentos de la paginacion
            pagesNumber: function(){
                if(!this.pagination.to){
                    return [];
                }

                var from = this.pagination.current_page - this.offset;

                if(from < 1){
                    from = 1;
                }

                var to = from + (this.offset * 2);
                if(to >= this.pagination.last_page){
                    to = this.pagination.last_page;
                }

                var pagesArray = [];
                while(from <= to){
                    pagesArray.push(from);
                    from++;
                }

                return pagesArray;

            },
            //fin metodos para la paginacion 

            //calculaciones
            calculartotal: function(){
               var resultado = 0.0;
               
               for (let index = 0; index < this.ArrayDetalle.length; index++) {
                   resultado = resultado + (this.ArrayDetalle[index].precio * this.ArrayDetalle[index].cantidad);
                   
               }

               return resultado;
            }

        },

        methods: {
            listarIngresos(page, criterio, buscar){
                let atrapar = this;
                //recibo el page dentro de la url de controller
                var url = '/ingresos?page=' + page + '&buscar=' + buscar + '&criterio=' + criterio;
                //capturo en la peticion la url
                axios.get(url).then(function (json) {

                    var  respuesta = json.data;
                    //capturo datos
                    //console.log(respuesta);
                    atrapar.ArrayIngreso = respuesta.ingresos.data;
                    //lo paso al paginacion
                    atrapar.pagination = respuesta.pagination; 
                    //console.log(paginacion);
                })
                .catch(function (json) {
                    
                    console.log(error);
                });
            },

            //select proveedor con v-select

            selectProveedor(search,loading){

                let atrapar = this;

                loading(true)

                var url ='/proveedor/select?filtro=' + search;

                axios.get(url).then(function (json) {
                    var  respuesta = json.data;

                    q:search

                    atrapar.arrayProveedor = respuesta.proveedores;

                    atrapar.getDatosProveedor();
                    //console.log(respuesta);
                    loading(false)

                })
                .catch(function (json) {
                    
                    console.log(error);
                });

            },
            //extraer los datos buscados en v-select
            getDatosProveedor(vall){
                let me = this;

                me.loading = true;

                for (let index = 0; index < me.arrayProveedor.length; index++) {
                     me.id_proveedor = me.arrayProveedor[index].id_persona;
                }

              
            },
            //metodo que permite buscar articulos
            buscarArticulo(){
                let e = this;

                var url = '/articulo/buscar?filtro=' + e.codigo;

                axios.get(url).then(function (json){

                    var respuesta = json.data;

                    e.arrayArticulo = respuesta.articulos;

                    //que si el array mayor largo que cero
                    if(e.arrayArticulo.length>0){
                        //y si existe capturo los datos en los iinputs
                        e.id_articulo = e.arrayArticulo[0]['id_articulo'];
                        e.articulo = e.arrayArticulo[0]['nombre'];
                        
                    }else{
                        e.articulo = 'No existe el articulo';
                        e.id_articulo = 0;
                    }
                }).catch(function (json) {
                    
                    console.log(error);
                });

            },
            //metodo paginaion 
            cambiarPagina(page, criterio, buscar){
                let me = this;
                //actualiza la pagina actual
                me.pagination.current_page = page;

                //envia la peticion para visualizar la data en la vista
                //el page lo recibe el metodo de lista cat
                me.listarIngresos(page, criterio, buscar);
            },
            //metodo encuentra si el dato existe en el array
            encuentra(id){
                var ac = false;
                var id_p = id;
                //recorro arrya 
                for (let i = 0; i < this.ArrayDetalle.length; i++) {
                    if(this.ArrayDetalle[i].id_articulo==id_p){
                        ac = true;
                    }
                    
                }

                return ac;
            },
            //agreagar detalles
            agregarDetalleI(){
                let me = this;

                if(me.id_articulo==0 | me.cantidad==0 | me.precio==0){
                    alert('Selecione datos');
                }else{

                    //validar si esta en el array el detalle
                    if(me.encuentra(me.id_articulo)){
                        alert('El producto ya esta agregado');
                    }else{
                        me.ArrayDetalle.push({
                            id_articulo: me.id_articulo,
                            articulo : me.articulo,
                            cantidad: me.cantidad,
                            precio: me.precio
                        });

                        //dejo vacio
                        this.codigo='';
                        this.id_articulo=0;
                        this.precio=0;
                        this.articulo='';
                        this.cantidad=0;
                    }
                }
            },

            //eliminar detalle 
            eliminarDetalle(index){
                this.ArrayDetalle.splice(index,1);
            },

            //metodo recibiendo los paramettos
            abrirModal(){

                this.ModalArticulos=[];
                this.modal = 1;
                this.tituloModal='Selecione uno o mas articulos';

                                
                        
            },
            //metodo que agrega desde el data
            agregarDetalleModal(data = []){

                let me = this;

                 //validar si esta en el array el detalle
                    if(me.encuentra(data['id_articulo'])){
                        alert('El producto ya esta agregado');
                    }else{
                        me.ArrayDetalle.push({
                            id_articulo: data['id_articulo'],
                            articulo : data['nombre'],
                            cantidad: 1,
                            precio: 1
                        });

                    }

            },

            listarArticulos(criterio, buscar){
                let atrapar = this;
                //recibo el page dentro de la url de controller
                var url = '/articulo/listado?buscar=' + buscar + '&criterio=' + criterio;
                //capturo en la peticion la url
                axios.get(url).then(function (json) {

                    var  respuesta = json.data;
                    //capturo datos
                    atrapar.ModalArticulos = respuesta.articulos.data;
                  
                })
                .catch(function (json) {
                    
                    console.log(error);
                });
            },

            motrarDetalle(){
                this.listado = 0;

                this.id_proveedor=0;
                this.tipo_documento='BOLETA';
                this.serie_comprobante='';
                this.num_comprobante='';
                this.impuesto=0.18;
                this.total=0.0;
                this.id_articulo=0;
                this.cantidad=0;
                this.precio=0,
                this.ArrayDetalle=[];
            },

            ocultarDetalle(){
                this.listado = 1;
            },

            verIngreso(id_ingreso){
                let me = this;
                me.listado=2;
                //consulta para trear la cabecera 
               
                var url ='/ingresos/cabecera?id_ingreso=' +  id_ingreso;

                 let array = [];

                axios.get(url).then(function (json) {
                    var  respuesta = json.data;

                    array = respuesta.ingreso;

                    me.proveedor = array[0]['nombre'];
                    me.tipo_documento = array[0]['tipo_documento'];
                    me.serie_comprobante = array[0]['serie_comprobante'];
                    me.num_comprobante = array[0]['num_comprobante'];
                    me.impuesto = array[0]['impuesto'];
                    me.total = array[0]['total'];

                })
                .catch(function (json) {
                    
                    console.log(error);
                });
                //consulta para trear deatalles

                var urld ='/ingresos/detalles?id_ingreso=' +  id_ingreso;

                axios.get(urld).then(function (json) {
                    var  respuesta = json.data;

                    me.ArrayDetalle = respuesta.Detalleingresos;

                })
                .catch(function (json) {
                    
                    console.log(error);
                });

            },
            cerrarModal(){
                //desactivar el modal
                this.modal = 0;
                this.tituloModal='';
            },

            //metodo para validar campos
            validarIngreso(){
                //iniciañlizo variables
                this.errorIngreso=0;
                this.errormensaje=[];
                //analixo o pcmprbo si tiene valor el campo nombre
                
                if(this.id_proveedor==0) this.errormensaje.push("Selecione un proveedor");
                if(this.tipo_documento==0) this.errormensaje.push("Selecione el tipo de documento");
                if(!this.num_comprobante) this.errormensaje.push("Asigne un numero de comprobante");
                if(!this.impuesto) this.errormensaje.push("Ingrese el impuesto de la compra");
                //valido array
                if(this.ArrayDetalle.length<=0) this.errormensaje.push("No existe detalles");

                //compruebo si el mensaje tiene algun error para convertir a la variabe erorcategoria en 1
                if(this.errormensaje.length) this.errorIngreso=1;
                //retorno el rror 
                return this.errorIngreso;
            },
            //registrar
            registrarIngreso(){

                //validar
                if(this.validarIngreso()){
                    return;
                }
                let me = this;

                let url = '/ingreso/registrar';
                //realizo peticion
                axios.post(url,{
                    'id_proveedor': this.id_proveedor,
                    'tipo_documento': this.tipo_documento,
                    'serie_comprobante':this.serie_comprobante,
                    'num_comprobante': this.num_comprobante,
                    'impuesto': this.impuesto,
                    'total': this.total,
                    'data':this.ArrayDetalle
                }).then(function (json) {
                    me.listado=1;
                    //agregar valores de page, criterio, buscar
                    me.listarIngresos(1,'num_comprobante','');

                    me.id_proveedor=0;
                    me.tipo_documento='BOLETA';
                    me.serie_comprobante='';
                    me.num_comprobante='';
                    me.impuesto=0.18;
                    me.total=0.0;
                    me.id_articulo=0;
                    me.cantidad=0;
                    me.precio=0,
                    me.ArrayDetalle=[];

                })
                .catch(function (json) {
                    
                    console.log(error);
                });
               
            },
            
            anularIngreso(id_ingreso){
                Swal.fire({
                title: 'Estas seguro de anular el ingreso?',
                text: "",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Anular',
                cancelButtonText: 'Cancelar'
                }).then((result) => {
                if (result.isConfirmed) {
                    let me = this;
                    let estado = 'Anulado';
                    //realizo peticion
                    axios.put('/ingreso/anular',{
                        'estado': estado,
                        'id_ingreso':id_ingreso
                    }).then(function (json) {
                         me.listarIngresos(1,'num_comprobante','');
                        Swal.fire(
                        'Anulado',
                        'El ingreso fue desactivada con exito',
                        'success'
                        )
                    }); 
                    
                }
                });
            },
            
         
        },
        mounted() {
            this.listarIngresos(1, this.criterio, this.buscar);
            this.selectProveedor();
        }
    }
        
</script>

<style >
/* //estilos para el modal */
.modal-content{
    width: 100% !important;
    position: absolute !important;
 }
/*clase que se le inyectara al modal */
.mostrar{
    display: list-item !important;
    opacity: 1 !important;
    position: absolute !important;
    background: #3c29297a !important;
}

/* //estilo de validacion */

.div-error{
    display: flex;
    justify-content: center;
}
.text-error{
    color:red;
    font-weight: bold;
}
@media (min-width: 600px) {
    .btnagregar{
        margin-top: 2rem;
    }
}


</style>