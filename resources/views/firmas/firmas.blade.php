@extends('layouts.main')

@section('content')

<div class="card">
    <div class="card-header">
        <h2>Actualizar Firmas</h2>
    </div>
    
    <div class="card-body">
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('firmas.update') }}" method="POST">
            @csrf
            
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="elaboro">Elaboró Cargo</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fas fa-user-tie"></i></span>
                        </div>
                        <input type="text" class="form-control" id="elaboro" name="elaboro" value="{{ old('elaboro', $firma->elaboro) }}" onFocus="this.select()" onkeyup="this.value = this.value.toUpperCase();" >
                    </div>
                </div>
                <div class="form-group col-md-6">
                    <label for="elaboroNombre">Elaboró Nombre</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fas fa-user"></i></span>
                        </div>
                        <input type="text" class="form-control" id="elaboroNombre" name="elaboroNombre" value="{{ old('elaboroNombre', $firma->elaboroNombre) }}" onFocus="this.select()" onkeyup="this.value = this.value.toUpperCase();" required>
                    </div>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="valido">Valido Cargo</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fas fa-eye"></i></span>
                        </div>
                        <input type="text" class="form-control" id="valido" name="valido" value="{{ old('valido', $firma->valido) }}" onFocus="this.select()" onkeyup="this.value = this.value.toUpperCase();" >
                    </div>
                </div>
                <div class="form-group col-md-6">
                    <label for="validoNombre">Valido Nombre</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fas fa-user-check"></i></span>
                        </div>
                        <input type="text" class="form-control" id="validoNombre" name="validoNombre" value="{{ old('validoNombre', $firma->validoNombre) }}" onFocus="this.select()" onkeyup="this.value = this.value.toUpperCase();" required>
                    </div>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="autorizo">Autorizó Cargo</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fas fa-user-shield"></i></span>
                        </div>
                        <input type="text" class="form-control" id="autorizo" name="autorizo" value="{{ old('autorizo', $firma->autorizo) }}" onFocus="this.select()" onkeyup="this.value = this.value.toUpperCase();" >
                    </div>
                </div>
                <div class="form-group col-md-6">
                    <label for="autorizoNombre">Autorizó Nombre</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fas fa-user-tie"></i></span>
                        </div>
                        <input type="text" class="form-control" id="autorizoNombre" name="autorizoNombre" value="{{ old('autorizoNombre', $firma->autorizoNombre) }}" onFocus="this.select()" onkeyup="this.value = this.value.toUpperCase();" required>
                    </div>
                </div>
            </div>
			

            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="reviso">Valido Cargo</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fas fa-user-edit"></i></span>
                        </div>
                        <input type="text" class="form-control" id="reviso" name="reviso" value="{{ old('reviso', $firma->reviso) }}" onFocus="this.select()" onkeyup="this.value = this.value.toUpperCase();" >
                    </div>
                </div>
                <div class="form-group col-md-6">
                    <label for="revisoNombre">Valido Nombre</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fas fa-user-cog"></i></span>
                        </div>
                        <input type="text" class="form-control" id="revisoNombre" name="revisoNombre" value="{{ old('revisoNombre', $firma->revisoNombre) }}" onFocus="this.select()" onkeyup="this.value = this.value.toUpperCase();" >
                    </div>
                </div>
            </div>			

            <button type="submit" class="btn btn-primary">Actualizar</button>
        </form>
    </div>
</div>

@endsection
