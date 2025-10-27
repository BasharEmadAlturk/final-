@extends('adminlte::page')
@section('title', 'User Profile')
@section('content_header')
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">User Profile</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item active">Add Post</li>
                </ol>
            </div>
        </div>
    </div>
@stop
@section('content')

    @if ($user->appointments->count())
        <!-- Modal -->
        <div class="modal fade" id="CustomerBookings" tabindex="-1" role="dialog" aria-labelledby="CustomerBookingsLabel"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="CustomerBookingsLabel">Appointment Details</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p><strong>User:</strong> <span id="modalUserName"></span></p>
                        <p><strong>Service:</strong> <span id="modalService"></span></p>
                        <p><strong>Staff:</strong> <span id="modalStaff"></span></p>
                        <p><strong>Amount:</strong> <span id="modalAmount"></span></p>
                        <p><strong>Date:</strong> <span id="modalDate"></span></p>
                        <p><strong>Time:</strong> <span id="modalTime"></span></p>
                        <p><strong>Notes:</strong> <span id="modalNotes"></span></p>
                        <p><strong>Status:</strong> <span id="modalStatusBadge"></span></p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if ($user->employee && $user->employee->appointments)
        <!-- Appointment Modal -->
        <form id="appointmentStatusForm" method="POST" action="{{ route('appointments.update.status') }}">
            @csrf
            <input type="hidden" name="appointment_id" id="modalAppointmentId">

            <div class="modal fade" id="appointmentModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Appointment Details</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>

                        <div class="modal-body">
                            <p><strong>Client:</strong> <span id="modalAppointmentName">N/A</span></p>
                            <p><strong>Service:</strong> <span id="Service">N/A</span></p>
                            <p><strong>Email:</strong> <span id="modalEmail">N/A</span></p>
                            <p><strong>Phone:</strong> <span id="modalPhone">N/A</span></p>
                            <p><strong>Staff:</strong> <span id="Staff">N/A</span></p>
                            <p><strong>Start:</strong> <span id="modalStartTime">N/A</span></p>
                            <p><strong>Amount:</strong> <span id="Amount">N/A</span></p>
                            <p><strong>Notes:</strong> <span id="Notes">N/A</span></p>
                            <p><strong>Current Status:</strong> <span id="modalStatusBadgeforEmployee"></span></p>


                            <div class="form-group ">
                                <label><strong>Status:</strong></label>
                                <select name="status" class="form-control" id="modalStatusSelect">
                                    <option value="Pending payment">Pending payment</option>
                                    <option value="Processing">Processing</option>
                                    <option value="Confirmed">Confirmed</option>
                                    <option value="Cancelled">Cancelled</option>
                                    <option value="Completed">Completed</option>
                                    <option value="On Hold">On Hold</option>
                                    {{-- <option value="Rescheduled">Rescheduled</option> --}}
                                    <option value="No Show">No Show</option>
                                </select>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="submit"
                                onclick="return confirm('Are you sure you want to update booking status?')"
                                class="btn btn-danger">Update Status</button>
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        </div>

                    </div>
                </div>
            </div>
        </form>
    @endif

    {{-- modal to chagne profile pic --}}
    <div class="modal fade" id="profileImageModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form action="{{ route('user.profile.image.update', $user->id) }}" method="post" enctype="multipart/form-data">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Update Profile Pic</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">

                        @csrf
                        @method('PUT')
                        <input type="file" name="image" class="form-control" id="">
                        @error('image')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save changes</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
            @if (count($errors) > 0)
                <div class="alert alert-dismissable alert-danger mt-3">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <strong>Whoops!</strong> There were some problems with your input.<br>
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            @if (session('success'))
                <div class="alert alert-success alert-dismissable">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <strong>{{ session('success') }}</strong>
                </div>
            @endif
            <div class="row">
                <div class="col-md-3">

                    <div class="card card-primary card-outline">
                        <div class="card-body box-profile">
                            <div class="text-center">
                                <img class="profile-user-img img-fluid img-circle" src="{{ $user->profileImage() }}"
                                    alt="User profile picture">
                            </div>
                            <div class="text-center">
                                <a data-toggle="modal" data-target="#profileImageModal" href="">Change image</a>
                                @if ($user->image)
                                    <form action="{{ route('delete.profile.image', $user->id) }}" method="post">
                                        @csrf
                                        @method('PATCH')
                                        <button onclick="return confirm('Are you sure you want to remove profile image?')"
                                            type="submit" class="btn btn-sm btn-danger py-0 fw-bold">Remove
                                            Image</button>
                                    </form>
                                @endif
                            </div>
                            <h3 class="profile-username text-center">{{ $user->name }}</h3>
                            <p class="text-muted text-center">{{ $user->email }}</p>
                            <ul class="list-group list-group-unbordered mb-3">

                                <li class="list-group-item">
                                    <b>Last Logged In</b> <a
                                        class="float-right">{{ $user->lastSuccessfulLoginAt() ? $user->lastSuccessfulLoginAt()->diffForHumans() : 'NA' }}</a>
                                </li>

                                <li class="list-group-item">
                                    <b>Account Created</b> <a
                                        class="float-right">{{ $user->created_at->diffForHumans() }}</a>
                                </li>
                                <li class="list-group-item">
                                    <b>Role: </b> <a class="float-right">{{ ucwords($user->getRoleNames()->first()) }}
                                    </a>
                                </li>

                            </ul>
                            {{-- @if (!$user->is_requesting_author)
                                <a href="#" class="btn btn-primary btn-block"><b>Become Author</b></a>
                            @endif --}}

                        </div>
                    </div>
                </div>

                <div class="col-md-9">
                    <div class="card">
                        <div class="card-header p-2">
                            <ul class="nav nav-pills">

                                @if ($user->comments)
                                    <li class="nav-item"><a class="nav-link" href="#timeline"
                                            data-toggle="tab">Comments</a>
                                    </li>
                                @endif
                                <li class="nav-item "><a class="nav-link active" href="#settings"
                                        data-toggle="tab">Profile</a>
                                </li>

                                <li class="nav-item "><a class="nav-link" href="#logs" data-toggle="tab">Logs</a>
                                </li>

                                @if ($user->employee)
                                    <li class="nav-item"><a class="nav-link" href="#bio" data-toggle="tab">Bio</a>
                                    </li>
                                @endif

                                @if ($user->employee)
                                    <li class="nav-item"><a class="nav-link" href="#availibility"
                                            data-toggle="tab">Avalibility</a>
                                    </li>
                                @endif
                                @if ($user->employee && $user->employee->appointments)
                                    <li class="nav-item"><a class="nav-link" href="#appointments"
                                            data-toggle="tab">Appointments</a>
                                    </li>
                                @endif

                                @if ($user->appointments->count())
                                    <li class="nav-item"><a class="nav-link" href="#bookings" data-toggle="tab">My
                                            Bookings</a>
                                    </li>
                                @endif



                                <li class="nav-item"><a class="nav-link" href="#password" data-toggle="tab">Change
                                        Password</a>
                                </li>

                            </ul>
                        </div>
                        <div class="card-body">
                            <div class="tab-content">


                                <div class="tab-pane" id="timeline">

                                    <div class="timeline timeline-inverse">

                                        <div class="time-label">
                                            <span class="bg-danger">
                                                10 Feb. 2014
                                            </span>
                                        </div>


                                        <div>
                                            <i class="fas fa-envelope bg-primary"></i>
                                            <div class="timeline-item">
                                                <span class="time"><i class="far fa-clock"></i> 12:05</span>
                                                <h3 class="timeline-header"><a href="#">Support Team</a> sent you an
                                                    email</h3>
                                                <div class="timeline-body">
                                                    Etsy doostang zoodles disqus groupon greplin oooj voxy zoodles,
                                                    weebly ning heekya handango imeem plugg dopplr jibjab, movity
                                                    jajah plickers sifteo edmodo ifttt zimbra. Babblely odeo kaboodle
                                                    quora plaxo ideeli hulu weebly balihoo...
                                                </div>
                                                <div class="timeline-footer">
                                                    <a href="#" class="btn btn-primary btn-sm">Read more</a>
                                                    <a href="#" class="btn btn-danger btn-sm">Delete</a>
                                                </div>
                                            </div>
                                        </div>


                                        <div>
                                            <i class="fas fa-user bg-info"></i>
                                            <div class="timeline-item">
                                                <span class="time"><i class="far fa-clock"></i> 5 mins ago</span>
                                                <h3 class="timeline-header border-0"><a href="#">Sarah Young</a>
                                                    accepted your friend request
                                                </h3>
                                            </div>
                                        </div>


                                        <div>
                                            <i class="fas fa-comments bg-warning"></i>
                                            <div class="timeline-item">
                                                <span class="time"><i class="far fa-clock"></i> 27 mins ago</span>
                                                <h3 class="timeline-header"><a href="#">Jay White</a> commented on
                                                    your post</h3>
                                                <div class="timeline-body">
                                                    Take me to your leader!
                                                    Switzerland is small and neutral!
                                                    We are more like Germany, ambitious and misunderstood!
                                                </div>
                                                <div class="timeline-footer">
                                                    <a href="#" class="btn btn-warning btn-flat btn-sm">View
                                                        comment</a>
                                                </div>
                                            </div>
                                        </div>


                                        <div class="time-label">
                                            <span class="bg-success">
                                                3 Jan. 2014
                                            </span>
                                        </div>


                                        <div>
                                            <i class="fas fa-camera bg-purple"></i>
                                            <div class="timeline-item">
                                                <span class="time"><i class="far fa-clock"></i> 2 days ago</span>
                                                <h3 class="timeline-header"><a href="#">Mina Lee</a> uploaded new
                                                    photos</h3>
                                                <div class="timeline-body">
                                                    <img src="https://placehold.it/150x100" alt="...">
                                                    <img src="https://placehold.it/150x100" alt="...">
                                                    <img src="https://placehold.it/150x100" alt="...">
                                                    <img src="https://placehold.it/150x100" alt="...">
                                                </div>
                                            </div>
                                        </div>

                                        <div>
                                            <i class="far fa-clock bg-gray"></i>
                                        </div>
                                    </div>
                                </div>

                                <div class="tab-pane" id="logs">

                                    <div class="row">
                                        <div class="col-12">
                                            <div class="card">


                                                <div class="card-body table-responsive">
                                                    <table class="table table-hover text-nowrap">
                                                        <thead>
                                                            <tr>
                                                                <th>Login</th>
                                                                <th>Logout</th>
                                                                {{-- <th>City</th>
                                                                <th>Country</th> --}}
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach ($user->authentications as $auth)
                                                                <tr>

                                                                    <td>{{ $auth->login_at ? $auth->login_at->format('H:i | d M Y') : 'NA' }}
                                                                    </td>

                                                                    <td>{{ $auth->logout_at ? $auth->logout_at->format('H:i | d M Y') : 'NA' }}
                                                                    </td>
                                                                    {{--
                                                                    <td>{{ $auth->location['city'] ?? 'NA' }}</td>
                                                                    <td>{{ $auth->location['country'] ?? 'NA' }}</td> --}}

                                                                </tr>
                                                            @endforeach

                                                        </tbody>
                                                    </table>
                                                </div>

                                            </div>

                                        </div>
                                    </div>
                                </div>

                                <div class="tab-pane show active" id="settings">

                                    <form action="{{ route('user.profile.update', $user->id) }}" method="post"
                                        class="form-horizontal">
                                        @csrf
                                        @method('PATCH')
                                        <div class="form-group row">
                                            <label for="inputName" class="col-sm-2 col-form-label">Name</label>
                                            <div class="col-sm-10">
                                                <input type="text" name="name" class="form-control" id="inputName"
                                                    placeholder="Name" value="{{ $user->name }}">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="inputEmail" class="col-sm-2 col-form-label">Email</label>
                                            <div class="col-sm-10">
                                                <input style="background-color: rgb(221, 221, 221);" type="email"
                                                    name="email"
                                                    class="form-control @error('email') is-invalid @enderror"
                                                    id="inputEmail" placeholder="Email" value="{{ $user->email }}">
                                                @error('email')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <div class="offset-sm-2 col-sm-10">
                                                <button onclick="return confirm('Are you sure you want ')" type="submit"
                                                    class="btn btn-danger">Submit</button>
                                            </div>
                                        </div>
                                    </form>

                                </div>

                                @if ($user->employee)
                                    <div class="tab-pane" id="bio">

                                        <form action="{{ route('employee.bio.update', $user->employee->id) }}"
                                            method="post" class="form-horizontal">
                                            @csrf
                                            @method('PUT')

                                            <div class="form-group row">
                                                <label for="inputExperience" class="col-sm-2 col-form-label">Bio</label>
                                                <div class="col-sm-10">
                                                    <textarea class="form-control" id="inputExperience" placeholder="Your profile details...." rows="10"
                                                        name="bio" value="{{ $user->employee->bio }}">{{ $user->employee->bio }}</textarea>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label for="inputSkills" class="col-sm-2 col-form-label">Facebook</label>
                                                <div class="col-sm-10">
                                                    <input type="text" class="form-control" id="inputSkills"
                                                        name="social[facebook]"
                                                        placeholder="www.facebook.com/your-profile"
                                                        value="{{ $user->employee->social['facebook'] ?? '' }}">
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label for="inputSkills" class="col-sm-2 col-form-label">Instagram</label>
                                                <div class="col-sm-10">
                                                    <input type="text" class="form-control" id="inputSkills"
                                                        name="social[instagram]"
                                                        placeholder="www.instagram.com/your-profile"
                                                        value="{{ $user->employee->social['instagram'] ?? '' }}">
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label for="inputSkills" class="col-sm-2 col-form-label">Twitter</label>
                                                <div class="col-sm-10">
                                                    <input type="text" class="form-control" id="inputSkills"
                                                        name="social[twitter]" placeholder="www.x.com/your-profile"
                                                        value="{{ $user->employee->social['twitter'] ?? '' }}">
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label for="inputSkills" class="col-sm-2 col-form-label">Linkedin</label>
                                                <div class="col-sm-10">
                                                    <input type="text" class="form-control" id="inputSkills"
                                                        name="social[linkedin]"
                                                        placeholder="www.linkeding.com/your-profile"
                                                        value="{{ $user->employee->social['linkedin'] ?? '' }}">
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <div class="offset-sm-2 col-sm-10">
                                                    <button onclick="return confirm('Are you sure you want ')"
                                                        type="submit" class="btn btn-danger">Submit</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                @endif

                                @if ($user->employee)
                                    <div class="tab-pane" id="availibility">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <hr>
                                                <div class="mb-3">
                                                    <h4 class="mb-0">Only For Employees </h4>
                                                    <small class="text-muted">Fill these details if adding an employee
                                                        only</small>
                                                </div>

                                                <form action="{{ route('employee.profile.update', $user->employee->id) }}"
                                                    method="post">
                                                    @csrf
                                                    @method('PATCH')

                                                    <div class="row">


                                                        <div class="col-xs-12 col-sm-12 col-md-12 mb-3">
                                                            <label for="slot_duration" class="my-0">
                                                                <i class="fas fa-stopwatch"></i> Service Duration
                                                            </label>
                                                            <small class="text-muted"> Create booking slots based on your
                                                                preferred time
                                                                duration.</small>

                                                            <select
                                                                class="form-control @error('slot_duration') is-invalid @enderror"
                                                                name="slot_duration" id="slot_duration">
                                                                <option value=""
                                                                    {{ old('slot_duration', optional($user->employee)->slot_duration) == '' ? 'selected' : '' }}>
                                                                    Select Duration
                                                                </option>

                                                                @foreach ($steps as $stepValue)
                                                                    <option value="{{ $stepValue }}"
                                                                        {{ old('slot_duration', optional($user->employee)->slot_duration) == $stepValue ? 'selected' : '' }}>
                                                                        {{ $stepValue }} minutes
                                                                    </option>
                                                                @endforeach
                                                            </select>


                                                            @error('slot_duration')
                                                                <small
                                                                    class="text-danger"><strong>{{ $message }}</strong></small>
                                                            @enderror
                                                        </div>


                                                        <div class="col-xs-12 col-sm-12 col-md-12 mb-3">
                                                            <label for="break_duration" class="my-0">
                                                                <i class="fas fa-coffee"></i> Preparation or Break time
                                                            </label>
                                                            <small class="text-muted"> Break between one to another
                                                                appointment</small>

                                                            <select
                                                                class="form-control @error('break_duration') is-invalid @enderror"
                                                                name="break_duration" id="break_duration">
                                                                <option value=""
                                                                    {{ old('break_duration', optional($user->employee)->break_duration) == '' ? 'selected' : '' }}>
                                                                    No Break
                                                                </option>

                                                                @foreach ($breaks as $breakValue)
                                                                    <option value="{{ $breakValue }}"
                                                                        {{ old('break_duration', optional($user->employee)->break_duration) == $breakValue ? 'selected' : '' }}>
                                                                        {{ $breakValue }}
                                                                    </option>
                                                                @endforeach
                                                            </select>

                                                            @error('break_duration')
                                                                <small
                                                                    class="text-danger"><strong>{{ $message }}</strong></small>
                                                            @enderror
                                                        </div>



                                                    </div>

                                                    <hr>

                                                    <div class="row">
                                                        <div class="mb-3">
                                                            <h4 class="mb-0">Set Availability - For Employee</h4>
                                                            <small class="text-muted">
                                                                Select days and timings, with the option to add multiple
                                                                time slots in a
                                                                day, e.g., 9 AM–12 PM and 4 PM–8 PM.
                                                            </small>
                                                        </div>

                                                        <div class="col-md-12">
                                                            @foreach ($days as $day)
                                                                <div class="row">
                                                                    <div class="col-md-2">
                                                                        <div class="form-group">
                                                                            <div class="custom-control custom-switch">
                                                                                <input type="checkbox"
                                                                                    class="custom-control-input"
                                                                                    id="{{ $day }}"
                                                                                    @if (old('days.' . $day) || isset($employeeDays[$day])) checked @endif>
                                                                                <label class="custom-control-label"
                                                                                    for="{{ $day }}">
                                                                                    {{ ucfirst($day) }}
                                                                                </label>
                                                                            </div>
                                                                        </div>
                                                                    </div>

                                                                    <!-- First Time Input Row -->
                                                                    <div class="col-md-4">
                                                                        <div class="form-group">
                                                                            <strong>From:</strong>
                                                                            <input type="time"
                                                                                class="form-control from"
                                                                                name="days[{{ $day }}][]"
                                                                                id="{{ $day }}From"
                                                                                value="{{ old('days.' . $day . '.0') ?? ($employeeDays[$day][0] ?? '') }}" />
                                                                        </div>
                                                                    </div>

                                                                    <div class="col-md-4">
                                                                        <div class="form-group">
                                                                            <strong>To:</strong>
                                                                            <input type="time" class="form-control to"
                                                                                name="days[{{ $day }}][]"
                                                                                id="{{ $day }}To"
                                                                                value="{{ old('days.' . $day . '.1') ?? ($employeeDays[$day][1] ?? '') }}" />
                                                                            <div style=""
                                                                                id="{{ $day }}AddMore"
                                                                                class="text-right d-none text-primary">
                                                                                Add More
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <!-- Render Additional Rows -->
                                                                @if (old('days.' . $day) || isset($employeeDays[$day]))
                                                                    @foreach (old('days.' . $day) ?: $employeeDays[$day] as $index => $time)
                                                                        @if ($index > 1 && $index % 2 == 0)
                                                                            <div
                                                                                class="row additional-{{ $day }}">
                                                                                <div class="col-md-2"></div>

                                                                                <div class="col-md-4">
                                                                                    <div class="form-group">
                                                                                        <strong>From:</strong>
                                                                                        <input type="time"
                                                                                            class="form-control from"
                                                                                            name="days[{{ $day }}][]"
                                                                                            value="{{ $time }}"
                                                                                            id="{{ $day }}MoreFrom" />
                                                                                    </div>
                                                                                </div>

                                                                                <div class="col-md-4">
                                                                                    <div class="form-group">
                                                                                        <strong>To</strong>
                                                                                        <input type="time"
                                                                                            class="form-control to"
                                                                                            name="days[{{ $day }}][]"
                                                                                            value="{{ old('days.' . $day . '.' . ($index + 1)) ?? ($employeeDays[$day][$index + 1] ?? '') }}"
                                                                                            id="{{ $day }}" />
                                                                                        <div
                                                                                            class="remove-field text-danger text-right">
                                                                                            Remove</div>
                                                                                    </div>
                                                                                </div>


                                                                            </div>
                                                                        @endif
                                                                    @endforeach
                                                                @endif
                                                            @endforeach
                                                        </div>
                                                    </div>


                                                    <hr>

                                                    <div class="row d-flex">
                                                        <div class="col-md-10">
                                                            <h2 class="mb-0">Add Holidays</h2>
                                                            <p class="text-muted">
                                                                No need to add time for a full day; for part-time work,
                                                                specify the day and time.
                                                            </p>
                                                            <span id="addHoliday" class="btn btn-primary mb-2 btn-sm">
                                                                <i class="fa fa-plus"></i> Add Holiday
                                                            </span>
                                                            <div class="holidayContainer">
                                                                @php
                                                                    // Get holidays from old input or database
                                                                    $holidaysInput = old('holidays.date', []);
                                                                    $dbHolidays = $user->employee->holidays ?? [];
                                                                    $holidaysToDisplay = !empty($holidaysInput)
                                                                        ? $holidaysInput
                                                                        : $dbHolidays;
                                                                @endphp

                                                                @forelse($holidaysToDisplay as $index => $holidayItem)
                                                                    @php
                                                                        // Determine if we're using old input or database data
$usingOldInput = !empty($holidaysInput);

if ($usingOldInput) {
    $date = old("holidays.date.$index");
    $holiday = null;
} else {
    $holiday = $holidayItem;
    $date = $holiday->date;
    // Format date for input field if it's not already in YYYY-MM-DD format
                                                                            if (
                                                                                !preg_match(
                                                                                    '/^\d{4}-\d{2}-\d{2}$/',
                                                                                    $date,
                                                                                )
                                                                            ) {
                                                                                try {
                                                                                    $date = \Carbon\Carbon::parse(
                                                                                        $date,
                                                                                    )->format('Y-m-d');
                                                                                } catch (Exception $e) {
                                                                                    $date = '';
                                                                                }
                                                                            }
                                                                        }

                                                                        $fromTime = old(
                                                                            "holidays.from_time.$index",
                                                                            $holiday && $holiday->hours
                                                                                ? explode('-', $holiday->hours[0])[0] ??
                                                                                    ''
                                                                                : '',
                                                                        );
                                                                        $toTime = old(
                                                                            "holidays.to_time.$index",
                                                                            $holiday && $holiday->hours
                                                                                ? explode('-', $holiday->hours[0])[1] ??
                                                                                    ''
                                                                                : '',
                                                                        );
                                                                        $recurring = old(
                                                                            "holidays.recurring.$index",
                                                                            $holiday->recurring ?? 0,
                                                                        );
                                                                    @endphp
                                                                    <div class="row holiday-row">
                                                                        <div class="col-md-4">
                                                                            <div class="form-group">
                                                                                <label class="mb-0">Date</label>
                                                                                <input class="form-control" type="date"
                                                                                    name="holidays[date][]"
                                                                                    value="{{ $date }}" required>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-3">
                                                                            <div class="form-group">
                                                                                <strong>From:</strong>
                                                                                <input type="time"
                                                                                    class="form-control from"
                                                                                    name="holidays[from_time][]"
                                                                                    value="{{ $fromTime }}">
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-3">
                                                                            <div class="form-group">
                                                                                <strong>To:</strong>
                                                                                <input type="time"
                                                                                    class="form-control to"
                                                                                    name="holidays[to_time][]"
                                                                                    value="{{ $toTime }}">
                                                                                <div class="text-right text-danger removeHoliday"
                                                                                    style="cursor:pointer;">
                                                                                    Remove
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <input type="hidden" name="holidays[recurring][]"
                                                                            value="{{ $recurring }}">
                                                                    </div>
                                                                @empty
                                                                    <p>No holidays found for this user. Click "Add Holiday"
                                                                        to create one.</p>
                                                                @endforelse
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-xs-12 col-sm-12 col-md-12 pt-2 pl-md-3">
                                                        <button type="submit" class="btn btn-danger"
                                                            onclick="return confirm('Are you sure you want to update this user?')">Update
                                                            Avalibility</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                @if ($user->employee && $user->employee->appointments)
                                    <div class="tab-pane" id="appointments">

                                        <div class="row">
                                            <div class="col-12">
                                                <div class="card">


                                                    <div class="card-body table-responsive ">
                                                        <table class="table table-hover text-nowrap myTable">
                                                            <thead>
                                                                <tr>
                                                                    <th>#</th>
                                                                    <th>User</th>
                                                                    <th>Service</th>
                                                                    <th>Staff</th>
                                                                    <th>Date</th>
                                                                    <th>Time</th>
                                                                    <th>Status</th>
                                                                    <th>Action</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                @foreach ($user->employee->appointments->sortByDesc('created_at') as $appointment)
                                                                    <tr>

                                                                        <td>{{ $loop->iteration }} </td>
                                                                        <td>{{ $appointment->name }} </td>
                                                                        <td>{{ $appointment->service->title }} </td>
                                                                        <td>{{ $appointment->employee->user->name }}</td>
                                                                        <td>{{ $appointment->booking_date }}</td>
                                                                        <td>{{ $appointment->booking_time }}</td>
                                                                        <td>
                                                                            @php
                                                                                $statusColors = [
                                                                                    'Pending payment' => '#f39c12',
                                                                                    'Processing' => '#3498db',
                                                                                    'Confirmed' => '#2ecc71',
                                                                                    'Cancelled' => '#ff0000',
                                                                                    'Completed' => '#008000',
                                                                                    'On Hold' => '#95a5a6',
                                                                                    'Rescheduled' => '#f1c40f',
                                                                                    'No Show' => '#e67e22',
                                                                                ];
                                                                            @endphp
                                                                            @php
                                                                                $status = $appointment->status;
                                                                                $color =
                                                                                    $statusColors[$status] ?? '#7f8c8d';
                                                                            @endphp
                                                                            <span class="badge px-2 py-1"
                                                                                style="background-color: {{ $color }}; color: white;">
                                                                                {{ $status }}
                                                                            </span>
                                                                        </td>

                                                                        <td>
                                                                            <button
                                                                                class="btn btn-primary btn-sm py-0 px-1 view-appointment-btn-employee"
                                                                                data-toggle="modal"
                                                                                data-target="#appointmentModal"
                                                                                data-id="{{ $appointment->id }}"
                                                                                data-name="{{ $appointment->name }}"
                                                                                data-service="{{ $appointment->service->title }}"
                                                                                data-email="{{ $appointment->email }}"
                                                                                data-phone="{{ $appointment->phone }}"
                                                                                data-employee="{{ $appointment->employee->user->name }}"
                                                                                data-start="{{ $appointment->booking_date . ' ' . $appointment->booking_time }}"
                                                                                data-amount="{{ $appointment->amount }}"
                                                                                data-notes="{{ $appointment->notes }}"
                                                                                data-status="{{ $appointment->status }}">View</button>
                                                                        </td>


                                                                    </tr>
                                                                @endforeach

                                                            </tbody>
                                                        </table>
                                                    </div>

                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                @endif

                                @if ($user->appointments->count())

                                    <div class="tab-pane" id="bookings">
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="card">


                                                    <div class="card-body table-responsive ">
                                                        <table class="table table-hover text-nowrap myTable">
                                                            <thead>
                                                                <tr>
                                                                    <th>#</th>
                                                                    <th>User</th>
                                                                    <th>Service</th>
                                                                    <th>Staff</th>
                                                                    <th>Date</th>
                                                                    <th>Time</th>
                                                                    <th>Status</th>
                                                                    <th>Action</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                @foreach ($user->appointments->sortByDesc('created_at') as $appointment)
                                                                    <tr>

                                                                        <td>{{ $loop->iteration }} </td>
                                                                        <td>{{ $appointment->name }} </td>
                                                                        <td>{{ $appointment->service->title }} </td>
                                                                        <td>{{ $appointment->employee->user->name }}</td>
                                                                        <td>{{ $appointment->booking_date }}</td>
                                                                        <td>{{ $appointment->booking_time }}</td>
                                                                        <td>
                                                                            @php
                                                                                $statusColors = [
                                                                                    'Pending payment' => '#f39c12',
                                                                                    'Processing' => '#3498db',
                                                                                    'Confirmed' => '#2ecc71',
                                                                                    'Cancelled' => '#ff0000',
                                                                                    'Completed' => '#008000',
                                                                                    'On Hold' => '#95a5a6',
                                                                                    'Rescheduled' => '#f1c40f',
                                                                                    'No Show' => '#e67e22',
                                                                                ];
                                                                            @endphp
                                                                            @php
                                                                                $status = $appointment->status;
                                                                                $color =
                                                                                    $statusColors[$status] ?? '#7f8c8d';
                                                                            @endphp
                                                                            <span class="badge px-2 py-1"
                                                                                style="background-color: {{ $color }}; color: white;">
                                                                                {{ $status }}
                                                                            </span>
                                                                        </td>
                                                                        <td>
                                                                            <button
                                                                                class="btn btn-primary btn-sm py-0 px-1 view-booking-btn"
                                                                                data-toggle="modal"
                                                                                data-target="#CustomerBookings"
                                                                                data-name="{{ $appointment->name }}"
                                                                                data-service="{{ $appointment->service->title }}"
                                                                                data-staff="{{ $appointment->employee->user->name }}"
                                                                                data-date="{{ $appointment->booking_date }}"
                                                                                data-time="{{ $appointment->booking_time }}"
                                                                                data-amount="{{ $appointment->amount }}"
                                                                                data-status="{{ $appointment->status }}"
                                                                                data-notes="{{ $appointment->notes }}">
                                                                                View
                                                                            </button>

                                                                        </td>

                                                                    </tr>
                                                                @endforeach

                                                            </tbody>
                                                        </table>
                                                    </div>

                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                @endif

                                <div class="tab-pane" id="password">

                                    <form action="{{ route('user.password.update', $user->id) }}" method="post">
                                        @csrf
                                        @method('PATCH')
                                        <div class="card-body">
                                            <div class="tab-content">
                                                <div class="active tab-pane" id="#password">
                                                    <!-- Password -->
                                                    <div class="tab-pane" id="password">
                                                        <div class="form-group row">
                                                            <label for="inputName" class="col-sm-2 col-form-label">Old
                                                                Password</label>
                                                            <div class="col-sm-10">
                                                                <input type="password" class="form-control"
                                                                    id="inputName" placeholder="Old Password"
                                                                    name="current_password"
                                                                    autocomplete="current_password">
                                                            </div>
                                                        </div>
                                                        <div class="form-group row">
                                                            <label for="inputEmail" class="col-sm-2 col-form-label">New
                                                                Password</label>
                                                            <div class="col-sm-10">
                                                                <input type="password" class="form-control"
                                                                    id="inputEmail" placeholder="New Password"
                                                                    name="password" autocomplete="password_confirmation">
                                                            </div>
                                                        </div>
                                                        <div class="form-group row">
                                                            <label for="inputName2"
                                                                class="col-sm-2 col-form-label">Confirm
                                                                Password</label>
                                                            <div class="col-sm-10">
                                                                <input type="password" class="form-control"
                                                                    id="inputName2" placeholder="Confirm Password"
                                                                    name="password_confirmation"
                                                                    autocomplete="password_confirmation">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!-- /.tab-pane -->
                                                </div>
                                                <!-- /.tab-content -->
                                                <div class="form-group row">
                                                    <div class="offset-sm-2 col-sm-10">
                                                        <button
                                                            onclick="return confirm('Are you sure you want to update profile?');"
                                                            type="submit" class="btn btn-danger">Update</button>
                                                    </div>
                                                </div>
                                    </form>
                                </div>

                            </div>

                        </div>
                    </div>

                </div>

            </div>

        </div>
    </section>
@stop

@section('js')
    <script>
        $(document).ready(function() {
            @if ($errors->has('image'))
                $('#profileImageModal').modal('show');
            @endif
        });
    </script>

    <script>
        $(document).ready(function() {
            $(".alert").delay(6000).slideUp(300);
        });
    </script>

    <script>
        $(document).ready(function() {
            function toggleDayFields(dayId) {
                var isChecked = $('#' + dayId).prop('checked');
                $('#' + dayId + 'From, #' + dayId + 'To').prop('disabled', !isChecked);

                // Show or hide the "Add More" button based on the checkbox state
                if (isChecked) {
                    $('#' + dayId + 'AddMore').removeClass('d-none');
                } else {
                    $('#' + dayId + 'AddMore').addClass('d-none');
                    // Remove all additional fields for the day if unchecked
                    $('.additional-' + dayId).remove();
                }
            }

            function addMoreFields(dayId) {
                // Clone the original row for the specific day
                var originalRow = $('#' + dayId + 'AddMore').closest('.row');
                var clonedRow = originalRow.clone();

                // Reset the values in the cloned row (but don't enable the fields yet)
                clonedRow.find('input').each(function() {
                    $(this).val(''); // Clear the value
                });

                // Replace the col-md-2 section with a blank div for the cloned row
                clonedRow.find('.col-md-2').replaceWith('<div class="col-md-2"></div>');

                // Update "Add More" to "Remove" for the cloned row
                clonedRow.find(`#${dayId}AddMore`).text('Remove').attr('id', '').addClass(
                    'remove-field text-danger');

                // Add a unique class to the cloned row for targeting specific day rows
                clonedRow.addClass('additional-' + dayId);

                // Append the cloned row after the original row or the last cloned row
                if (originalRow.closest('.row').siblings('.additional-' + dayId).length === 0) {
                    originalRow.after(clonedRow);
                } else {
                    originalRow.closest('.row').siblings('.additional-' + dayId).last().after(clonedRow);
                }
            }

            // Remove cloned rows
            $(document).on('click', '.remove-field', function() {
                $(this).closest('.row').remove();
            });

            // Bind change and add-more events to all days
            ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'].forEach(function(day) {
                $('#' + day).on('change', function() {
                    toggleDayFields(day);
                }).trigger('change');

                $('#' + day + 'AddMore').on('click', function() {
                    addMoreFields(day);
                });
            });
        });
    </script>

    <script>
        $(document).ready(function() {
            // Initially hide the row with id 'employee' when the page loads
            // Check if the checkbox is checked on page load and toggle visibility accordingly
            if ($('#is_employee').prop('checked')) {
                $('#employee').show(); // Show the row if checkbox is checked
            } else {
                $('#employee').hide(); // Hide the row if checkbox is unchecked
            }

            // When the 'Is Employee' checkbox is changed, toggle the row visibility
            $('#is_employee').change(function() {
                if ($(this).prop('checked')) {
                    $('#employee').show(); // Show the row if checkbox is checked
                } else {
                    $('#employee').hide(); // Hide the row if checkbox is unchecked
                }
            });
        });
    </script>

    <script>
        $(document).ready(function() {
            // Add new holiday row
            $('#addHoliday').click(function() {
                const holidayRow = `
            <div class="row holiday-row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="mb-0">Date</label>
                        <input class="form-control" type="date" name="holidays[date][]" required>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <strong>From:</strong>
                        <input type="time" class="form-control from" name="holidays[from_time][]">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <strong>To:</strong>
                        <input type="time" class="form-control to" name="holidays[to_time][]">
                        <div class="text-right text-danger removeHoliday" style="cursor:pointer;">Remove</div>
                    </div>
                </div>
                <input type="hidden" name="holidays[recurring][]" value="0">
            </div>`;
                $('.holidayContainer').append(holidayRow);
            });

            // Remove holiday row
            $(document).on('click', '.removeHoliday', function() {
                $(this).closest('.holiday-row').remove();
            });
        });
    </script>
    <script>
        $(document).ready(function() {
            $('.myTable').DataTable({
                responsive: true
            });

        });
    </script>

    {{-- for employee --}}
    <script>
        $(document).on('click', '.view-appointment-btn-employee', function() {
            $('#modalAppointmentId').val($(this).data('id'));
            $('#modalAppointmentName').text($(this).data('name'));
            $('#Service').text($(this).data('service'));
            $('#modalEmail').text($(this).data('email'));
            $('#modalPhone').text($(this).data('phone'));
            $('#Staff').text($(this).data('employee'));
            $('#modalStartTime').text($(this).data('start'));
            $('#Amount').text($(this).data('amount'));
            $('#Notes').text($(this).data('notes'));
            $('#modalStatusBadgeforEmployee').text($(this).data('status'));

            // Set status select
            var status = $(this).data('status');
            $('#CurrentStatus').val(status);

            // Set colored status badge
            var statusColors = {
                'Pending payment': '#f39c12',
                'Processing': '#3498db',
                'Confirmed': '#2ecc71',
                'Cancelled': '#ff0000',
                'Completed': '#008000',
                'On Hold': '#95a5a6',
                'Rescheduled': '#f1c40f',
                'No Show': '#e67e22',
            };

            var badgeColor = statusColors[status] || '#7f8c8d';

            $('#modalStatusBadgeforEmployee').html(
                `<span class="badge px-2 py-1" style="background-color: ${badgeColor}; color: white;">${status}</span>`
            );
        });
    </script>

    {{-- user booking data --}}
    <script>
        const statusColors = {
            'Pending payment': '#f39c12',
            'Processing': '#3498db',
            'Confirmed': '#2ecc71',
            'Cancelled': '#ff0000',
            'Completed': '#008000',
            'On Hold': '#95a5a6',
            'Rescheduled': '#f1c40f',
            'No Show': '#e67e22',
        };

        $(document).on('click', '.view-booking-btn', function() {
            var button = $(this);
            var status = button.data('status');
            var badgeColor = statusColors[status] || '#7f8c8d';

            $('#modalUserName').text(button.data('name'));
            $('#modalService').text(button.data('service'));
            $('#modalStaff').text(button.data('staff'));
            $('#modalDate').text(button.data('date'));
            $('#modalAmount').text(button.data('amount'));
            $('#modalTime').text(button.data('time'));
            $('#modalNotes').text(button.data('notes'));

            // Show colored badge
            $('#modalStatusBadge').html(
                `<span class="badge px-2 py-1" style="background-color: ${badgeColor}; color: white;">${status}</span>`
            );
        });
    </script>





@stop
