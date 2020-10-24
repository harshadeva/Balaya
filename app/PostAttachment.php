<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PostAttachment extends Model
{
    protected $table = 'post_attachment';
    protected $primaryKey = 'idpost_attachment';
    protected $appends = ['full_path','api_path'];

    public function post()
    {
        return $this->belongsTo(Post::class, 'idPost');
    }

    public function getPath()
    {
        if ($this->file_type == 1) {
            return 'storage/' . $this->post->user->office->random . '/posts/images/';
        } else if ($this->file_type == 2) {
            return 'storage/' . $this->post->user->office->random . '/posts/videos/';
        } else if ($this->file_type == 3) {
            return 'storage/' . $this->post->user->office->random . '/posts/audios/';
        } else {
            return 'false';
        }
    }

    public function getFullPathAttribute()
    {
        if ($this->file_type == 1) {
            return 'storage/' . $this->post->user->office->random . '/posts/images/'.$this->attachment;
        } else if ($this->file_type == 2) {
            return 'storage/' . $this->post->user->office->random . '/posts/videos/'.$this->attachment;
        } else if ($this->file_type == 3) {
            return 'storage/' . $this->post->user->office->random . '/posts/audios/'.$this->attachment;
        } else {
            return 'false';
        }
    }

    public function getApiPathAttribute()
    {
        if ($this->file_type == 1) {
            return asset('').'storage/' . $this->post->user->office->random . '/posts/images/'.$this->attachment;
        } else if ($this->file_type == 2) {
            return asset('').'storage/' . $this->post->user->office->random . '/posts/videos/'.$this->attachment;
        } else if ($this->file_type == 3) {
            return asset('').'storage/' . $this->post->user->office->random . '/posts/audios/'.$this->attachment;
        } else {
            return 'false';
        }
    }
}
