<?php

return [
    'roles' => [
        'admin' => 'Admin',
        'doctor' => 'Doctor',
        'nurse' => 'Nurse',
        'receptionist' => 'Receptionist',
        'accountant' => 'Accountant',
        'lab_technician' => 'Lab Technician',
    ],

    'permissions' => [
        'access_admin_panel' => 'Access admin panel',

        'view_any_facility' => 'View any facility',
        'view_facility' => 'View facility',
        'create_facility' => 'Create facility',
        'update_facility' => 'Update facility',
        'delete_facility' => 'Delete facility',

        'view_any_patient' => 'View any patient',
        'view_patient' => 'View patient',
        'create_patient' => 'Create patient',
        'update_patient' => 'Update patient',
        'delete_patient' => 'Delete patient',

        'view_any_appointment' => 'View any appointment',
        'view_appointment' => 'View appointment',
        'create_appointment' => 'Create appointment',
        'update_appointment' => 'Update appointment',
        'delete_appointment' => 'Delete appointment',

        'view_any_encounter' => 'View any encounter',
        'view_encounter' => 'View encounter',
        'create_encounter' => 'Create encounter',
        'update_encounter' => 'Update encounter',
        'delete_encounter' => 'Delete encounter',

        'view_any_prescription' => 'View any prescription',
        'view_prescription' => 'View prescription',
        'create_prescription' => 'Create prescription',
        'update_prescription' => 'Update prescription',
        'delete_prescription' => 'Delete prescription',

        'view_any_lab_order' => 'View any lab order',
        'view_lab_order' => 'View lab order',
        'create_lab_order' => 'Create lab order',
        'record_lab_result' => 'Record lab result',
        'review_lab_result' => 'Review lab result',

        'view_any_report' => 'View any report',

        'view_any_service' => 'View any service',
        'view_service' => 'View service',
        'create_service' => 'Create service',
        'update_service' => 'Update service',
        'delete_service' => 'Delete service',

        'view_any_billing' => 'View any billing line',
        'view_billing' => 'View billing line',
        'create_billing' => 'Create billing line',
        'update_billing' => 'Update billing line',
        'delete_billing' => 'Delete billing line',

        'view_any_payment' => 'View any payment',
        'view_payment' => 'View payment',
        'create_payment' => 'Create payment',
        'update_payment' => 'Update payment',
        'delete_payment' => 'Delete payment',

        'view_any_claim' => 'View any claim',
        'view_claim' => 'View claim',
        'create_claim' => 'Create claim',
        'update_claim' => 'Update claim',
        'delete_claim' => 'Delete claim',
    ],

    'navigation' => [
        'organization' => 'Organization',
        'patients' => 'Patients',
        'clinical' => 'Clinical',
        'billing' => 'Billing',
        'laboratory' => 'Laboratory',
        'reports' => 'Reports',
        'settings' => 'Settings',
    ],

    'genders' => [
        'male' => 'Male',
        'female' => 'Female',
        'other' => 'Other',
    ],

    'blood_types' => [
        'A+' => 'A Rh+',
        'A-' => 'A Rh-',
        'B+' => 'B Rh+',
        'B-' => 'B Rh-',
        'AB+' => 'AB Rh+',
        'AB-' => 'AB Rh-',
        '0+' => 'O Rh+',
        '0-' => 'O Rh-',
    ],

    'age_unit' => [
        'years' => 'years old',
    ],

    'patients' => [
        'singular' => 'Patient',
        'plural' => 'Patients',
        'invite_to_portal' => 'Invite to patient portal',
        'invite_to_portal_help' => 'A portal account will be created for this patient and a password setup link will be sent to :email.',
        'invite_sent' => 'Portal invitation sent. The patient can set their password from the email.',
        'invite_failed' => 'The invitation link could not be sent. Check the email settings.',
    ],

    'patient-form' => [
        'mrn' => 'MRN',
        'full_name' => 'Full Name',
        'first_name' => 'First Name',
        'last_name' => 'Last Name',
        'birth_date' => 'Date of Birth',
        'age' => 'Age',
        'gender' => 'Gender',
        'national_id' => 'National ID',
        'blood_type' => 'Blood Type',
        'phone' => 'Phone',
        'email' => 'Email',
        'address' => 'Address',
        'city' => 'City',
        'district' => 'District',
        'emergency_contact_name' => 'Emergency Contact',
        'emergency_contact_phone' => 'Emergency Phone',
        'emergency_contact_relation' => 'Relationship',
        'is_deceased' => 'Deceased',
        'deceased_at' => 'Date of Death',
        'notes' => 'Notes',
        'policies' => 'Insurance Policies',
        'registered_at' => 'Registered At',
        'tabs' => [
            'demographics' => 'Demographics',
            'contact' => 'Contact',
            'emergency' => 'Emergency',
            'additional' => 'Additional',
        ],
    ],

    'insurance' => [
        'company_name' => 'Insurance Company',
        'policy_number' => 'Policy Number',
        'start_date' => 'Start Date',
        'end_date' => 'End Date',
        'notes' => 'Notes',
    ],

    'documents' => [
        'title' => 'Title',
        'file' => 'File',
        'uploaded_by' => 'Uploaded By',
        'uploaded_at' => 'Uploaded At',
    ],

    'appointments' => [
        'singular' => 'Appointment',
        'plural' => 'Appointments',
        'patient' => 'Patient',
        'provider' => 'Provider',
        'facility' => 'Facility',
        'title' => 'Title',
        'starts_at' => 'Starts At',
        'ends_at' => 'Ends At',
        'duration' => 'Duration',
        'status' => 'Status',
        'room' => 'Room',
        'notes' => 'Notes',
        'section' => [
            'appointment' => 'Appointment Details',
            'notes' => 'Notes',
        ],
        'filters' => [
            'today' => 'Today',
            'upcoming' => 'Upcoming',
        ],
    ],

    'appointment-statuses' => [
        'scheduled' => 'Scheduled',
        'checked_in' => 'Checked In',
        'in_progress' => 'In Progress',
        'completed' => 'Completed',
        'no_show' => 'No Show',
        'cancelled' => 'Cancelled',
    ],

    'flow-board' => [
        'singular' => 'Flow Board',
        'title' => "Today's Flow Board",
    ],

    'encounters' => [
        'singular' => 'Encounter',
        'plural' => 'Encounters',
        'patient' => 'Patient',
        'started_at' => 'Encounter Date',
        'ended_at' => 'Ended At',
        'chief_complaint' => 'Chief Complaint',
        'subjective' => 'Subjective (S)',
        'objective' => 'Objective (O)',
        'assessment' => 'Assessment (A)',
        'plan' => 'Plan (P)',
        'tabs' => [
            'soap' => 'SOAP Note',
            'notes' => 'Notes',
        ],
        'filters' => [
            'open' => 'Open Encounters',
        ],
    ],

    'vitals' => [
        'singular' => 'Vital',
        'plural' => 'Vitals',
        'measured_at' => 'Measured At',
        'height_cm' => 'Height (cm)',
        'weight' => 'Weight',
        'weight_help' => 'Enter in grams (e.g. 72000 = 72 kg)',
        'systolic' => 'Systolic',
        'diastolic' => 'Diastolic',
        'blood_pressure' => 'Blood Pressure',
        'pulse' => 'Pulse',
        'temperature' => 'Temperature',
        'spo2' => 'SpO2',
        'respiratory_rate' => 'Respiratory Rate',
        'bmi' => 'BMI',
    ],

    'diagnoses' => [
        'singular' => 'Diagnosis',
        'plural' => 'Diagnoses',
        'code' => 'Code',
        'description' => 'Description',
        'is_primary' => 'Primary Diagnosis',
        'attach' => 'Attach Diagnosis',
    ],

    'allergies' => [
        'singular' => 'Allergy',
        'plural' => 'Allergies',
        'allergen' => 'Allergen',
        'reaction' => 'Reaction',
        'severity' => 'Severity',
        'noted_at' => 'Noted At',
    ],

    'allergy-severities' => [
        'mild' => 'Mild',
        'moderate' => 'Moderate',
        'severe' => 'Severe',
        'anaphylactic' => 'Anaphylactic',
    ],

    'immunizations' => [
        'singular' => 'Immunization',
        'plural' => 'Immunizations',
        'vaccine_name' => 'Vaccine',
        'dose_number' => 'Dose No',
        'administered_at' => 'Administered At',
    ],

    'prescriptions' => [
        'singular' => 'Prescription',
        'plural' => 'Prescriptions',
        'prescriber' => 'Prescriber',
        'issued_at' => 'Issued At',
        'dispensed_at' => 'Dispensed At',
        'dispense' => 'Dispensed',
        'drug' => 'Drug',
        'dosage' => 'Dosage',
        'frequency' => 'Frequency',
        'duration_days' => 'Duration (days)',
        'quantity' => 'Quantity',
        'instructions' => 'Instructions',
        'items' => 'Medication Items',
        'print' => 'Print',
        'print_title' => 'Prescription #RX-:id',
        'print_footer' => 'This document was generated by HospitaleMR. (Prescription No: RX-:id)',
        'days' => 'days',
        'section' => [
            'details' => 'Prescription Details',
            'items' => 'Medication Items',
        ],
    ],

    'prescription-statuses' => [
        'draft' => 'Draft',
        'active' => 'Active',
        'dispensed' => 'Dispensed',
        'cancelled' => 'Cancelled',
    ],

    'drugs' => [
        'singular' => 'Drug',
        'plural' => 'Drugs',
        'code' => 'Drug Code',
        'name' => 'Drug Name',
        'active_ingredient' => 'Active Ingredient',
        'form' => 'Form',
        'strength' => 'Strength',
        'unit' => 'Unit',
        'is_prescription_only' => 'Prescription Only',
        'stock_quantity' => 'Stock Quantity',
        'low_stock_threshold' => 'Low Stock Threshold',
        'is_low_stock' => 'Low Stock',
        'filters' => [
            'low_stock' => 'Low Stock',
        ],
        'section' => [
            'details' => 'Drug Details',
            'stock' => 'Stock',
        ],
    ],

    'services' => [
        'singular' => 'Service',
        'plural' => 'Services',
        'code' => 'Service Code',
        'name' => 'Service Name',
        'description' => 'Description',
        'price' => 'Price',
        'is_active' => 'Active',
        'times_billed' => 'Times Billed',
        'section' => [
            'details' => 'Service Details',
        ],
    ],

    'billing-lines' => [
        'singular' => 'Billing Line',
        'plural' => 'Billing Lines',
        'service' => 'Service',
        'quantity' => 'Quantity',
        'unit_price' => 'Unit Price',
        'discount' => 'Discount',
        'total' => 'Total',
        'billed_at' => 'Billed At',
        'apply_service_price' => 'Apply catalog price',
        'summary' => 'Billing Summary',
        'filters' => [
            'linked_to_encounter' => 'Linked to encounter',
        ],
        'section' => [
            'details' => 'Billing Line Details',
        ],
    ],

    'payments' => [
        'singular' => 'Payment',
        'plural' => 'Payments',
        'method' => 'Method',
        'amount' => 'Amount',
        'paid_at' => 'Paid At',
        'reference' => 'Reference',
        'received_by' => 'Received By',
        'total' => 'Grand Total',
        'section' => [
            'details' => 'Payment Details',
        ],
    ],

    'payment-methods' => [
        'cash' => 'Cash',
        'card' => 'Card',
        'transfer' => 'Transfer',
        'insurance' => 'Insurance',
    ],

    'claims' => [
        'singular' => 'Claim',
        'plural' => 'Claims',
        'insurer_name' => 'Insurance Company',
        'claim_number' => 'Claim Number',
        'amount' => 'Claim Amount',
        'submitted_at' => 'Submitted At',
        'decided_at' => 'Decided At',
        'section' => [
            'details' => 'Claim Details',
        ],
    ],

    'claim-statuses' => [
        'draft' => 'Draft',
        'submitted' => 'Submitted',
        'approved' => 'Approved',
        'denied' => 'Denied',
        'paid' => 'Paid',
    ],

    'lab-orders' => [
        'singular' => 'Lab Order',
        'plural' => 'Lab Orders',
        'ordered_at' => 'Ordered At',
        'ordered_by' => 'Ordered By',
        'sample_collected_at' => 'Sample Collected At',
        'tests' => 'Ordered Tests',
        'results_count' => 'Result Count',
        'filters' => [
            'pending' => 'Pending Orders',
        ],
        'section' => [
            'details' => 'Order Details',
            'results' => 'Results',
        ],
    ],

    'lab-order-statuses' => [
        'ordered' => 'Ordered',
        'collected' => 'Collected',
        'in_laboratory' => 'In Laboratory',
        'resulted' => 'Resulted',
        'reviewed' => 'Reviewed',
        'cancelled' => 'Cancelled',
    ],

    'lab-tests' => [
        'singular' => 'Test',
        'plural' => 'Tests',
        'code' => 'Test Code',
        'name' => 'Test Name',
        'category' => 'Category',
        'sample_type' => 'Sample Type',
        'unit' => 'Unit',
        'reference_range' => 'Reference Range',
    ],

    'lab-results' => [
        'singular' => 'Result',
        'plural' => 'Results',
        'value' => 'Value',
        'value_text' => 'Text Value',
        'flag' => 'Flag',
        'resulted_by' => 'Resulted By',
        'reviewed_by' => 'Reviewed By',
        'review' => 'Mark as reviewed',
    ],

    'lab-result-flags' => [
        'normal' => 'Normal',
        'low' => 'Low',
        'high' => 'High',
        'critical' => 'Critical',
    ],

    'widgets' => [
        'appointments_today' => "Today's Appointments",
        'appointments_today_help' => 'Total appointments scheduled today',
        'open_encounters' => 'Open Encounters',
        'open_encounters_help' => 'Encounters not yet completed',
        'revenue_today' => "Today's Revenue",
        'revenue_help' => 'Total payments collected today',
        'pending_lab_orders' => 'Pending Lab Orders',
        'pending_lab_orders_help' => 'Orders waiting at the laboratory',
    ],

    'users' => [
        'singular' => 'User',
        'plural' => 'Users',
        'name' => 'Name',
        'email' => 'Email',
        'password' => 'Password',
        'roles' => 'Roles',
        'current_team' => 'Current Clinic',
        'verified_at' => 'Verified At',
        'created_at' => 'Created At',
        'section' => [
            'details' => 'User Details',
        ],
    ],

    'facilities' => [
        'singular' => 'Facility',
        'plural' => 'Facilities',
        'name' => 'Facility Name',
        'code' => 'Facility Code',
        'phone' => 'Phone',
        'email' => 'Email',
        'address' => 'Address',
        'city' => 'City',
        'district' => 'District',
        'postal_code' => 'Postal Code',
        'is_active' => 'Active',
        'section' => [
            'details' => 'Facility Details',
            'notes' => 'Notes',
        ],
    ],

    'api-clients' => [
        'singular' => 'API Client',
        'plural' => 'API Clients',
        'name' => 'Client Name',
        'client_code' => 'Client Code',
        'is_active' => 'Active',
        'last_used_at' => 'Last Used',
        'requests_count' => 'Requests',
        'generate_token' => 'Generate Token',
        'token_title' => 'New API Token',
        'token_warning' => 'This token is shown only once. Store it somewhere safe.',
        'back' => 'Back to API clients',
        'section' => [
            'details' => 'Client Details',
        ],
    ],

    'integration' => [
        'singular' => 'Integration Log',
        'plural' => 'Integration Logs',
        'service' => 'Service',
        'operation' => 'Operation',
        'subject' => 'Record',
        'code' => 'Response Code',
        'message' => 'Message',
        'attempts' => 'Attempts',
        'sent_at' => 'Sent At',
        'retry' => 'Retry',
        'request' => 'Request Payload',
        'response' => 'Response Payload',
        'statuses' => [
            'pending' => 'Pending',
            'success' => 'Success',
            'failed' => 'Failed',
        ],
    ],

    'validation' => [
        'appointment_overlap' => 'The selected provider already has an appointment at this time.',
    ],
];
