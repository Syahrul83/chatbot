<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChatInteraction extends Model
{
    protected $fillable = ['question', 'answer', 'user_id']; // Specify the fillable fields for mass assignment
}
