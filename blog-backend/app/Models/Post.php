<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    // Define the table name if different from the plural form of the model name
    protected $table = 'posts';

    // Define the fillable attributes to allow mass assignment
    protected $fillable = [
        'title',
        'content',
        'image',
        'user_id', // User ID for the creator of the post
    ];

    // Set up the relationship with the User model (each post has a user creator)
    public function user()
    {
        return $this->belongsTo(User::class);  // Assumes you have a User model
    }

    public function getImageUrlAttribute()
    {
        return url('storage/' . $this->image);
    }

    // Optionally, you can add validation rules as a function
    public static function rules()
    {
        return [
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ];
    }
}

