<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Blog extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'body']; // データベース一括登録する際に設定が必要

    public function category() {
        return $this->belongsTo(Category::class);
    }

    public function cats() {
        return $this->belongsToMany(Cat::class)->withTimestamps();
    }
}
