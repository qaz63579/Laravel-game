<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Gamelist extends Model
{
    protected $table = 'gamelist';
    protected $primanykey = 'id';
    public $timestamps = 'false';
}
