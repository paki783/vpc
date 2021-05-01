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
			<div class="box">
            <form action="{{ url('/admin/settings/countries/saveCountry') }}" method="post" enctype="multipart/form-data">
			    <!-- /.box-header -->
			    <div class="box-body">
                    @include("include.message")
                    <div class="row">
                        <div class="col-md-12 form-group">
                            <label>Name</label>
                            <input type="text" class="form-control" value="{{ $data->name }}" name="name" />
                        </div>
                        <div class="col-md-12 form-group">
                            <label>Code</label>
                            <input type="text" class="form-control" value="{{ $data->code }}" name="code" />
                        </div>
                        <div class="col-md-12 form-group">
                            <label>Regions</label>
                            <select name="region" class="search_select form-control">
                                @foreach($regions as $r)
                                    <option value="{{ $r }}" @if($data->region == $r) selected @endif>{{ $r }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-12 form-group">
                            <div class="row">
                                <div class="col-md-8">
                                    <label>Flag</label>
                                    <input type="file" name="flag" />
                                </div>
                                <div class="col-md-4">
                                    @if(!empty($data->attachment))
                                        <img src="{{ $data->attachment->photo }}" class="img-responsive" />
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="box-footer clearfix">
                    <input type="hidden" value="{{ $menu }}" name="tournament_type" />
                    <input type="hidden" value="{{ csrf_token() }}" name="_token" />
                    <input type="hidden" value="{{ $data->id }}" name="id" />
                    <input type="submit" class="btn btn-primary" value="Save {{ ucwords($menu) }}" />
                </div>
            </form>
			</div>
		</div>
	</div>
</section>

@endsection