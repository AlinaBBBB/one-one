<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Query extends Model
{
    use HasFactory;
    protected $table = 'queries';
    public $incrementing = true;
    protected $primarykey = 'query_id';
    protected $fillable = [
        "query_id",
        "user_id",
        "category_id",
        "status",
        "photo_before",
        "photo_after",
        "description",
        "title",
        "created_at",
        "updated_at",
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }
}

