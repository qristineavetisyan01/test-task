<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lead extends Model
{
    public const STATUSES = ['new', 'contacted', 'qualified', 'lost'];

    protected $fillable = [
        'name',
        'email',
        'phone',
        'company',
        'status',
        'notes',
    ];
}
