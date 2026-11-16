<?php

namespace App\Models\Contrasis;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class Contrasis extends Model
{

    protected $table = 'contracts';
    protected $connection = 'contrasis';
    
}
