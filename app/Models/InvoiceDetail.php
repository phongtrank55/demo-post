<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvoiceDetail extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public function fit(){
        return $this->belongsTo('App\Fit', 'product_id');
    }

    public function phone(){
        return $this->belongsTo('App\Phone', 'product_id');
    }

    public function accessory(){
        return $this->belongsTo('App\accessory', 'product_id');
    }
}
