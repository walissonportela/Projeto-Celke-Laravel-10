<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SituacaoConta extends Model
{
    use HasFactory;

    // Indicar o nome da tabela
    protected $table = "situacoes_contas";

    // Indicar quais colunas podem ser cadastradas
    protected $fillable = ['nome', 'cor'];

    // Tabela pai usa hasMany (SitucaoConta - hasMany - Conta)
    public function conta() {
        return $this->hasMany(Conta::class);
    }
}

