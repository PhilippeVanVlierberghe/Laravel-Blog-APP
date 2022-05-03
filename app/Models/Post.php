<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $fillable = ['title', 'content'];

    public function likes(){
        return $this->hasMany(Like::class, 'post_id');
    }

    public function tags(){
        return $this->belongsToMany(Tag::class, 'post_tag', 'post_id', 'tag_id')->withTimestamps();
    }

    public function user(){
        return $this->belongsTo(User::class);
    }
    // Lavarel data Mutator  => store data as 
    // Laravel data Accessor => show data as
    // Laravel Pagination in de postcontroller
    //dit is een comment
}
