<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Picqer\Barcode\BarcodeGeneratorPNG;
use Illuminate\Support\Str;

class Variant extends Model
{
    protected $table = 'variants';
    protected $primaryKey = 'variant_id';

    protected $fillable = [
        'warna',
        'ukuran',
        'product_price',
        'product_sale',
        'barcode',
        'photo',
        'product_id'
    ];

    /*
    |--------------------------------------------------------------------------
    |   AUTO GENERATE BARCODE + BARCODE IMAGE
    |--------------------------------------------------------------------------
    */
    protected static function boot()
    {
        parent::boot();

        // Generate barcode otomatis saat create
        static::creating(function ($variant) {
            if (empty($variant->barcode)) {
                $variant->barcode = $variant->generateSimpleBarcode();
            }
        });

        // Generate barcode image setelah data berhasil dibuat
        static::created(function ($variant) {
            $variant->generateBarcodeImage();
        });
    }

    // Barcode format: VAR0001, VAR0002, ...
    public function generateSimpleBarcode()
    {
        $last = Variant::orderBy('variant_id', 'DESC')->first();

        // Ambil barcode terakhir (angka)
        $number = $last ? intval($last->barcode) + 1 : 100000;

        return str_pad($number, 6, '0', STR_PAD_LEFT);
    }


    // Membuat gambar barcode otomatis
    public function generateBarcodeImage()
    {
        $generator = new BarcodeGeneratorPNG();

        $barcodeImage = $generator->getBarcode(
            $this->barcode,
            $generator::TYPE_CODE_128,
            2,
            50
        );

        $path = 'barcodes/' . $this->barcode . '.png';

        Storage::disk('public')->put($path, $barcodeImage);

        return $path;
    }

    // Untuk menampilkan path barcode di blade
    public function getBarcodeImagePathAttribute()
    {
        $path = 'barcodes/' . $this->barcode . '.png';

        if (Storage::disk('public')->exists($path)) {
            return Storage::url($path);
        }

        return null;
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'product_id');
    }

    public function item()
    {
        return $this->hasMany(sales_item::class, 'variant_id', 'variant_id');
    }

    public function stocks()
    {
        return $this->hasMany(Stock::class, 'variant_id', 'variant_id');
    }
}
