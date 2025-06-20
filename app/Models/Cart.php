<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;
    
    protected $guarded = ['id'];
    
    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];
    
    protected $casts = [
        "created_at" => 'datetime:d-m-Y H:i a',
        "updated_at" => 'datetime:d-m-Y H:i a',
        "deleted_at" => 'datetime:d-m-Y H:i a'
    ];

    public const ACTIVE = 'active';
    public const CLOSED = 'closed';

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function cart_items(){
        return $this->hasMany(CartItem::class);
    }

}
