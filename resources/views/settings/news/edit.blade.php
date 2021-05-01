@extends('include.main')
@section('content')
	<section class="content-header">
		<div class="pull-left">
			<h1>
				{{ $title }}
			</h1>
		</div>
        <div class="pull-right">
            
        </div>
	</section>
	<section class="content">
	<div class="row">
		<div class="col-xs-12">
			<div class="box">
                <form action="{{ url('/admin/settings/news/saveNews') }}" method="post" enctype="multipart/form-data">
		    	    <!-- /.box-header -->
                    <div class="box-body">
                        @include("include.message")
                        <div class="row">
                            <div class="col-md-12 form-group">
                                <label>Title</label>
                                <input type="text" class="form-control" name="news_title" value="{{ $data->title }}" placeholder="News Title" />
                            </div>
                            <div class="col-md-12 form-group">
                                <label>Description</label>
                                <textarea class="form-control" name="news_desc" placeholder="Description">{{ $data->desc }}</textarea>
                            </div>
                            <div class="row">
                                <div class="col-md-8 form-group">
                                    <label>Image</label>
                                    <input type="file" class="form-control" name="image" placeholder="Description" />
                                </div>
                                <div class="col-md-4 form-group">
                                    @if(!empty($data->image))
                                        <img src="{{ $data->image }}" style="width:100%" />
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="box-footer">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                        <input type="hidden" name="id" value="{{ $data->id }}" />
                        <input type="submit" value="Save News" class="btn btn-primary">
                    </div>
                </form>
			</div>
		</div>
	</div>
</section>

@endsection