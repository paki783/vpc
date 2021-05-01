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
        <form action="{{ url('admin/awards/saveAward') }}" method="post" enctype="multipart/form-data">
			<div class="box">
			    <!-- /.box-header -->
			    <div class="box-body">
                    @include("include.message")
                    <div class="form-group">
                        <label>Award Name</label>
                        <input type="text" class="form-control" value="{{ $data->achievement_name }}" name="award_name" />
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Logo</label>
                                <input type="file" class="form-control" name="award_logo" />
                            </div>
                        </div>
                        <div class="col-md-6">
                            @if(!empty($data->getPicture))
                                <img src="{{ $data->getPicture->photo }}" class="img-responsive" />
                            @endif
                        </div>
                    </div>
                </div>
                <div class="box-footer">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                    <input type="hidden" name="id" value="{{ $data->id }}" />
                    <input type="submit" class="btn btn-primary" value="Update Award" />
                </div>
            </div>
        </form>
		</div>
	</div>
</section>

@endsection