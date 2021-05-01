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
                            <input type="text" class="form-control" name="name" />
                        </div>
                        <div class="col-md-12 form-group">
                            <label>Description</label>
                            <textarea class="form-control" name="description"></textarea>
                        </div>
                        <div class="col-md-12 form-group">
                            <label>VPC System</label>
                            <select name="vpc_systemid" class="form-control" id="getvpcsystem"></select>
                        </div>
                        <div class="col-md-12 form-group">
                            <label>Logo</label>
                            <input type="file" name="logo" class="form-control" />
                        </div>
                        <div class="col-md-12 form-group">
                            <label>Banner</label>
                            <input type="file" name="banner" class="form-control" />
                        </div>
                        <div class="col-md-12 form-group">
                            <label>Game Mode</label>
                            <select name="modeid[]" id="getMode" class="form-control" multiple></select>
                        </div>
                        <div class="col-md-12 form-group">
                            <label>Seasons</label>
                            <select name="seasons[]" class="form-control search_select" multiple>
                                @for($i = 1; $i<=20; $i++)
                                    <option value="Season {{ $i }}">Season {{ $i }}</option>
                                @endfor
                            </select>
                        </div>
                        @if($menu == "tournament")
                        <div class="col-md-12 form-group">
                            <label>Select Team</label>
                            <select class="form-select" id="team_id" name="team_id[]" multiple></select>
                        </div>
                        @endif
                        @if($menu == "league")
                        
                        @endif
                    </div>
                </div>
                <div class="box-footer clearfix">
                    <input type="hidden" value="{{ $menu }}" name="tournament_type" />
                    <input type="hidden" value="{{ csrf_token() }}" name="_token" />
                    <input type="hidden" value="0" name="id" />
                    <input type="submit" class="btn btn-primary" value="Save {{ ucwords($menu) }}" />
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