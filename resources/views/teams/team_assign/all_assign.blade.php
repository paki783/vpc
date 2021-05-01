@extends('include.main')
@section('content')
	<section class="content-header">
		<div class="pull-left">
			<h1>
				{{ $title }}
			</h1>
		</div>
        <div class="pull-right">
            <a href="{{ url('admin/teams/add_assign_team') }}" class="btn btn-primary">Add Teams</a>
        </div>
	</section>
	<section class="content">
	<div class="row">
		<div class="col-xs-12">
			<div class="box">
                <div class="box_header">
                    <form action="" method="get" class="form-inline">
                        <input type="text" class="form-control" name="searhitems" placeholder="Search by league...">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                        <button type="submit" class="btn btn-default">Search Now</button>
                    </form>
                </div>
			    <div class="box-body">
                    @include("include.message")
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>League</th>
                                <th>Division</th>
                                <th>Teams</th>
                                <th>Current Season</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(!empty($data))
                                @foreach($data as $d)
                                    <tr>
                                        <td>@if(!empty($d->getLeagues)) {{ $d->getLeagues->name }} @endif</td>
                                        <td>@if(!empty($d->getDivision)) {{ $d->getDivision->divisions_name }} @endif</td>
                                        <td>
                                            @if(!empty($d->teams))
                                                <?php $count = 0; ?>
                                                @foreach($d->teams as $t)
                                                    @if(!empty($t->getTeams))
                                                        @if($count == 0)
                                                            {{ $t->getTeams->team_name }}
                                                        @else
                                                            , {{ $t->getTeams->team_name }}
                                                        @endif
                                                    <?php $count++ ?>
                                                    @endif
                                                @endforeach
                                            @endif
                                        </td>
                                        <td>{{ $d->current_Season }}</td>
                                        <td>
                                        <div class="btn-group" role="group" aria-label="...">
                                            <a href="{{ url('/admin/teams/team_assign/delete') }}?league_id={{ $d->league_id }}&divion_id={{ $d->division_id }}" class="btn btn-danger">
                                                <i class="glyphicon glyphicon-trash"></i>
                                            </a>
                                            <a href="{{ url('/admin/teams/team_assign/edit') }}?league_id={{ $d->league_id }}&divion_id={{ $d->division_id }}" class="btn btn-warning">
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