<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PrimaryCategory extends Model
{
    public function secondaryCategories()
    {
        // 1対多のリレーションを定義する
        return $this->hasMany(SecondaryCategory::class);
    }
}
