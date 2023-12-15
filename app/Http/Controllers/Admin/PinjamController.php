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
            $query = Pinjam::with(['ruang', 'borrowed_by', 'processed_by', 'created_by', 'updated_by'])->select(sprintf('%s.*', (new Pinjam)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'pinjam_show';
                $editGate      = 'pinjam_edit';
                $deleteGate    = 'pinjam_delete';
                $crudRoutePart = 'pinjams';

                return view('partials.datatablesActions', compact(
                    'viewGate',
                    'editGate',
                    'deleteGate',
                    'crudRoutePart',
                    'row'
                ));
            });

            $table->editColumn('name', function ($row) {
                return $row->name ? $row->name : '';
            });
            $table->editColumn('no_wa', function ($row) {
                return $row->no_wa ? $row->no_wa : '';
            });
            $table->addColumn('ruang_name', function ($row) {
                return $row->ruang ? $row->ruang->name : '';
            });

            $table->editColumn('reason', function ($row) {
                return $row->reason ? $row->reason : '';
            });
            $table->editColumn('status', function ($row) {
                return $row->status ? Pinjam::STATUS_SELECT[$row->status] : '';
            });
            $table->editColumn('surat_permohonan', function ($row) {
                return $row->surat_permohonan ? '<a href="' . $row->surat_permohonan->getUrl() . '" target="_blank">' . trans('global.downloadFile') . '</a>' : '';
            });

            $table->rawColumns(['actions', 'placeholder', 'ruang', 'surat_permohonan']);

            return $table->make(true);
        }

        return view('admin.pinjams.index');
    }

    public function create()
    {
        abort_if(Gate::denies('pinjam_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $ruangs = Ruang::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $borrowed_bies = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $processed_bies = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $created_bies = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $updated_bies = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.pinjams.create', compact('borrowed_bies', 'created_bies', 'processed_bies', 'ruangs', 'updated_bies'));
    }

    public function store(StorePinjamRequest $request)
    {
        $pinjam = Pinjam::create($request->all());

        if ($request->input('surat_permohonan', false)) {
            $pinjam->addMedia(storage_path('tmp/uploads/' . basename($request->input('surat_permohonan'))))->toMediaCollection('surat_permohonan');
        }

        if ($request->input('surat_izin', false)) {
            $pinjam->addMedia(storage_path('tmp/uploads/' . basename($request->input('surat_izin'))))->toMediaCollection('surat_izin');
        }

        if ($request->input('surat_balasan', false)) {
            $pinjam->addMedia(storage_path('tmp/uploads/' . basename($request->input('surat_balasan'))))->toMediaCollection('surat_balasan');
        }

        foreach ($request->input('laporan_kegiatan', []) as $file) {
            $pinjam->addMedia(storage_path('tmp/uploads/' . basename($file)))->toMediaCollection('laporan_kegiatan');
        }

        foreach ($request->input('foto_kegiatan', []) as $file) {
            $pinjam->addMedia(storage_path('tmp/uploads/' . basename($file)))->toMediaCollection('foto_kegiatan');
        }

        if ($media = $request->input('ck-media', false)) {
            Media::whereIn('id', $media)->update(['model_id' => $pinjam->id]);
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
