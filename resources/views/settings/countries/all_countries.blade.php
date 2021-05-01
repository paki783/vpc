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
			    <!-- /.box-header -->
			    <div class="box-body">
                    @include("include.message")
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Country Name</th>
                                <th>Country Code</th>
                                <th>Flag</th>
                                <th>Region</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                           @if(!empty($data))
                                @foreach($data as $d)
                                    <tr>
                                        <td>{{ $d->name }}</td>
                                        <td>{{ $d->code }}</td>
                                        <td>
                                            @if(!empty($d->country_flag))
                                                <img src="{{ $d->country_flag->photo }}" style="width:30px; height:20px" />
                                            @endif
                                        </td>
                                        <td>{{ $d->region }}</td>
                                        <td>
                                            <div class="btn-group" role="group" aria-label="...">
                                                <a href="{{ url('/admin/settings/countries/edit') }}?id={{ $d->id }}" class="btn btn-warning">
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
                <div class="box-footer clearfix">
                    @if(!empty($data))
                        {{ $data->links() }}
                        <ul class="pagination pagination-sm no-margin pull-right">
                            <li>Total Records: {{ $data->total() }}</li>
                        </ul>
                    @endif
                </div>
			</div>
		</div>
	</div>
</section>

@endsection