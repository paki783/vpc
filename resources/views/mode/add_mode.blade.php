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
        <form action="{{ url('admin/modes/saveMode') }}" method="post" enctype="multipart/form-data">
			<div class="box">
			    <!-- /.box-header -->
			    <div class="box-body">
                    @include("include.message")
                    <div class="form-group">
                        <label>Mode Title</label>
                        <input type="text" class="form-control" value="{{ old('mode_name') }}" name="mode_name" />
					</div>
					<div class="form-group">
						<label>Select Game</label>
						<select name="gameids[]" class="form-control search_select" multiple>
							@if(!empty($games))
								@foreach($games as $g)
									<option value="{{ $g->id }}">{{ $g->game_name}}</option>
								@endforeach
							@endif
						</select>
					</div>
                </div>
                <div class="box-footer">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                    <input type="hidden" name="id" value="0" />
                    <input type="submit" class="btn btn-primary" value="Save Mode" />
                </div>
            </div>
        </form>
		</div>
	</div>
</section>

@endsection