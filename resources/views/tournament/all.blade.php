@extends('include.main')
@section('content')
	<section class="content-header">
		<div class="pull-left">
			<h1>
				{{ $title }}
			</h1>
        </div>
        <div class="pull-right">
            <a href="{{ url('/admin/tournament/add_tournament') }}" class="btn btn-primary">Add {{ ucwords($menu) }}</a>
        </div>
	</section>
	<section class="content">
	<div class="row">
		<div class="col-xs-12">
			<div class="box">
                <!-- /.box-header -->
                @include("include.searchform", ["placeholder" => "Search by tournament or vpc system"])
			    <div class="box-body">
                    @include("include.message")
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(!empty($data))
                                @foreach($data as $d)
                                    <tr>
                                        <td>{{ $d->id }}</td>
                                        <td>{{ $d->name }}</td>
                                        <td>
                                            <div class="btn-group" role="group" aria-label="...">
                                                <a href="{{ url('/admin/tournament/delete') }}?id={{ $d->id }}" class="btn btn-danger">
                                                    <i class="glyphicon glyphicon-trash"></i>
                                                </a>
                                                <a href="{{ url('/admin/tournament/edit') }}?id={{ $d->id }}" class="btn btn-warning">
                                                    <i class="glyphicon glyphicon-pencil"></i>
                                                </a>
                                                <a href="{{ url('/admin/tournament/setGroup') }}?id={{ $d->id }}" class="btn btn-primary">
                                                    Set Group
                                                </a>
                                                <a href="{{ url('/admin/tournament/genGroup') }}?id={{ $d->id }}" class="btn btn-primary">
                                                    Gen Group
                                                </a>
                                                <a href="{{ url('/admin/tournament/all_tournament_matches') }}?id={{ $d->id }}" class="btn btn-primary">
                                                    All Group Matches
                                                </a>
                                                <a href="{{ url('/admin/tournament/setBracket') }}?id={{ $d->id }}" class="btn btn-primary">
                                                    Set Bracket
                                                </a>
                                                <a href="{{ url('/admin/tournament/getMatcheByBracket') }}?id={{ $d->id }}" class="btn btn-primary">
                                                    All Bracket Matches
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

        url: "{{ url('/admin/tournament/all_tournament') }}",
        ajaxSettings: {
            dataType: "json",
            method: "get",
            data: {
                dataType: "json",
                is_api : 1,
                search_now : true,
            }
        },
        getValue: "name",

        list: {
            match: {
            enabled: true
            }
        },

        theme: "square"
        };

        $("#name").easyAutocomplete(options);
    });
</script>
@endsection