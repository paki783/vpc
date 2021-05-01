@extends('include.main')
@section('content')
	<section class="content-header">
		<div class="pull-left">
			<h1>
				{{ $title }}
			</h1>
		</div>
	</section>
	<section class="content">
	<div class="row">
		<div class="col-xs-12">
			<div class="box">
                <!-- /.box-header -->
                <div class="box_header">
                    <form action="" method="get" class="form-inline">
                        <input type="text" class="form-control" name="user_name" placeholder="Search Username...">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                        <button type="submit" class="btn btn-default">Search Now</button>
                    </form>
                </div>
			    <div class="box-body">
                    @include("include.message")
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>User Name</th>
                                <th>Email</th>
                                <th>Current Image</th>
                                <th>Approval Image</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(!empty($data))
                                @foreach($data as $d)
                                    <tr>
                                        <td>{{ $d->id }}</td>
                                        <td>@if(!empty($d->user_name)) {{ $d->user_name }} @endif</td>
                                        <td>{{ $d->email }}</td>
                                        <td><img class="img-responsive" width="100" src="@if(!empty($d->profile_image)){{ $d->profile_image }}@endif" alt="User profile picture"></td>
                                        <td><img class="img-responsive" width="100" src="@if(!empty($d->profile_image)){{ $d->profile_image }}@endif" alt="User Approval picture"></td>
                                        <td>
                                            <div class="btn-group" role="group" aria-label="...">
                                                <a href="{{ url('admin/user/detail') }}?id={{ Crypt::encryptString($d->id) }}" class="btn btn-primary">
                                                    <i class="glyphicon glyphicon-eye-open"></i>
                                                </a>
                                                <a href="{{ url('s/edit') }}?id={{ $d->id }}" class="btn btn-warning">
                                                    <i class="glyphicon glyphicon-pencil"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
                <div class="box-footer clearfix">
                    {{ $data->links() }}
                    <ul class="pagination pagination-sm no-margin pull-right">
                        <li>Total Records: {{ $data->total() }}</li>
                    </ul>
                </div>
			</div>
		</div>
	</div>
</section>

@endsection