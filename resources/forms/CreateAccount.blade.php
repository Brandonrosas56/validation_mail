@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Crear Solicitud</h1>
    <form action="{{ route('create-account.store') }}" method="POST">
        @csrf
        <div>
            <label for="regional">Regional</label>
            <input type="text" name="regional" id="regional" value="{{ old('regional') }}" required>
        </div>
        <div>
            <label for="primer_nombre">Primer Nombre</label>
            <input type="text" name="primer_nombre" id="primer_nombre" value="{{ old('primer_nombre') }}" required>
        </div>
        <div>
            <label for="segundo_nombre">Segundo Nombre</label>
            <input type="text" name="segundo_nombre" id="segundo_nombre" value="{{ old('segundo_nombre') }}">
        </div>
        <div>
            <label for="primer_apellido">Primer Apellido</label>
            <input type="text" name="primer_apellido" id="primer_apellido" value="{{ old('primer_apellido') }}" required>
        </div>
        <div>
            <label for="segundo_apellido">Segundo Apellido</label>
            <input type="text" name="segundo_apellido" id="segundo_apellido" value="{{ old('segundo_apellido') }}">
        </div>
        <div>
            <label for="correo_personal">Correo Personal</label>
            <input type="email" name="correo_personal" id="correo_personal" value="{{ old('correo_personal') }}" required>
        </div>
        <div>
            <label for="numero_contrato">Número de Contrato</label>
            <input type="text" name="numero_contrato" id="numero_contrato" value="{{ old('numero_contrato') }}" required>
        </div>
        <div>
            <label for="fecha_inicio_contrato">Fecha de Inicio del Contrato</label>
            <input type="date" name="fecha_inicio_contrato" id="fecha_inicio_contrato" value="{{ old('fecha_inicio_contrato') }}" required>
        </div>
        <div>
            <label for="fecha_terminacion_contrato">Fecha de Terminación del Contrato</label>
            <input type="date" name="fecha_terminacion_contrato" id="fecha_terminacion_contrato" value="{{ old('fecha_terminacion_contrato') }}" required>
        </div>
        <button type="submit">Enviar</button>
    </form>
</div>
@endsection
