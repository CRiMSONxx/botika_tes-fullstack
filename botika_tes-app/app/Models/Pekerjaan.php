<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pekerjaan extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nama_pekerjaan',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    /**
     * Get the karyawan associated with the pekerjaan.
     */
    public function karyawan()
    {
        return $this->hasMany(Karyawan::class);
    }
}
