<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $table = 'product';
    protected $primaryKey = 'id';
    protected $fillable = [
      'id',
      'category',
      'name',
      'stock',
      'price',
      'status',
      'thumbnail'
    ];

    const CREATED_AT = 'created_date';
    const UPDATED_AT = 'updated_date';
}
