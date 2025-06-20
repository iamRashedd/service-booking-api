<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
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
    
    public function services(){
        return $this->hasMany(Service::class);
    }
    
    protected static function booted()
    {
        static::deleting(function ($category) {
            if (! $category->isForceDeleting()) {
                $category->services()->each(function ($service) {
                    $service->delete();
                });
            }
        });
    }
}
