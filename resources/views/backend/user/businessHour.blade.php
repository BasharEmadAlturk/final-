@extends('adminlte::page')

@section('title', 'Business Hours')

@section('content_header')
<div class="row  mb-3">
    <div class="col-lg-8 margin-tb">
         <h1>All Business Hours</h1>
        <div class="pull-right">
            <a class="btn btn-primary mt-3" href="{{ route('employee.index') }}"> Back</a>
        </div>
    </div>
@stop

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card p-3 shadow">
                <div id="example_wrapper" class="dataTables_wrapper">
                    <table id="example" class="display dataTable table table-striped" style="width: 100%;"
                        aria-describedby="example_info">
                        <thead>
                            <tr>
                                <th class="width:30%;">#</th>

                                <th>Days</th>
                                <th>From</th>
                                <th>To</th>
                                {{-- <th width="20%">Action</th> --}}
                            </tr>
                        </thead>
                        <tbody>

                            @foreach ($user['days'] as $days)
                                <tr>

                                    <td> {{ $loop->iteration }}</td>

                                    <td> {{ $days['day'] }}</td>

                                    <td> {{ \Carbon\Carbon::parse($days['from'])->format('h:i A') }} </td>

                                    <td>{{ \Carbon\Carbon::parse($days['to'])->format('h:i A') }} </td>

                                </tr>
                            @endforeach

                        </tbody>
                        <tfoot>
                            <tr>
                                <th>#</th>
                                <th>Days</th>
                                <th>From</th>
                                <th>To</th>
                                {{-- <th width="280px">Action</th> --}}
                            </tr>
                        </tfoot>
                    </table>
                </div>

            </div>
        </div>

    </div>

@stop

@section('css')

@stop

@section('js')

@stop
