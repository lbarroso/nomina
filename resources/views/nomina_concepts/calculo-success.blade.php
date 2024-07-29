@extends('layouts.main')

@section('content')
    <div class="card text-center">
        <div class="card-header bg-success text-white">
            <h2>Cálculo Realizado Correctamente</h2>
        </div>
        <div class="card-body">
            <div class="my-4">
                <i class="fas fa-check-circle fa-5x text-success"></i>
            </div>
            <p class="card-text">El cálculo se ha realizado correctamente.</p>
            <a href="{{ route('impresion.tabla') }}" class="btn btn-primary">
                <i class="fas fa-arrow-right"></i> Continuar
            </a>
        </div>
    </div>
@endsection

<style>
    .card-header {
        background-color: #28a745;
    }
    .card-body {
        background-color: #f8f9fa;
    }
</style>