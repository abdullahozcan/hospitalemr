<?php

return [
    'brand' => [
        'tagline' => 'Patient Portal',
        'auth_pitch' => 'Your healthcare, from anywhere',
        'auth_pitch_sub' => 'Appointments, prescriptions and lab results in one place.',
    ],

    'nav' => [
        'dashboard' => 'Home',
        'appointments' => 'My Appointments',
        'prescriptions' => 'My Prescriptions',
        'lab_results' => 'Lab Results',
        'invoices' => 'My Invoices',
        'profile' => 'My Profile',
        'logout' => 'Log out',
    ],

    'common' => [
        'view_all' => 'View all',
        'details' => 'Details',
        'records' => 'records',
        'orders' => 'orders',
    ],

    'dashboard' => [
        'greeting' => 'Hello, :name',
        'mrn_label' => 'MRN',
        'next_appointment' => 'Your next appointment',
        'view_all' => 'My appointments',
        'recent_activity' => 'Recent visits',
        'no_activity' => 'No visit records yet.',
        'balance_label' => 'Open balance',
        'balance_help' => 'Total unpaid service charges',
        'no_patient_record' => 'Patient record not found',
        'call_clinic' => 'You can contact our clinic to schedule an appointment.',
        'contact_support' => 'Please contact our clinic.',
    ],

    'appointments' => [
        'title' => 'My Appointments',
        'subtitle' => 'Your past and upcoming appointments',
        'upcoming' => 'Upcoming',
        'past' => 'Past',
        'default_title' => 'Visit',
        'with_provider' => 'Provider: :provider',
        'room' => 'Room :room',
        'no_upcoming' => 'No upcoming appointments. Call our clinic to schedule one.',
        'no_past' => 'No past appointment records.',
    ],

    'prescriptions' => [
        'title' => 'My Prescriptions',
        'subtitle' => 'Your e-prescription history and medication instructions',
        'number' => 'Prescription #RX-:number',
        'prescriber' => 'Prescriber',
        'dosage' => 'Dosage',
        'instructions' => 'Instructions',
        'days' => 'days',
        'empty' => 'No prescription records found.',
    ],

    'lab-results' => [
        'title' => 'Lab Results',
        'subtitle' => 'Your laboratory results and reference ranges',
        'number' => 'Order #LAB-:number',
        'empty' => 'No laboratory results found.',
    ],

    'invoices' => [
        'title' => 'My Invoices',
        'subtitle' => 'Your service charges and payments',
        'balance_label' => 'Open balance',
        'charges' => 'Service Charges',
        'payments' => 'Your Payments',
        'visit' => 'Visit',
        'discount' => ':amount discount',
        'no_charges' => 'No billed service charges.',
        'no_payments' => 'No payment records.',
    ],

    'profile' => [
        'title' => 'My Profile',
        'subtitle' => 'Update your personal information',
        'personal_info' => 'Account Details',
        'account' => 'Health Record Details',
        'name' => 'Name',
        'email' => 'Email',
        'mrn' => 'MRN',
        'birth_date' => 'Date of Birth',
    ],
];
