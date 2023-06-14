<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Category extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $guarded = [];

    public function creator_info(){
        return $this->belongsTo(User::class,'creator','id' );
    }

    public function editor_info(){
        return $this->belongsTo(User::class,'editor','id' );
    }
}
