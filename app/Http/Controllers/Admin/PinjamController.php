<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\MassDestroyPinjamRequest;
use App\Http\Requests\StorePinjamRequest;
use App\Http\Requests\UpdatePinjamRequest;
use App\Models\Pinjam;
use App\Models\Ruang;
use App\Models\User;
use Gate;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class PinjamController extends Controller
{
    use MediaUploadingTrait;

    public function index(Request $request)
    {
        abort_if(Gate::denies('pinjam_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = Pinjam::with(['ruang'])->select(sprintf('%s.*', (new Pinjam)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                return view('partials.admintablesActions', compact('row'));
            });

            $table->editColumn('name', function ($row) {
                return $row->name ? ('<u>'.$row->name.'</u><br>No WhatsApp :<br>('.($row->no_wa).')') : '';
            });
            $table->editColumn('no_wa', function ($row) {
                return $row->no_wa ? $row->no_wa : '';
            });
            $table->addColumn('ruang_name', function ($row) {
                return $row->ruang ? ('<u>'.$row->ruang->code.'</u><br>('.$row->ruang->name.')') : '';
            });
            $table->addColumn('waktu_peminjaman', function ($row) {
                return $row->date_start. '<br><i>sd</i><br>'. $row->date_end;
            });
            $table->editColumn('reason', function ($row) {
                return $row->reason ? $row->reason : '';
            });
            $table->editColumn('status', function ($row) {
                if ($row->status == 'pesan') {
                    return '<span class="badge badge-info">Diajukan Pemesanan<br>Pada Tanggal : '. $row->tanggal_pengajuan. '</span>';
                } else if ($row->status == 'terpesan') {
                    return '<span class="badge badge-primary">Pemesanan Disetujui</span>';
                } else if ($row->status == 'pinjam') {
                    return '<span class="badge badge-success">Diajukan Peminjaman<br>Pada Tanggal : '. $row->tanggal_pengajuan. '</span>';
                } else if ($row->status == 'ditolak') {
                    $arr = explode(' : ', $row->status_text);
                    return '<span class="badge badge-dark">'. $arr[0].'<br>'. $arr[1] .'</span>';
                } else {
                    $status = '<span class="badge badge-'.Pinjam::STATUS_BACKGROUND[$row->status].'">'.$row->status_peminjaman.'</span><br>
                    <span class="badge badge-warning">Surat Balasan : <b>'. ($row->surat_balasan ? 'Sudah Dikirim' : 'Belum Dikirim'). '</b></span>';
                    if ($row->status == 'disetujui') {
                        $driver = '<br><span class="badge badge-warning">'.($row->sopir_id ? ('Sopir : '.$row->sopir->nama.'<br>No WA : ('.$row->sopir->no_wa.')') : 'Belum Pilih Sopir').'</span>';
                        return $status.' '.$driver;
                    }
                    return $status;
                }
            });
            $table->editColumn('surat_permohonan', function ($row) {
                $permohonan = $row->surat_permohonan ? '<a class="btn btn-xs btn-success" href="' . $row->surat_permohonan->getFullUrl() . '" target="_blank">' . trans('global.downloadFile') . '</a>' : '<span class="badge badge-warning">Belum Upload</span>';
                $izin = $row->surat_izin ? '<a class="btn btn-xs btn-success" href="' . $row->surat_izin->getFullUrl() . '" target="_blank">' . trans('global.downloadFile') . '</a>' : '<span class="badge badge-warning">Belum Upload</span>';
                return 'Surat Permohonan :<br>'.$permohonan. '<br>Surat Izin Kegiatan : <br>'.$izin;
            });

            $table->rawColumns(['actions', 'name', 'placeholder', 'ruang', 'waktu_peminjaman', 'status', 'surat_permohonan']);

            return $table->make(true);
        }

        return view('admin.pinjams.index');
    }

    public function create()
    {
        abort_if(Gate::denies('pinjam_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.pinjams.create');
    }

    public function store(StorePinjamRequest $request)
    {
        $ruang = Ruang::find($request->ruang_id);

        $request->request->add(['status' => 'disetujui']);
        $request->request->add(['status' => 'borrowed']);
        $request->request->add(['status_text' => 'Peminjaman Ruangan oleh "' . $request->name .' ('. $request->no_wa .')" Untuk Ruang "'.$ruang->nama .'"']);
        $request->request->add(['borrowed_by_id' => auth()->user()->id]);

        DB::beginTransaction();
        try {
            $pinjam = Pinjam::create($request->all());

            if ($request->input('surat_permohonan', false)) {
                $pinjam->addMedia(storage_path('tmp/uploads/' . basename($request->input('surat_permohonan'))))->toMediaCollection('surat_permohonan');
            }

            if ($request->input('surat_izin', false)) {
                $pinjam->addMedia(storage_path('tmp/uploads/' . basename($request->input('surat_izin'))))->toMediaCollection('surat_izin');
            }

            if ($media = $request->input('ck-media', false)) {
                Media::whereIn('id', $media)->update(['model_id' => $pinjam->id]);
            }

            LogPinjam::create([
                'peminjaman_id' => $pinjam->id,
                'ruang_id' => $pinjam->ruang_id,
                'peminjam_id' => $pinjam->borrowed_by_id,
                'jenis' => 'disetujui',
                'log' => 'Peminjaman ruang '. $pinjam->ruang->nama. ' Diajukan oleh "'. $pinjam->name.'" Untuk tanggal '. $pinjam->WaktuPeminjaman . ' Dengan keperluan "' . $pinjam->reason .'"  Disetujui oleh "'. auth()->user()->name .'"',
            ]);

            DB::commit();

            Alert::success('Success', 'Peminjaman ruang Berhasil Disimpan');

            return redirect()->route('admin.pinjams.index');
        } catch (\Exception $e) {
            DB::rollback();

            return redirect()->back()->with('error-message', $e->getMessage())->withInput();
        }

        return redirect()->route('admin.pinjams.index');
    }

    public function edit(Pinjam $pinjam)
    {
        abort_if(Gate::denies('pinjam_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $ruangs = Ruang::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $borrowed_bies = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $processed_bies = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $created_bies = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $updated_bies = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $pinjam->load('ruang', 'borrowed_by', 'processed_by', 'created_by', 'updated_by');

        return view('admin.pinjams.edit', compact('borrowed_bies', 'created_bies', 'pinjam', 'processed_bies', 'ruangs', 'updated_bies'));
    }

    public function update(UpdatePinjamRequest $request, Pinjam $pinjam)
    {
        $pinjam->update($request->all());

        if ($request->input('surat_permohonan', false)) {
            if (! $pinjam->surat_permohonan || $request->input('surat_permohonan') !== $pinjam->surat_permohonan->file_name) {
                if ($pinjam->surat_permohonan) {
                    $pinjam->surat_permohonan->delete();
                }
                $pinjam->addMedia(storage_path('tmp/uploads/' . basename($request->input('surat_permohonan'))))->toMediaCollection('surat_permohonan');
            }
        } elseif ($pinjam->surat_permohonan) {
            $pinjam->surat_permohonan->delete();
        }

        if ($request->input('surat_izin', false)) {
            if (! $pinjam->surat_izin || $request->input('surat_izin') !== $pinjam->surat_izin->file_name) {
                if ($pinjam->surat_izin) {
                    $pinjam->surat_izin->delete();
                }
                $pinjam->addMedia(storage_path('tmp/uploads/' . basename($request->input('surat_izin'))))->toMediaCollection('surat_izin');
            }
        } elseif ($pinjam->surat_izin) {
            $pinjam->surat_izin->delete();
        }

        if ($request->input('surat_balasan', false)) {
            if (! $pinjam->surat_balasan || $request->input('surat_balasan') !== $pinjam->surat_balasan->file_name) {
                if ($pinjam->surat_balasan) {
                    $pinjam->surat_balasan->delete();
                }
                $pinjam->addMedia(storage_path('tmp/uploads/' . basename($request->input('surat_balasan'))))->toMediaCollection('surat_balasan');
            }
        } elseif ($pinjam->surat_balasan) {
            $pinjam->surat_balasan->delete();
        }

        if (count($pinjam->laporan_kegiatan) > 0) {
            foreach ($pinjam->laporan_kegiatan as $media) {
                if (! in_array($media->file_name, $request->input('laporan_kegiatan', []))) {
                    $media->delete();
                }
            }
        }
        $media = $pinjam->laporan_kegiatan->pluck('file_name')->toArray();
        foreach ($request->input('laporan_kegiatan', []) as $file) {
            if (count($media) === 0 || ! in_array($file, $media)) {
                $pinjam->addMedia(storage_path('tmp/uploads/' . basename($file)))->toMediaCollection('laporan_kegiatan');
            }
        }

        if (count($pinjam->foto_kegiatan) > 0) {
            foreach ($pinjam->foto_kegiatan as $media) {
                if (! in_array($media->file_name, $request->input('foto_kegiatan', []))) {
                    $media->delete();
                }
            }
        }
        $media = $pinjam->foto_kegiatan->pluck('file_name')->toArray();
        foreach ($request->input('foto_kegiatan', []) as $file) {
            if (count($media) === 0 || ! in_array($file, $media)) {
                $pinjam->addMedia(storage_path('tmp/uploads/' . basename($file)))->toMediaCollection('foto_kegiatan');
            }
        }

        return redirect()->route('admin.pinjams.index');
    }

    public function show(Pinjam $pinjam)
    {
        abort_if(Gate::denies('pinjam_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $pinjam->load('ruang', 'borrowed_by', 'processed_by', 'created_by', 'updated_by');

        return view('admin.pinjams.show', compact('pinjam'));
    }

    public function destroy(Pinjam $pinjam)
    {
        abort_if(Gate::denies('pinjam_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $pinjam->delete();

        return back();
    }

    public function massDestroy(MassDestroyPinjamRequest $request)
    {
        $pinjams = Pinjam::find(request('ids'));

        foreach ($pinjams as $pinjam) {
            $pinjam->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function storeCKEditorImages(Request $request)
    {
        abort_if(Gate::denies('pinjam_create') && Gate::denies('pinjam_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $model         = new Pinjam();
        $model->id     = $request->input('crud_id', 0);
        $model->exists = true;
        $media         = $model->addMediaFromRequest('upload')->toMediaCollection('ck-media');

        return response()->json(['id' => $media->id, 'url' => $media->getUrl()], Response::HTTP_CREATED);
    }
}
