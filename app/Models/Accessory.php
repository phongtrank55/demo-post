<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\InvoiceDetail;

class Accessory extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public function invoiceDetails()
    {
        return $this->hasMany('App\InvoiceDetail', 'product_id');
    }
}
