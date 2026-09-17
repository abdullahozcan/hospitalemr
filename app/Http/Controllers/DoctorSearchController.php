<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class DoctorSearchController extends Controller
{
    /**
     * The public home page with featured doctors.
     */
    public function home(): View
    {
        $doctors = $this->doctorQuery()->limit(6)->get();

        return view('welcome', ['doctors' => $doctors]);
    }

    /**
     * The public doctor directory with search.
     */
    public function index(Request $request): View
    {
        $query = User::query()
            ->role('doctor')
            ->with(['providerSchedules.facility', 'providerSchedules']);

        if (is_string($q = $request->query('q')) && $q !== '') {
            $query->where('name', 'like', '%'.$q.'%');
        }

        $doctors = $query->orderBy('name')
            ->limit(24)
            ->get();

        return view('public.doctors', [
            'doctors' => $doctors,
            'q' => $q ?? '',
        ]);
    }

    /**
     * Base doctor query with schedules.
     *
     * @return Builder<User>
     */
    protected function doctorQuery(): Builder
    {
        return User::query()
            ->role('doctor')
            ->with(['providerSchedules.facility'])
            ->orderBy('name');
    }
}
