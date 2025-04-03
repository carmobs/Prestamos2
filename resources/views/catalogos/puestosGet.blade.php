@extends("components.layout")
@section("content")
@component("components.breadcrumbs",["breadcrumbs"=>$breadcrumbs])
@endcomponent

<div class="row my-4">
    <div class="col">
        <h1>Puestos</h1>
    </div>
    <div class="col-auto titlebar-commands">
        <a class = "btn btn-primary" href="{{url('/catalogos/puestos/agregar')}}">Agregar</a>
    </div>
</div>

<table class="table" id="maintable">
<thead>
    <tr>
        <th scope="col">ID</th>
        <th scope="col">Puesto</th>
        <th scope="col">SUELDO</th>
    </tr>
</thead>
<tbody>
@foreach($puestos as $puesto)
    <tr>
        <td class="text-center">{{$puesto->id_puesto}}</td>
        <td class="text-center">{{$puesto->nombre}}</td>
        <td class="text-center">{{$puesto->sueldo}}</td>
    </tr>
@endforeach
</tbody>
</table>
<script>

</script>
@endsection
