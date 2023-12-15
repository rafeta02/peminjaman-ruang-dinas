<?php

return [
    'userManagement' => [
        'title'          => 'Manajemen User',
        'title_singular' => 'Manajemen User',
    ],
    'permission' => [
        'title'          => 'Izin',
        'title_singular' => 'Izin',
        'fields'         => [
            'id'                => 'ID',
            'id_helper'         => ' ',
            'title'             => 'Title',
            'title_helper'      => ' ',
            'created_at'        => 'Created at',
            'created_at_helper' => ' ',
            'updated_at'        => 'Updated at',
            'updated_at_helper' => ' ',
            'deleted_at'        => 'Deleted at',
            'deleted_at_helper' => ' ',
        ],
    ],
    'role' => [
        'title'          => 'Peranan',
        'title_singular' => 'Peranan',
        'fields'         => [
            'id'                 => 'ID',
            'id_helper'          => ' ',
            'title'              => 'Title',
            'title_helper'       => ' ',
            'permissions'        => 'Permissions',
            'permissions_helper' => ' ',
            'created_at'         => 'Created at',
            'created_at_helper'  => ' ',
            'updated_at'         => 'Updated at',
            'updated_at_helper'  => ' ',
            'deleted_at'         => 'Deleted at',
            'deleted_at_helper'  => ' ',
        ],
    ],
    'user' => [
        'title'          => 'Daftar Pengguna',
        'title_singular' => 'User',
        'fields'         => [
            'id'                       => 'ID',
            'id_helper'                => ' ',
            'name'                     => 'Name',
            'name_helper'              => ' ',
            'email'                    => 'Email',
            'email_helper'             => ' ',
            'email_verified_at'        => 'Email verified at',
            'email_verified_at_helper' => ' ',
            'password'                 => 'Password',
            'password_helper'          => ' ',
            'roles'                    => 'Roles',
            'roles_helper'             => ' ',
            'remember_token'           => 'Remember Token',
            'remember_token_helper'    => ' ',
            'created_at'               => 'Created at',
            'created_at_helper'        => ' ',
            'updated_at'               => 'Updated at',
            'updated_at_helper'        => ' ',
            'deleted_at'               => 'Deleted at',
            'deleted_at_helper'        => ' ',
        ],
    ],
    'auditLog' => [
        'title'          => 'Audit Logs',
        'title_singular' => 'Audit Log',
        'fields'         => [
            'id'                  => 'ID',
            'id_helper'           => ' ',
            'description'         => 'Description',
            'description_helper'  => ' ',
            'subject_id'          => 'Subject ID',
            'subject_id_helper'   => ' ',
            'subject_type'        => 'Subject Type',
            'subject_type_helper' => ' ',
            'user_id'             => 'User ID',
            'user_id_helper'      => ' ',
            'properties'          => 'Properties',
            'properties_helper'   => ' ',
            'host'                => 'Host',
            'host_helper'         => ' ',
            'created_at'          => 'Created at',
            'created_at_helper'   => ' ',
            'updated_at'          => 'Updated at',
            'updated_at_helper'   => ' ',
        ],
    ],
    'userAlert' => [
        'title'          => 'User Alerts',
        'title_singular' => 'User Alert',
        'fields'         => [
            'id'                => 'ID',
            'id_helper'         => ' ',
            'alert_text'        => 'Alert Text',
            'alert_text_helper' => ' ',
            'alert_link'        => 'Alert Link',
            'alert_link_helper' => ' ',
            'user'              => 'Users',
            'user_helper'       => ' ',
            'created_at'        => 'Created at',
            'created_at_helper' => ' ',
            'updated_at'        => 'Updated at',
            'updated_at_helper' => ' ',
        ],
    ],
    'master' => [
        'title'          => 'Master',
        'title_singular' => 'Master',
    ],
    'ruang' => [
        'title'          => 'Ruang',
        'title_singular' => 'Ruang',
        'fields'         => [
            'id'                 => 'ID',
            'id_helper'          => ' ',
            'code'               => 'Kode Ruang',
            'code_helper'        => ' ',
            'name'               => 'Nama Ruang',
            'name_helper'        => ' ',
            'slug'               => 'Slug',
            'slug_helper'        => ' ',
            'capacity'           => 'Kapasitas',
            'capacity_helper'    => ' ',
            'facility'           => 'Fasilitas',
            'facility_helper'    => ' ',
            'description'        => 'Deskripsi',
            'description_helper' => ' ',
            'images'             => 'Images',
            'images_helper'      => ' ',
            'created_at'         => 'Created at',
            'created_at_helper'  => ' ',
            'updated_at'         => 'Updated at',
            'updated_at_helper'  => ' ',
            'deleted_at'         => 'Deleted at',
            'deleted_at_helper'  => ' ',
        ],
    ],
    'pinjam' => [
        'title'          => 'Pinjam',
        'title_singular' => 'Pinjam',
        'fields'         => [
            'id'                      => 'ID',
            'id_helper'               => ' ',
            'name'                    => 'Identitas Peminjam',
            'name_helper'             => ' ',
            'no_wa'                   => 'No WhatsApp',
            'no_wa_helper'            => ' ',
            'ruang'                   => 'Ruang',
            'ruang_helper'            => ' ',
            'date_start'              => 'Waktu Mulai',
            'date_start_helper'       => ' ',
            'date_end'                => 'Waktu Selesai',
            'date_end_helper'         => ' ',
            'reason'                  => 'Keperluan',
            'reason_helper'           => ' ',
            'status'                  => 'Status',
            'status_helper'           => ' ',
            'status_calender'         => 'Status Calender',
            'status_calender_helper'  => ' ',
            'status_text'             => 'Status Text',
            'status_text_helper'      => ' ',
            'surat_permohonan'        => 'Surat Permohonan',
            'surat_permohonan_helper' => ' ',
            'surat_izin'              => 'Surat Izin Kegiatan',
            'surat_izin_helper'       => ' ',
            'surat_balasan'           => 'Surat Balasan',
            'surat_balasan_helper'    => ' ',
            'laporan_kegiatan'        => 'Laporan Kegiatan',
            'laporan_kegiatan_helper' => ' ',
            'foto_kegiatan'           => 'Foto Kegiatan',
            'foto_kegiatan_helper'    => ' ',
            'borrowed_by'             => 'Borrowed By',
            'borrowed_by_helper'      => ' ',
            'processed_by'            => 'Processed By',
            'processed_by_helper'     => ' ',
            'created_by'              => 'Created By',
            'created_by_helper'       => ' ',
            'updated_by'              => 'Updated By',
            'updated_by_helper'       => ' ',
            'created_at'              => 'Created at',
            'created_at_helper'       => ' ',
            'updated_at'              => 'Updated at',
            'updated_at_helper'       => ' ',
            'deleted_at'              => 'Deleted at',
            'deleted_at_helper'       => ' ',
        ],
    ],
    'logPinjam' => [
        'title'          => 'Log Peminjaman',
        'title_singular' => 'Log Peminjaman',
        'fields'         => [
            'id'                => 'ID',
            'id_helper'         => ' ',
            'peminjaman'        => 'Peminjaman',
            'peminjaman_helper' => ' ',
            'ruang'             => 'Ruang',
            'ruang_helper'      => ' ',
            'peminjam'          => 'Peminjam',
            'peminjam_helper'   => ' ',
            'jenis'             => 'Jenis',
            'jenis_helper'      => ' ',
            'log'               => 'Log',
            'log_helper'        => ' ',
            'created_at'        => 'Created at',
            'created_at_helper' => ' ',
            'updated_at'        => 'Updated at',
            'updated_at_helper' => ' ',
            'deleted_at'        => 'Deleted at',
            'deleted_at_helper' => ' ',
        ],
    ],

];
