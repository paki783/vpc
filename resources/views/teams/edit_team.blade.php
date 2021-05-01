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
						<input type="text" class="form-control" required value="{{ $data->team_name }}" name="team_name" />
					</div>
					<div class="form-group">
						<label>Team Abbrivation</label>
						<input type="text" class="form-control" required value="{{ $data->team_abbrivation }}" name="team_abr" />
					</div>
					<div class="form-group">
                        <label>Select Country</label>
                        <select name="country_id" class="search_select form-control">
                            @foreach($countries as $c)
                                <option value="{{ $c->id }}" @if($data->country_id == $c->id) selected @endif>{{ $c->name }}</option>
                            @endforeach
                        </select>
                    </div>
					<div class="form-group">
						<label>Managers</label>
						<select name="manager[]" class="form-control search_select" required multiple>
							@if(!empty($users))
								@foreach($users as $u)
									<option value="{{ $u->id }}" @if(in_array($u->id, $selectedIDS)) selected @endif>{{ $u->email }}</option>
								@endforeach
							@endif
						</select>
					</div>
					<div class="row">
                        <div class="col-md-8 form-group">
                            <label>Team Logo</label>
						    <input type="file" name="team_logo" class="form-control" />
                        </div>
                        <div class="col-md-4 form-group">
                            @if(!empty($data->team_logo))
                                <img src="{{ $data->team_logo }}" style="width:100%" /> 
                            @endif
                        </div>
					</div>
					<div class="row">
                        <div class="col-md-8 form-group">
                            <label>Team Banner</label>
						    <input type="file" name="team_banner" class="form-control" />
                        </div>
                        <div class="col-md-4 form-group">
                            @if(!empty($data->team_banner))
                                <img src="{{ $data->team_banner }}" style="width:100%" /> 
                            @endif
                        </div>
					</div>
                </div>
                <div class="box-footer clearfix">
					<input type="hidden" value="{{ $data->id }}" name="id" />
					<input type="hidden" value="{{ csrf_token() }}" name="_token" />
                    <input type="submit" class="btn btn-primary" value="Update Teams" />
                </div>
            </form>
			</div>
		</div>
	</div>
</section>

@endsection