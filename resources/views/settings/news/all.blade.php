@extends('include.main')
@section('content')
	<section class="content-header">
		<div class="pull-left">
			<h1>
				All {{ $title }}
			</h1>
		</div>
        <div class="pull-right">
            <a href="{{ url('/admin/settings/news/add') }}" class="btn btn-primary">Add News</a>
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
                                <th>News Title</th>
                                <th>News Desc</th>
                                <th>Image</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(!empty($data))
                                @foreach($data as $n)
                                    <tr>
                                        <td>{{ $n->title }}</td>
                                        <td>{{ $n->desc }}</td>
                                        <td>
                                            @if(!empty($n->image))
                                                <img src="{{ $n->image }}" style="width:50px; height:50px" />
                                            @endif
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group" aria-label="...">
                                                <a href="{{ url('/admin/settings/news/deleteNews') }}?id={{ $n->id }}" class="btn btn-danger">
                                                    <i class="glyphicon glyphicon-trash"></i>
                                                </a>
                                                <a href="{{ url('/admin/settings/news/edit') }}?id={{ $n->id }}" class="btn btn-warning">
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