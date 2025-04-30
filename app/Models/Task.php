<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $fillable = [
        'user_id',
        'title',
        'description',
        'due_date',
        'priority',
        'status',
        'category_id',
        ' user_id',
    ];
    public function category()
{
    return $this->belongsTo(Category::class);
}
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
