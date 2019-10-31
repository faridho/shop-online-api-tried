<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $table = 'category';
    protected $primaryKey = 'id';
    protected $fillable = [
      'category_name',
      'status'
    ];

    const CREATED_AT = 'created_date';
    const UPDATED_AT = 'updated_date';
}
