<template>
      <main class="main">
    <!-- Breadcrumb -->
    <ol class="breadcrumb">
        <!-- <li class="breadcrumb-item">Home</li>
        <li class="breadcrumb-item"><a href="#">Admin</a></li>
        <li class="breadcrumb-item active">Dashboard</li> -->
        <li class="breadcrumb-item" ><a href="/principal"  >Escritorio</a></li>
    </ol>
    <div class="container-fluid">
        <!-- Ejemplo de tabla Listado -->
        <div class="card">
            <div class="card-header">
                <i class="fa fa-align-justify"></i> Roles
                <!-- en el metodo abrir modal envio 2 elementos -->
                
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
                            @keyup.enter="listarRoles(1, criterio, buscar)">
                            <button type="button" @click="listarRoles(1, criterio, buscar)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                        </div>
                    </div>
                </div>
                <table class="table table-bordered table-striped table-sm">
                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Descripción</th>
                            <th>Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- cnsidrar la :key  para identificar la id de la table-->
                        <tr v-for="rol in roles" :key="rol.id_rol">
                            
                            <td v-text="rol.nombre"></td>
                            <td v-text="rol.descripcion"></td>
                            <td>
                                <div v-if="rol.condicion">
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



    
</main>  
</template>

<script>
    export default {
        data(){
            return{
                 id_rol:0,
                 prueba: 'hola mundo',
                 nombre:'',
                 descripcion:'',
                 roles:[],
                 //variable o idemficador del modal
                 modal: 0,
                 //titulo dle modal
                 tituloModal : '',
                 //logica del boton
                 tipoAccion: 0,
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
            listarRoles(page, criterio, buscar){
                let atrapar = this;
                //recibo el page dentro de la url de controller
                var url = '/roles?page=' + page + '&buscar=' + buscar + '&criterio=' + criterio;
                //capturo en la peticion la url
                axios.get(url).then(function (json) {

                    var  respuesta = json.data;
                    //capturo datos
                    atrapar.roles = respuesta.roles.data;
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
                me.listarRoles(page, criterio, buscar);
            },

           

        },
        mounted() {
            this.listarRoles(1, this.criterio, this.buscar);
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