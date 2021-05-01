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
        <form action="{{ url('admin/leagues/saveleague') }}" method="post" enctype="multipart/form-data">
			<div class="box">
			    <!-- /.box-header -->
			    <div class="box-body">
                    @include("include.message")
                    <div class="form-group">
                        <label>Title</label>
                        <input type="text" class="form-control" value="{{ $data->league_name }}" name="league_name" />
                    </div>
                    <div class="form-group">
                        <label>Desc</label>
                        <input type="text" class="form-control" value="{{ $data->league_description }}" name="league_desc" />
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-8">
                                <label>Logo</label>
                                <input type="file" class="form-control" name="league_logo" />   
                                <small>If you want to replace the league logo add file other leave it blank</small>
                            </div>
                            <div class="col-md-4">
                                @if(!empty($data->logo))
                                    <img src="{{ $data->logo }}" class="img-responsive" /> 
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Region</label>
                        <select name="league_region" class="form-control">
                            @if(!empty($regions))
                                @foreach($regions as $r)
                                    <option value="{{ $r }}" @if($data->league_region == $r) selected @endif>{{ $r }}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                    <div class="form-group">
                        <div class="row">
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
                        </div>
                    </div>
                </div>
                <div class="box-footer">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                    <input type="hidden" value="league" name="tournament_type" />
                    <input type="hidden" name="id" value="{{ $data->id }}" />
                    <input type="submit" class="btn btn-primary" value="Save League" />
                </div>
            </div>
        </form>
		</div>
	</div>
</section>

@endsection