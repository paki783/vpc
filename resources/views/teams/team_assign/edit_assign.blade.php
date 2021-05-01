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
            <form action="{{ url('admin/teams/save_assign_team') }}" method="post" enctype="multipart/form-data">
			    <!-- /.box-header -->
			    <div class="box-body">
                    @include("include.message")
                    <div class="form-group">
                        <label>League</label>
                        <select name="league_id" onchange="getSeason(this.value)" id="league_id" class="form-control">
                            @if(!empty($data->getLeagues))
                                <option value="{{ $data->getLeagues->id }}" selected>{{ $data->getLeagues->name }}</option>
                            @endif
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Select Division</label>
                        <select name="division_id" id="division_id" class="form-control search_select">
                            <option value="{{ $data->id }}" selected>{{ $data->divisions_name }}</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Select Team</label>
                        <select name="team_id[]" id="team_id" class="form-control search_select" multiple>
                            @if(!empty($data->getDivisionTeams))
                                @foreach($data->getDivisionTeams as $t)
                                    <option value="{{ $t->getTeam->id }}" @if(in_array($t->getTeam->id, $selectedIDS)) selected @endif)>{{ $t->getTeam->team_name }}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                    <div class="form-group">
                        <label>ALL Season</label>
                        <select class="form-control search_select" multiple disabled>
                            @if(!empty($data->seasons->getSeasons))
                                @foreach($data->seasons->getSeasons as $seasons)
                                    <option value="{{ $seasons->season }}" selected>{{ $seasons->season }}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                    <div class="form-group" id="seasons">
                        <label>Current Season</label>
                        <select name="current_Season" id="current_Season" class="form-control search_select">
                            @if(!empty($data->seasons->getSeasons))
                                @foreach($data->seasons->getSeasons as $seasons)
                                    <option value="{{ $seasons->season }}" @if($seasons->season == $data->current_Season) selected @endif>{{ $seasons->season }}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                </div>
                <div class="box-footer clearfix">
					<input type="hidden" value="{{ $data->league_id }}" name="id" />
					<input type="hidden" value="{{ csrf_token() }}" name="_token" />
                    <input type="submit" class="btn btn-primary" value="Update Changes" />
                </div>
            </form>
			</div>
		</div>
	</div>
</section>
@include('include.filterjs')
<script>
    $(document).ready(function () {
        $("form").submit(function(e){
            if($("#league_id").val() == 0){
                alert("Kindly select league");
                e.preventDefault();
                return false;
            }
        });
        getleague(id);
        getdivisionbyleague({{ $data->getLeagues->id }}, "#division_id", {{ $data->id }});
        getTeam("#team_id");
    });
    function getSeason(id){
        getdivisionbyleague(id, "#division_id")
        getLeaguebyid_forseason(id, "#current_Season");
    }
</script>
@endsection