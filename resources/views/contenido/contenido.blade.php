@extends('layout.principal')
@section('contenido')

@if (Auth::check())
    {{-- autenticado como Administrador --}}
    @if(Auth::user()->id_rol==1)

      <template v-if="menu==0">
        <contenido-component></contenido-component>
      </template>
    
      <template v-if="menu==1">
        <categoria-component></categoria-component>
      </template>
    
      <template v-if="menu==2">
        <articulo-component></articulo-component>
      </template>
    
      <template v-if="menu==3">
        <ingreso-component></ingreso-component>
      </template>
    
      <template v-if="menu==4">
        <proveedor-component></proveedor-component>
      </template>
    
      <template v-if="menu==5">
        <venta-component></venta-component>
      </template>
    
      <template v-if="menu==6">
        <cliente-component></cliente-component>
      </template>
    
      <template v-if="menu==7">
        <usuario-component></usuario-component>
      </template>
    
      <template v-if="menu==8">
        <rol-component></rol-component>
      </template>
    
      <template v-if="menu==9">
        <h1>Reporte Ingresos</h1>
      </template>
    
      <template v-if="menu==10">
        <h1>Reporte Ventas</h1>
      </template>
    
      <template v-if="menu==11">
        <h1>Ayuda </h1>
      </template>
    
      <template v-if="menu==12">
        <h1>Ac Acerca de...ceso</h1>
      </template>
      
      <template v-if="menu==13">
        <prueba-component></prueba-component>
      </template>

    {{-- autenticado como Vendedor--}}
    @elseif(Auth::user()->id_rol==2)

      <template v-if="menu==5">
        <venta-component></venta-component>
      </template>
    
      <template v-if="menu==6">
        <cliente-component></cliente-component>
      </template>

      <template v-if="menu==10">
        <h1>Reporte Ventas</h1>
      </template>
    
      <template v-if="menu==11">
        <h1>Ayuda </h1>
      </template>
    
      <template v-if="menu==12">
        <h1>Ac Acerca de...ceso</h1>
      </template>
      
      <template v-if="menu==13">
        <prueba-component></prueba-component>
      </template>

    {{-- autenticado como Almacenero --}}
    @elseif(Auth::user()->id_rol==3)

    <template v-if="menu==1">
      <categoria-component></categoria-component>
    </template>
  
    <template v-if="menu==2">
      <articulo-component></articulo-component>
    </template>
  
    <template v-if="menu==3">
      <ingreso-component></ingreso-component>
    </template>
  
    <template v-if="menu==4">
      <proveedor-component></proveedor-component>
    </template>

    <template v-if="menu==9">
      <h1>Reporte Ingresos</h1>
    </template>
  
    <template v-if="menu==11">
      <h1>Ayuda </h1>
    </template>
  
    <template v-if="menu==12">
      <h1>Ac Acerca de...ceso</h1>
    </template>
    
    <template v-if="menu==13">
      <prueba-component></prueba-component>
    </template>

    @else

    @endif

@endif



@endsection
