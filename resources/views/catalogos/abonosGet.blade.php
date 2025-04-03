@extends("components.layout")
@section("content")
@component("components.breadcrumbs",["breadcrumbs"=>$breadcrumbs])
@endcomponent

<div class="row my-4">
    <div class="col">
        <h1>Empleados</h1>
    </div>
    <div class="col-auto titlebar-commands">
        <a class = "btn btn-primary" href="{{url('/abonos/agregar')}}">Agregar</a>
    </div>
</div>

<table class="table" id="maintable">
<thead>
    <tr>
        <th scope="col">ID</th>
        <th scope="col">ID PRESTAMO</th>
        <th scope="col">NUM ABONO</th>
        <th scope="col">MONTO CAPITAL</th>
        <th scope="col">MONTO OTROS</th>
        <th scope="col">MONTO COLOCADO</th>
        <th scope="col">SALDO PENDIENTE</th>
        <th scope="col">ACCIONES</th>
    </tr>
</thead>
<tbody>
@foreach($abonos as $abono)
    <tr>
        <td class="text-center">{{$abono->id_empleado}}</td>
        <td class="text-center">{{$abono->nombre}}</td>
        <td class="text-center">{{$abono->fecha_ingreso}}</td>
        <td class="text-center">{{$abono->activo}}</td>
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