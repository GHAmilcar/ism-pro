@extends('admin.admin_master')
@section('admin')


 <div class="page-content">
                    <div class="container-fluid">

                        <!-- start page title -->
                        <div class="row">
                            <div class="col-12">
                                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                                    <h4 class="mb-sm-0">Paid Customer All</h4>



                                </div>
                            </div>
                        </div>
                        <!-- end page title -->

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">

        <a href="{{ route('paid.customer.print.pdf') }}" class="btn btn-dark btn-rounded waves-effect waves-light" target="_black" style="float:right;"><i class="fa fa-print"> Print Paid Customer </i></a> <br>  <br>

                    <h4 class="card-title">Paid All Data </h4>


                    <table id="datatable" class="table table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                        <thead class="table-dark">
                        <tr>
                            <th class="text-center">Sl</th>
                            <th class="text-center">Customer Name</th>
                            <th class="text-center">Invoice No </th>
                            <th class="text-center">Date</th>
                            <th class="text-center">Paid Status</th>
                            <th class="text-center">Action</th>

                        </thead>


                        <tbody>

                        	@foreach($allData as $key => $item)
                        <tr>
                            <td class="text-center"> {{ $key+1}} </td>
                            <td class="text-center"> {{ $item['customer']['name'] }} </td>
                            <td class="text-center"> {{ $item['invoice']['invoice_no'] }}</td>
                            <td class="text-center"> {{ date('d-m-Y',strtotime($item['invoice']['date'])) }} </td>
                            <td class="text-center">
                                @if($item->due_amount > '0')
                                <span class="btn btn-warning">Pending</span>
                                @elseif($item->due_amount == '0')
                                <span class="btn btn-success">Paid</span>
                                @endif
                                </td>
                            <td class="text-center">
                            <a href="{{ route('customer.invoice.details.pdf',$item->invoice_id) }}" class="btn btn-info sm" target="_black" title="Customer Details">  <i class="fa fa-eye"></i> </a>


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


@endsection
