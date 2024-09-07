@extends('layouts.email')

@section('content')

    <p>Olá, </p>

    <p>Contas a pagar: </p>

    @foreach ($contas as $conta)

        - <strong><a href="{{ route('conta.show', ['conta' => $conta->id])}}" style="text-decoration: underline;">
            {{ $conta->nome }}</a></strong>: R$ {{ number_format($conta->valor, 2, ',', '.')}} -
            {{ $conta->situacaoConta->nome}} - {{ \Carbon\Carbon::parse($conta->vencimento)->format('d/m/Y')}} <br>
    
    @endforeach

    <br><br>

    <p>E-Mail enviado pelo sistema Celke.</p>

@endsection