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
                        <input type="text" class="form-control" name="leaderboard_name" value="{{ old('leaderboard_name') }}" />
                    </div>
                    <div class="form-group">
                        <label>Select Static</label>
                        <select class="form-control" name="static_id[]" id="static_id" multiple></select>
                    </div>
                    <div class="form-group">
                        <label>Select League</label>
                        <select class="form-control" name="league_id[]" id="league_id" multiple></select>
                    </div>
                    <div class="form-group">
                        <label>Select Position</label>
                        <select class="form-control" name="position_id[]" id="position_id" multiple></select>
                    </div>
                </div>
                <div class="box-footer">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                    <input type="hidden" name="id" value="0" />
                    <input type="submit" class="btn btn-primary" value="Add Leaderboard" />
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