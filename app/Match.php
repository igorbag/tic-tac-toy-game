<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Match extends Model
{
    /**
     * All atributes of database
     */
    protected $fillable = [
        'name',
        'next',
        'winner',
        'board'
    ];
    
    /**
     * Cast database information to array structure for tic tac toy board
     */
    protected $casts = [
        'board' => 'array'
    ];
}
