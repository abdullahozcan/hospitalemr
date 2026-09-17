<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum UserRole: string implements HasLabel
{
    case Admin = 'admin';
    case Doctor = 'doctor';
    case Nurse = 'nurse';
    case Receptionist = 'receptionist';
    case Accountant = 'accountant';
    case LabTechnician = 'lab_technician';

    /**
     * Get the display label for the role.
     */
    public function getLabel(): string
    {
        return __('roles.'.$this->value);
    }

    /**
     * Get the Filament badge color for the role.
     */
    public function getColor(): string
    {
        return match ($this) {
            self::Admin => 'danger',
            self::Doctor => 'primary',
            self::Nurse => 'success',
            self::Receptionist => 'info',
            self::Accountant => 'warning',
            self::LabTechnician => 'gray',
        };
    }

    /**
     * Get the permission names granted to this role.
     *
     * @return list<Permission>
     */
    public function permissions(): array
    {
        return match ($this) {
            self::Admin => Permission::cases(),
            self::Doctor => [
                Permission::AccessAdminPanel,
                Permission::ViewAnyFacility,
                Permission::ViewFacility,
                Permission::ViewAnyPatient,
                Permission::ViewPatient,
                Permission::CreatePatient,
                Permission::UpdatePatient,
                Permission::DeletePatient,
                Permission::ViewAnyAppointment,
                Permission::ViewAppointment,
                Permission::CreateAppointment,
                Permission::UpdateAppointment,
                Permission::DeleteAppointment,
                Permission::ViewAnyEncounter,
                Permission::ViewEncounter,
                Permission::CreateEncounter,
                Permission::UpdateEncounter,
                Permission::ViewAnyPrescription,
                Permission::ViewPrescription,
                Permission::CreatePrescription,
                Permission::UpdatePrescription,
                Permission::ViewAnyLabOrder,
                Permission::ViewLabOrder,
                Permission::CreateLabOrder,
                Permission::ReviewLabResult,
                Permission::ViewAnyReport,
                Permission::ViewAnyBilling,
                Permission::ViewBilling,
                Permission::CreateBilling,
                Permission::UpdateBilling,
            ],
            self::Nurse => [
                Permission::AccessAdminPanel,
                Permission::ViewAnyFacility,
                Permission::ViewFacility,
                Permission::ViewAnyPatient,
                Permission::ViewPatient,
                Permission::CreatePatient,
                Permission::UpdatePatient,
                Permission::ViewAnyAppointment,
                Permission::ViewAppointment,
                Permission::CreateAppointment,
                Permission::UpdateAppointment,
                Permission::ViewAnyEncounter,
                Permission::ViewEncounter,
                Permission::CreateEncounter,
                Permission::UpdateEncounter,
                Permission::ViewAnyLabOrder,
                Permission::ViewLabOrder,
                Permission::CreateLabOrder,
                Permission::RecordLabResult,
            ],
            self::Receptionist => [
                Permission::AccessAdminPanel,
                Permission::ViewAnyFacility,
                Permission::ViewFacility,
                Permission::ViewAnyPatient,
                Permission::ViewPatient,
                Permission::CreatePatient,
                Permission::UpdatePatient,
                Permission::ViewAnyAppointment,
                Permission::ViewAppointment,
                Permission::CreateAppointment,
                Permission::UpdateAppointment,
                Permission::DeleteAppointment,
                Permission::ViewAnyEncounter,
                Permission::ViewEncounter,
                Permission::CreateEncounter,
                Permission::ViewAnyService,
                Permission::ViewService,
                Permission::ViewAnyBilling,
                Permission::ViewBilling,
                Permission::CreateBilling,
                Permission::UpdateBilling,
                Permission::ViewAnyPayment,
                Permission::ViewPayment,
                Permission::CreatePayment,
            ],
            self::Accountant => [
                Permission::AccessAdminPanel,
                Permission::ViewAnyFacility,
                Permission::ViewFacility,
                Permission::ViewAnyPatient,
                Permission::ViewPatient,
                Permission::ViewAnyReport,
                Permission::ViewAnyService,
                Permission::ViewService,
                Permission::CreateService,
                Permission::UpdateService,
                Permission::ViewAnyBilling,
                Permission::ViewBilling,
                Permission::CreateBilling,
                Permission::UpdateBilling,
                Permission::DeleteBilling,
                Permission::ViewAnyPayment,
                Permission::ViewPayment,
                Permission::CreatePayment,
                Permission::UpdatePayment,
                Permission::DeletePayment,
                Permission::ViewAnyClaim,
                Permission::ViewClaim,
                Permission::CreateClaim,
                Permission::UpdateClaim,
                Permission::DeleteClaim,
            ],
            self::LabTechnician => [
                Permission::AccessAdminPanel,
                Permission::ViewAnyFacility,
                Permission::ViewFacility,
                Permission::ViewAnyPatient,
                Permission::ViewPatient,
                Permission::ViewAnyLabOrder,
                Permission::ViewLabOrder,
                Permission::RecordLabResult,
            ],
        };
    }

    /**
     * Get all role values.
     *
     * @return list<string>
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
