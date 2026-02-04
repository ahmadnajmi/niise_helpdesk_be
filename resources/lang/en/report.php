<?php

return [

    'message' => [
        'default_report' => 'This adhoc report cannot delete because it applies other modules',
    ],

    // mini report
    'general' => [
        'yes' => 'Yes',
        'no' => 'No',
        'active' => 'Active',
        'inactive' => 'Inactive',
        'none' => 'None',
    ],

    'individuals' => [
        'item_title' => 'Individual Information',
        'list_title' => 'List of Individuals',

        'ic_no' => 'ID',
        'name' => 'Name',
        'phone_no' => 'Phone No.',
        'role' => 'Role',
        'status' => 'Status',
        'nickname' => 'Nickname',
        'email' => 'Email',
        'organization' => 'Organization Name',
        'groups' => 'Groups',
        'accessible_groups' => 'Accessible Groups',
    ],

    'groups' => [
        'item_title' => 'Group Information',
        'list_title' => 'List of Groups',

        'name' => 'Name',
        'description' => 'Description',
        'status' => 'Status',
        //...
    ],

    'contractors' => [
        'item_title' => 'Contractor Information',
        'list_title' => 'List of Contractors',

        'name' => 'Contractor Name',
        'nickname' => 'Nickname',
        'phone_no' => 'Phone No.',
        'email' => 'Email',
        'status' => 'Status',
        //...
    ],

    'calendar' => [
        'item_title' => 'Holiday Information',
        'list_title' => 'List of Holidays',

        'name' => 'Name',
        'holiday_name' => 'Holiday Name',
        'start_date' => 'Start Date',
        'end_date' => 'End Date',
        'status' => 'Status',
        'states' => 'States',
    ],

    'operation_times' => [
        'item_title' => 'Operation Time Information',
        'list_title' => 'List of Operation Times',

        'branch' => 'Branch',
        'time' => 'Time',
        'day' => 'Day',
        'duration' => 'Duration',
        'status' => 'Status',
        //...
    ],

    'categories' => [
        'item_title' => 'Category Information',
        'list_title' => 'List of Categories',

        'code' => 'Code',
        'parent_category' => 'Parent Category',
        'abbreviation' => 'Abbreviation',
        'description' => 'Description',
        'status' => 'Status',
    ],

    'email_templates' => [
        'item_title' => 'Email Template Information',
        'list_title' => 'List of Email Templates',

        'name' => 'Template Name',
        'notes' => 'Notes',
        'sender_email' => 'Sender Email',
        'sender_name' => 'Sender Name',
        'status' => 'Status',
    ],

    'action_codes' => [
        'item_title' => 'Action Code Information',
        'list_title' => 'List of Action Codes',

        'abbreviation' => 'Abbreviation',
        'name' => 'Name',
        'description' => 'Description',
        'status' => 'Status',
        'roles_allowed' => 'Allowed Roles',
        'send_email' => 'Send Email',
        'skip_penalty' => 'Exclude Penalty',
        'email_receiver' => 'Email Recipient',
    ],

    'incidents' => [
        'item_title' => 'Incident Information',
        'list_title' => 'List of Incidents',

        'incident_no' => 'Incident No.',
        'branch' => 'Branch',
        'description' => 'Description',
        'status' => 'Status',
        'start_date' => 'Start Date',
        'end_date' => 'End Date',
        'severity' => 'Severity',
        'phone_no' => 'Phone No.',
    ],

    'sla' => [
        'item_title' => 'SLA Setting Information',
        'list_title' => 'List of SLA Settings',

        'sla_code' => 'SLA Code',

        // #1
        'branch_list' => 'Branch List',
        'state' => 'State',
        'branch' => 'Branch',
        'category' => 'Category',

        // #2
        'sla_template_details' => 'SLA Template Details',
        'contractor' => 'Contractor',
        'contract' => 'Contract',
        'sla_template' => 'SLA Template',
        'severity' => 'Severity',
        'start_date' => 'Start Date',
        'end_date' => 'End Date',

        'response_time' => 'Incident Response Time (IRT)',
        'response_time_type' => 'Incident Response Time Type',
        'response_time_penalty' => 'Incident Response Time Penalty (RM)',
        'response_time_penalty_type' => 'Incident Response Time Type Penalty',

        'response_time_location' => 'On Site Response Time (ORT)',
        'response_time_location_type' => 'On Site Response Time Type',
        'response_time_location_penalty' => 'On Site Response Time Penalty (RM)',
        'response_time_location_penalty_type' => 'On Site Response Time Type Penalty',

        'temporary_resolution_time' => 'Temporary Solution',
        'temporary_resolution_time_type' => 'Temporary Solution Type',
        'temporary_resolution_time_penalty' => 'Temporary Solution Penalty (RM)',
        'temporary_resolution_time_penalty_type' => 'Temporary Solution Type Penalty',

        'resolution_time' => 'Problem Solution Incident (PRT)',
        'resolution_time_type' => 'Problem Solution Incident Type',
        'resolution_time_penalty' => 'Problem Solution Incident Penalty (RM)',
        'resolution_time_penalty_type' => 'Problem Solution Incident Type Penalty',

        'verify_resolution_time' => 'Verify Solution Incident',
        'verify_resolution_time_type' => 'Verify Solution Incident Type',
        'verify_resolution_time_penalty' => 'Verify Solution Incident Penalty (RM)',
        'verify_resolution_time_penalty_type' => 'Verify Solution Incident Penalty Type',

        // #3
        'other_details' => 'Other Details',
        'email_group' => 'Notification Email Group',
        'status' => 'Status',

    ],

    'sla_template' => [
        'item_title' => 'SLA Template Information',
        'list_title' => 'List of SLA Templates',

        'sla_code' => 'SLA Code',
        'contractor' => 'Contractor',
        'contract' => 'Contract',
        'contract_no' => 'Contract No.',
        'severity' => 'Severity',

        'response_time' => 'Incident Response Time (IRT)',
        'response_time_type' => 'Incident Response Time Type',
        'response_time_penalty' => 'Incident Response Time Penalty (RM)',
        'response_time_penalty_type' => 'Incident Response Time Type Penalty',

        'response_time_location' => 'On Site Response Time (ORT)',
        'response_time_location_type' => 'On Site Response Time Type',
        'response_time_location_penalty' => 'On Site Response Time Penalty (RM)',
        'response_time_location_penalty_type' => 'On Site Response Time Type Penalty',

        'temporary_resolution_time' => 'Temporary Solution',
        'temporary_resolution_time_type' => 'Temporary Solution Type',
        'temporary_resolution_time_penalty' => 'Temporary Solution Penalty (RM)',
        'temporary_resolution_time_penalty_type' => 'Temporary Solution Type Penalty',

        'resolution_time' => 'Problem Solution Incident (PRT)',
        'resolution_time_type' => 'Problem Solution Incident Type',
        'resolution_time_penalty' => 'Problem Solution Incident Penalty (RM)',
        'resolution_time_penalty_type' => 'Problem Solution Incident Type Penalty',

        'verify_resolution_time' => 'Verify Solution Incident',
        'verify_resolution_time_type' => 'Verify Solution Incident Type',
        'verify_resolution_time_penalty' => 'Verify Solution Incident Penalty (RM)',
        'verify_resolution_time_penalty_type' => 'Verify Solution Incident Penalty Type',

        'notes' => 'Notes',
    ],

    'global_settings' => [
        'list_title' => 'Global Settings List',
        'item_title' => 'Global Setting Information',

        'id' => 'ID',
        'category' => 'Category',
        'reference_code' => 'Reference Code',
        'name_ms' => 'Name (MS)',
        'name_en' => 'Name (EN)',
        'category_code' => 'Category Code',
        'nickname' => 'Nickname',
        'description' => 'Description',
        'status' => 'Status',
        'categories' => [
            'state' => 'States',
            'action_code_category' => 'Action Codes',
            'issue_level' => 'Issue Levels',
            'status' => 'Statuses',
            'branch_type' => 'Branch Types',
            'received_by' => 'Received By',
            'branch_category' => 'Branch Category',
            'day' => 'Day',
            'duration' => 'Duration',
            'action_code_email_recipient' => 'Email Recipient (Action Code)',
            'sla_type' => 'SLA Type',
            'severity' => 'Severity',
            'incident_asset_type' => 'Incident Asset Type',
            'received_via' => 'Received Via',
            'penalty_price' => 'Penalty Price',
            'loaner_type' => 'Loaner Type',
            'penalty_response_time' => 'Penalty Response Time',
            'penalty_timeframe_solution' => 'Penalty Resolution Timeframe',
            'penalty_response_time_location' => 'Penalty Location Response',
            'penalty_loaner' => 'Loaner Penalty',
            'incident_status' => 'Incident Status',
            'incident_resolution_status' => 'Incident Resolution Status',
            'workbasket_status' => 'Workbasket Status',
            'report_type' => 'Report Type',
            'report' => 'Report',
            'report_format' => 'Report Format',
            'group_user_type' => 'User Group Type'
        ],
    ],

    'audit_trails' => [
        'item_title' => 'Audit Trail Information',
        'list_title' => 'List of Audit Trails',

        'date' => 'Date',
        'time' => 'Time',
        'user' => 'User',
        'user_id' => 'User ID',
        'event' => 'Function',
        'old_values' => 'Old Values',
        'new_values' => 'New Values',
    ],

    'knowledge_base' => [
        'item_title' => 'Knowledge Base Entry Information',
        'list_title' => 'List of Knowledge Base Entries',

        'keywords' => 'Keywords',
        'category' => 'Category',
        'problem' => 'Problem',
        'solution' => 'Solution',
    ],

];
