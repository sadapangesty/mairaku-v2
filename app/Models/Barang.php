<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
    use HasFactory;

    // Nama tabel (opsional karena defaultnya 'barangs')
    protected $table = 'barangs';

    // Primary key (opsional, default sudah 'id')
    protected $primaryKey = 'id';

    // Field yang boleh diisi mass assignment (create/update)
    protected $fillable = [
        'nama_barang',
        'id_kategori',
        'id_jenis',
        'sku',
        'harga',
        'link_barang',
        'foto',
    ];

    /**
     * Relasi: Barang belongsTo Kategori
     * id_kategori di tabel barangs mengacu ke id_kategori di tabel kategoris
     */
    public function kategori()
    {
        return $this->belongsTo(Kategori::class, 'id_kategori', 'id_kategori');
    }

    /**
     * Relasi: Barang belongsTo Jenis
     * id_jenis di tabel barangs mengacu ke id_jenis di tabel jenis
     */
    public function jenis()
    {
        return $this->belongsTo(Jenis::class, 'id_jenis', 'id_jenis');
    }
}
