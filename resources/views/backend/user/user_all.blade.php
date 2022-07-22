@extends('admin.admin_master')

@section('admin')
<div class="page-content">
    <div class="container-fluid">

                <!-- start page title -->
                <div class="row">
                    <div class="col-12">
                        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                            <h4 class="mb-sm-0">Users All</h4>



                        </div>
                    </div>
                </div>
                <!-- end page title -->

                    <div class="row">
                    <div class="col-12">
                    <div class="card">
                    <div class="card-body">

                    <a href="{{ route('user.add') }}" class="btn btn-dark btn-rounded waves-effect waves-light" style="float:right;"><i class="fas fa-plus-circle"> Add User </i></a> <br>  <br>

                        <h4 class="card-title">Users All Data </h4>


                        <table id="datatable" class="table table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                            <thead class="table-dark">
                            <tr>
                                <th>Sl</th>
                                <th>Name</th>
                                <th>E-mail</th>
                                <th>User Name</th>

                                <th>Action</th>
                            </thead>


                            <tbody>

                                @foreach($users as $key => $item)
                            <tr>
                                <td> {{ $key+1}} </td>
                                <td> {{ $item->name }} </td>
                                <td> {{ $item->email  }} </td>
                                <td> {{ $item->username }} </td>
                                <td>
                                <a href="{{ route('user.delete',$item->id) }}" class="btn btn-danger sm" title="Delete Data" id="delete">  <i class="fas fa-trash-alt"></i> </a>
                                </td>

                            </tr>
                            @endforeach

                            </tbody>
                        </table>

                    </div>
                </div>
            </div> <!-- end col -->
        </div> <!-- end row -->
    </div> <!-- container-fluid -->
</div>

@stop
