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
        <form action="{{ url('admin/settings/slider/saveSlide') }}" method="post" enctype="multipart/form-data">
			<div class="box">
			    <!-- /.box-header -->
			    <div class="box-body">
                    @include("include.message")
                    <div class="form-group">
                        <label>Caption One</label>
                        <input type="text" class="form-control" value="{{ $data->caption_one }}" name="caption_one" />
                    </div>
                    <div class="form-group">
                        <label>Caption Two</label>
                        <input type="text" class="form-control" value="{{ $data->caption_two }}" name="caption_two" />
                    </div>
                    <div class="row">
                        <div class="form-group col-md-8">
                            <label>Slide</label>
                            <input type="file" class="form-control" name="image" />
                        </div>
                        <div class="form-group col-md-4">
                             @if(!empty($data->picture))
                                <img src="{{ $data->picture }}" style="width:100%" /> 
                            @endif
                        </div>
                    </div>
                </div>
                <div class="box-footer">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                    <input type="hidden" name="id" value="{{ $data->id }}" />
                    <input type="submit" class="btn btn-primary" value="Save Slide" />
                </div>
            </div>
        </form>
		</div>
	</div>
</section>

@endsection