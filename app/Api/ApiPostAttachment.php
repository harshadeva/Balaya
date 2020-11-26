<?php

namespace App\Api;

use App\Post;
use Illuminate\Database\Eloquent\Model;

class ApiPostAttachment extends Model
{
    protected $table = 'post_attachment';
    protected $primaryKey = 'idpost_attachment';
    protected $appends = ['api_path'];
    protected $visible = ['api_path','file_type'];

    public function post()
    {
        return $this->belongsTo(Post::class, 'idPost');
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
