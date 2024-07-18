<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductEntry extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'brands_id',
        'id_obra',
        'quantity',
        'unit_price',
        'total_price',
        'invoice_number',
        'invoice_file',
        'number_OC',
        'cert_aut_entrada',
        'num_lote_entrada',
        'data_validade_lote_ca'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
    public function brands()
    {
        return $this->belongsTo(Brand::class);
    }
}
