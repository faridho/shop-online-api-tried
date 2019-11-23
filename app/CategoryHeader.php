<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CategoryHeader extends Model
{
  protected $table = 'category_header';
  protected $primaryKey = 'id';
  protected $fillable = [
    'name',
    'status'
  ];

  const CREATED_AT = 'created_date';
  const UPDATED_AT = 'updated_date';
}
