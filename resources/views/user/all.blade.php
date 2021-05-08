@extends('include.main')
@section('content')
	<section class="content-header">
		<div class="pull-left">
			<h1>
				{{ $title }}
			</h1>
		</div>
        <div class="pull-right text-right">
			<a href="{{ url('/admin/user/addUser') }}" class="btn btn-primary">Add User</a>
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
                                <th>User Promotion</th>
                                <th>Assistant</th>
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
                                        <td>
                                            @if($d->role_names[0] == "manager")
                                            <a href="{{ url('/admin/user/manager/all') }}?id={{ $d->id }}">
                                                {{ $d->role_names[0] }}
                                            </a>
                                            @else
                                                {{ $d->role_names[0] }}
                                            @endif
                                        </td>
                                        <td>
                                            @if(isset($d->managerAssistant) && count($d->managerAssistant) > 0)
                                                <?php $count = 0; ?>
                                                @foreach($d->managerAssistant as $ass)
                                                    @if(!empty($ass->getUser->user_name))
                                                        @if($count == 0)
                                                            {{ $ass->getUser->user_name }}
                                                        @else
                                                            , {{ $ass->getUser->user_name }}
                                                        @endif
                                                    @endif
                                                    <?php $count++; ?>
                                                @endforeach
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group" aria-label="...">
                                                <a href="{{ url('admin/user/detail') }}?id={{ Crypt::encryptString($d->id) }}" class="btn btn-primary">
                                                    <i class="glyphicon glyphicon-eye-open"></i>
                                                </a>
                                                <a href="{{ url('admin/user/edit') }}?id={{ $d->id }}" class="btn btn-warning">
                                                    <i class="glyphicon glyphicon-pencil"></i>
                                                </a>
                                                <!-- <a href="{{ url('admin/user/promote') }}?id={{ Crypt::encryptString($d->id) }}&prmote=true" class="btn btn-success">
                                                    <i class="glyphicon glyphicon-arrow-up"></i>
                                                </a>
                                                <a href="{{ url('admin/user/promote') }}?id={{ Crypt::encryptString($d->id) }}&prmote=false" class="btn btn-danger">
                                                    <i class="glyphicon glyphicon-arrow-down"></i>
                                                </a> -->
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