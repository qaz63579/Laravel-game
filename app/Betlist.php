<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Betlist extends Model
{
    protected $table = "betlist";
    protected $primanykey = "id";
    public $timestamps = false;
}
