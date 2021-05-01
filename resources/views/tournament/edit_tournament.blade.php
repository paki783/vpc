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
            <form action="{{ url('/admin/tournament/saveTournament') }}" method="post" enctype="multipart/form-data">
			    <!-- /.box-header -->
			    <div class="box-body">
                    @include("include.message")
                    <div class="row">
                        <div class="col-md-12 form-group">
                            <label>Name</label>
                            <input type="text" class="form-control" value="{{ $data->name }}" name="name" />
                        </div>
                        <div class="col-md-12 form-group">
                            <label>Description</label>
                            <textarea class="form-control" name="description">{{ $data->description }}</textarea>
                        </div>
                        <div class="col-md-12 form-group">
                            <label>VPC System</label>
                            <select name="vpc_systemid" class="form-control" id="getvpcsystem">
                                @if(!empty($data->getVPCSystem))
                                    <option value="{{ $data->getVPCSystem->id }}">{{ $data->getVPCSystem->syste_name }}</option>
                                @endif
                            </select>
                        </div>
                        <div class="col-md-8">
                            <label>Logo</label>
                            <input type="file" class="form-control" name="logo" />   
                            <small>If you want to replace the league logo add file other leave it blank</small>
                        </div>
                        <div class="col-md-4">
                            @if(!empty($data->logo))
                                <img src="{{ $data->logo }}" class="img-responsive" /> 
                            @endif
                        </div>
                        <div class="col-md-8">
                            <label>Banner</label>
                            <input type="file" class="form-control" name="banner" />   
                            <small>If you want to replace the league logo add file other leave it blank</small>
                        </div>
                        <div class="col-md-4">
                            @if(!empty($data->banner))
                                <img src="{{ $data->banner }}" class="img-responsive" /> 
                            @endif
                        </div>
                        <div class="col-md-12 form-group">
                            <label>Game Mode</label>
                            <select name="modeid[]" id="getMode" class="form-control" multiple>
                                @if(!empty($data->getTournamentMode))
                                    @foreach($data->getTournamentMode as $mode)
                                        <option value="{{ $mode->getMode->id }}" selected>{{ $mode->getMode->mode_name }}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                        <div class="col-md-12 form-group">
                            <label>Seasons</label>
                            <select name="seasons[]" class="form-control search_select" multiple>
                                @for($i = 1; $i<=20; $i++)
                                    <option value="Season {{ $i }}" @if(in_array('Season '.$i, $SeasonsIds)) selected @endif>Season {{ $i }}</option>
                                @endfor
                            </select>
                        </div>
                        @if($menu == "tournament")
                        <div class="col-md-12 form-group">
                            <label>Select Team</label>
                            <select class="form-select" id="team_id" name="team_id[]" multiple>
                                @if(!empty($data->getTournamentTeams))
                                    @foreach($data->getTournamentTeams as $t)
                                        <option value="{{ $t->getTeam->id }}" selected>{{ $t->getTeam->team_name }}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                        @endif
                        @if($menu == "league")
                            <div class="col-md-8">
                                <label>Rules</label>
                                <input type="file" name="rules" class="form-control" />
                                <small>If you want to replace the league logo add file other leave it blank</small>
                            </div>
                            <div class="col-md-4">
                                @if(!empty($data->getLeagueRules))
                                    <img src="{{ $data->getLeagueRules->photo }}" class="img-responsive" /> 
                                @endif
                            </div>
                        @endif
                    </div>
                </div>
                <div class="box-footer clearfix">
                    <input type="hidden" value="{{ $data->tournament_type }}" name="tournament_type" />
                    <input type="hidden" value="{{ csrf_token() }}" name="_token" />
                    <input type="hidden" value="{{ $data->id }}" name="id" />
                    <input type="submit" class="btn btn-primary" value="Update {{ ucwords($menu) }}" />
                </div>
            </form>
			</div>
		</div>
	</div>
</section>

@include('include.filterjs')

<script>
    $(document).ready(function () {
        getTeam("#team_id");
        getMode("#getMode");
        getVPCSystem("#getvpcsystem")
    });
</script>

@endsection