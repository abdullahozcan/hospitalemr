<?php

namespace App\Enums;

enum Permission: string
{
    case AccessAdminPanel = 'access_admin_panel';

    case ViewAnyFacility = 'view_any_facility';
    case ViewFacility = 'view_facility';
    case CreateFacility = 'create_facility';
    case UpdateFacility = 'update_facility';
    case DeleteFacility = 'delete_facility';

    case ViewAnyPatient = 'view_any_patient';
    case ViewPatient = 'view_patient';
    case CreatePatient = 'create_patient';
    case UpdatePatient = 'update_patient';
    case DeletePatient = 'delete_patient';

    case ViewAnyAppointment = 'view_any_appointment';
    case ViewAppointment = 'view_appointment';
    case CreateAppointment = 'create_appointment';
    case UpdateAppointment = 'update_appointment';
    case DeleteAppointment = 'delete_appointment';

    case ViewAnyEncounter = 'view_any_encounter';
    case ViewEncounter = 'view_encounter';
    case CreateEncounter = 'create_encounter';
    case UpdateEncounter = 'update_encounter';
    case DeleteEncounter = 'delete_encounter';

    case ViewAnyPrescription = 'view_any_prescription';
    case ViewPrescription = 'view_prescription';
    case CreatePrescription = 'create_prescription';
    case UpdatePrescription = 'update_prescription';
    case DeletePrescription = 'delete_prescription';

    case ViewAnyLabOrder = 'view_any_lab_order';
    case ViewLabOrder = 'view_lab_order';
    case CreateLabOrder = 'create_lab_order';
    case RecordLabResult = 'record_lab_result';
    case ReviewLabResult = 'review_lab_result';

    case ViewAnyReport = 'view_any_report';

    case ViewAnyService = 'view_any_service';
    case ViewService = 'view_service';
    case CreateService = 'create_service';
    case UpdateService = 'update_service';
    case DeleteService = 'delete_service';

    case ViewAnyBilling = 'view_any_billing';
    case ViewBilling = 'view_billing';
    case CreateBilling = 'create_billing';
    case UpdateBilling = 'update_billing';
    case DeleteBilling = 'delete_billing';

    case ViewAnyPayment = 'view_any_payment';
    case ViewPayment = 'view_payment';
    case CreatePayment = 'create_payment';
    case UpdatePayment = 'update_payment';
    case DeletePayment = 'delete_payment';

    case ViewAnyClaim = 'view_any_claim';
    case ViewClaim = 'view_claim';
    case CreateClaim = 'create_claim';
    case UpdateClaim = 'update_claim';
    case DeleteClaim = 'delete_claim';

    /**
     * Get all permission values.
     *
     * @return array<string>
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
