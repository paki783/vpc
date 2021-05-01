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
        <form action="{{ url('/admin/vpc_system/saveVpcSystem') }}" method="post" enctype="multipart/form-data">
			<div class="box">
			    <!-- /.box-header -->
			    <div class="box-body">
                    @include("include.message")
                    <div class="form-group">
                        <label>System Name</label>
                        <input type="text" class="form-control" value="{{ $data->syste_name }}" name="syste_name" />
                    </div>
                    <div class="form-group">
                        <label>Regions</label>
                        <select name="region" class="search_select form-control">
                            @foreach($regions as $r)
                                <option value="{{ $r }}" @if($data->region == $r) selected @endif>{{ $r }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Select Country</label>
                        <select name="country_id" class="search_select form-control">
                            <option value="">-- Select Country --</option>
                            @foreach($countries as $c)
                                <option value="{{ $c->id }}" @if($data->country_id == $c->id) selected @endif>{{ $c->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Platform</label>
                        <select name="plateform[]" class="search_select form-control" multiple>
                            @if(!empty($plateforms))
                                @foreach($plateforms as $r)
                                    <option value="{{ $r->id }}" @if(in_array($r->id, $selected_plateformsid)) selected @endif>{{ $r->plateform_name }}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label>Assign User</label>
                        <select name="assign_user[]" class="search_select form-control" multiple>
                            @if(!empty($users))
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}" @if(in_array($user->id, $selected_assign_usersid)) selected @endif>{{ $user->user_name }}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label>Game</label>
                        <select name="game" class="search_select form-control" style="width: 100%;">
                            @if(!empty($game))
                                @foreach($game as $r)
                                    <option value="{{ $r->id }}" @if($data->game == $r->id) selected @endif>{{ $r->game_name }}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                </div>
                <div class="box-footer">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                    <input type="hidden" name="id" value="{{ $data->id }}" />
                    <input type="submit" class="btn btn-primary" value="Update System" />
                </div>
            </div>
        </form>
		</div>
	</div>
</section>

@endsection