@extends('layouts.frontend')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">

            <div class="card">
                <div class="card-header">
                    {{ trans('global.show') }} {{ trans('cruds.pinjam.title') }}
                </div>

                <div class="card-body">
                    <div class="form-group">
                        <div class="form-group">
                            <a class="btn btn-default" href="{{ route('frontend.pinjams.index') }}">
                                {{ trans('global.back_to_list') }}
                            </a>
                        </div>
                        <table class="table table-bordered table-striped">
                            <tbody>
                                <tr>
                                    <th>
                                        {{ trans('cruds.pinjam.fields.name') }}
                                    </th>
                                    <td>
                                        {{ $pinjam->name }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.pinjam.fields.no_wa') }}
                                    </th>
                                    <td>
                                        {{ $pinjam->no_wa }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.pinjam.fields.ruang') }}
                                    </th>
                                    <td>
                                        {{ $pinjam->ruang->name ?? '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.pinjam.fields.date_start') }}
                                    </th>
                                    <td>
                                        {{ $pinjam->date_start }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.pinjam.fields.date_end') }}
                                    </th>
                                    <td>
                                        {{ $pinjam->date_end }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.pinjam.fields.reason') }}
                                    </th>
                                    <td>
                                        {{ $pinjam->reason }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.pinjam.fields.status') }}
                                    </th>
                                    <td>
                                        {{ App\Models\Pinjam::STATUS_SELECT[$pinjam->status] ?? '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.pinjam.fields.status_calender') }}
                                    </th>
                                    <td>
                                        {{ App\Models\Pinjam::STATUS_CALENDER_SELECT[$pinjam->status_calender] ?? '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.pinjam.fields.status_text') }}
                                    </th>
                                    <td>
                                        {{ $pinjam->status_text }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.pinjam.fields.surat_permohonan') }}
                                    </th>
                                    <td>
                                        @if($pinjam->surat_permohonan)
                                            <a href="{{ $pinjam->surat_permohonan->getUrl() }}" target="_blank">
                                                {{ trans('global.view_file') }}
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.pinjam.fields.surat_izin') }}
                                    </th>
                                    <td>
                                        @if($pinjam->surat_izin)
                                            <a href="{{ $pinjam->surat_izin->getUrl() }}" target="_blank">
                                                {{ trans('global.view_file') }}
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.pinjam.fields.surat_balasan') }}
                                    </th>
                                    <td>
                                        @if($pinjam->surat_balasan)
                                            <a href="{{ $pinjam->surat_balasan->getUrl() }}" target="_blank">
                                                {{ trans('global.view_file') }}
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.pinjam.fields.laporan_kegiatan') }}
                                    </th>
                                    <td>
                                        @foreach($pinjam->laporan_kegiatan as $key => $media)
                                            <a href="{{ $media->getUrl() }}" target="_blank">
                                                {{ trans('global.view_file') }}
                                            </a>
                                        @endforeach
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.pinjam.fields.foto_kegiatan') }}
                                    </th>
                                    <td>
                                        @foreach($pinjam->foto_kegiatan as $key => $media)
                                            <a href="{{ $media->getUrl() }}" target="_blank" style="display: inline-block">
                                                <img src="{{ $media->getUrl('thumb') }}">
                                            </a>
                                        @endforeach
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.pinjam.fields.borrowed_by') }}
                                    </th>
                                    <td>
                                        {{ $pinjam->borrowed_by->name ?? '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.pinjam.fields.processed_by') }}
                                    </th>
                                    <td>
                                        {{ $pinjam->processed_by->name ?? '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.pinjam.fields.created_by') }}
                                    </th>
                                    <td>
                                        {{ $pinjam->created_by->name ?? '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.pinjam.fields.updated_by') }}
                                    </th>
                                    <td>
                                        {{ $pinjam->updated_by->name ?? '' }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="form-group">
                            <a class="btn btn-default" href="{{ route('frontend.pinjams.index') }}">
                                {{ trans('global.back_to_list') }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection