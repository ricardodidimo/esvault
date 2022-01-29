<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Credential extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'first_claim',
        'second_claim',
        'user_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
