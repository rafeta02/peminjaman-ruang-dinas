<?php

namespace App\Models;

use App\Traits\Auditable;
use App\Traits\MultiTenantModelTrait;
use Carbon\Carbon;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Pinjam extends Model implements HasMedia
{
    use SoftDeletes, MultiTenantModelTrait, InteractsWithMedia, Auditable, HasFactory;

    public $table = 'pinjams';

    protected $dates = [
        'date_start',
        'date_end',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $appends = [
        'surat_permohonan',
        'surat_izin',
        'surat_balasan',
        'laporan_kegiatan',
        'foto_kegiatan',
    ];

    public const STATUS_SELECT = [
        'diajukan' => 'Diajukan',
        'diterima' => 'Diterima',
        'selesai'  => 'Selesai',
        'ditolak'  => 'Ditolak',
    ];

    public const STATUS_CALENDER_SELECT = [
        'booked'   => 'Booked',
        'borrowed' => 'Borrowed',
        'rejected' => 'Rejected',
        'canceled' => 'Canceled',
    ];

    protected $fillable = [
        'name',
        'no_wa',
        'ruang_id',
        'date_start',
        'date_end',
        'reason',
        'status',
        'status_calender',
        'status_text',
        'borrowed_by_id',
        'processed_by_id',
        'created_by_id',
        'updated_by_id',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('thumb')->fit('crop', 50, 50);
        $this->addMediaConversion('preview')->fit('crop', 120, 120);
    }

    public function ruang()
    {
        return $this->belongsTo(Ruang::class, 'ruang_id');
    }

    public function getDateStartAttribute($value)
    {
        return $value ? Carbon::createFromFormat('Y-m-d H:i:s', $value)->format(config('panel.date_format') . ' ' . config('panel.time_format')) : null;
    }

    public function setDateStartAttribute($value)
    {
        $this->attributes['date_start'] = $value ? Carbon::createFromFormat(config('panel.date_format') . ' ' . config('panel.time_format'), $value)->format('Y-m-d H:i:s') : null;
    }

    public function getDateEndAttribute($value)
    {
        return $value ? Carbon::createFromFormat('Y-m-d H:i:s', $value)->format(config('panel.date_format') . ' ' . config('panel.time_format')) : null;
    }

    public function setDateEndAttribute($value)
    {
        $this->attributes['date_end'] = $value ? Carbon::createFromFormat(config('panel.date_format') . ' ' . config('panel.time_format'), $value)->format('Y-m-d H:i:s') : null;
    }

    public function getSuratPermohonanAttribute()
    {
        return $this->getMedia('surat_permohonan')->last();
    }

    public function getSuratIzinAttribute()
    {
        return $this->getMedia('surat_izin')->last();
    }

    public function getSuratBalasanAttribute()
    {
        return $this->getMedia('surat_balasan')->last();
    }

    public function getLaporanKegiatanAttribute()
    {
        return $this->getMedia('laporan_kegiatan');
    }

    public function getFotoKegiatanAttribute()
    {
        $files = $this->getMedia('foto_kegiatan');
        $files->each(function ($item) {
            $item->url       = $item->getUrl();
            $item->thumbnail = $item->getUrl('thumb');
            $item->preview   = $item->getUrl('preview');
        });

        return $files;
    }

    public function borrowed_by()
    {
        return $this->belongsTo(User::class, 'borrowed_by_id');
    }

    public function processed_by()
    {
        return $this->belongsTo(User::class, 'processed_by_id');
    }

    public function created_by()
    {
        return $this->belongsTo(User::class, 'created_by_id');
    }

    public function updated_by()
    {
        return $this->belongsTo(User::class, 'updated_by_id');
    }
}
