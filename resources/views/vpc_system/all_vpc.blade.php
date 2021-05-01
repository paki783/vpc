@extends('include.main')
@section('content')
	<section class="content-header">
		<div class="pull-left">
			<h1>
				{{ $title }}
			</h1>
		</div>
        <div class="pull-right">
            <a href="{{ url('admin/vpc_system/add_vpc_system') }}" class="btn btn-primary">Add VPC System</a>
        </div>
	</section>
	<section class="content">
	<div class="row">
		<div class="col-xs-12">
			<div class="box">
                <!-- /.box-header -->
                @include("include.searchform", ["placeholder" => "Search By System name or id"])
			    <div class="box-body">
                    @include("include.message")
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Logo</th>
                                <th>System Name</th>
                                <th>Game</th>
                                <th>Plateform</th>
                                <th>Region</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(!empty($data))
                                @foreach($data as $d)
                                    <tr>
                                        <td>{{ $d->id }}</td>
                                        <td>
                                            @if(!empty($d->logo))
                                                <img src="{{ $d->logo }}" style="height: 50px; width:50px" /> 
                                            @endif
                                        </td>
                                        <td>{{ $d->syste_name }}</td>
                                        <td>{{ @$d->GetGame->game_name }}</td>
                                        <td>@if(!empty($d->getVpcPlatformAssign))
                                            <?php $count = 0; ?>
                                            @foreach($d->getVpcPlatformAssign as $getP)
                                                @if($count == 0)
                                                    {{ $getP->getPlateform->plateform_name }}
                                                @else
                                                    , {{ $getP->getPlateform->plateform_name }}
                                                @endif
                                            <?php $count++; ?>
                                            @endforeach
                                        @endif</td>
                                        <td>{{ $d->region }}</td>
                                        <td>
                                            <div class="btn-group" role="group" aria-label="...">
                                                <a href="{{ url('/admin/vpc_system/delete') }}?id={{ $d->id }}" class="btn btn-danger">
                                                    <i class="glyphicon glyphicon-trash"></i>
                                                </a>
                                                <a href="{{ url('/admin/vpc_system/edit') }}?id={{ $d->id }}" class="btn btn-warning">
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
    $(document).ready(function(){
        $(".vpc_system_id").select2({
			ajax: {
				url: "{{ url('/admin/vpc_system/all') }}",
				dataType: 'json',
				method : "get",
                allowClear: true,
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
								text: item.syste_name,
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