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
        <form autocomplete="off" action="{{ url('/admin/user/manager/assignUser') }}" method="post" enctype="multipart/form-data">
			<div class="box">
			    <!-- /.box-header -->
			    <div class="box-body">
                    @include("include.message")
                    <div class="form-group">
                        <input type="text" class="form-control" disabled value="{{ $manager->email }}" />
                    </div>
                    <div class="form-group">
                        <label>Select League</label>
                        <select name="league_id" onchange="getdivisionbyleague(this.value, '#division_id')" id="league_id" class="form-control">
                        
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
                        
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Select User</label>
                        <select name="user_id" id="user_id" class="form-control">
                            
                        </select>
                    </div>
                </div>
                <div class="box-footer">
                    <input type="hidden" name="manager_id" value="{{ $manager_id }}" />
                    <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                    <input type="submit" class="btn btn-primary" value="Assign User" />
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
    })
</script>

@endsection