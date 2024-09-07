<?php

namespace App\Http\Controllers;

use App\Http\Requests\ContaRequest;
use App\Models\Conta;
use App\Models\SituacaoConta;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use PhpOffice\PhpWord\PhpWord;

class ContaController extends Controller
{
    // Listas as contas
    public function index(Request $request){
        
        // Recuperar o registro dos bancos de dados
        $contas = Conta::when($request->has('nome'), function($whenQuery) use ($request){
            $whenQuery->where('nome','like','%' . $request->nome . '%');
        })
        ->when($request->filled('data_inicio'), function($whenQuery) use ($request){
            $whenQuery->where('vencimento', '>=', \Carbon\Carbon::parse($request->data_inicio)->format('Y-m-d H:i:s'));
        }) 
        ->when($request->filled('data_fim'), function($whenQuery) use ($request){
            $whenQuery->where('vencimento', '<=', \Carbon\Carbon::parse($request->data_fim)->format('Y-m-d H:i:s'));
        }) 
        ->with('situacaoConta')
        ->orderByDesc("created_at")
        ->paginate(5)
        ->withQueryString();

        // Carregra a VIEW
        return view('contas.index', [
            'contas'=> $contas,
            'nome' => $request-> nome,
            'data_inicio' => $request-> data_inicio,
            'data_fim' => $request-> data_fim,
        ]);
    }

    // Detalhes da conta
    public function create(){

        // Recuperar do banco de dados as situações
        $situacoesContas = SituacaoConta::orderBy('nome', 'asc')->get();

        // Carregar a VIEW
        return view('contas.create', [
            'situacoesContas' => $situacoesContas,
        ]);
    }

    // Carregar o formulário cadastrar nova conta
    public function store(ContaRequest $request){

        // Validar o formulário 
        $request ->validated();

        try {

            // Carregar no banco de dados na tabela contas os valores de todos os campos
            $conta = Conta::create([
                'nome' => $request->nome,
                'valor' => str_replace(',','.', str_replace('.','',$request->valor)),
                'vencimento' => $request->vencimento,
                'situacao_conta_id' => $request->situacao_conta_id
            ]);
    
            // Redirecionar o usuário, enviar a mensagem sucesso
            return redirect()->route('conta.show',['conta' => $conta->id])->with('success', 'Conta cadastrada com sucesso!');

        }catch (Exception $e){

            // Salvar o Log
            Log::warning('Conta não editada!', ['error' => $e ->getMessage()]);
            // Redirecionar o usuário, enviar mensagem de error
            return back()->withInput()->with('error', 'Conta não cadastrada!');
        }
    }

    // Cadastrar no banco de dados nova conta
    public function show(Conta $conta){

        // Carregar a VIEW
        return view('contas.show', ['conta' => $conta]);
        
    }

    // Carregar o formulário editar a conta
    public function edit(Conta $conta){

        // Recuperar do banco de dados as situações
        $situacoesContas = SituacaoConta::orderBy('nome', 'asc')->get();

        // Carregar a VIEW
        return view('contas.edit', [
            'conta' => $conta,
            'situacoesContas' => $situacoesContas,
        ]);
    }

    // Editar no banco de dados a conta
    public function update(ContaRequest $request,Conta $conta){

        // Validar o formulário
        $request->validated();

        try{
            // Editar as informações que chegam no banco de dados
            $conta->update([
                'nome' => $request->nome,
                'valor' => str_replace(',', '.',str_replace('.', '',$request->valor)),
                'vencimento' => $request->vencimento,
                'situacao_conta_id' => $request->situacao_conta_id
            ]);

            // Salvar o Log
            Log::info('Conta editada com sucesso!', ['id' => $conta->id, 'conta' => $conta]);

            // Redirecionar o usuário, enviar mensagem de sucesso
            return redirect()->route('conta.show',['conta' => $conta->id])->with('success', 'Conta editada com sucesso!');
        }catch (Exception $e){

            // Salvar o Log
            Log::warning('Conta não editada!', ['error' => $e ->getMessage()]);
            // Redirecionar o usuário, enviar mensagem de error
            return back()->withInput()->with('error', 'Conta não editada!');
        }
    }

    // Excluir a conta do banco de dados
    public function destroy(Conta $conta){

        // Excluir o cadastro dos banco de dados
        $conta->delete();

        // Redirecionar o usuário, enviar mensagem de sucesso
        return redirect()->route('conta.index')->with('success', 'Conta apagada com sucesso!');
        
    }

    // Gerar PDF dos dados
    public function gerarPdf(Request $request){

        // Recuperar os registros dos bancos de dados
        // $contas = Conta::orderByDesc('created_at')->get();

        // Recuperar o registro dos bancos de dados
            $contas = Conta::when($request->has('nome'), function($whenQuery) use ($request){
            $whenQuery->where('nome','like','%' . $request->nome . '%');
        })
            ->when($request->filled('data_inicio'), function($whenQuery) use ($request){
            $whenQuery->where('vencimento', '>=', \Carbon\Carbon::parse($request->data_inicio)->format('Y-m-d H:i:s'));
        }) 
            ->when($request->filled('data_fim'), function($whenQuery) use ($request){
            $whenQuery->where('vencimento', '<=', \Carbon\Carbon::parse($request->data_fim)->format('Y-m-d H:i:s'));
        }) 
        ->orderByDesc("created_at")
        ->get();

        // Calcular a soma de todos os valores
        $totalValor = $contas->sum("valor");
        
        $pdf = PDF::loadView('contas.gerar-pdf', [
            'contas' => $contas,
            'totalValor' => $totalValor
            ])->setPaper('a4', 'portrait');

        return $pdf->download('listar_contas.pdf');
    }

    // Alterar situação da conta
    public function changeSituation(Conta $conta){

        try{

            // Editar as informações do registro no banco de dados
            $conta->update([
                'situacao_conta_id' => $conta->situacao_conta_id == 1 ? 2 : 1,
            ]);

            // Salvar o Log
            Log::info('Situação da conta editada com sucesso!', ['id' => $conta->id, 'conta' => $conta]);

            // Redirecionar o usuário, enviar mensagem de sucesso
            return back()->withInput()->with('success', 'Situação da conta editada com sucesso!');

        } catch (Exception $e) {

             // Salvar o Log
             Log::warning('Situação da conta não editada!', ['error' => $e ->getMessage()]);
             // Redirecionar o usuário, enviar mensagem de error
             return back()->withInput()->with('error', 'Situação da conta não editada!');

        }

    }

    // Gerar CSV
    public function gerarCsv(Request $request){

        // Recuperar o registro dos bancos de dados
        $contas = Conta::when($request->has('nome'), function($whenQuery) use ($request){
            $whenQuery->where('nome','like','%' . $request->nome . '%');
        })
            ->when($request->filled('data_inicio'), function($whenQuery) use ($request){
            $whenQuery->where('vencimento', '>=', \Carbon\Carbon::parse($request->data_inicio)->format('Y-m-d H:i:s'));
        }) 
            ->when($request->filled('data_fim'), function($whenQuery) use ($request){
            $whenQuery->where('vencimento', '<=', \Carbon\Carbon::parse($request->data_fim)->format('Y-m-d H:i:s'));
        }) 
        ->with('situacaoConta')
        ->orderBy("vencimento")
        ->get();

        // Calcular a soma de todos os valores
        $totalValor = $contas->sum("valor");

        // Criar o arquivo temporário
        $csvNomeArquivo = tempnam(sys_get_temp_dir(),"csv_" . Str::ulid());

        // Abrir o arquivo na forma de escrita
        $arquivoAberto = fopen($csvNomeArquivo,"w");

        // Criar o cabeçalho no Excel - Usar a função mb_convert_encoding para converter os caractéres especiais
        $cabecalho = ['id', 'Nome', 'Vencimento', mb_convert_encoding('Situação', 'ISO-8859-1', 'UTF-8'), 'Valor'];

        // Escrever o cabeçalho no arquivo
        fputcsv($arquivoAberto, $cabecalho, ';');

        // Ler os arquivos recuperados do banco de dados
        forEach($contas as $conta){

            // Criar o array com os dados da linha do Excel
            $contaArray = [
                'id' => $conta->id,
                'nome' => mb_convert_encoding($conta->nome, 'ISO-8859-1', 'UTF-8'),
                'vencimento' => Carbon::parse($conta->vencimento)->format('d/m/Y'),
                'situacao' => mb_convert_encoding($conta->situacaoConta->nome, 'ISO-8859-1', 'UTF-8'),
                'valor' => number_format($conta->valor, 2, ',', '.'),
            ];

            // Escrever o conteúdo no arquivo 
            fputcsv($arquivoAberto, $contaArray,';');
        }

        // Criar o rodapé do Excel
        $rodape = ['', '', '', '', number_format($totalValor, 2,',','.')];

        // Escrever o conteúdo no arquivo 
        fputcsv($arquivoAberto, $rodape,';');

        // Fechar o arquivo após a escrita
        fclose($arquivoAberto);

        // Realizar o download do arquivo
        return response()->download($csvNomeArquivo, 'relatorio_contas' . Str::ulid() . '.csv');
        
    }

    // Gera World
    public function gerarWorld(Request $request){

         // Recuperar o registro dos bancos de dados
         $contas = Conta::when($request->has('nome'), function($whenQuery) use ($request){
            $whenQuery->where('nome','like','%' . $request->nome . '%');
        })
            ->when($request->filled('data_inicio'), function($whenQuery) use ($request){
            $whenQuery->where('vencimento', '>=', \Carbon\Carbon::parse($request->data_inicio)->format('Y-m-d H:i:s'));
        }) 
            ->when($request->filled('data_fim'), function($whenQuery) use ($request){
            $whenQuery->where('vencimento', '<=', \Carbon\Carbon::parse($request->data_fim)->format('Y-m-d H:i:s'));
        }) 
        ->with('situacaoConta')
        ->orderBy("vencimento")
        ->get();

        // Calcular a soma de todos os valores
        $totalValor = $contas->sum("valor");

        // Criar uma instância no PhpWorld
        $phpWorld = new PhpWord();

        // Adicionar conteúdo ao documento
        $section = $phpWorld->addSection();

        // Adicionar uma tabela
        $table = $section->addTable();

        // Definir as configurações de borda
        $borderStyle = [
            'borderColor' => '000000',
            'borderSize' => 6, 
        ];

        // Adicionar o cabeçalho da tabela
        $table->addRow();
        $table->addCell(2000, $borderStyle)->addText('id');
        $table->addCell(2000, $borderStyle)->addText('Nome');
        $table->addCell(2000, $borderStyle)->addText('Vencimento');
        $table->addCell(2000, $borderStyle)->addText('Situação');
        $table->addCell(2000, $borderStyle)->addText('Valor');

        // Ler os reegistro recuperados do banco de dados
        foreach ($contas as $conta) {
            // Adicionar a linha da tabela
            $table->addRow();
            $table->addCell(2000, $borderStyle)->addText($conta->id);
            $table->addCell(2000, $borderStyle)->addText($conta->nome);
            $table->addCell(2000, $borderStyle)->addText(Carbon::parse($conta->vencimento)->format('d/m/Y'));
            $table->addCell(2000, $borderStyle)->addText($conta->situacaoConta->nome);
            $table->addCell(2000, $borderStyle)->addText(number_format($conta->valor, 2, ',', '.'));
        }

        // Adicionar o total na tabela
        $table->addRow();
        $table->addCell(2000)->addText('');
        $table->addCell(2000)->addText('');
        $table->addCell(2000)->addText('');
        $table->addCell(2000)->addText('');
        $table->addCell(2000, $borderStyle)->addText(number_format($totalValor, 2, ',', '.'));

        // Criar um nome para o arquivo
        $filename = 'relatorio_contas.docx';

        // Obter o caminho completo onde o arquivo gerado pelo PhpWorld será salvo
        $savePath = storage_path($filename);

        // Salvar o arquivo
        $phpWorld->save($savePath);

        // Forçar o download do arquivo no caminho indicado, após o download remover
        return response()->download($savePath)->deleteFileAfterSend(true);

    }   

}