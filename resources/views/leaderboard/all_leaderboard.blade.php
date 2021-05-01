@extends('include.main')
@section('content')
	<section class="content-header">
		<div class="pull-left">
			<h1>
				{{ $title }}
			</h1>
		</div>
        <div class="pull-right">
            <a href="{{ url('admin/leaderboard/add_leaderboard') }}" class="btn btn-primary">Add Leaderboard</a>
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
                                <th>Leaderboard Name</th>
                                <th>Position</th>
                                <th>Static</th>
                                <th>League</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(!empty($data))
                                @foreach($data as $d)
                                    <tr>
                                        <td>{{ $d->name }}</td>
                                        <td>
                                            @if(!empty($d->getLeaderboardPosition))
                                                <?php $count = 0; ?>
                                                @foreach($d->getLeaderboardPosition as $position)
                                                    @if($count == 0)
                                                        {{ $position->getPosition->position_abr }}
                                                    @else
                                                        , {{ $position->getPosition->position_abr }}
                                                    @endif
                                                    <?php $count = 1; ?>
                                                @endforeach
                                            @endif
                                        </td>
                                        <td>
                                            @if(!empty($d->getLeaderboardStatic))
                                                <?php $count = 0; ?>
                                                @foreach($d->getLeaderboardStatic as $static)
                                                    @if($count == 0)
                                                        {{ $static->getStatic->statistic_abr }}
                                                    @else
                                                        , {{ $static->getStatic->statistic_abr }}
                                                    @endif
                                                    <?php $count = 1; ?>
                                                @endforeach
                                            @endif
                                        </td>
                                        <td>
                                            @if(!empty($d->getLeaderboardLeague))
                                                <?php $count = 0; ?>
                                                @foreach($d->getLeaderboardLeague as $league)
                                                    @if($count == 0)
                                                        {{ $league->getLeague->name }}
                                                    @else
                                                        , {{ $league->getLeague->name }}
                                                    @endif
                                                    <?php $count = 1; ?>
                                                @endforeach
                                            @endif
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group" aria-label="...">
                                                <a href="{{ url('admin/leaderboard/delete') }}?id={{ $d->id }}" class="btn btn-danger">
                                                    <i class="glyphicon glyphicon-trash"></i>
                                                </a>
                                                <a href="{{ url('admin/leaderboard/edit') }}?id={{ $d->id }}" class="btn btn-warning">
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