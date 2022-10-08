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
                <i class="fa fa-align-justify"></i> Categorías
                <!-- en el metodo abrir modal envio 2 elementos -->
                <button type="button" class="btn btn-secondary" v-on:click="abrirModal('categoria','registrar')">
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
                            @keyup.enter="listarCategorias(1, criterio, buscar)">
                            <button type="button" @click="listarCategorias(1, criterio, buscar)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                        </div>
                    </div>
                </div>
                <table class="table table-bordered table-striped table-sm">
                    <thead>
                        <tr>
                            <th>Opciones</th>
                            <th>Nombre</th>
                            <th>Descripción</th>
                            <th>Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- cnsidrar la :key  para identificar la id de la table-->
                        <tr v-for="categoria in categorias" :key="categoria.id_categoria">
                            <td>
                                <button type="button" class="btn btn-warning btn-sm" v-on:click="abrirModal('categoria','actualizar',categoria)" >
                                  <i class="icon-pencil" ></i>
                                </button> &nbsp;
                                <!-- //si la categoria.condicion es 1 -->
                                <template v-if="categoria.condicion">
                                    <button type="button" class="btn btn-danger btn-sm" v-on:click="desactivarCategoria(categoria.id_categoria)">
                                        <i class="icon-trash"></i>
                                    </button>
                                </template>
                                <template v-else>
                                    <button type="button" class="btn btn-info btn-sm" v-on:click="activarCategoria(categoria.id_categoria)">
                                        <i class="icon-check"></i>
                                    </button>
                                </template>
                            </td>
                            <td v-text="categoria.nombre"></td>
                            <td v-text="categoria.descripcion"></td>
                            <td>
                                <div v-if="categoria.condicion">
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
                                <input type="text" v-model="nombre" class="form-control" placeholder="Nombre de categoría">
                                <span class="help-block">(*) Ingrese el nombre de la categoría</span>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 form-control-label" for="email-input">Descripción</label>
                            <div class="col-md-9">
                                <input type="text" v-model="descripcion" class="form-control" placeholder="Descripcion">
                            </div>
                        </div>
                        <!-- mensaje de validacion -->
                        <div v-show="errorCategoria" class="form-group row div-error">
                            <div class="text-center text-error">
                                <div v-for="error in errormensaje" :key="error" v-text="error">

                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" v-on:click="cerrarModal()">Cerrar</button>
                    <button type="button" v-if="tipoAccion==1" class="btn btn-primary" v-on:click="registrarCategoria()">Guardar</button>
                     <button type="button" v-if="tipoAccion==2" class="btn btn-primary" v-on:click="actualizarCategoria()">Actualizar</button>
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
    export default {
        data(){
            return{
                 id_categoria:0,
                 prueba: 'hola mundo',
                 nombre:'',
                 descripcion:'',
                 categorias:[],
                 //variable o idemficador del modal
                 modal: 0,
                 //titulo dle modal
                 tituloModal : '',
                 //logica del boton
                 tipoAccion: 0,
                 ///validacion
                 errorCategoria: 0,
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
            listarCategorias(page, criterio, buscar){
                let atrapar = this;
                //recibo el page dentro de la url de controller
                var url = '/categoria?page=' + page + '&buscar=' + buscar + '&criterio=' + criterio;
                //capturo en la peticion la url
                axios.get(url).then(function (json) {

                    var  respuesta = json.data;
                    //capturo datos
                    atrapar.categorias = respuesta.categorias.data;
                    //lo paso al paginacion
                    atrapar.pagination = respuesta.pagination; 
                    //console.log(paginacion);
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
                me.listarCategorias(page, criterio, buscar);
            },

            registrarCategoria(){

                //validar
                if(this.validarCategoria()){
                    return;
                }
                let me = this;
                //realizo peticion
                axios.post('/categoria/registrar',{
                    'nombre': this.nombre,
                    'descripcion': this.descripcion
                }).then(function (json) {
                    //atrapar.categorias = json.data;
                    //console.log(json);
                    me.modal = 0;
                    //agregar valores de page, criterio, buscar
                    me.listarCategorias(1,'nombre','');
                })
                .catch(function (json) {
                    
                    console.log(error);
                });
               
            },
            //metodo para validar campos
            validarCategoria(){
                //iniciañlizo variables
                this.errorCategoria=0;
                this.errormensaje=[];
                //analixo o pcmprbo si tiene valor el campo nombre
                if(!this.nombre) this.errormensaje.push("El nombre es requerido");
                //compruebo si el mensaje tiene algun error para convertir a la variabe erorcategoria en 1
                if(this.errormensaje.length) this.errorCategoria=1;
                //retorno el rror 
                return this.errorCategoria;
            },

            cerrarModal(){
                //desactivar el modal
                this.modal = 0;
                this.tituloModal='';
                this.nombre = '';
                this.descripcion='';

                this.errorCategoria=0;
            },
            //metodo recibiendo los paramettos
            abrirModal(modelo, accion, data=[]){
                switch(modelo){
                    //verefico si el modelo fue de tipo categoria
                    case "categoria":
                    {
                        //analizola accion
                       switch(accion){
                           //si es registrar logica implementada
                           case 'registrar':
                               {
                                   this.modal = 1;
                                   this.tituloModal='REGISTRAR CATEGORIA';
                                   this.nombre = '';
                                   this.descripcion='';
                                   this.tipoAccion=1;
                                   break;
                               }
                               //si es actualizar logica implementada
                            case 'actualizar':
                                {
                                   this.modal = 1;
                                   this.tituloModal='ACTUALIZAR CATEGORIA';
                                   this.id_categoria = data.id_categoria;
                                   this.nombre = data['nombre'];
                                   this.descripcion= data.descripcion;
                                   this.tipoAccion=2;
                                   break;
                                   // console.log(data);
                                }
                       } 
                       
                    }
                }
            },

            actualizarCategoria(){
                //validar
                if(this.validarCategoria()){
                    return;
                }
                let me = this;
                //realizo peticion
                axios.put('/categoria/actualizar',{
                    'nombre': this.nombre,
                    'descripcion': this.descripcion,
                    'id_categoria':this.id_categoria
                }).then(function (json) {
                    //atrapar.categorias = json.data;
                    //console.log(json);
                    me.modal = 0;
                    me.listarCategorias(1,'nombre','');
                })
                .catch(function (json) {
                    
                    console.log(error);
                });  
            }, 

            desactivarCategoria(id){
                Swal.fire({
                title: 'Estas seguro de desactivar',
                text: "Una vez desactivado que inhabilitado la categoria",
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
                    axios.put('/categoria/desactivar',{
                        'condicion': estado,
                        'id_categoria':id
                    }).then(function (json) {
                        me.listarCategorias(1,'nombre','');
                        Swal.fire(
                        'Se desactivo',
                        'La categoria fue desactivada con exito',
                        'success'
                        )
                    }); 
                    
                }
                });
            },
            activarCategoria(id){
                Swal.fire({
                title: 'Quieres activar la categoria',
                text: "Se activara la categoria",
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
                    axios.put('/categoria/activar',{
                        'condicion': estado,
                        'id_categoria':id
                    }).then(function (json) {
                        me.listarCategorias(1,'nombre','');
                        Swal.fire(
                        'Se activo',
                        'La categoria fue activada con exito',
                        'success'
                        )
                    });  
                }
                });
            }

        },
        mounted() {
            this.listarCategorias(1, this.criterio, this.buscar);
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