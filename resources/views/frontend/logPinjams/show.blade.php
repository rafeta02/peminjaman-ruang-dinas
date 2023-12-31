@extends('layouts.frontend')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">

            <div class="card">
                <div class="card-header">
                    {{ trans('global.show') }} {{ trans('cruds.logPinjam.title') }}
                </div>

                <div class="card-body">
                    <div class="form-group">
                        <div class="form-group">
                            <a class="btn btn-default" href="{{ route('frontend.log-pinjams.index') }}">
                                {{ trans('global.back_to_list') }}
                            </a>
                        </div>
                        <table class="table table-bordered table-striped">
                            <tbody>
                                <tr>
                                    <th>
                                        {{ trans('cruds.logPinjam.fields.peminjaman') }}
                                    </th>
                                    <td>
                                        {{ $logPinjam->peminjaman->date_start ?? '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.logPinjam.fields.ruang') }}
                                    </th>
                                    <td>
                                        {{ $logPinjam->ruang->name ?? '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.logPinjam.fields.peminjam') }}
                                    </th>
                                    <td>
                                        {{ $logPinjam->peminjam->name ?? '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.logPinjam.fields.jenis') }}
                                    </th>
                                    <td>
                                        {{ App\Models\LogPinjam::JENIS_SELECT[$logPinjam->jenis] ?? '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.logPinjam.fields.log') }}
                                    </th>
                                    <td>
                                        {{ $logPinjam->log }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="form-group">
                            <a class="btn btn-default" href="{{ route('frontend.log-pinjams.index') }}">
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