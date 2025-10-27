<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use App\Models\User;
use App\Models\Service;
use App\Models\Holiday;
use App\Models\Employee;
use Hash;
use Session;
class UserController extends Controller
{


    public function index(Request $request)
    {
        // Get the role type from the request (either 'employee', 'customer', or 'moderator')
        $users = User::latest()->get();
        return view('backend.user.index', compact('users'));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $days = [
            'monday',
            'tuesday',
            'wednesday',
            'thursday',
            'friday',
            'saturday',
            'sunday',
        ];

        //$roles = Role::where('name', '!=', 'admin')->get();
        $roles = Role::where('name', '!=', 'admin')->get();
        $services = Service::whereStatus(1)->get();
        return view('backend.user.create',compact('roles','services','days'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        //dd($request->all());
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|unique:users',
            'phone' => 'required|string|unique:users,phone',
            'password' => 'required|string|min:8|confirmed',
            'roles' => 'required|exists:roles,name', // Validate role
            'service' => 'nullable',
            'slot_duration' => 'nullable',
            'break_duration' => 'nullable',
            'break' => 'nullable',
            'days' => 'nullable',
            'is_employee' => 'nullable',
        ]);

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'] ?? "", // Optional field
            'email_verified_at' => now(),
            'password' => \Hash::make($data['password']),
        ]);

        // Assign the role to the user
        $user->assignRole($data['roles']);


        // transform time slots into from and to combination
        if($request->is_employee)
        {
            $transformedData = $this->transformOpeningHours($data['days']); // Use $this->transformOpeningHours
            $data['days'] = $transformedData;

            $employee = Employee::create([
                'user_id'           => $user['id'],
                'days'              => $data['days'],
                'slot_duration'     => $data['slot_duration'],
                'break_duration'    => $data['break_duration'],
            ]);

            $employee->services()->attach($data['service']);
        }

        return redirect()->back()->withSuccess('User has been created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {

        // Available days of the week
        $days = [
            'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday',
        ];

        // Available slot duration steps
        $steps = ['10', '15', '20', '30', '45', '60'];

        // Available break duration steps
        $breaks = ['5', '10', '15', '20', '25', '30'];

        // Get the user and the related employee data
        $user = User::with('employee.holidays')->findOrFail($id);

        //dd($user->employee->holidays);

        // Get the employee's availability (days) data if it exists and convert to an array
        $employeeDays = $user->employee->days ?? [];

        // Transform availability slots
        $employeeDays = $this->transformAvailabilitySlotsForEdit($employeeDays);

        //dd($employeeDays);

        // Get all roles excluding 'admin'
        $roles = Role::all();
       // $roles = Role::where('name', '!=', 'admin')->get();

        // Get all active services
        $services = Service::whereStatus(1)->get();

        // Return the view with data
        return view('backend.user.edit', compact('user', 'roles', 'services', 'days', 'steps', 'breaks', 'employeeDays'));
    }

    public function update(Request $request, User $user)
    {

        //dd($user);
        // Validate request data
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'sometimes|email|unique:users,email,' . $user->id ,
            'social.*' => 'sometimes',
            'password' => 'nullable|string|min:8|confirmed',
            'roles' => 'nullable|array|exists:roles,name', // Validate roles array
            'service' => 'nullable',
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
            'status' => 'nullable|numeric',
            'is_employee' => 'nullable',
            'holidays.date.*' => 'sometimes|required',
            'holidays.from_time' => 'nullable',
            'holidays.to_time' => 'nullable',
            'holidays.recurring' => 'nullable',
        ]);

        // Block logged-in user from changing their own role or status
        // Block logged-in user from changing their own role or status
        if (\Auth::id() === $user->id) {
            // Check if roles key exists AND its value is different from current roles
            if ($request->filled('roles') && !$user->hasAnyRole($request->roles)) {
                return redirect()->back()->withErrors(['roles' => 'You cannot change your own role.']);
            }

            if ($request->has('status') && $request->status != $user->status) {
                return redirect()->back()->withErrors(['status' => 'You cannot change your own status.']);
            }

        }


        // Always keep 'admin' role for super admin (user ID 1)
        if ($user->id === 1 && (!in_array('admin', $request->roles ?? []))) {
            return redirect()->back()->withErrors(['roles' => 'The first user must always have the admin role.']);
        }

        // Ensure admin role is not removed from any user who currently has it
        if ($user->hasRole('admin') && !in_array('admin', $request->roles ?? [])) {
            return redirect()->back()->withErrors(['roles' => 'The admin role cannot be removed.']);
        }


        // Ensure that user ID 1's status always remains 1
        if ($user->id === 1) {
            $status = 1;
        } else {
            $status = $request->status ?? 0;
        }

        // Update user details
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone ?? $user->phone,
            'password' => $request->password ? \Hash::make($request->password) : $user->password,
            'status' => $status,
        ]);

        // Sync roles: Always retain admin role for the first user
        if ($request->roles) {
            $roles = $request->roles;

            // Ensure admin role is present
            if ($user->id === 1 || $user->hasRole('admin')) {
                if (!in_array('admin', $roles)) {
                    $roles[] = 'admin';
                }
            }

            // Sync roles
            $user->syncRoles($roles);
        }



        // Check if is_employee is set and true
        if (!empty($data['is_employee'])) {
            // Transform days data if provided
            if (!empty($data['days'])) {
                $data['days'] = $this->transformOpeningHours($data['days']);
            }

            // Update or create Employee record
            $employee = Employee::updateOrCreate(
                ['user_id' => $user->id], // Condition to check
                [
                    'days' => $data['days'] ?? null,
                    'slot_duration' => $data['slot_duration'] ?? null,
                    'break_duration' => $data['break_duration'] ?? null
                ]
            );

            // Attach services if provided
            if (!empty($data['service'])) {
                $employee->services()->sync($data['service']); // Use sync to avoid duplicate entries
            }

            if ($request->has('holidays.date') && is_array($request->input('holidays.date'))) {
                // Get all existing holiday IDs for this employee
                $existingHolidayIds = $user->employee->holidays->pluck('id')->toArray();
                $submittedHolidayIds = [];

                $dates = $request->input('holidays.date');
                $fromTimes = $request->input('holidays.from_time');
                $toTimes = $request->input('holidays.to_time');
                $recurring = $request->input('holidays.recurring');
                $holidayIds = $request->input('holidays.id', []); // Add hidden input for holiday IDs in your form

                foreach ($dates as $index => $date) {
                    $holidayData = [
                        'employee_id' => $user->employee->id,
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
                if ($user->employee->holidays()->exists()) {
                    $user->employee->holidays()->delete();
                }
            }

        }


        return redirect()->route('user.index')->with('success', 'Profile has been updated successfully!');
    }



    // Custom method to log out a specific user
    protected function logoutUser(User $user)
    {
          // Check if the application is using the database session driver
        if (config('session.driver') === 'database') {
            // Delete all sessions for this user by matching the user ID
            DB::table('sessions')->where('user_id', $user->id)->delete();
        }
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user, Request $request)
    {
        if($user->id == 1)
        {
            return back()->withErrors('First admin user cannot be deleted.');
        }

        if ($user->id === $request->user()->id) {
            return back()->withErrors('You cannot delete yourself.');
        }

        $user->delete();
        return redirect()->back()->with('success', 'User has been successfully trashed!');
    }


    public function trashView(Request $request)
    {
        $users = User::onlyTrashed()->latest()->get();
        return view('backend.user.trash',compact('users'));
    }

    // restore data
    public function restore($id)
    {
        $user = User::withTrashed()->find($id);
        if(!is_null($user)){
            $user->restore();
        }
        return redirect()->back()->with("success", "User Restored Succesfully");
    }


    public function force_delete($id)
    {
        // Retrieve the trashed user with its associated employee, holidays, appointments, and bookings
        $user = User::withTrashed()->findOrFail($id);

        //for employee
        if($user->employee->appointments->count())
        {
            return back()->withErrors('User cannot be deleted permanently, already engaged in existing bookings!');
        }

        //for user
        if($user->appointments->count())
        {
            return back()->withErrors('User cannot be deleted permanently, already engaged in existing bookings!');
        }

        // Check if the user has an associated employee
        if ($user->employee) {
            // Delete all holidays related to the employee
            foreach ($user->employee->holidays as $holiday) {
                $holiday->forceDelete(); // Force delete each holiday
            }


            // Delete all appointments related to the employee
            // foreach ($user->employee->appointments as $appointment) {
            //     $appointment->forceDelete(); // Force delete each appointment
            // }

                 // Detach all services related to the employee (many-to-many relationship)
            if ($user->employee->services()->exists()) {
                $user->employee->services()->detach(); // Detach the services from the employee
            }

            // Finally, delete the employee data
            $user->employee->forceDelete();
        }

        // Delete the user's profile image if exists
        if ($user->image) {
            $destination = public_path('uploads/images/profile/' . $user->image);
            if (\File::exists($destination)) {
                \File::delete($destination);
            }
        }

        // Permanently delete the user from the database
        $user->forceDelete();

        return back()->withSuccess('User and all related data (employee, holidays, appointments, bookings) have been deleted permanently!');
    }



    public function password_update(Request $request, User $user)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|string|min:8|confirmed',
            'password_confirmation' => 'required',
        ]);

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Current password does not match!']);
        }

        $user->password = Hash::make($request->password);
        $user->save();

        return redirect()->back()->with('success', 'Password has been successfully Updated!');
    }

    public function updateProfileImage(Request $request, User $user)
    {
        $request->validate([
            'image' => 'required|image|mimes:png,jpg,jpeg,webp|max:2048',
            'delete_image' => 'nullable'
        ]);

        //remove old image
        $destination = public_path('uploads/images/profile/'. $user->image);
        if(\File::exists($destination))
        {
            \File::delete($destination);
        }

        $imageName = time().'.'.$request->image->getClientOriginalExtension();
        $request->image->move(public_path('uploads/images/profile/'),$imageName);
        $user->update([
            'image' => $imageName
        ]);

        return back()->withSuccess('Profile image has been updated successfully!');

    }


    //delete profile image
    public function deleteProfileImage(User $user)
    {
        $destination = public_path('uploads/images/profile/'.$user->image);
        if(\File::exists($destination))
        {
            \File::delete($destination);
        }

        $user->update([
            'image' => null
        ]);
        return back()->withSuccess('Profile image deleted!');
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
