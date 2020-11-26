<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DirectMessage extends Model
{
    protected $table = 'direct_message';
    protected $primaryKey = 'iddirect_message';
    protected $appends = array('full_path');

    public function toUser()
    {
        return $this->belongsTo(User::class, 'to_idUser');
    }

    public function fromUser()
    {
        return $this->belongsTo(User::class, 'from_idUser');
    }

    public function getFullPathAttribute()
    {
        if ($this->message_type == 2) {
            return 'storage/' . $this->fromUser->office->random . '/messages/images/' . $this->attachment;
        } else if ($this->message_type == 3) {
            return 'storage/' . $this->fromUser->office->random . '/messages/videos/' . $this->attachment;
        } else if ($this->message_type == 4) {
            return 'storage/' . $this->fromUser->office->random . '/messages/audios/' . $this->attachment;
        } else {
            return '';
        }
    }
}
