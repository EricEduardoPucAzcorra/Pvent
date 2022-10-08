<template>
      <main class="main">
    <!-- Breadcrumb -->
    <ol class="breadcrumb">
       
        <li class="breadcrumb-item"><a href="/">Escritorio</a></li>
      
    </ol>
    <div class="container-fluid">
        <!-- Ejemplo de tabla Listado -->
        <div class="card">
            <div class="card-header">
                <i class="fa fa-align-justify"></i>Articulos
                <!-- en el metodo abrir modal envio 2 elementos -->
                <button type="button" class="btn btn-secondary" v-on:click="abrirModal('articulo','registrar')">
                    <i class="icon-plus"></i>&nbsp;Nuevo
                </button>
            </div>
           
            <div class="card-body">
                <div class="form-group row">
                    <div class="col-md-6">
                        <div class="input-group">
                            <select class="form-control col-md-3" v-model="criterio">
                              <option value="nombre">Nombre</option>
                              <option value="descripcion">Descripción</option>
                            </select>
                            <input type="text" class="form-control" placeholder="Texto a buscar" v-model="buscar" 
                            @keyup.enter="listarArticulos(1, criterio, buscar)">
                            <button type="button" @click="listarArticulos(1, criterio, buscar)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                        </div>
                    </div>
                </div>
                <table class="table table-bordered table-striped table-sm">
                    <thead>
                        <tr>
                            <th>Opciones</th>
                            <th>Codigo</th>
                            <th>Nombre</th>
                            <th>Categoria</th>
                            <th>Precio</th>
                            <th>Stock</th>
                            <th>Descripción</th>
                            <th>Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- cnsidrar la :key  para identificar la id de la table-->
                        <tr v-for="articulo in articulos" :key="articulo.id_articulo">
                            <td>
                                <button type="button" class="btn btn-warning btn-sm" v-on:click="abrirModal('articulo','actualizar',articulo)" >
                                  <i class="icon-pencil" ></i>
                                </button> &nbsp;
                                <!-- //si la categoria.condicion es 1 -->
                                <template v-if="articulo.condicion">
                                    <button type="button" class="btn btn-danger btn-sm" v-on:click="desactivarArticulo(articulo.id_articulo)">
                                        <i class="icon-trash"></i>
                                    </button>
                                </template>
                                <template v-else>
                                    <button type="button" class="btn btn-info btn-sm" v-on:click="activarArticulo(articulo.id_articulo)">
                                        <i class="icon-check"></i>
                                    </button>
                                </template>
                            </td>
                            <td v-text="articulo.codigo"></td>
                            <td v-text="articulo.nombre"></td>
                            <td v-text="articulo.categoria.nombre"></td>
                            <td v-text="articulo.precio_venta"></td>
                            <td v-text="articulo.stock"></td>
                            <td v-text="articulo.descripcion"></td>
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
                    <form action="" method="post" enctype="multipart/form-data" class="form-horizontal">
                        
                         <div class="form-group row">
                            <label class="col-md-3 form-control-label" for="text-input">Nombre</label>
                            <div class="col-md-9">
                                <select class="form-control" v-model="id_categoria">
                                    <option value="0" disabled>Selecione</option>
                                    <option v-for="categoria in categorias" :key="categoria.id_categoria" :value="categoria.id_categoria"
                                     v-text="categoria.nombre">   
                                    </option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-3 form-control-label" for="">Codigo</label>
                            <div class="col-md-9">
                                <input type="text" v-model="codigo" class="form-control" placeholder="Iserte su serie del producto">
                                <!-- etiquetas barcde de vue  -->
                                <barcode :value="codigo" :options="{format:'EAN-13'}">
                                    Gererando codigo de barra.....
                                </barcode>

                            </div>
                        </div>
                        
                        <div class="form-group row">
                            <label class="col-md-3 form-control-label" for="text-input">Nombre</label>
                            <div class="col-md-9">
                                <input type="text" v-model="nombre" class="form-control" placeholder="Nombre del producto">
                                
                            </div>
                        </div>

                       <div class="form-group row">
                            <label class="col-md-3 form-control-label" for="email-input">Precio de venta</label>
                            <div class="col-md-9">
                                <input type="number" v-model="precio_venta" class="form-control" placeholder="">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-3 form-control-label" for="email-input">Stock</label>
                            <div class="col-md-9">
                                <input type="number" v-model="stock" class="form-control" placeholder="stock">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-3 form-control-label" for="email-input">Descripción</label>
                            <div class="col-md-9">
                                <input type="text" v-model="descripcion" class="form-control" placeholder="Descripcion">
                            </div>
                        </div>

                        <!-- mensaje de validacion -->
                        <div v-show="errorArticulo" class="form-group row div-error">
                            <div class="text-center text-error">
                                <div v-for="error in errormensaje" :key="error" v-text="error">

                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" v-on:click="cerrarModal()">Cerrar</button>
                    <button type="button" v-if="tipoAccion==1" class="btn btn-primary" v-on:click="registrarArticulo()">Guardar</button>
                     <button type="button" v-if="tipoAccion==2" class="btn btn-primary" v-on:click="actualizarArticulo()">Actualizar</button>
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
    //imortar vuecode
    import VueBarcode from 'vue-barcode';

    export default {
        data(){
            return{
                 id_articulo:0,
                 id_categoria:0,
                 nombre_categoria:'',
                 codigo: '',
                 nombre:'',
                 precio_venta:0,
                 stock:0,
                 descripcion:'',
                 articulos:[],
                 categorias:[],
                 //variable o idemficador del modal
                 modal: 0,
                 //titulo dle modal
                 tituloModal : '',
                 //logica del boton
                 tipoAccion: 0,
                 ///validacion
                 errorArticulo: 0,
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
                 criterio:'nombre',
                 buscar:''

            } 
        },

        components: {
            'barcode': VueBarcode
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

            }
            //fin metodos para la paginacion 

        },

        methods: {
            listarArticulos(page, criterio, buscar){
                let atrapar = this;
                //recibo el page dentro de la url de controller
                var url = '/articulo?page=' + page + '&buscar=' + buscar + '&criterio=' + criterio;
                //capturo en la peticion la url
                axios.get(url).then(function (json) {

                    var  respuesta = json.data;
                    //capturo datos
                    atrapar.articulos = respuesta.articulos.data;
                    //lo paso al paginacion
                    atrapar.pagination = respuesta.pagination; 
                    //console.log(paginacion);
                })
                .catch(function (json) {
                    
                    console.log(error);
                });
            },

            traerCategorias(){
                let atrapar = this;
                //recibo el page dentro de la url de controller
                var url = '/categoria/activo';
                //capturo en la peticion la url
                axios.get(url).then(function (json) {
                    //console.log(json.data);
                    atrapar.categorias = json.data.categorias;
                })
                .catch(function (json) {
                    
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
                me.listarArticulos(page, criterio, buscar);
            },

            cerrarModal(){
                //desactivar el modal
                this.modal = 0;
                this.tituloModal='';
                this.id_categoria=0;
                this.nombre_categoria='';
                this.codigo='';
                this.nombre='';
                this.precio_venta=0;
                this.stock=0;
                this.descripcion='';
                this.errorCategoria=0;
            },
            //metodo recibiendo los paramettos
            abrirModal(modelo, accion, data=[]){
                switch(modelo){
                    //verefico si el modelo fue de tipo categoria
                    case "articulo":
                    {
                        //analizola accion
                       switch(accion){
                           //si es registrar logica implementada
                           case 'registrar':
                               {
                                   this.modal = 1;
                                   this.tituloModal='REGISTRAR ARTICULO';
                                   this.id_categoria=0;
                                    this.nombre_categoria='';
                                    this.codigo='';
                                    this.nombre='';
                                    this.precio_venta=0;
                                    this.stock=0;
                                    this.descripcion='';
                                   this.tipoAccion=1;
                                   break;
                               }
                               //si es actualizar logica implementada
                            case 'actualizar':
                                {
                                    //enviar valor del array a cada input al actualizar
                                   this.modal = 1;
                                   this.tituloModal='ACTUALIZAR ARTICULO';
                                   this.id_articulo=data['id_articulo'];
                                   this.id_categoria=data['id_categoria'];
                                    this.nombre_categoria='';
                                    this.codigo=data['codigo'];
                                    this.nombre=data['nombre'];
                                    this.precio_venta=data['precio_venta'];
                                    this.stock=data['stock'];
                                    this.descripcion=data['descripcion'];
                                   this.tipoAccion=2;
                                   break;
                                   // console.log(data);
                                }
                       } 

                       
                    }

                    this.traerCategorias();
                }
            },

                       //metodo para validar campos
            validarArticulo(){
                //iniciañlizo variables
                this.errorArticulo=0;
                this.errormensaje=[];
                //analixo o pcmprbo si tiene valor el campo nombre
                if(this.id_categoria==0) this.errormensaje.push("La categoria es requerido");
                if(!this.nombre) this.errormensaje.push("El nombre es requerido");
                if(!this.stock) this.errormensaje.push("El stock es numerico y requerido");
                if(!this.precio_venta) this.errormensaje.push("El precio es numerico y requerido");
                //compruebo si el mensaje tiene algun error para convertir a la variabe erorcategoria en 1
                if(this.errormensaje.length) this.errorArticulo=1;
                //retorno el rror 
                return this.errorArticulo;
            },

            registrarArticulo(){

                //validar
                if(this.validarArticulo()){
                    return;
                }
                let me = this;
                //realizo peticion
                axios.post('/articulo/registrar',{
                    'id_categoria': this.id_categoria,
                    'codigo': this.codigo,
                    'nombre' : this.nombre,
                    'precio_venta' : this.precio_venta,
                    'stock' : this.stock,
                    'descripcion': this.descripcion

                }).then(function (json) {
                    //atrapar.categorias = json.data;
                    //console.log(json);
                    me.modal = 0;
                    //agregar valores de page, criterio, buscar
                    me.listarArticulos(1,'nombre','');
                })
                .catch(function (json) {
                    
                    console.log(error);
                });
               
            },

            actualizarArticulo(){
                //validar
                if(this.validarArticulo()){
                    return;
                }
                let me = this;
                //realizo peticion
                axios.put('/articulo/actualizar',{
                    'id_categoria': this.id_categoria,
                    'codigo': this.codigo,
                    'nombre' : this.nombre,
                    'precio_venta' : this.precio_venta,
                    'stock' : this.stock,
                    'descripcion': this.descripcion,
                    'id_articulo':this.id_articulo
                }).then(function (json) {
                    //atrapar.categorias = json.data;
                    //console.log(json);
                    me.modal = 0;
                    me.listarArticulos(1,'nombre','');
                })
                .catch(function (json) {
                    
                    console.log(error);
                });  
            }, 

            desactivarArticulo(id){
                Swal.fire({
                title: 'Estas seguro de desactivar',
                text: "",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Desactivar',
                cancelButtonText: 'Cancelar'
                }).then((result) => {
                if (result.isConfirmed) {
                    let me = this;
                    let estado = 0;
                    //realizo peticion
                    axios.put('/articulo/desactivar',{
                        'condicion': estado,
                        'id_articulo':id
                    }).then(function (json) {
                        me.listarArticulos(1,'nombre','');
                        Swal.fire(
                        'Se desactivo',
                        'El articulo fue desactivada con exito',
                        'success'
                        )
                    }); 
                    
                }
                });
            },
            activarArticulo(id){
                Swal.fire({
                title: 'Quieres activar el articulo',
                text: "",
                icon: 'success',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Activar',
                cancelButtonText: 'Cancelar'
                }).then((result) => {
                if (result.isConfirmed) {
                    let me = this;
                    let estado = 1;
                    //realizo peticion
                    axios.put('/articulo/activar',{
                        'condicion': estado,
                        'id_articulo':id
                    }).then(function (json) {
                        me.listarArticulos(1,'nombre','');
                        Swal.fire(
                        'Se activo',
                        'El articulo fue activada con exito',
                        'success'
                        )
                    });  
                }
                });
            }

        },
        mounted() {
            this.listarArticulos(1, this.criterio, this.buscar);

            //this.traerCategorias();
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

</style>