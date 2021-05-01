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
                        <input type="text" class="form-control" value="{{ old('caption_one') }}" name="caption_one" />
                    </div>
                    <div class="form-group">
                        <label>Caption Two</label>
                        <input type="text" class="form-control" value="{{ old('caption_two') }}" name="caption_two" />
                    </div>
                    <div class="form-group">
                        <label>Slide</label>
                        <input type="file" class="form-control" name="image" />
                    </div>
                </div>
                <div class="box-footer">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                    <input type="hidden" name="id" value="0" />
                    <input type="submit" class="btn btn-primary" value="Save Slide" />
                </div>
            </div>
        </form>
		</div>
	</div>
</section>

@endsection