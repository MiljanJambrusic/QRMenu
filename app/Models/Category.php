<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    public static function CategoriesList(){
        return Category::select('id','name')->orderByDesc('name')->get();
    }

    protected $guarded = [];
}
