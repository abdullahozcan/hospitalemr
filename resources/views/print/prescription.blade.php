<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ __('app.prescriptions.print_title', ['id' => $prescription->id]) }}</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: -apple-system, "Segoe UI", Arial, sans-serif; color: #111; padding: 48px; max-width: 800px; margin: 0 auto; }
        .header { display: flex; justify-content: space-between; align-items: flex-start; border-bottom: 2px solid #111; padding-bottom: 16px; margin-bottom: 24px; }
        .clinic-name { font-size: 20px; font-weight: 700; }
        .clinic-sub { font-size: 12px; color: #444; }
        .rx { font-size: 32px; font-weight: 800; }
        .info-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 8px 32px; font-size: 14px; margin-bottom: 24px; }
        .label { font-size: 10px; text-transform: uppercase; color: #666; letter-spacing: 0.05em; }
        table { width: 100%; border-collapse: collapse; font-size: 14px; margin-bottom: 24px; }
        th, td { text-align: left; padding: 8px 6px; border-bottom: 1px solid #ddd; vertical-align: top; }
        th { font-size: 10px; text-transform: uppercase; color: #666; letter-spacing: 0.05em; }
        .notes { font-size: 13px; color: #333; margin-bottom: 24px; }
        .signature { margin-top: 64px; text-align: right; font-size: 14px; }
        .signature-line { display: inline-block; border-top: 1px solid #111; padding-top: 4px; min-width: 220px; }
        .footer { margin-top: 32px; font-size: 10px; color: #999; text-align: center; }
        @media print {
            body { padding: 24px; }
            .print-button { display: none; }
        }
        .print-button { margin-bottom: 24px; }
        .print-button button { padding: 8px 16px; font-size: 14px; cursor: pointer; }
    </style>
</head>
<body>
    <div class="print-button">
        <button onclick="window.print()">{{ __('app.prescriptions.print') }}</button>
    </div>

    <div class="header">
        <div>
            <div class="clinic-name">{{ config('app.name') }}</div>
            <div class="clinic-sub">{{ $prescription->team->name ?? '' }}</div>
        </div>
        <div class="rx">℞</div>
    </div>

    <div class="info-grid">
        <div>
            <div class="label">{{ __('app.patient-form.mrn') }}</div>
            {{ $prescription->patient->mrn }}
        </div>
        <div>
            <div class="label">{{ __('app.appointments.patient') }}</div>
            {{ $prescription->patient->full_name }}
        </div>
        <div>
            <div class="label">{{ __('app.prescriptions.prescriber') }}</div>
            {{ $prescription->prescriber?->name ?? '—' }}
        </div>
        <div>
            <div class="label">{{ __('app.prescriptions.issued_at') }}</div>
            {{ $prescription->issued_at->format('d.m.Y H:i') }}
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>{{ __('app.prescriptions.drug') }}</th>
                <th>{{ __('app.prescriptions.dosage') }}</th>
                <th>{{ __('app.prescriptions.frequency') }}</th>
                <th>{{ __('app.prescriptions.duration_days') }}</th>
                <th>{{ __('app.prescriptions.quantity') }}</th>
                <th>{{ __('app.prescriptions.instructions') }}</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($prescription->items as $item)
                <tr>
                    <td>{{ $item->drug->display_name }}</td>
                    <td>{{ $item->dosage ?? '—' }}</td>
                    <td>{{ $item->frequency ?? '—' }}</td>
                    <td>{{ $item->duration_days ? $item->duration_days.' '.__('app.prescriptions.days') : '—' }}</td>
                    <td>{{ $item->quantity ?? '—' }}</td>
                    <td>{{ $item->instructions ?? '—' }}</td>
                </tr>
            @empty
                <tr><td colspan="6">—</td></tr>
            @endforelse
        </tbody>
    </table>

    @if ($prescription->notes)
        <div class="notes">
            <div class="label">{{ __('app.patient-form.notes') }}</div>
            {{ $prescription->notes }}
        </div>
    @endif

    <div class="signature">
        <div class="signature-line">{{ $prescription->prescriber?->name ?? '' }}</div>
    </div>

    <div class="footer">
        {{ __('app.prescriptions.print_footer', ['id' => $prescription->id]) }}
    </div>
</body>
</html>
