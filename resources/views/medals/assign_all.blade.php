@extends('include.main')
@section('content')
	<section class="content-header">
		<div class="pull-left">
			<h1>
				{{ $title }}
			</h1>
		</div>
        <div class="pull-right">
            <a href="{{ url('admin/medals/assign/add_assign_medals') }}" class="btn btn-primary">Assign Medal</a>
        </div>
	</section>
	<section class="content">
	<div class="row">
		<div class="col-xs-12">
			<div class="box">
			    <!-- /.box-header -->
			    <div class="box-body">
                    @include("include.message")
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>League Name</th>
                                <th>Division Name</th>
                                <th>Team Name</th>
                                <th>Team User</th>
                                <th>Medal Name</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(!empty($data) && count($data) > 0)
                                @foreach($data as $u)
                                    <tr>
                                        <td>{{ $u->getLeague->name }}</td>
                                        <td>{{ $u->getDivision->divisions_name }}</td>
                                        <td>{{ $u->getTeam->team_name }}</td>
                                        <td>{{ $u->getallUser->user_name }}</td>
                                        <td>{{ $u->getMedal->achievement_name }}</td>
                                        <td>
                                            <div class="btn-group" role="group" aria-label="...">
                                                <a href="{{ url('admin/medals/delete_assign_medals') }}?id={{ $u->id }}" class="btn btn-danger">
                                                    <i class="glyphicon glyphicon-trash"></i>
                                                </a>
                                                <a href="{{ url('admin/medals/edit_assign_medals') }}?id={{ $u->id }}" class="btn btn-warning">
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
                    @if(!empty($data))
                    {{ $data->links() }}
                    <ul class="pagination pagination-sm no-margin pull-right">
                        <li>Total Records: {{ $data->total() }}</li>
                    </ul>
                    @endif
                </div>
			</div>
		</div>
	</div>
</section>

@endsection