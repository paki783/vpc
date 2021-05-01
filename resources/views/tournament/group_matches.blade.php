@extends('include.main')
@section('content')
    <section class="content-header">
        <div class="pull-left">
            <h1>
                {{ $title }}
            </h1>
        </div>
        <div class="pull-right">
            <a href="{{ url('admin/tournament/genGroup') }}/?id=<?= @$_GET['id']; ?>" class="btn btn-primary">Create Tournament Match</a>
        </div>
    </section>
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <!-- /.box-header -->
                    @include("include.searchform")
                    <div class="box-body">
                        @include("include.message")
                        <table class="table table-striped">
                            <thead>
                            <tr>
                                <th>ID</th>
                                <th>League</th>
                                <th>Group</th>
                                <th>Home Team</th>
                                <th>Away Team</th>
                                <th>Match Date</th>
                                <th>Match Status</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            @if(!empty($data))
                                @foreach($data as $d)
                                    <tr>
                                        <td>{{ $d->id }}</td>
                                        <td>{{ $d->getLeague->name }}</td>
                                        <td>{{ $d->getGroupName->groupName->group_name }}</td>
                                        <td>
                                            @if(!empty($d->getTeamOne))
                                                {{ $d->getTeamOne->team_name }}
                                            @endif
                                        </td>
                                        <td>
                                            @if(!empty($d->getTeamTwo))
                                                {{ $d->getTeamTwo->team_name }}
                                            @endif
                                        </td>
                                        <td>{{ $d->match_start_date }}</td>
                                        <td>{{ $d->match_status }}</td>
                                        <td>
                                            <div class="btn-group" role="group" aria-label="...">
                                                <a href="{{ url('/admin/match/delete') }}?league_id={{ $d->league_id }}&match_id={{ $d->id }}" class="btn btn-danger">
                                                    <i class="glyphicon glyphicon-trash"></i>
                                                </a>
                                                <a href="{{ url('/admin/tournament/edit_group_tournament_matches') }}?league_id={{ $d->league_id }}&match_id={{ $d->id }}" class="btn btn-warning">
                                                    <i class="glyphicon glyphicon-pencil"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                    <div class="box-footer clearfix">
                        @if(!empty($data))
                            {{ $data->appends(request()->input())->links() }}
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