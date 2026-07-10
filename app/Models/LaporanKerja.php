<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class LaporanKerja extends Model
{
    protected $fillable = [
        'keterangan',
        'foto',
    ];

    protected $casts = [
        'waktu_lapor' => 'datetime',
        'reviewed_at' => 'datetime',
    ];

    public function scopeMilikPegawai(Builder $query, int $employeeId): Builder
    {
        return $query->where('employee_id_eksternal', $employeeId);
    }

    public function bisaDiubah(): bool
    {
        return $this->status === 'menunggu' && $this->waktu_lapor->isToday();
    }

    protected function fotoUrl(): Attribute
    {
        return Attribute::get(fn () => Storage::url($this->foto));
    }
}
