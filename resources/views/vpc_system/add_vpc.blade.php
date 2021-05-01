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
                        <input type="text" class="form-control" value="{{ old('syste_name') }}" name="syste_name" />
                    </div>
                    <div class="form-group">
                        <label>Regions</label>
                        <select name="region" class="search_select form-control">
                            @foreach($regions as $r)
                                <option value="{{ $r }}" @if(old('region') == $r) selected @endif>{{ $r }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Select Country</label>
                        <select name="country_id" class="search_select form-control">
                            <option value="">-- Select Country --</option>
                            @foreach($countries as $c)
                                <option value="{{ $c->id }}" @if(old('country_id') == $c->id) selected @endif>{{ $c->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Platform</label>
                        <select name="plateform[]" class="search_select form-control" multiple>
                            @if(!empty($plateforms))
                                @foreach($plateforms as $r)
                                    <option value="{{ $r->id }}" @if(old('plateform') == $r->id) selected @endif>{{ $r->plateform_name }}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Assign User</label>
                        <select name="assign_user[]" class="search_select form-control" multiple>
                            @if(!empty($users))
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}" @if(old('assign_user') == $user->id) selected @endif>{{ $user->user_name }}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Game</label>
                        <select name="game" class="search_select form-control" style="width: 100%;">
                            @if(!empty($game))
                                @foreach($game as $r)
                                    <option value="{{ $r->id }}" @if(old('game') == $r->id) selected @endif>{{ $r->game_name }}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                </div>
                <div class="box-footer">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                    <input type="hidden" name="id" value="0" />
                    <input type="submit" class="btn btn-primary" value="Save System" />
                </div>
            </div>
        </form>
		</div>
	</div>
</section>

@endsection