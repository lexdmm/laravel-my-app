<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $fillable = [
        'title',
        'description',
        'scheduled_date',
        'scheduled_time',
        'status',
        'priority',
    ];

    protected $casts = [
        'scheduled_date' => 'date',
    ];
}
