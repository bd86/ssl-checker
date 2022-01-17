<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SslList extends Model
{
    protected $table = "site_list";
    protected $fillable = ['site', 'expire_date'];
    protected $dates = ['created_at', 'updated_at', 'expire_date'];
    protected $casts = [
        'expire_date' => 'date:m/d/Y'
    ];
    use HasFactory;
}
