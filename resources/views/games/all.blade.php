@extends('include.main')
@section('content')
	<section class="content-header">
		<div class="pull-left">
			<h1>
				{{ $title }}
			</h1>
		</div>
        <div class="pull-right">
            <a href="{{ url('admin/games/add_game') }}" class="btn btn-primary">Add Games</a>
        </div>
	</section>
	<section class="content">
	<div class="row">
		<div class="col-xs-12">
			<div class="box">
                <!-- /.box-header -->
                 @include("include.searchform", ["placeholder" => "Search By Game name or id"])
			    <div class="box-body">
                    @include("include.message")
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Game Logo</th>
                                <th>Game Title</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(!empty($data))
                                @foreach($data as $d)
                                    <tr>
                                        <td>{{ $d->id }}</td>
                                        <td>
                                            @if(!empty($d->game_logo))
                                                <img src="{{ $d->game_logo }}" style="height: 50px; with:50px" /> 
                                            @endif
                                        </td>
                                        <td>{{ $d->game_name }}</td>
                                        <td>
                                            <div class="btn-group" role="group" aria-label="...">
                                                <a href="{{ url('admin/games/delete') }}?id={{ $d->id }}" class="btn btn-danger">
                                                    <i class="glyphicon glyphicon-trash"></i>
                                                </a>
                                                <a href="{{ url('admin/games/edit') }}?id={{ $d->id }}" class="btn btn-warning">
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
        var options = {

        url: "{{ url('/admin/games/all_game') }}",
        ajaxSettings: {
            dataType: "json",
            method: "get",
            data: {
                dataType: "json",
                is_api : 1,
                search_now : true,
            }
        },
        getValue: "game_name",

        list: {
            match: {
            enabled: true
            }
        },

        theme: "square"
        };

        $("#game_name").easyAutocomplete(options);
    });
</script>

@endsection