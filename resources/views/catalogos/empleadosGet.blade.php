@extends("components.layout")
@section("content")
@component("components.breadcrumbs",["breadcrumbs"=>$breadcrumbs])
@endcomponent

<div class="row my-4">
    <div class="col">
        <h1>Empleados</h1>
    </div>
    <div class="col-auto titlebar-commands">
        <a class = "btn btn-primary" href="{{url('/empleados/agregar')}}">Agregar</a>
    </div>
</div>

<table class="table" id="maintable">
<thead>
    <tr>
        <th scope="col">ID</th>
        <th scope="col">NOMBRE</th>
        <th scope="col">FECHA INGRESO</th>
        <th scope="col">ACTIVO</th>
        <th scope="col">ACCIONES</th>
    </tr>
</thead>
<tbody>
@foreach($empleados as $empleado)
    <tr>
        <td class="text-center">{{$empleado->id_empleado}}</td>
        <td class="text-center">{{$empleado->nombre}}</td>
        <td class="text-center">{{$empleado->fecha_ingreso}}</td>
        <td class="text-center">{{$empleado->activo}}</td>
        <td class="text-center">
            <a href='{{url("/empleados/{$empleado->id_empleado}/puestos")}}'>Puestos</a> <!-- enlace al histórico de puestos -->
            <a href='{{url("/empleados/{$empleado->id_empleado}/prestamos")}}'>Prestamos</a> <!-- enlace al histórico de préstamos -->
        </td>
    </tr>
@endforeach

</tbody>
</table>
<script>

</script>
@endsection