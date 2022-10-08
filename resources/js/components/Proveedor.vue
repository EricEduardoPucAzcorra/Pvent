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
                <i class="fa fa-align-justify"></i> Proveedores
                <!-- en el metodo abrir modal envio 2 elementos -->
                <button type="button" class="btn btn-secondary" v-on:click="abrirModal('proveedor','registrar')">
                    <i class="icon-plus"></i>&nbsp;Nuevo
                </button>
            </div>
           
            <div class="card-body">
                <div class="form-group row">
                    <div class="col-md-6">
                        <div class="input-group">
                            <select class="form-control col-md-3" v-model="criterio">
                              <option value="nombre">Nombre</option>
                              <option value="num_documento">Num doc</option>
                              <option value="direccion">Direccion</option>
                              <option value="telefono">Telefono</option>
                              <option value="email">Email</option>
                            </select>
                            <input type="text" class="form-control" placeholder="Texto a buscar" v-model="buscar" 
                            @keyup.enter="listarProveedores(1, criterio, buscar)">
                            <button type="button" @click="listarProveedores(1, criterio, buscar)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                        </div>
                    </div>
                </div>
                <table class="table table-bordered table-striped table-sm">
                    <thead>
                        <tr>
                            <th>Opciones</th>
                            <th>Nombre</th>
                            <th>Tipo doc</th>
                            <th>Num doc</th>
                            <th>Direccion</th>
                            <th>Telefono</th>
                            <th>Email</th>
                            <th>Contacto</th>
                            <th>Telefono contacto</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- cnsidrar la :key  para identificar la id de la table-->
                        <tr v-for="proveedor in proveedores" :key="proveedor.id_persona">
                            <td>
                                <button type="button" class="btn btn-warning btn-sm" v-on:click="abrirModal('proveedor','actualizar',proveedor)" >
                                  <i class="icon-pencil" ></i>
                                </button> &nbsp;
                               
                            </td>
                            <td v-text="proveedor.nombre"></td>
                            <td v-text="proveedor.tipo_documento"></td>
                            <td v-text="proveedor.num_documento"></td>
                            <td v-text="proveedor.direccion"></td>
                            <td v-text="proveedor.telefono"></td>
                            <td v-text="proveedor.email"></td>
                            <td v-text="proveedor.contacto"></td>
                            <td v-text="proveedor.telefono_contacto"></td>
                            
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
                                <input type="text" v-model="nombre" class="form-control" placeholder="Nombre del cliente">
                              
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 form-control-label" for="email-input">Tipo docuemtno</label>
                            <div class="col-md-9">
                                <select  v-model="tipo_documento" class="form-control" >
                                    <option value="0" disabled>Elige un tipo documento</option>
                                    <option value="DNI">DNI</option>
                                    <option value="RUC">RUC</option>
                                    <option value="PASS">PASS</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 form-control-label" for="">Numero documento</label>
                            <div class="col-md-9">
                                <input type="text" v-model="num_documento" class="form-control" placeholder="N° documento">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 form-control-label" for="email-input">Direccion</label>
                            <div class="col-md-9">
                                <input type="text" v-model="direccion" class="form-control" placeholder="Direccio">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 form-control-label" for="email-input">Telefono</label>
                            <div class="col-md-9">
                                <input type="text" v-model="telefono" class="form-control" placeholder="Telefono">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 form-control-label" for="email-input">Email</label>
                            <div class="col-md-9">
                                <input type="text" v-model="email" class="form-control" placeholder="example@gmail.com">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-3 form-control-label" for="email-input">Contacto</label>
                            <div class="col-md-9">
                                <input type="text" v-model="contacto" class="form-control" placeholder="example@gmail.com">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 form-control-label" for="email-input">Contacto telefono</label>
                            <div class="col-md-9">
                                <input type="text" v-model="telefono_contacto" class="form-control" placeholder="example@gmail.com">
                            </div>
                        </div>
                        <!-- mensaje de validacion -->
                        <div v-show="errorProveedor" class="form-group row div-error">
                            <div class="text-center text-error">
                                <div v-for="error in errormensaje" :key="error" >
                                    {{error}}
                                </div>
                            </div>
                        </div>

                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" v-on:click="cerrarModal()">Cerrar</button>
                    <button type="button" v-if="tipoAccion==1" class="btn btn-primary" v-on:click="registrarProveedor()">Guardar</button>
                     <button type="button" v-if="tipoAccion==2" class="btn btn-primary" v-on:click="actualizarProveedor()">Actualizar</button>
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
                 id_proveedor: 0,
                 prueba: 'hola mundo',
                 nombre:'',
                 tipo_documento:'',
                 num_documento:'',
                 direccion:'',
                 telefono:'',
                 email:'',
                 contacto:'',
                 telefono_contacto:'',
                 proveedores:[],
                 //variable o idemficador del modal
                 modal: 0,
                 //titulo dle modal
                 tituloModal : '',
                 //logica del boton
                 tipoAccion: 0,
                 ///validacion
                 errorProveedor: 0,
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
            listarProveedores(page, criterio, buscar){
                let atrapar = this;
                //recibo el page dentro de la url de controller
                var url = '/proveedor?page=' + page + '&buscar=' + buscar + '&criterio=' + criterio;
                //capturo en la peticion la url
                axios.get(url).then(function (json) {

                    var  respuesta = json.data;
                    //capturo datos
                    atrapar.proveedores = respuesta.proveedores.data;
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
                me.listarProveedores(page, criterio, buscar);
            },

            //metodo recibiendo los paramettos
            abrirModal(modelo, accion, data=[]){
                switch(modelo){
                    //verefico si el modelo fue de tipo categoria
                    case "proveedor":
                    {
                        //analizola accion
                       switch(accion){
                           //si es registrar logica implementada
                           case 'registrar':
                               {
                                   this.modal = 1;
                                   this.tituloModal='REGISTRAR PROVEEDOR';
                                   this.nombre = '';
                                   this.tipo_documento = '';
                                   this.num_documento='';
                                   this.direccion='';
                                   this.telefono='';
                                   this.email='';
                                   this.contacto='';
                                   this.telefono_contacto='';
                                   this.tipoAccion=1;
                                   break;
                               }
                               //si es actualizar logica implementada
                            case 'actualizar':
                                {
                                   this.modal = 1;
                                   this.tituloModal='ACTUALIZAR PROVEEDOR';
                                   this.id_proveedor = data['id_persona'];
                                   this.nombre = data['nombre'];
                                   this.tipo_documento = data['tipo_documento'];
                                   this.num_documento=data['num_documento'];
                                   this.direccion=data['direccion'];
                                   this.telefono=data['telefono'];
                                   this.email=data['email'];
                                   this.contacto=data['contacto'];
                                   this.telefono_contacto=data['telefono_contacto'];
                                   this.tipoAccion=2;
                                   break;
                                   // console.log(data);
                                }
                       } 
                       
                    }
                }
            },

            cerrarModal(){
                //desactivar el modal
                this.modal = 0;
                this.tituloModal='';
                this.nombre = '';
                this.tipo_documento = 'RUC';
                this.num_documento='';
                this.direccion='';
                this.telefono='';
                this.email='';
                this.contacto='';
                this.telefono_contacto='';
                this.errorProveedor=0;
            },

            //metodo para validar campos
            validarProveedor(){
                //iniciañlizo variables
                this.errorProveedor=0;
                this.errormensaje=[];
                //analixo o pcmprbo si tiene valor el campo nombre
                
                if(!this.nombre) this.errormensaje.push("El nombre es requerido");
              

                //compruebo si el mensaje tiene algun error para convertir a la variabe erorcategoria en 1
                if(this.errormensaje.length) this.errorProveedor=1;
                //retorno el rror 
                return this.errorProveedor;
            },
            //registrar
            registrarProveedor(){

                //validar
                if(this.validarProveedor()){
                    return;
                }
                let me = this;
                //realizo peticion
                axios.post('/proveedor/registrar',{
                    'nombre': this.nombre,
                    'tipo_documento': this.tipo_documento,
                    'num_documento': this.num_documento,
                    'direccion': this.direccion,
                    'telefono': this.telefono,
                    'email':this.email,
                    'contacto':this.contacto,
                    'telefono_contacto':this.telefono_contacto
                }).then(function (json) {
                    //atrapar.categorias = json.data;
                    //console.log(json);
                    me.modal = 0;
                    //agregar valores de page, criterio, buscar
                    me.listarProveedores(1,'nombre','');
                })
                .catch(function (json) {
                    
                    console.log(error);
                });
               
            },
            actualizarProveedor(){
                //validar
                if(this.validarProveedor()){
                    return;
                }
                let me = this;
                //realizo peticion
                axios.put('/proveedor/actualizar',{
                    'nombre': this.nombre,
                    'tipo_documento': this.tipo_documento,
                    'num_documento': this.num_documento,
                    'direccion': this.direccion,
                    'telefono': this.telefono,
                    'email':this.email,
                    'contacto':this.contacto,
                    'telefono_contacto':this.telefono_contacto,
                    'id_proveedor':this.id_proveedor
                }).then(function (json) {
                   
                    //console.log(json);
                    me.modal = 0;
                    me.listarProveedores(1,'nombre','');

                    console.log('hecho');
                })
                .catch(function (json) {
                    
                    console.log(error);
                });  
            }, 
        },
        mounted() {
            this.listarProveedores(1, this.criterio, this.buscar);
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