@extends('include.main')
@section('content')
    <section class="content-header">
        <div class="pull-left">
            <h1>
                {{ $title }} ({{ roundName($tournamentBracket->round) }})
            </h1>
        </div>
    </section>
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <!-- /.box-header -->
                    <div class="box-body">
                        @include("include.message")
                        <table id="example1" class="table table-bordered table-striped">
                            <thead>
                            <tr>
                                <th>ID</th>
                                <th>Tournament</th>
                                <th>Home Team</th>
                                <th>Away Team</th>
                                <th>Match Date</th>
                                <th>Match Status</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                                @if(!empty($data))
                                    @foreach($data as $d)
                                        <tr>
                                            <td>{{ $d->id }}</td>
                                            <td>{{ $d->getLeague->name }}</td>
                                            <td>
                                                @if(!empty($d->getTournamentGroupTeamOne->getTeam))
                                                    {{ $d->getTournamentGroupTeamOne->getTeam->team_name }}
                                                @endif
                                            </td>
                                            <td>
                                                @if(!empty($d->getTournamentGroupTeamTwo->getTeam))
                                                    {{ $d->getTournamentGroupTeamTwo->getTeam->team_name }}
                                                @endif
                                            </td>
                                            <td>{{ $d->match_start_date }}</td>

                                            <td>{{ $d->match_status }}</td>
                                            <td>
                                                <div class="btn-group" role="group" aria-label="...">
                                                    <a href="{{ url('admin/tournament/bracket/match/edit') }}?id={{ $d->id }}" class="btn btn-warning">
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
                </div>
            </div>
        </div>
    </section>
@endsection