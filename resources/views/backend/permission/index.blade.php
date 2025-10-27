@extends('adminlte::page')
@section('title','Role & Permission')
@section('content_header')
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">All Role & Permissions</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item active">Category</li>
                </ol>
            </div>
        </div>
    </div>
@stop

@section('content')

<section class="content">
    <div class="container-fluid">
        @if ($errors->any())
            <div class="alert alert-danger mt-2">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

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
        <div class="row">
            <div class="col-md-12">
                <div class="card py-2 px-2">

                    <div class="card-body p-0">
                        <table id="myTable" class="table table-striped projects ">
                            <thead>
                                <tr>
                                    <th style="width: 1%">
                                        #
                                    </th>
                                    <th style="width: 69%">
                                        Title
                                    </th>

                                    <th style="width: 21%">
                                        Action
                                    </th>
                                </tr>
                            </thead>
                            <tbody>

                                @foreach ($roles as $role)
                                    <tr>
                                        <td>
                                            {{ $loop->iteration }}
                                        </td>
                                        <td>
                                            <span class="text-capitalize">
                                                {{ $role->name }}
                                            </span>
                                        </td>
                                        <td class="project-actions text-right d-flex">

                                            <div>
                                                <a class="btn btn-danger btn-sm ml-2"
                                                    href="{{ route('permission.edit', $role->id) }}">
                                                    <i class="fas fa-pencil-alt">
                                                    </i>
                                                    Edit
                                                </a>
                                            </div>

                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                    </div>
                    <!-- /.card-body -->
                </div>
            </div>
            <!-- /.col -->

        </div>
        <!-- /.row -->
    </div><!-- /.container-fluid -->
</section>

@section('js')

    {{-- hide notifcation --}}
    <script>
        $(document).ready(function() {
            $(".alert").delay(6000).slideUp(300);
        });
    </script>

    <script>
        $(document).ready(function() {
            $('#myTable').DataTable( {
                responsive: true
            } );

        });
    </script>
@endsection

@stop
