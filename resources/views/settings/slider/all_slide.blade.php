@extends('include.main')
@section('content')
	<section class="content-header">
		<div class="pull-left">
			<h1>
				All {{ $title }}
			</h1>
		</div>
        <div class="pull-right">
            <a href="{{ url('admin/settings/slider/add_slide') }}" class="btn btn-primary" >Add Slide</a>
        </div>
	</section>
	<section class="content">
	<div class="row">
		<div class="col-xs-12">
			<div class="box">
			    <!-- /.box-header -->
			    <div class="box-body">
                    @include("include.message")
                    <table class="table table-striped datatable">
                        <thead>
                            <tr>
                                <th>Caption One</th>
                                <th>Caption Two</th>
                                <th>Image</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(!empty($data))
                                @foreach($data as $d)
                                    <tr>
                                        <td>{{ $d->caption_one }}</td>
                                        <td>{{ $d->caption_two }}</td>
                                        <td>
                                            @if(!empty($d->picture))
                                                <img src="{{ $d->picture }}" style="height: 50px; with:50px" /> 
                                            @endif
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group" aria-label="...">
                                                <a href="{{ url('admin/settings/slider/deleteSlide') }}?id={{ $d->id }}" class="btn btn-danger">
                                                    <i class="glyphicon glyphicon-trash"></i>
                                                </a>
                                                <a href="{{ url('admin/settings/slider/edit') }}?id={{ $d->id }}" class="btn btn-warning">
                                                    <i class="glyphicon glyphicon-pencil"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
			</div>
		</div>
	</div>
</section>


@endsection