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
        <form action="{{ url('/admin/position/savePosition') }}" method="post" enctype="multipart/form-data">
			<div class="box">
			    <!-- /.box-header -->
			    <div class="box-body">
                    @include("include.message")
                   <div class="form-group">
                       <label>Postion Name</label>
                       <input type="text" class="form-control" value="{{ $position->name }}" name="name" />
                   </div>
                   <div class="form-group">
                       <label>Postion Abr</label>
                       <input type="text" class="form-control" value="{{ $position->position_abr }}" name="position_abr" />
                   </div>
                   <div class="form-group">
                       <label>Select Games</label>
                       <select class="form-control search_select" name="game_id">
                           @if(!empty($data))
                                @foreach($data as $d)
                                    <option value="{{ $d->id }}" @if($position->game_id == $d->id) selected @endif>{{ $d->game_name }}</option>
                                @endforeach
                           @endif
                       </select>
                   </div>
                </div>
                <div class="box-footer">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                    <input type="hidden" name="id" value="{{ $position->id }}" />
                    <input type="submit" class="btn btn-primary" value="Update Position" />
                </div>
            </div>
        </form>
		</div>
	</div>
</section>

@endsection