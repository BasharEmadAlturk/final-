<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Employee;
use App\Models\Service;
use App\Models\Setting;
use App\Models\Appointment;
use Spatie\OpeningHours\OpeningHours;
use Carbon\Carbon;
use Illuminate\Support\Number;
use View;

class FrontendController extends Controller
{
    public function __construct()
    {
        $setting = Setting::first(); // بدل firstOrFail()
        if ($setting) {
            View::share('setting', $setting);
        }
    }

    public function index()
    {
        $setting = Setting::first(); // هذا يسحب أول سجل من settings

        $categories = Category::with([
            'services' => function ($query) {
                $query->where('status', 1)
                      ->with('employees');
            }
        ])->where('status', 1)->get();

        $employees = Employee::with('services')->with('user')->get();

        return view('frontend.index', compact('setting', 'categories', 'employees'));
    }

    public function getServices(Request $request, Category $category)
    {
        $services = $category->services()
            ->where('status', 1)
            ->with('category')
            ->get();

        return response()->json([
            'success' => true,
            'services' => $services
        ]);
    }

    public function getEmployees(Request $request, Service $service)
    {
        $employees = $service->employees()
            ->whereHas('user', function ($query) {
                $query->where('status', 1);
            })
            ->with('user')
            ->get();

        if ($employees->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'No employees available for this service'
            ]);
        }

        return response()->json([
            'success' => true,
            'employees' => $employees,
            'service' => $service
        ]);
    }

    public function getEmployeeAvailability(Employee $employee, $date = null)
    {
        $date = $date ? Carbon::parse($date) : now();

        if (!$employee->slot_duration) {
            return response()->json(['error' => 'Slot duration not set for this employee'], 400);
        }

        try {
            // Function to ensure proper time formatting
            function formatTimeRange($timeRange)
            {
                if (str_contains($timeRange, 'AM') || str_contains($timeRange, 'PM')) {
                    $timeRange = str_replace([' AM', ' PM', ' '], '', $timeRange);
                }
                $times = explode('-', $timeRange);
                $formattedTimes = array_map(function ($time) {
                    $parts = explode(':', $time);
                    $hours = str_pad(trim($parts[0]), 2, '0', STR_PAD_LEFT);
                    return $hours . ':' . $parts[1];
                }, $times);

                return implode('-', $formattedTimes);
            }

            // Process holidays exceptions
            $holidaysExceptions = $employee->holidays->mapWithKeys(function ($holiday) {
                $hours = !empty($holiday->hours) ? collect($holiday->hours)->map(function ($timeRange) {
                    return formatTimeRange($timeRange);
                })->toArray() : [];
                return [$holiday->date => $hours];
            })->toArray();

            $openingHours = OpeningHours::create(array_merge(
                $employee->days,
                ['exceptions' => $holidaysExceptions]
            ));

            $availableRanges = $openingHours->forDate($date);

            if ($availableRanges->isEmpty()) {
                return response()->json(['available_slots' => []]);
            }

            $slots = $this->generateTimeSlots(
                $availableRanges,
                $employee->slot_duration,
                $employee->break_duration ?? 0,
                $date,
                $employee->id
            );

            return response()->json([
                'employee_id' => $employee->id,
                'date' => $date->toDateString(),
                'available_slots' => $slots,
                'slot_duration' => $employee->slot_duration,
                'break_duration' => $employee->break_duration,
            ]);

        } catch (\Exception $e) {
            return response()->json(['error' => 'Error processing availability: ' . $e->getMessage()], 500);
        }
    }

    protected function generateTimeSlots($availableRanges, $slotDuration, $breakDuration, $date, $employeeId)
    {
        $slots = [];
        $now = now();
        $isToday = $date->isToday();

        $existingAppointments = Appointment::where('booking_date', $date->toDateString())
            ->where('employee_id', $employeeId)
            ->whereNotIn('status', ['Cancelled'])
            ->get(['booking_time']);

        $bookedSlots = $existingAppointments->map(function ($appointment) use ($date) {
            $times = explode(' - ', $appointment->booking_time);
            return [
                'start' => Carbon::createFromFormat('g:i A', trim($times[0]))->format('H:i'),
                'end' => Carbon::createFromFormat('g:i A', trim($times[1]))->format('H:i')
            ];
        })->toArray();

        foreach ($availableRanges as $range) {
            $start = Carbon::parse($date->toDateString() . ' ' . $range->start()->format('H:i'));
            $end = Carbon::parse($date->toDateString() . ' ' . $range->end()->format('H:i'));

            if ($isToday && $end->lte($now)) continue;

            $currentSlotStart = clone $start;

            if ($isToday && $currentSlotStart->lt($now)) {
                $currentSlotStart = clone $now;
                $minutes = $currentSlotStart->minute;
                $remainder = $minutes % $slotDuration;
                if ($remainder > 0) {
                    $currentSlotStart->addMinutes($slotDuration - $remainder)->second(0);
                }
            }

            while ($currentSlotStart->copy()->addMinutes($slotDuration)->lte($end)) {
                $slotEnd = $currentSlotStart->copy()->addMinutes($slotDuration);

                $isAvailable = true;
                foreach ($bookedSlots as $bookedSlot) {
                    $bookedStart = Carbon::parse($date->toDateString() . ' ' . $bookedSlot['start']);
                    $bookedEnd = Carbon::parse($date->toDateString() . ' ' . $bookedSlot['end']);
                    if ($currentSlotStart->lt($bookedEnd) && $slotEnd->gt($bookedStart)) {
                        $isAvailable = false;
                        break;
                    }
                }

                if ($isAvailable && (!$isToday || $slotEnd->gt($now))) {
                    $slots[] = [
                        'start' => $currentSlotStart->format('H:i'),
                        'end' => $slotEnd->format('H:i'),
                        'display' => $currentSlotStart->format('g:i A') . ' - ' . $slotEnd->format('g:i A'),
                    ];
                }

                $currentSlotStart->addMinutes($slotDuration + $breakDuration);

                if ($currentSlotStart->copy()->addMinutes($slotDuration)->gt($end)) {
                    break;
                }
            }
        }

        return $slots;
    }
}
