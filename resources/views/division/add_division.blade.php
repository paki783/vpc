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
						<select class="form-control" name="league_id" id="league_id"></select>
					</div>
                    <div class="form-group">
                        <label>Division Name</label>
                        <input type="text" class="form-control" name="divisions_name" required />
                    </div>
					<div class="form-group">
						<label>Picture</label>
						<input type="file" class="form-control" name="picture" required />
					</div>
					<div class="form-group">
						<label>Select Team</label>
						<select class="form-control" id="team_id" name="team_id[]" multiple>
						</select>
					</div>
                </div>
                <div class="box-footer">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                    <input type="hidden" name="id" value="0" />
                    <input type="submit" class="btn btn-primary" value="Save Division" />
                </div>
            </div>
        </form>
		</div>
	</div>
</section>
<script>
    $(document).ready(function () {
		getleague();
		getTeam('#team_id');
    });
</script>

@endsection