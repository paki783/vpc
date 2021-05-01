@extends('include.main')
@section('content')
<style>
    .col-md-1 {
        width: 90px;
    }
</style>
<section class="content-header">
    <div class="pull-left">
        <h1>
            {{ $title }}
        </h1>
    </div>
</section>
<form action="{{ url('admin/tournament/bracket/match/update') }}" method="post" enctype="multipart/form-data">
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <!-- /.box-header -->
                    <div class="box-header">
                        Team Detail
                    </div>
                    <div class="box-body">
                        <div class="form-group">
                            <label>League</label>
                            <input type="text" disabled class="form-control" value="{{ $data->getLeague->name }}" />
                        </div>
                        <div class="form-group">
                            <label>Home Team</label>
                            <select class="form-control search_select" name="home_team_id">
                                @if($league->getTournamentTeams)
                                    @foreach($league->getTournamentTeams as $teams)
                                        <option value="{{ $teams->id }}" <?= (@$teams->id == @$data->team_one_id)?'selected':''; ?>>{{ $teams->getTeam->team_name }}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Away Team</label>
                            <select class="form-control search_select" name="away_team_id">
                                @if($league->getTournamentTeams)
                                    @foreach($league->getTournamentTeams as $teams)
                                        <option value="{{ $teams->id }}" <?= (@$teams->id == @$data->team_two_id)?'selected':''; ?>>{{ $teams->getTeam->team_name }}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <!-- /.box-header -->
                    <div class="box-header">
                        Score
                    </div>
                    <div class="box-body">
                        @include("include.message")

                        <div class="form-group">
                            <label>Start Date</label>
                            <input type="text" class="form-control single_datepicker" value="{{ $data->match_start_date }}" name="single_datepicker" />
                        </div>

                        @if(!empty($data->getTournamentGroupTeamOne->getTeam->score))
                            <div class="form-group">
                                {{ $data->getTournamentGroupTeamOne->getTeam->team_name }} - Home Score: {{ $data->getTournamentGroupTeamOne->getTeam->score->home_score }}, Away Score: {{ $data->getTournamentGroupTeamOne->getTeam->score->away_score }}
                                <a href="javascript:void(0)" class="btn btn-primary btn-xs" onclick="scoreProof({{ $data->getTournamentGroupTeamOne->getTeam->score->id }})">View Proof</a>
                            </div>
                        @endif
                        @if(!empty($data->getTournamentGroupTeamTwo->getTeam->score))
                            <div class="form-group">
                                {{ $data->getTournamentGroupTeamTwo->getTeam->team_name }} - Home Score: {{ $data->getTournamentGroupTeamTwo->getTeam->score->home_score }}, Away Score: {{ $data->getTournamentGroupTeamTwo->getTeam->score->away_score }}
                                <a href="javascript:void(0)" class="btn btn-primary btn-xs" onclick="scoreProof({{ $data->getTournamentGroupTeamTwo->getTeam->score->id }})">View Proof</a>
                            </div>
                        @endif
                        <div class="form-group">
                            <label>Home Score</label>
                            <input type="text" class="form-control" value="{{ $data->home_score }}" name="home_score" />
                        </div>
                        <div class="form-group">
                            <label>Away Score</label>
                            <input type="text" class="form-control" value="{{ $data->away_score }}" name="away_score" />
                        </div>
                        
                        <div class="form-group">
                            <label>Status</label>
                            <select class="form-control search_select" name="match_status">
                                <option value="scheduled" @if($data->match_status == "scheduled") selected @endif>{{ ucwords('Scheduled') }}</option>
                                <option value="disputed" @if($data->match_status == "disputed") selected @endif>{{ ucwords('disputed') }}</option>
                                <option value="completed" @if($data->match_status == "completed") selected @endif>{{ ucwords('completed') }}</option>
                                <option value="in progress" @if($data->match_status == "in progress") selected @endif>{{ ucwords('in progress') }}</option>
                                <option value="pending" @if($data->match_status == "pending") selected @endif>{{ ucwords('pending') }}</option>
                            </select>
                        </div>
                    </div>
                    <div class="box-footer">
                        <input type="hidden" value="{{ csrf_token() }}" name="_token" />
                        <input type="hidden" value="{{ $data->id }}" name="match_id" />
                        <input type="submit" class="btn btn-primary" value="Submit" />
                    </div>
                </div>
            </div>
        </div>
    </section>
</form>

@endsection