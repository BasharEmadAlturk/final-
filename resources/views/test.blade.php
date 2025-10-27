<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Schedule Form</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-datepicker@1.9.0/dist/css/bootstrap-datepicker.min.css" rel="stylesheet">
  <style>
    .time-range {
      display: flex;
      gap: 10px;
    }
  </style>
</head>
<body>

    <div class="container mt-5">
        <h2>Weekly Schedule</h2>
        <form action="{{ route('test') }}" method="post">
          @csrf
          <!-- Monday -->

          @php
              $days = [
                "Monday",
                "Tuesday",
                "Wednesday",
                "Thursday",
                "Friday",
                "Saturday",
                "Sunday",
              ];
          @endphp
       @foreach ($days as $day)
       <div class="mb-3">
        <label for="monday" class="form-label">{{ $day }}</label>
        <div class="time-range">
          <input type="time" class="form-control"  name="day[{{ $day }}][]" />
          <input type="time" class="form-control"  name="day[monday][]" />
          <input type="time" class="form-control"  name="day[monday][]" />
          <input type="time" class="form-control"  name="day[monday][]" />
        </div>
       @endforeach




          <button type="submit" class="btn btn-primary">Submit</button>
        </form>
      </div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap-datepicker@1.9.0/dist/js/bootstrap-datepicker.min.js"></script>

</body>
</html>
