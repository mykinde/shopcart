<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $table = 'items';
    
    use HasFactory;
    protected $fillable = ['name', 'image', 'price', 'description'];
}
