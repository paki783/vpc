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
        <form action="{{ url('admin/division/saveDivision') }}" method="post" enctype="multipart/form-data">
			<div class="box">
			    <!-- /.box-header -->
			    <div class="box-body">
					@include("include.message")
					<div class="form-group">
						<label>Select League</label>
						<select class="form-control" name="league_id" id="league_id">
							@if(!empty($data->getLeagues))
								<option value="{{ $data->getLeagues->id }}" selected>{{ $data->getLeagues->name }}</option>
							@endif
						</select>
					</div>
                    <div class="form-group">
                        <label>Division Name</label>
                        <input type="text" value="{{ $data->divisions_name }}" class="form-control" name="divisions_name" required />
                    </div>
					<div class="row">
						<div class="col-md-8 form-group">
							<label>Picture</label>
							<input type="file" class="form-control" name="picture" />
						</div>
						<div class="col-md-4">
							@if(!empty($data->picture))
								<img src="{{ $data->picture }}" class="img-responsive" />
							@endif
						</div>
					</div>
					<div class="form-group">
						<label>Select Team</label>
						<select class="form-control" id="team_id" name="team_id[]" multiple>
							@if(!empty($data->getDivisionTeams))
								@foreach($data->getDivisionTeams as $teams)
									<option value="{{ $teams->getTeam->id }}" selected>{{ $teams->getTeam->team_name }}</option>
								@endforeach
							@endif
						</select>
					</div>
                </div>
                <div class="box-footer">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                    <input type="hidden" name="id" value="{{ $data->id }}" />
                    <input type="submit" class="btn btn-primary" value="Update Division" />
                </div>
            </div>
        </form>
		</div>
	</div>
</section>

@include('include.filterjs')

<script>
    $(document).ready(function () {
		getleague({{ $data->getLeagues->id }});
		getTeam('#team_id');
    });
</script>

@endsection