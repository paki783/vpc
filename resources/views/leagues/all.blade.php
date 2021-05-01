@extends('include.main')
@section('content')
	<section class="content-header">
		<div class="pull-left">
			<h1>
				{{ $title }}
			</h1>
		</div>
        <div class="pull-right">
            <a href="{{ url('admin/league/add_league') }}" class="btn btn-primary">Add League</a>
        </div>
	</section>
	<section class="content">
	<div class="row">
		<div class="col-xs-12">
			<div class="box">
                <!-- /.box-header -->
                @include("include.searchform")
			    <div class="box-body">
                    @include("include.message")
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>League Name</th>
                                <th>VPC System</th>
                                <th>League Desc</th>
                                <th>League Region</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(!empty($data))
                                @foreach($data as $d)
                                    <tr>
                                        <td>{{ $d->id }}</td>
                                        <td>{{ $d->name }}</td>
                                        <td>{{ @$d->getVPCSystem->syste_name }}</td>
                                        <td>@if(!empty($d->getSeasons))
                                            <?php $count = 0; ?>
                                            @foreach($d->getSeasons as $getP)
                                                @if($count == 0)
                                                    {{ $getP->season }}
                                                @else
                                                    , {{ $getP->season }}
                                                @endif
                                            <?php $count++; ?>
                                            @endforeach
                                        @endif</td>
                                        <td>@if(!empty($d->getTournamentMode))
                                            <?php $count = 0; ?>
                                            @foreach($d->getTournamentMode as $getP)
                                                @if($count == 0)
                                                    {{ $getP->getMode->mode_name }}
                                                @else
                                                    , {{ $getP->getMode->mode_name }}
                                                @endif
                                            <?php $count++; ?>
                                            @endforeach
                                        @endif</td>
                                        <td>
                                            @if(!empty($d->logo))
                                                <img src="{{ $d->logo }}" style="height: 50px; width:50px" /> 
                                            @endif
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group" aria-label="...">
                                                <a href="{{ url('/admin/league/delete') }}?id={{ $d->id }}" class="btn btn-danger">
                                                    <i class="glyphicon glyphicon-trash"></i>
                                                </a>
                                                <a href="{{ url('/admin/league/edit') }}?id={{ $d->id }}" class="btn btn-warning">
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
                    {{ $data->links() }}
                    <ul class="pagination pagination-sm no-margin pull-right">
                        <li>Total Records: {{ $data->total() }}</li>
                    </ul>
                </div>
			</div>
		</div>
	</div>
</section>

<script>
    $(document).ready(function () {
        $("#league_id").select2({
			ajax: {
				url: "{{ url('/admin/league/all_league') }}",
				dataType: 'json',
				method : "get",
                allowClear: true,
				delay: 250,
				data: function (params) {
					return {
						name: params.term, // search term
						page: params.page,
						_token : '{{ csrf_token() }}',
						is_api : 1,
                        search_now : true,
					};
				},
				processResults: function (data, params) {
					return {
						results: $.map(data.data, function (item) {
							return {
								text: item.name,
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