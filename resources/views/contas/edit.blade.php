@extends('layouts.admin')

@section('content')

    <div class="card mt-4 mb-4 border-light shadow">
        <div class="card-header d-flex justify-content-between">
            <span>Editar conta</span>
            <span>
                <a href="{{ route('conta.index') }}" class="btn btn-info btn-sm me-1">Listar</a>
                <a href="{{ route('conta.show', ['conta' => $conta->id]) }}"  
                    class="btn btn-primary btn-sm me-1">Visualizar</a>
            </span>
        </div>

        {{-- Verificar se existe a sessão success e imprimir o valor --}}
        <x-alert/>

        <div class="card-body">

            <form action="{{ route('conta.update', ['conta' => $conta->id])}}" method="POST" class="row g-3" >
                @csrf
                @method('PUT')

                <div class="col-md-12 col-sm-12">
                    <label for="nome" class="form-label">Nome</label>
                    <input type="text" name="nome" class="form-control" id="nome" 
                    placeholder="Nome da Conta" value={{old('nome', $conta->nome)}}>
                </div>

                <div class="col-md-4 col-sm-12">
                    <label for="valor" class="form-label">Valor</label>
                    <input type="text" name="valor" class="form-control" id="valor" 
                    placeholder="Valor da Conta" value={{old('valor', isset($conta->valor) ? number_format($conta->valor, '2', ',', '.') : '')}}>
                </div>

                <div class="col-md-4 col-sm-12">
                    <label for="vencimento" class="form-label">Vencimento</label>
                    <input type="date" name="vencimento" class="form-control" id="vencimento" 
                    placeholder="Vencimento da Conta" value={{old('vencimento', $conta->vencimento)}}>
                </div>

                <div class="col-md-4 col-sm-12">
                    <label for="situacao_conta_id" class="form-label">Situação da Conta</label>
                    <select name="situacao_conta_id" id="situacao_conta_id" class="form-select select2">
                        <option value="">Selecione</option>
                        @forelse ($situacoesContas as $situacaoConta)
                            <option value="{{ $situacaoConta->id }}" 
                                {{ old('situacao_conta_id', $conta->situacao_conta_id) == $situacaoConta->id ? 'selected' : ''}}>
                                {{ $situacaoConta->nome }}</option>
                        @empty
                            <option value="">Nenhuma situação da conta encontrada!</option>
                        @endforelse
                    </select>
                </div>

                <div class="col-12">
                    <button type="submit" class="btn btn-warning btn-sm">Salvar</button>
                </div>

            </form>

        </div>
    </div>
 @endsection
