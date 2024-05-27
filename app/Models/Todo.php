<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Todo extends Model
{
    use HasFactory;

    protected $fillable = [
        'project',
        'name_requested',
        'dept',
        'desc_project',
        'status_project',
        'requested_date',
        'deadline',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'requested_date' => 'date',
        'deadline' => 'date',
       
    ];
}
