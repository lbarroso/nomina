@extends('layouts.main')

@section('content')
    <div class="card text-center">
        <div class="card-header bg-warning text-white">
            <h2>Reseteo de Movimientos</h2>
        </div>
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            <p class="card-text">Esta opción permitirá borrar todos los movimientos de la semana seleccionada.</p>
            <form action="{{ route('reset.movements') }}" method="POST">
                @csrf

                <div class="form-row justify-content-center">
                    <div class="form-group col-md-4">
                        <label for="semana">Semana</label>
                        <select name="semana" id="semana" class="form-control" required>
                            @for ($i = 1; $i <= 52; $i++)
                                <option value="{{ $i }}">Semana {{ $i }}</option>
                            @endfor
                        </select>
                    </div>
                </div>

                <button type="submit" class="btn btn-danger">
                    <i class="fas fa-trash-alt"></i> Resetear Movimientos
                </button>
            </form>
        </div>
    </div>
@endsection

<style>
    .card-header {
        background-color: #ffc107;
    }
    .card-body {
        background-color: #f8f9fa;
    }
</style>
