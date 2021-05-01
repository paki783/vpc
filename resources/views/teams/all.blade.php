@extends('include.main')
@section('content')
	<section class="content-header">
		<div class="pull-left">
			<h1>
				{{ $title }}
			</h1>
		</div>
        <div class="pull-right">
            <a href="{{ url('admin/teams/add_team') }}" class="btn btn-primary">Add Teams</a>
        </div>
	</section>
	<section class="content">
	<div class="row">
		<div class="col-xs-12">
			<div class="box">
                <!-- /.box-header -->
                <!-- <div class="box-header">
                    <div class="row">
                        <div class="col-md-6">
                            <form action="" method="get">
                                <div class="row">
                                    <div class="col-md-9">
                                        <select class="form-control team_id" name="team_id">
                                        </select> 
                                    </div>
                                    <div class="col-md-3">
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                                        <button type="submit" class="btn btn-default">Search Now</button>  
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div> -->
                <!-- <div class="box_header">
                    <form action="" method="get" class="form-inline">
                        <input type="text" class="form-control" name="searhitems" placeholder="Search by id or title...">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                        <button type="submit" class="btn btn-default">Search Now</button>
                    </form>
                </div> -->
                <div class="box_header">
                    <form action="" method="get" class="form-inline">
                        <input type="text" class="form-control" name="searhitems" placeholder="Search by team or team id...">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                        <button type="submit" class="btn btn-default">Search Now</button>
                    </form>
                </div>
			    <div class="box-body">
                    @include("include.message")
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Logo</th>
                                <th>Team Title</th>
                                <th>Team ABR.</th>
                                <th>Team Manager</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                        @if(!empty($data))
                            @foreach($data as $d)
                                <tr>
                                    <td>{{ $d->id }}</td>
                                    <td>
                                        @if(!empty($d->team_logo))
                                            <img src="{{ $d->team_logo }}" style="height: 50px; width:50px" /> 
                                        @endif
                                    </td>
                                    <td>{{ $d->team_name }}</td>
                                    <td>{{ $d->team_abbrivation }}</td>
                                    <td>
                                        @if(!empty($d->getTeamManager))
                                            <?php $count = 0; ?>
                                            @foreach($d->getTeamManager as $manager)
                                                @if($count == 0)
                                                    {{ $manager->getUser->email }}
                                                @else
                                                    , {{ $manager->getUser->email }}
                                                @endif
                                                <?php $count++; ?>
                                            @endforeach
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group" aria-label="...">
                                            <a href="{{ url('/admin/teams/delete') }}?id={{ $d->id }}" class="btn btn-danger">
                                                <i class="glyphicon glyphicon-trash"></i>
                                            </a>
                                            <a href="{{ url('/admin/teams/edit') }}?id={{ $d->id }}" class="btn btn-warning">
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


<script>
	$(document).ready(function () {
		$(".team_id").select2({
			ajax: {
				url: "{{ url('/admin/teams/all') }}",
				dataType: 'json',
				method : "get",
				delay: 250,
				data: function (params) {
					return {
						q: params.term, // search term
						page: params.page,
						_token : '{{ csrf_token() }}',
						is_api : 1,
					};
				},
				processResults: function (data, params) {
					return {
						results: $.map(data.data, function (item) {
							return {
								text: item.team_name,
								id: item.id
							}
						}),
						pagination: {
							more: (params.page * 30) < data.total_count
						}
					};
				},
				cache: true
			},
			minimumInputLength: 2,
		});
	});
</script>
@endsection