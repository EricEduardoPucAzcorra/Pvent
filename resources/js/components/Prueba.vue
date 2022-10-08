<template>
      <main class="main">
    <!-- Breadcrumb -->
    <ol class="breadcrumb">
        <li class="breadcrumb-item">Home</li>
        <li class="breadcrumb-item"><a href="#">Admin</a></li>
        <li class="breadcrumb-item active">Dashboard</li>
    </ol>
    <div class="container-fluid">
        <!-- Ejemplo de tabla Listado -->
        <div class="card">
            <div class="card-header">
                <i class="fa fa-align-justify"></i> {{prueba}}
                <button type="button" class="btn btn-secondary" v-on:click="activarModal('alumno','registrar')">
                    <i class="icon-plus"></i>&nbsp;Nuevo
                </button>
            </div>
            <div class="card-body">
                <div class="form-group row">
                    <div class="col-md-6">
                        <div class="input-group">
                            <select class="form-control col-md-3" v-model="criterio" >
                              <option value="nombre">Nombre</option>
                              <option value="apellidos">Apellidos</option>
                            </select>
                            <input type="text" v-model="buscar" @keyup.enter="listarAlumnos(1, criterio, buscar)" class="form-control" placeholder="Texto a buscar">
                            <button type="button" @click="listarAlumnos(1, criterio, buscar)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                        </div>
                    </div>
                </div>
                <table class="table table-bordered table-striped table-sm">
                    <thead>
                        <tr>
                            <th>Opciones</th>
                            <th>Nombre</th>
                            <th>Apellidos</th>
                            <th>Edad</th>
                            <th>Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="alumno in alumnos" :key="alumno.id">
                            <td>
                                <button type="button" class="btn btn-warning btn-sm" v-on:click="activarModal('alumno', 'actualizar', alumno)">
                                  <i class="icon-pencil"></i>
                                </button> &nbsp;
                                <template v-if="alumno.condicion">
                                    <button type="button" class="btn btn-danger btn-sm" @click="bajaAlumno(alumno.id)" >
                                    <i class="icon-trash"></i>
                                    </button>
                                </template>
                                <template v-else >
                                    <button type="button" class="btn btn-success btn-sm" @click="altaAlumno(alumno.id)">
                                    <i class="icon-check"></i>
                                    </button>
                                </template>
                                
                            </td>
                            <td>{{alumno.nombre}}</td>
                            <td>{{alumno.apellidos}}</td>
                            <td>{{alumno.edad}}</td>
                            <td>
                                <div v-if="alumno.condicion">
                                    <span class="badge badge-success">Activo</span>
                                </div>
                                <div v-else>
                                    <span class="badge badge-danger">Desactivado</span>
                                </div>
                                
                            </td>
                        </tr>
  
                    </tbody>
                </table>
                 <nav>
                    <!-- aplicar paginacion -->
                    <ul class="pagination">
                        <li class="page-item" v-if="pagination.current_page > 1">
                            <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page - 1)">Ant</a>
                        </li>

                        <li class="page-item " v-for="page in pagesNumber" :key="page" :class="[page == isActived ? 'active' : '']">
                            <a class="page-link" href="#" @click.prevent="cambiarPagina(page)" v-text="page"></a>
                        </li>
                     
                        <li class="page-item" v-if="pagination.current_page < pagination.last_page">
                            <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page + 1)">Sig</a>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
        <!-- Fin ejemplo de tabla Listado -->
    </div>


    <!--Inicio del modal agregar/actualizar-->
    <div class="modal fade" :class="{'mostrar':modal}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-primary modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">{{tituloModal}}</h4>
                    <button type="button" class="close" @click="cerrarModal()">
                      <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="" method="post" enctype="multipart/form-data" class="form-horizontal">
                        <div class="form-group row">
                            <label class="col-md-3 form-control-label" for="text-input">Nombre</label>
                            <div class="col-md-9">
                                <input type="text" v-model="nombre" class="form-control" placeholder="Nombre">
                               
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 form-control-label" for="text-input">Apellidos</label>
                            <div class="col-md-9">
                                <input type="text" v-model="apellidos" class="form-control" placeholder="Apellidos">
                              
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 form-control-label" for="email-input">Edad</label>
                            <div class="col-md-9">
                                <input type="number" v-model="edad"  class="form-control" placeholder="Edad">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-3 form-control-label" for="email-input">Imegen</label>
                            <div class="col-md-9">
                                <input type="file" name="imagen" @change="select"  class="form-control" placeholder="imagen">
                            </div>
                        </div>

                        <div v-show="errorAlumno==1"  class="form-group row div-error">
                            <div class="text-center text-error">
                                <div v-for="error in mensajeError" :key="error">
                                    {{error}}
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>
                    <button type="button" class="btn btn-primary" v-if="bandera==true" @click="regitrarAlumno()" >Guardar</button>
                    <button type="button" class="btn btn-success" v-if="bandera==false" @click="actualizarAlumno()">Actualizar</button>
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
              prueba:'Alumnos',
               nombre:'',
               apellidos:'',
               edad:'',
               imagen:null,
               alumnos:'',
               //modal
               modal:0,
               tituloModal:'',
               //mensajes de error
               errorAlumno : 0,
               mensajeError : [],
               //accion del boton actualiza
               bandera :false,
               //id
               id_alumno: 0,
               //pagination
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
                 buscar:'',
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
        },
        methods:{
            select(event){
                //Asignamos la imagen a  nuestra data
                this.imagen = event.target.files[0];
            },
            //metodo traer alumnos
            //recibo el 3 criterios, 1 para paginacion y 2 para la busqueda(criterio y buscar)
            //enviar las 2 variables de busqueda en todos los retornamientos de array listamiento de alumnos
            listarAlumnos(page, criterio, buscar){
                let i =  this;
                var url = '/alumnos?page=' + page + '&buscar=' + buscar + '&criterio=' + criterio;

                axios.get(url)
                .then(function (json) {
                    var datos = json.data;

                    i.alumnos = datos.alumnos.data;

                    i.pagination = datos.pagination; 
                    //console.log(json);
                })
                .catch(function (error) {
                    // handle error
                    console.log(error);
                });
                
            },
            //activar modelo para registrar y actualiza
            //recibe 3 parametros modelo, accion y la data
            activarModal(modelo, accion, data=[]){
                switch(modelo){
                    //si el modelo es categoria
                    case 'alumno':
                    {
                        switch(accion){
                            //si la accion es registrar
                           case 'registrar':{
                               //activo modal
                               this.modal=1;
                               //titulo modal
                               this.tituloModal='Agregar alumno';
                               //dejo vacio los campos
                               this.nombre='';
                               this.apellidos='';
                               this.edad='';
                               //boton
                               this.bandera=true;
                               //brincar
                                break;
                           }
                           //si la accion es actualizar
                           case 'actualizar':{
                               this.modal=1;
                               this.tituloModal='Actualizar alumno';
                               this.nombre= data['nombre'];
                               this.apellidos= data['apellidos'];
                               this.edad= data['edad'];
                               this.id_alumno = data['id'];
                               //boton
                               this.bandera=false;
                               //console.log(data);
                               break;
                           }
                        }
                    }
                }
               
            },
            //cerrarmodal
            cerrarModal(){
                this.modal = 0;
                this.nombre = '';
                this.apellidos = '';
                this.edad='';
            },

            //metdo que valida los campos
            validar(){
                //inicializo variabes
                this.errorAlumno = 0;
                this.mensajeError = [];

                if(!this.nombre ) this.mensajeError.push('Es necesario agregar nombre');
                if(!this.apellidos ) this.mensajeError.push('Es necesario agregar los apellidos');
                if(!this.edad) this.mensajeError.push('Es necesario agregar la edad');

                if(this.mensajeError.length) this.errorAlumno=1;

                return this.errorAlumno;

            },

            regitrarAlumno(){

                //Creamos el formData
                var data = new  FormData();
                //Añadimos la imagen seleccionada
                data.append('imagen', this.imagen);

                //capturo url
                var url= '/alumnos/registrar';
                let i =  this;
                //valido
                if(this.validar()){
                    return;
                }
                //peticion axios
                axios.post(url,{
                    'nombre' : this.nombre,
                    'apellidos' : this.apellidos,
                    'edad' : this.edad,
                    'imagen': this.imagen
                })
                .then(function (json) {
                    //desactivo modal
                    i.modal = 0;
                    //listo alumnos
                    i.listarAlumnos(1,'nombre','');
                })
                .catch(function (error) {
                    // handle error
                    console.log(error);
                });
            },
            //actualiza alumno
            actualizarAlumno(){
                //capturo url
                var url= '/alumnos/actualizar';
                let i =  this;
                //valido
                 if(this.validar()){
                    return;
                }
                //peticion axios
                axios.put(url,{
                    'nombre' : this.nombre,
                    'apellidos' : this.apellidos,
                    'edad' : this.edad,
                    'id'  : this.id_alumno
                })
                .then(function (json) {
                    //desactivo modal
                    i.modal = 0;
                    //listo alumnos
                    i.listarAlumnos(1,'nombre','');
                })
                .catch(function (error) {
                    // handle error
                    console.log(error);
                });
            },

            bajaAlumno(id){
                Swal.fire({
                title: 'Estas seguro de dar baja al alumno',
                text: "",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Aceptar',
                cancelButtonText: 'Cancelar'
                }).then((result) => {
                if (result.isConfirmed) {
                    let me = this;
                    let estado = 0;
                    //realizo peticion
                    var url= '/alumnos/desactivar';
                    axios.put(url,{
                        'condicion': estado,
                        'id':id
                    }).then(function (json) {
                        me.listarAlumnos(1,'nombre','');
                        Swal.fire(
                        'Se desactivo',
                        'El alumno fue dado de baja',
                        'success'
                        )
                    }); 
                    
                }
                });
            },

            altaAlumno(id){
                Swal.fire({
                title: 'Estas seguro de dar de alta al alumno',
                text: "",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Aceptar',
                cancelButtonText: 'Cancelar'
                }).then((result) => {
                if (result.isConfirmed) {
                    let me = this;
                    let estado = 1;
                    //realizo peticion
                    var url= '/alumnos/activar';
                    axios.put(url,{
                        'condicion': estado,
                        'id':id
                    }).then(function (json) {
                        me.listarAlumnos(1,'nombre','');
                        Swal.fire(
                        'Se de dado de alta',
                        'El alumno fue dado de baja',
                        'success'
                        )
                    }); 
                    
                }
                });
            },

             //metodo paginaion 
            cambiarPagina(page, criterio, buscar){
                let me = this;
                //actualiza la pagina actual
                me.pagination.current_page = page;

                //envia la peticion para visualizar la data en la vista
                //el page lo recibe el metodo de lista cat
                me.listarAlumnos(page, criterio, buscar);
            },
        },
        
        mounted() {
            this.listarAlumnos(1, this.criterio, this.buscar);
            console.log(this.imagen);
        }
    }
        
</script>

<style>
.mostrar{
    display: list-item !important;
    opacity: 1 !important;
    position: absolute !important;
    background: #3c29297a !important; 
}
</style>