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
                    <form action="{{ url('/admin/tournament/createMatchBracket') }}" method="post" enctype="multipart/form-data">
                        <!-- /.box-header -->
                        <div class="box-header">
                            <b>Set Bracket Match</b>
                        </div>
                        <div class="box-body">
                            @include("include.message")
                            <div class="form-group">
                                <label>Round Name</label>
                                <input type="text" class="form-control" name="round_name" />
                            </div>
                            <div class="form-group">
                                <label>Set Start Date</label>
                                <input type="text" class="form-control single_datepicker" name="single_datepicker" />
                            </div>
                            <div class="form-group">
                                <label>Teams</label>
                                <select class="form-control search_select" name="team_id[]" name="team_id[]" multiple>
                                    @if($data->getTournamentTeams)
                                        @foreach($data->getTournamentTeams as $teams)
                                            <option value="{{ $teams->id }}">{{ $teams->getTeam->team_name }}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="team_shuffle">
                                    <input type="checkbox" id="team_shuffle" name="team_shuffle" class="minimal" style="position: relative;top: 1px;" value="1">
                                    Shuffle
                                </label>
                            </div>
                        </div>
                        <div class="box-footer">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                            <input type="hidden" name="tournament_id" value="{{ $tournament_id }}" />
                            <input type="submit" class="btn btn-primary" value="Save Changes" />
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
    @include('include.filterjs')
<script>
    $(document).ready(function(){
        getTeam("#team_id");
    });
</script>
@endsection