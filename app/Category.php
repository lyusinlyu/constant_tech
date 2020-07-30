<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $table = 'categories';
    protected $fillable = ['name', 'order', 'user_id', 'parent_id'];

    public function childrenCategories()
    {
        return $this->hasMany(self::class, 'parent_id', 'id')->orderBy('order', 'ASC');
    }

    public function allChildrenCategories()
    {
        return $this->childrenCategories()->with('allChildrenCategories');
    }
}
