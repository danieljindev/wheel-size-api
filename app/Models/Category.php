<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        "parent_id",
        "title",
        "children",
        "depth"
    ];

    public function subcategory()
    {
        return $this->hasMany('App\Category', 'parent_id');
    }
}
