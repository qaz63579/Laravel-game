<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Record extends Model
{
    protected $table = "Record";
    protected $primanykey = "id";
    public $timestamps = false;
}
