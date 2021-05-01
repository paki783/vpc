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
            <form action="{{ url('admin/teams/save_teams') }}" method="post" enctype="multipart/form-data">
			    <!-- /.box-header -->
			    <div class="box-body">
                    @include("include.message")
                    <div class="form-group">
						<label>Team Name</label>
						<input type="text" class="form-control" required name="team_name" />
					</div>
					<div class="form-group">
						<label>Team Abbrivation</label>
						<input type="text" class="form-control" required name="team_abr" />
					</div>
					<div class="form-group">
                        <label>Select Country</label>
                        <select name="country_id" class="search_select form-control">
                            @foreach($countries as $c)
                                <option value="{{ $c->id }}" @if(old('country_id') == $c->id) selected @endif>{{ $c->name }}</option>
                            @endforeach
                        </select>
                    </div>
					<div class="form-group">
						<label>Managers</label>
						<select name="manager[]" class="form-control search_select" required multiple>
							@if(!empty($users))
								@foreach($users as $u)
									<option value="{{ $u->id }}">{{ $u->email }}</option>
								@endforeach
							@endif
						</select>
					</div>
					<div class="form-group">
						<label>Team Logo</label>
						<input type="file" name="team_logo" class="form-control" required />
					</div>
					<div class="form-group">
						<label>Team Banner</label>
						<input type="file" name="team_banner" class="form-control" />
					</div>
                </div>
                <div class="box-footer clearfix">
					<input type="hidden" value="0" name="id" />
					<input type="hidden" value="{{ csrf_token() }}" name="_token" />
                    <input type="submit" class="btn btn-primary" value="Save Teams" />
                </div>
            </form>
			</div>
		</div>
	</div>
</section>

@endsection