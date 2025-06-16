<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Action extends Model
{
    use HasFactory;
    public function getCamera() {
        return $this->belongsTo(Camera::class, 'camera_id', 'id');
    }
}
