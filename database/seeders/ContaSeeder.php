<?php

namespace Database\Seeders;

use App\Models\Conta;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ContaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        if(!Conta::where('nome', 'Energia')->first()){
            Conta::create([
                'nome'=> 'Energia',
                'valor'=> '147.52',
                'vencimento'=> '2024-08-31',
            ]);
        }

        if(!Conta::where('nome', 'Internet')->first()){
            Conta::create([
                'nome'=> 'Internet',
                'valor'=> '90.52',
                'vencimento'=> '2024-08-31',
            ]);
        }

        if(!Conta::where('nome', 'Água')->first()){
            Conta::create([
                'nome'=> 'Água',
                'valor'=> '75.30',
                'vencimento'=> '2024-08-31',
            ]);
        }
        
        if(!Conta::where('nome', 'Telefone')->first()){
            Conta::create([
                'nome'=> 'Telefone',
                'valor'=> '45.90',
                'vencimento'=> '2024-09-05',
            ]);
        }
        
        if(!Conta::where('nome', 'Aluguel')->first()){
            Conta::create([
                'nome'=> 'Aluguel',
                'valor'=> '1200.00',
                'vencimento'=> '2024-09-10',
            ]);
        }
        
        if(!Conta::where('nome', 'Condomínio')->first()){
            Conta::create([
                'nome'=> 'Condomínio',
                'valor'=> '350.00',
                'vencimento'=> '2024-09-10',
            ]);
        }
        
        if(!Conta::where('nome', 'Cartão de Crédito')->first()){
            Conta::create([
                'nome'=> 'Cartão de Crédito',
                'valor'=> '780.45',
                'vencimento'=> '2024-09-15',
            ]);
        }
        
        if(!Conta::where('nome', 'Academia')->first()){
            Conta::create([
                'nome'=> 'Academia',
                'valor'=> '120.00',
                'vencimento'=> '2024-09-20',
            ]);
        }
        
        if(!Conta::where('nome', 'Netflix')->first()){
            Conta::create([
                'nome'=> 'Netflix',
                'valor'=> '55.90',
                'vencimento'=> '2024-09-25',
            ]);
        }
        
        if(!Conta::where('nome', 'Amazon Prime')->first()){
            Conta::create([
                'nome'=> 'Amazon Prime',
                'valor'=> '29.90',
                'vencimento'=> '2024-09-25',
            ]);
        }
        
        if(!Conta::where('nome', 'Gasolina')->first()){
            Conta::create([
                'nome'=> 'Gasolina',
                'valor'=> '300.00',
                'vencimento'=> '2024-09-28',
            ]);
        }
        
        if(!Conta::where('nome', 'IPTU')->first()){
            Conta::create([
                'nome'=> 'IPTU',
                'valor'=> '200.00',
                'vencimento'=> '2024-10-01',
            ]);
        }
        
        if(!Conta::where('nome', 'Seguro Carro')->first()){
            Conta::create([
                'nome'=> 'Seguro Carro',
                'valor'=> '450.00',
                'vencimento'=> '2024-10-05',
            ]);
        }
        
        if(!Conta::where('nome', 'Manutenção Carro')->first()){
            Conta::create([
                'nome'=> 'Manutenção Carro',
                'valor'=> '600.00',
                'vencimento'=> '2024-10-10',
            ]);
        }
        
        if(!Conta::where('nome', 'Plano de Saúde')->first()){
            Conta::create([
                'nome'=> 'Plano de Saúde',
                'valor'=> '800.00',
                'vencimento'=> '2024-10-15',
            ]);
        }
        
        if(!Conta::where('nome', 'Streaming de Música')->first()){
            Conta::create([
                'nome'=> 'Streaming de Música',
                'valor'=> '19.90',
                'vencimento'=> '2024-10-20',
            ]);
        }
        
        if(!Conta::where('nome', 'Seguro Residencial')->first()){
            Conta::create([
                'nome'=> 'Seguro Residencial',
                'valor'=> '250.00',
                'vencimento'=> '2024-10-25',
            ]);
        }
        
    }
}
