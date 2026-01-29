<?php

return [

    'message' => [
        'default_report' => 'Laporan adhoc ini tidak boleh dipadam kerana digunakan oleh modul lain',
    ],

    // mini-report
    'sla' => [
        'main_title' => 'Tetapan SLA',
        'list_title' => 'Senarai Tetapan SLA',

        // #1
        'branch_list' => 'Senarai Cawangan',
        'state' => 'Negeri',
        'branch' => 'Cawangan',
        'category' => 'Kategori',

        // #2
        'sla_template_details' => 'Maklumat Templat SLA',
        'contractor' => 'Kontraktor',
        'contract' => 'Kontrak',
        'sla_template' => 'Templat SLA',
        'severity' => 'Severiti',
        'start_date' => 'Tarikh Mula',
        'end_date' => 'Tarikh Tamat',

        'response_time' => 'Insiden Masa Respon (IRT)',
        'response_time_type' => 'Jenis Insiden Masa Respon',
        'response_time_penalty' => 'Penalti Insiden Masa Respon (RM)',
        'response_time_penalty_type' => 'Jenis Penalti Insiden Masa Respon',

        'response_time_location' => 'Masa Respon di Lokasi (ORT)',
        'response_time_location_type' => 'Jenis Masa Respon di Lokasi',
        'response_time_location_penalty' => 'Penalti Masa Respon di Lokasi (RM)',
        'response_time_location_penalty_type' => 'Jenis Penalti Masa Respon di Lokasi',

        'temporary_resolution_time' => 'Tempoh Penyelesaian Sementara',
        'temporary_resolution_time_type' => 'Jenis Tempoh Penyelesaian Sementara',
        'temporary_resolution_time_penalty' => 'Penalti Tempoh Penyelesaian Sementara (RM)',
        'temporary_resolution_time_penalty_type' => 'Jenis Penalti Tempoh Penyelesaian Sementara',

        'resolution_time' => 'Masalah Tempoh Penyelesaian Insiden (PRT)',
        'resolution_time_type' => 'Jenis Masalah Tempoh Penyelesaian Insiden',
        'resolution_time_penalty' => 'Penalti Masalah Tempoh Penyelesaian Insiden (RM)',
        'resolution_time_penalty_type' => 'Jenis Penalti Masalah Tempoh Penyelesaian Insiden',

        'verify_resolution_time' => 'Tempoh Pengesahan Insiden',
        'verify_resolution_time_type' => 'Jenis Tempoh Pengesahan Insiden',
        'verify_resolution_time_penalty' => 'Penalti Tempoh Pengesahan Insiden (RM)',
        'verify_resolution_time_penalty_type' => 'Jenis Penalti Tempoh Pengesahan Insiden',

        // #3
        'other_details' => 'Maklumat Lain',
        'email_group' => 'Kumpulan E-mel Pemberitahuan',
        'status' => 'Status',
    ],
    'global_settings' => [
        'list_title' => 'Senarai Tetapan Global',
        'item_title' => 'Tetapan Global',

        'id' => 'ID',
        'category' => 'Kategori',
        'reference_code' => 'Kod Rujukan',
        'name_ms' => 'Nama (BM)',
        'name_en' => 'Nama (BI)',
        'category_code' => 'Kod Kategori',
        'nickname' => 'Nama Singkatan',
        'description' => 'Keterangan',
        'status' => 'Status',
        'categories' => [
            'state' => 'Negeri',
            'action_code_category' => 'Kod Tindakan',
            'issue_level' => 'Tahap Isu',
            'status' => 'Status',
            'branch_type' => 'Jenis Cawangan',
            'received_by' => 'Diterima Oleh',
            'branch_category' => 'Kategori Cawangan',
            'day' => 'Hari',
            'duration' => 'Tempoh',
            'action_code_email_recipient' => 'Penerima E-mel (Kod Tindakan)',
            'sla_type' => 'Jenis SLA',
            'severity' => 'Severiti',
            'incident_asset_type' => 'Jenis Aset Insiden',
            'received_via' => 'Diterima Melalui',
            'penalty_price' => 'Harga Penalti',
            'loaner_type' => 'Jenis Peminjam',
            'penalty_response_time' => 'Respon Penalti',
            'penalty_timeframe_solution' => 'Tempoh Penyelesaian Penalti',
            'penalty_response_time_location' => 'Respon Lokasi Penalti',
            'penalty_loaner' => 'Penggantian Sementara Penalti',
            'incident_status' => 'Status Insiden',
            'incident_resolution_status' => 'Status Penyelesaian Insiden',
            'workbasket_status' => 'Status Senarai Tugas',
            'report_type' => 'Jenis Laporan',
            'report' => 'Laporan',
            'report_format' => 'Format Laporan',
            'group_user_type' => 'Jenis Kumpulan Pengguna'
        ],
    ]
];
