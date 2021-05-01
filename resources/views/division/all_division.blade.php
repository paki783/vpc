@extends('include.main')
@section('content')
	<section class="content-header">
		<div class="pull-left">
			<h1>
				{{ $title }}
			</h1>
		</div>
        <div class="pull-right">
            <a href="{{ url('admin/division/add_division') }}" class="btn btn-primary">Add Division</a>
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
                                <th>Logo</th>
                                <th>Division Name</th>
                                <th>League Name</th>
                                <th>Teams</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(!empty($data))
                                @foreach($data as $d)
                                    <tr>
                                        <td>{{ $d->id }}</td>
                                        <td>
                                            @if(!empty($d->picture))
                                                <img src="{{ $d->picture }}" style="width:50px; height:50px" />
                                            @endif
                                        </td>
                                        <td>{{ $d->divisions_name }}</td>
                                        <td>
                                            @if(!empty($d->getLeagues))
                                                {{ $d->getLeagues->name }}
                                            @endif
                                        </td>
                                        <td>
                                            @if(!empty($d->getDivisionTeams))
                                                <?php $count = 0; ?>
                                                @foreach($d->getDivisionTeams as $teams)
                                                    @if($count == 0)
                                                        {{ $teams->getTeam->team_name }}
                                                    @else
                                                        , {{ $teams->getTeam->team_name }}
                                                    @endif
                                                    <?php $count = 1; ?>
                                                @endforeach
                                            @endif
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group" aria-label="...">
                                                <a href="{{ url('admin/division/delete') }}?id={{ $d->id }}" class="btn btn-danger">
                                                    <i class="glyphicon glyphicon-trash"></i>
                                                </a>
                                                <a href="{{ url('admin/division/edit') }}?id={{ $d->id }}" class="btn btn-warning">
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

        url: "{{ url('/admin/division/all_division') }}",
        ajaxSettings: {
            dataType: "json",
            method: "get",
            data: {
                dataType: "json",
                is_api : 1,
                search_now : true,
            }
        },
        getValue: "divisions_name",

        list: {
            match: {
            enabled: true
            }
        },

        theme: "square"
        };

        $("#divisions_name").easyAutocomplete(options);
    });
</script>
@endsection