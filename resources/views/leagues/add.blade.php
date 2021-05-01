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
        <form action="{{ url('admin/league/saveleague') }}" method="post" enctype="multipart/form-data">
			<div class="box">
			    <!-- /.box-header -->
			    <div class="box-body">
                    @include("include.message")
                    <div class="form-group">
                        <label>Name</label>
                        <input type="text" class="form-control" name="name" />
                    </div>
                    <div class="form-group">
                        <label>Description</label>
                        <textarea class="form-control" name="description"></textarea>
                    </div>
                    <div class="form-group">
                        <label>VPC System</label>
                        @if(!empty($VPCSystems))
                            <select name="vpc_systemid" class="form-control search_select">
                                @foreach($VPCSystems as $system)
                                    <option value="{{ $system->id }}">{{ $system->syste_name }}</option>
                                @endforeach
                            </select>
                        @endif
                    </div>
                    <div class="form-group">
                        <label>Logo</label>
                        <input type="file" name="logo" class="form-control" />
                    </div>
                    <div class="form-group">
                        <label>Banner</label>
                        <input type="file" name="banner" class="form-control" />
                    </div>
                    <div class="form-group">
                        <label>Game Mode</label>
                        @if(!empty($modes))
                            <select name="modeid[]" class="form-control search_select" multiple>
                                @foreach($modes as $system)
                                    <option value="{{ $system->id }}">{{ $system->mode_name }}</option>
                                @endforeach
                            </select>
                        @endif
                    </div>
                    <div class="form-group">
                        <label>Seasons</label>
                        <select name="seasons[]" class="form-control search_select" multiple>
                            @for($i = 1; $i<=20; $i++)
                                <option value="Season {{ $i }}">Season {{ $i }}</option>
                            @endfor
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Rules</label>
                        <input type="file" name="rules" class="form-control" />
                    </div>
                </div>
                <div class="box-footer">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                    <input type="hidden" value="league" name="tournament_type" />
                    <input type="hidden" name="id" value="0" />
                    <input type="submit" class="btn btn-primary" value="Save League" />
                </div>
            </div>
        </form>
		</div>
	</div>
</section>

@endsection