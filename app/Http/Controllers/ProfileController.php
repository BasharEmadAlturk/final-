<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Employee;
use App\Models\Holiday;

class ProfileController extends Controller
{
    public function index()
    {
        $user = \Auth::user();
         // Available days of the week
         $days = [
            'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday',
        ];

        // Available slot duration steps
        $steps = ['10', '15', '20', '30', '45', '60'];

        // Available break duration steps
        $breaks = ['5', '10', '15', '20', '25', '30'];


         // Get the employee's availability (days) data if it exists and convert to an array
         $employeeDays = $user->employee->days ?? [];

         // Transform availability slots
         $employeeDays = $this->transformAvailabilitySlotsForEdit($employeeDays);



        return view('backend.profile.index',compact('user','days','steps','breaks','employeeDays'));
    }



    public function profileUpdate(Request $request, User $user)
    {
        // Validate the incoming request data
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
        ]);

        // Check if email is being changed and if user is not admin
        if ($request->email !== $user->email && !auth()->user()->hasRole('admin')) {
            return redirect()->back()->withErrors(['email' => 'Only administrators can change email addresses.']);
        }

        // Update the user with validated data
        $user->update($data);

        // Redirect back with a success message
        return redirect()->back()->withSuccess('Profile has been successfully updated!');
    }


    public function employeeProfileUpdate(Request $request, Employee $employee)
    {

        $data = $request->validate([
            'slot_duration' => function ($attribute, $value, $fail) use ($request) {
                // Check if 'is_employee' is true and 'slot_duration' is missing
                if ($request->is_employee && !$value) {
                    $fail('The ' . $attribute . ' field is required when the employee is true.');
                }
                // If it's present, it should be numeric
                if ($value && !is_numeric($value)) {
                    $fail('The ' . $attribute . ' field must be numeric.');
                }
            },
            'break_duration' => 'nullable',
            'days' => 'nullable',
            'holidays.date.*' => 'sometimes|required',
            'holidays.from_time' => 'nullable',
            'holidays.to_time' => 'nullable',
            'holidays.recurring' => 'nullable',
        ]);



        if (!empty($data['days'])) {
            $data['days'] = $this->transformOpeningHours($data['days']);
        }

        // dd($data);

        // Update or create Employee record
        // $employee = Employee::updateOrCreate(
        //     ['user_id' => $employee->id], // Condition to check
        //     [
        //         'days' => $data['days'] ?? null,
        //         'slot_duration' => $data['slot_duration'] ?? null,
        //         'break_duration' => $data['break_duration'] ?? null
        //     ]
        // );

        $employee->update(
            [
                'days' => $data['days'] ?? null,
                'slot_duration' => $data['slot_duration'] ?? null,
                'break_duration' => $data['break_duration'] ?? null
            ]
        );

        if ($request->has('holidays.date') && is_array($request->input('holidays.date'))) {
            // Get all existing holiday IDs for this employee
            $existingHolidayIds = $employee->holidays->pluck('id')->toArray();
            $submittedHolidayIds = [];

            $dates = $request->input('holidays.date');
            $fromTimes = $request->input('holidays.from_time');
            $toTimes = $request->input('holidays.to_time');
            $recurring = $request->input('holidays.recurring');
            $holidayIds = $request->input('holidays.id', []); // Add hidden input for holiday IDs in your form

            foreach ($dates as $index => $date) {
                $holidayData = [
                    'employee_id' => $employee->id,
                    'hours' => isset($fromTimes[$index]) && isset($toTimes[$index])
                        ? [$fromTimes[$index] . '-' . $toTimes[$index]]
                        : [],
                    'recurring' => isset($recurring[$index]) && $recurring[$index] == 1,
                ];

                // Handle date format based on recurring
                if ($holidayData['recurring']) {
                    $holidayData['date'] = \Carbon\Carbon::parse($date)->format('m-d');
                } else {
                    $holidayData['date'] = $date;
                }

                // Check if this is an existing holiday (has an ID)
                if (isset($holidayIds[$index])) {
                    $holiday = Holiday::find($holidayIds[$index]);
                    if ($holiday) {
                        $holiday->update($holidayData);
                        $submittedHolidayIds[] = $holiday->id;
                    }
                } else {
                    // Create new holiday
                    $holiday = Holiday::create($holidayData);
                    $submittedHolidayIds[] = $holiday->id;
                }
            }

            // Delete any holidays that weren't submitted in the form
            $holidaysToDelete = array_diff($existingHolidayIds, $submittedHolidayIds);
            if (!empty($holidaysToDelete)) {
                Holiday::whereIn('id', $holidaysToDelete)->delete();
            }
        } else {
            // If no holidays were submitted but there were existing ones, delete them all
            if ($employee->holidays()->exists()) {
                $employee->holidays()->delete();
            }
        }

        return redirect()->back()->with('success', 'Profile has been updated successfully!');
    }


    // Transform the data
    function transformOpeningHours($data)
    {
        $result = [];

        foreach ($data as $day => $times) {
            $dayHours = [];
            for ($i = 0; $i < count($times); $i += 2) {
                if (isset($times[$i + 1])) {
                    $dayHours[] = $times[$i] . '-' . $times[$i + 1];
                }
            }
            $result[$day] = $dayHours;
        }

        return $result;
    }

    protected function transformAvailabilitySlotsForEdit(array $employeeDays)
    {
        foreach ($employeeDays as $day => $slots) {
            $transformedSlots = [];
            foreach ($slots as $slot) {
                list($startTime, $endTime) = explode('-', $slot);
                $transformedSlots[] = $startTime;
                $transformedSlots[] = $endTime;
            }
            $employeeDays[$day] = $transformedSlots;
        }

        return $employeeDays;
    }

}


