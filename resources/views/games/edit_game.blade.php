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
        <form action="{{ url('admin/games/saveGame') }}" method="post" enctype="multipart/form-data">
			<div class="box">
			    <!-- /.box-header -->
			    <div class="box-body">
                    @include("include.message")
                    <div class="form-group">
                        <label>Game Title</label>
                        <input type="text" class="form-control" value="{{ $data->game_name }}" name="game_name" />
                    </div>
                    <div class="row">
                        <div class="form-group col-md-8">
                            <label>Logo</label>
                            <input type="file" class="form-control" name="game_logo" />
                        </div>
                        <div class="form-group col-md-4">
                            @if(!empty($data->game_logo))
                                <img src="{{ $data->game_logo }}" style="width:100%" /> 
                            @endif
                        </div>
                    </div>
                </div>
                <div class="box-footer">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                    <input type="hidden" name="id" value="{{ $data->id }}" />
                    <input type="submit" class="btn btn-primary" value="Update Game" />
                </div>
            </div>
        </form>
		</div>
	</div>
</section>

@endsection