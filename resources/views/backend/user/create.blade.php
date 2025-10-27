@extends('adminlte::page')

@section('title', 'Create User')

@section('content_header')
    <div class="container-fluid">
        <div class="row mb-1">
            <div class="col-sm-6">
                <h1 class="m-0">Add User</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item active">Add user</li>
                </ol>
            </div>
        </div>
    </div>
@stop

@section('content')
    <div class="">
        <!-- Content Header (Page header) -->
        <!-- /.content-header -->

        <!-- Main content -->
        <div class="content  py-2">
            <div class="">
                @if (session()->has('success'))
                    <div class="alert alert-dismissable alert-success">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <strong>
                            {!! session()->get('success') !!}
                        </strong>
                    </div>
                @endif
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


                <form action="{{ route('user.store') }}" method="post">
                    @csrf
                    <div class="row pl-md-2">
                        <div class="col-md-8">
                            <div class="row">
                                <div class="col-xs-12 col-sm-12 col-md-12">
                                    <div class="form-group">
                                        <label class="my-0">Name</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend ">
                                                <span class="input-group-text ">
                                                    <i class="fas fa-user">
                                                    </i>
                                                </span>
                                            </div>
                                            <input type="text" class="form-control @error('name') is-invalid @enderror"
                                                name="name" value="{{ old('name') }}" placeholder="Full Name">
                                        </div>
                                        @error('name')
                                            <small class="text-danger"><strong>{{ $message }}</strong></small>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-xs-12 col-sm-12 col-md-12">
                                    <div class="form-group">
                                        <label class="my-0">Email</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">
                                                    <i class="fas fa-envelope">
                                                    </i>
                                                </span>
                                            </div>
                                            <input type="email" class="form-control @error('email') is-invalid @enderror"
                                                name="email" value="{{ old('email') }}" placeholder="Email">
                                        </div>
                                        @error('email')
                                            <small class="text-danger"><strong>{{ $message }}</strong></small>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-xs-12 col-sm-12 col-md-12">
                                    <div class="form-group">
                                        <label class="my-0">Phone</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">
                                                    <i class="fas fa-phone">
                                                    </i>
                                                </span>
                                            </div>
                                            <input type="tel" class="form-control @error('phone') is-invalid @enderror"
                                                name="phone" value="{{ old('phone') }}" placeholder="Phone No.">
                                        </div>
                                        @error('phone')
                                            <small class="text-danger"><strong>{{ $message }}</strong></small>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-xs-12 col-sm-12 col-md-12">
                                    <div class="form-group">
                                        <label class="my-0">Password</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">
                                                    <i class="fas fa-lock"></i>
                                                </span>
                                            </div>
                                            <input type="password"
                                                class="form-control @error('password') is-invalid @enderror"
                                                name="password"
                                                placeholder="Enter Password">
                                        </div>
                                        @error('password')
                                            <small class="text-danger"><strong>{{ $message }}</strong></small>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-xs-12 col-sm-12 col-md-12">
                                    <div class="form-group">
                                        <label class="my-0">Confirm Password</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">
                                                    <i class="fas fa-lock"></i>
                                                </span>
                                            </div>
                                            <input type="password"
                                                class="form-control @error('password_confirmation') is-invalid @enderror"
                                                name="password_confirmation"
                                                placeholder="Confirm Password">
                                        </div>
                                        @error('password_confirmation')
                                            <small class="text-danger"><strong>{{ $message }}</strong></small>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-xs-12 col-sm-12 col-md-12 mb-3 select2-primary">
                                    <label class="my-0"><i class="fas fa-user-lock"></i> User Role</label>
                                    <select name="roles[]" class="form-control select2 @error('roles[]') is-invalid @enderror" data-placeholder="Select Role" multiple>
                                        @foreach ($roles as $role)
                                            <option value="{{ $role->name }}"
                                                {{ in_array($role->name, old('roles', [])) ? 'selected' : '' }}>
                                                {{ ucfirst($role->name) }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('roles')
                                        <small class="text-danger"><strong>{{ $message }}</strong></small>
                                    @enderror
                                </div>



                            </div>
                        </div>
                    </div>

                    <div class="row pt-3 pl-md-2">
                        <div class="col-md-2">
                            <div class="form-group">
                                <div class="custom-control custom-switch">
                                    <!-- Use old('is_employee') to check if it was previously checked -->
                                    <input type="checkbox" class="custom-control-input" id="is_employee"
                                        name="is_employee"
                                        {{ old('is_employee') ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="is_employee">Is Employee</label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div id="employee" class="row pl-md-2 pb-5">
                        <div class="col-md-8">
                            <div class="row">
                                <div class="col-md-12">
                                    <hr>
                                    <div class="mb-3">
                                        <h4 class="mb-0">Only For Employees </h4>
                                        <small class="text-muted">Fill these details if adding an employee only</small>
                                    </div>

                                    <div class="row">
                                        <div class="col-xs-12 col-sm-12 col-md-12 mb-3 select2-dark">
                                            <label for="service_id" class="my-0"><i class="fas fa-id-card"></i>
                                                Select
                                                Service</label> <small class="text-muted"> Link employees to services they
                                                are assigned to</small>
                                            <select class="form-control select2 @error('service[]') is-invalid @enderror"
                                                name="service[]" data-placeholder="Select Service" id="service"
                                                multiple>
                                                @foreach ($services as $service)
                                                    <option
                                                        {{ in_array($service->id, old('service', [])) ? 'selected' : '' }}
                                                        value="{{ $service->id }}">{{ $service->title }}</option>
                                                @endforeach
                                            </select>
                                            @error('service')
                                                <small class="text-danger"><strong>{{ $message }}</strong></small>
                                            @enderror
                                        </div>

                                        <div class="col-xs-12 col-sm-12 col-md-12 mb-3">
                                            <label for="slot_duration" class="my-0"><i class="fas fa-stopwatch"></i>
                                                Service
                                                Duration</label> <small class="text-muted"> Create booking slots based on
                                                your preferred time duration.</small>
                                            @php
                                                $steps = ['10', '15', '20', '30', '45', '60'];
                                                $selectedStep = old('slot_duration'); // Get the selected step value from old input
                                            @endphp
                                            <select class="form-control @error('step') is-invalid @enderror"
                                                name="slot_duration" id="slot_duration">
                                                <option value="" {{ !$selectedStep ? 'selected' : '' }}>Select
                                                    Duration
                                                </option>
                                                @foreach ($steps as $stepValue)
                                                    <option {{ $selectedStep == $stepValue ? 'selected' : '' }}
                                                        value="{{ $stepValue }}">{{ $stepValue }}</option>
                                                @endforeach
                                            </select>
                                            @error('slot_duration')
                                                <small class="text-danger"><strong>{{ $message }}</strong></small>
                                            @enderror
                                        </div>

                                        <div class="col-xs-12 col-sm-12 col-md-12 mb-3">
                                            <label for="break_duration" class="my-0"><i class="fas fa-coffee"></i>
                                                Preparation or Break time</label> <small class="text-muted"> Break between
                                                one to another appointment</small>
                                            @php
                                                $breaks = ['5', '10', '15', '20', '25', '30'];
                                                $selectedBreak = old('break_duration'); // Get the selected step value from old input
                                            @endphp
                                            <select class="form-control @error('step') is-invalid @enderror"
                                                name="break_duration" id="break_duration">
                                                <option value="" {{ !$selectedBreak ? 'selected' : '' }}>No Break
                                                </option>
                                                @foreach ($breaks as $breakValue)
                                                    <option {{ $selectedBreak == $breakValue ? 'selected' : '' }}
                                                        value="{{ $breakValue }}">{{ $breakValue }}</option>
                                                @endforeach
                                            </select>
                                            @error('break_duration')
                                                <small class="text-danger"><strong>{{ $message }}</strong></small>
                                            @enderror
                                        </div>


                                    </div>

                                    <hr>
                                    <div class="row">
                                        <div class="mb-3">
                                            <h4 class="mb-0">Set Availibity - For Employee</h4>
                                            <small class="text-muted">Select days and timings, with the option to add
                                                multiple time slots in a day, e.g., 9 AM–12 PM and 4 PM–8 PM</small>
                                        </div>
                                        <div class="col-md-12">
                                            @foreach ($days as $day)
                                                <!-- Main row (first time pair for each day) -->
                                                <div class="row">
                                                    <div class="col-md-2">
                                                        <div class="form-group">
                                                            <div class="custom-control custom-switch">
                                                                <input type="checkbox" class="custom-control-input"
                                                                    id="{{ $day }}"
                                                                    @if (old('days.' . $day)) checked @endif>
                                                                <label class="custom-control-label"
                                                                    for="{{ $day }}">{{ ucfirst($day) }}</label>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <!-- First time input row (main row) -->
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <strong>From:</strong>
                                                            <input type="time" class="form-control from"
                                                                name="days[{{ $day }}][]"
                                                                value="{{ old('days.' . $day . '.0') }}"
                                                                id="{{ $day }}From">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <strong>To:</strong>
                                                            <input type="time" class="form-control to"
                                                                name="days[{{ $day }}][]"
                                                                value="{{ old('days.' . $day . '.1') }}"
                                                                id="{{ $day }}To">
                                                        </div>
                                                        <div style="margin-top:-15px;" id="{{ $day }}AddMore"
                                                            class="text-right d-none text-primary">Add More</div>
                                                    </div>
                                                </div>

                                                <!-- Render additional rows -->
                                                @if (old('days.' . $day))
                                                    <!-- Check if there are any times for the day -->
                                                    @foreach (old('days.' . $day) as $index => $time)
                                                        <!-- Skip the first time pair, as it's already rendered above -->
                                                        @if ($index > 1 && $index % 2 == 0)
                                                            <!-- Skip last pair by checking if index is even -->
                                                            <div class="row additional-{{ $day }}">
                                                                <div class="col-md-2"></div>
                                                                <div class="col-md-4">
                                                                    <div class="form-group">
                                                                        <strong>From:</strong>
                                                                        <input type="time" class="form-control from"
                                                                            name="days[{{ $day }}][]"
                                                                            value="{{ $time }}"
                                                                            id="{{ $day }}From">
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <div class="form-group">
                                                                        <strong>To:</strong>
                                                                        <input type="time" class="form-control to"
                                                                            name="days[{{ $day }}][]"
                                                                            value="{{ old('days.' . $day . '.' . ($index + 1)) }}"
                                                                            id="{{ $day }}To">
                                                                    </div>
                                                                    <div style="margin-top:-15px;"
                                                                        class="text-right remove-field text-danger">
                                                                        Remove</div>
                                                                </div>
                                                            </div>
                                                        @endif
                                                    @endforeach
                                                @endif
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-12 pt-3 pl-md-3">
                        <button type="submit" class="btn btn-primary">Add User</button>
                    </div>
            </div>
        </div>
    </div>
    </div>
    </div>



    </form>
    </div>
    </div>
    </div>
    </div>

@stop

@section('css')

@stop

@section('js')

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
        // In your Javascript (external .js resource or <script> tag)
        $(document).ready(function() {
            $('.select2').select2({
                allowClear: true,
                search: true,
                //maximumSelectionLength: 2
            });
        });
    </script>


<script>
    $(document).ready(function () {
        // Initially hide the row with id 'employee' when the page loads
        // Check if the checkbox is checked on page load and toggle visibility accordingly
        if ($('#is_employee').prop('checked')) {
            $('#employee').show();  // Show the row if checkbox is checked
        } else {
            $('#employee').hide();  // Hide the row if checkbox is unchecked
        }

        // When the 'Is Employee' checkbox is changed, toggle the row visibility
        $('#is_employee').change(function () {
            if ($(this).prop('checked')) {
                $('#employee').show();  // Show the row if checkbox is checked
            } else {
                $('#employee').hide();  // Hide the row if checkbox is unchecked
            }
        });
    });
</script>




@stop
