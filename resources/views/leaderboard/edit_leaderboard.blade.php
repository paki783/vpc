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
        <form action="{{ url('admin/leaderboard/saveLeaderboard') }}" method="post" enctype="multipart/form-data">
			<div class="box">
			    <!-- /.box-header -->
			    <div class="box-body">
                    @include("include.message")
                    <div class="form-group">
                        <label>Leaderboard Name</label>
                        <input type="text" class="form-control" name="leaderboard_name" value="{{ $data->name }}" />
                    </div>
                    <div class="form-group">
                        <label>Select Static</label>
                        <select class="form-control" name="static_id[]" id="static_id" multiple>
                            @if(!empty($data->getLeaderboardStatic))
                                @foreach($data->getLeaderboardStatic as $static)
                                    <option value="{{ $static->getStatic->id }}" selected>{{ $static->getStatic->name }}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Select League</label>
                        <select class="form-control" name="league_id[]" id="league_id" multiple>
                            @if(!empty($data->getLeaderboardLeague))
                                @foreach($data->getLeaderboardLeague as $league)
                                    <option value="{{ $league->getLeague->id }}" selected>{{ $league->getLeague->name }}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Select Position</label>
                        <select class="form-control" name="position_id[]" id="position_id" multiple>
                            @if(!empty($data->getLeaderboardPosition))
                                @foreach($data->getLeaderboardPosition as $position)
                                    <option value="{{ $position->getPosition->id }}" selected>{{ $position->getPosition->name }}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                </div>
                <div class="box-footer">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                    <input type="hidden" name="id" value="{{ $data->id }}" />
                    <input type="submit" class="btn btn-primary" value="Update Leaderboard" />
                </div>
            </div>
        </form>
		</div>
	</div>
</section>

@include('include.filterjs')

<script>
    $(document).ready(function () {
        getStatic("#static_id");
        getleague();
        getPosition("#position_id");
    });
</script>
@endsection