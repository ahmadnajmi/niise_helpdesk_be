<?php

return [

    'message' => [
        'default_report' => 'Laporan adhoc ini tidak boleh dipadam kerana digunakan oleh modul lain',
    ],

    // mini-report
    'general' => [
        'yes' => 'Ya',
        'no' => 'Tidak',
        'active' => 'Aktif',
        'inactive' => 'Tidak Aktif',
        'none' => 'Tiada',
    ],

    'individuals' => [
        'item_title' => 'Informasi Individu',
        'list_title' => 'Senarai Individu',

        'ic_no' => 'ID',
        'name' => 'Nama',
        'phone_no' => 'No. Telefon Bimbit',
        'role' => 'Peranan',
        'status' => 'Status',
        'nickname' => 'Nama Panggilan',
        'email' => 'Emel',
        'organization' => 'Nama Organisasi',
        'groups' => 'Kumpulan',
        'accessible_groups' => 'Kebenaran Akses Kumpulan',
    ],

    'group' => [
        'item_title' => 'Informasi Kumpulan',
        'list_title' => 'Senarai Kumpulan',

        'name' => 'Nama',
        'description' => 'Keterangan',
        'status' => 'Status',
        //...
    ],

    'contractors' => [
        'item_title' => 'Informasi Kontraktor',
        'list_title' => 'Senarai Kontraktor',

        'name' => 'Nama Kontraktor',
        'nickname' => 'Nama Singkatan',
        'phone_no' => 'No. Telefon',
        'email' => 'Emel',
        'status' => 'Status',
        //...
    ],

    'calendar' => [
        'item_title' => 'Informasi Cuti',
        'list_title' => 'Senarai Cuti',

        'name' => 'Nama',
        'holiday_name' => 'Nama Cuti',
        'start_date' => 'Tarikh Mula',
        'end_date' => 'Tarikh Tamat',
        'status' => 'Status',
        'states' => 'Negeri',
    ],

    'operation_times' => [
        'item_title' => 'Informasi Masa Operasi',
        'list_title' => 'Senarai Masa Operasi',

        'branch' => 'Cawangan',
        'time' => 'Waktu',
        'day' => 'Hari',
        'duration' => 'Durasi',
        'status' => 'Status',
        //...
    ],

    'categories' => [
        'item_title' => 'Informasi Kategori',
        'list_title' => 'Senarai Kategori',

        'code' => 'Kod',
        'parent_category' => 'Kategori Induk',
        'abbreviation' => 'Nama Singkatan',
        'description' => 'Keterangan',
        'status' => 'Status',
    ],

    'email_templates' => [
        'item_title' => 'Informasi Templat Emel',
        'list_title' => 'Senarai Templat Emel',

        'name' => 'Nama Templat',
        'notes' => 'Nota',
        'sender_email' => 'Emel Penghantar',
        'sender_name' => 'Nama Penghantar',
        'status' => 'Status',
    ],

    'action_codes' => [
        'item_title' => 'Informasi Kod Tindakan',
        'list_title' => 'Senarai Kod Tindakan',

        'abbreviation' => 'Nama Singkatan',
        'name' => 'Nama',
        'description' => 'Keterangan',
        'status' => 'Status',
        'roles_allowed' => 'Peranan yang Dibenarkan',
        'send_email' => 'Penghantaran Email',
        'skip_penalty' => 'Pengecualian Penalti',
        'email_receiver' => 'Penerima Emel',
    ],

    'incidents' => [
        'item_title' => 'Informasi Insiden',
        'list_title' => 'Senarai Insiden',

        'incident_no' => 'No. Insiden',
        'branch' => 'Cawangan',
        'description' => 'Keterangan',
        'status' => 'Status',
        'start_date' => 'Tarikh Mula',
        'end_date' => 'Tarikh Tamat',
        'severity' => 'Severiti',
        'phone_no' => 'No. Telefon Bimbit',
    ],

    'sla' => [
        'item_title' => 'Informasi Tetapan SLA',
        'list_title' => 'Senarai Tetapan SLA',

        'sla_code' => 'Kod SLA',

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

    'sla_template' => [
        'item_title' => 'Informasi Templat SLA',
        'list_title' => 'Senarai Templat SLA',

        'sla_code' => 'Kod SLA',
        'contractor' => 'Kontraktor',
        'contract' => 'Kontrak',
        'contract_no' => 'No. Kontrak',
        'severity' => 'Severiti',

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

        'notes' => 'Catatan'
    ],

    'global_settings' => [
        'list_title' => 'Senarai Tetapan Global',
        'item_title' => 'Informasi Tetapan Global',

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
    ],

    'audit_trails' => [
        'item_title' => 'Informasi Jejak Audit',
        'list_title' => 'Senarai Jejak Audit',

        'date' => 'Tarikh',
        'time' => 'Masa',
        'user' => 'Pengguna',
        'user_id' => 'ID Pengguna',
        'event' => 'Fungsi',
        'old_values' => 'Nilai Lama',
        'new_values' => 'Nilai Baharu',
    ],

    'knowledge_base' => [
        'item_title' => 'Informasi Entri Knowledge Base',
        'list_title' => 'Senarai Entri Knowledge Base',

        'keywords' => 'Kata Kunci',
        'category' => 'Kategori',
        'problem' => 'Masalah',
        'solution' => 'Penyelesaian',
    ],
];
