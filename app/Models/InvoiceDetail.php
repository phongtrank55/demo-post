<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InvoiceDetail extends Model
{
    protected $guarded = ['id'];

    public function fit(){
        return $this->belongsTo(Fit::class, 'product_id');
    }

    public function phone(){
        return $this->belongsTo(Phone::class, 'product_id');
    }

    public function accessory(){
        return $this->belongsTo(Accessory::class, 'product_id');
    }
}


