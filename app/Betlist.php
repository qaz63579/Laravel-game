<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Betlist extends Model
{
    // protected $connection = 'pi';
    protected $table = "betlist";
    protected $primanykey = "id";
    public $timestamps = false;
}
