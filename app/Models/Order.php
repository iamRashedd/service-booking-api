<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use HasFactory, SoftDeletes;

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

    public const IN_PROGRESS = 'in-progress';
    public const CANCELLED = 'cancelled';
    public const COMPLETED = 'completed';

    public function user(){
        return $this->belongsTo(User::class);
    }
    public function order_items(){
        return $this->hasMany(OrderItem::class);
    }
}
