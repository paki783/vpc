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
        <form autocomplete="off" action="{{ url('/admin/user/manager/UpdateAssignUser') }}" method="post" enctype="multipart/form-data">
			<div class="box">
			    <!-- /.box-header -->
			    <div class="box-body">
                    @include("include.message")
                    <div class="form-group">
                        <input type="text" class="form-control" disabled value="{{ $data->getManager->email }}" />
                    </div>
                    <div class="form-group">
                        <label>Select League</label>
                        <select name="league_id" onchange="getdivisionbyleague(this.value, '#division_id')" id="league_id" class="form-control">
                            @if(!empty($data->getLeague))
                                <option value="{{ $data->getLeague->id }}">{{ $data->getLeague->name }}</option>
                            @endif
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Select Division</label>
                        <select name="division_id" id="division_id" class="form-control">
                            
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Select Team</label>
                        <select name="team_id" onchange="getUserByTeam(this.value, '#user_id')"  id="team_id" class="form-control">
                            @if(!empty($data->getTeam))
                                <option value="{{ $data->getTeam->id }}">{{ $data->getTeam->team_name }}</option>
                            @endif
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Select User</label>
                        <select name="user_id" id="user_id" class="form-control">
                            
                        </select>
                    </div>
                </div>
                <div class="box-footer">
                    <input type="hidden" name="id" value="{{ $data->id }}" />
                    <input type="hidden" name="manager_id" value="{{ $data->manager_id }}" />
                    <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                    <input type="submit" class="btn btn-primary" value="Update Assign User" />
                </div>
            </div>
        </form>
		</div>
	</div>
</section>
@include('include.filterjs')

<script>
    $(document).ready(function(){
        getleague("#league_id");
        getTeam("#team_id");
        @if(!empty($data->getDivision))
            getdivisionbyleague({{ $data->getLeague->id }}, '#division_id', {{ $data->getDivision->id }})
        @endif
        @if(!empty($data->getTeam))
            getUserByTeam({{ $data->getTeam->id }}, '#user_id', {{ $data->getUser->id }})
        @endif
    })
</script>

@endsection