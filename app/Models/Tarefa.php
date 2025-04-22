<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tarefa extends Model
{
    protected $fillable = ['nome', 'custo', 'data_limite', 'ordem'];
}
