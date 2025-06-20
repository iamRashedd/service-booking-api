<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
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

    public function cart(){
        return $this->belongsTo(Cart::class);
    }
    public function service(){
        return $this->belongsTo(Service::class);
    }
}
