<?php

namespace App\Models;

use AnourValar\EloquentSerialize\Tests\Models\Post;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'parent_id',
    ];

    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }
    public function posts()
    {
        return $this->hasMany(Post::class);
    }
    public function blogs()
    {
        return $this->hasMany(Blog::class);
    }
}
