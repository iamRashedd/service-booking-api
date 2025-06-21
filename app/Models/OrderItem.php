<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrderItem extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = ['id'];
    
    protected $appends = ['sub_total_price'];
    
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

    public function order(){
        return $this->belongsTo(Order::class);
    }
    public function service(){
        return $this->belongsTo(Service::class);
    }
    public function getSubTotalPriceAttribute(){
        return $this->service_price*$this->quantity;
    }
}
