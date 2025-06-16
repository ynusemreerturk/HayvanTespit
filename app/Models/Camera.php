<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Camera extends Model
{
    use HasFactory;
    public function getUser() {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
    public function getCamera() {
        return $this->hasMany(Action::class, 'camera_id', 'id')->withTrashed();
    }
}
