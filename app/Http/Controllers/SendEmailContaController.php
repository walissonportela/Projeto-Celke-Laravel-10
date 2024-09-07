<?php

namespace App\Http\Controllers;

use App\Mail\SendEmailContaPagar;
use App\Models\Conta;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;


class SendEmailContaController extends Controller
{

    //Enviar email das contas pendentes
    public function sendEmailPendenteConta(){

        try {

            // Obter a data atual
            $dataAtual = Carbon::now()->toDateString();

            // Recuperar as contas do banco de dados
            $contas = Conta::whereDate("vencimento", $dataAtual)->with('situacaoConta')->get();

            // Enviar os dados para enviar o e-mail
            Mail::to(env('MAIL_TO'))->send(new SendEmailContaPagar($contas));

            // Redirecionar de volta à pagina anterior
            return back()->with('success', 'E-Mail enviado com sucesso!');

        } catch (Exception $e) {

            // Salvar o log
            Log::warning('E-Mail não enviado.', ['error'=> $e->getMessage()]);

            // Redirecionar o usuário, enviar a mensagem de erro
            return back()->with('error','E-Mail não enviado!');

        }
    }
    
}
