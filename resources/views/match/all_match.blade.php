@extends('include.main')
@section('content')
	<section class="content-header">
        <div class="row">
		    <div class="col-xs-12">
                <div class="pull-left">
                    <h1>
                        {{ $title }}
                    </h1>
                </div>
                <div class="pull-right">
                    <div><a href="{{ url('admin/match/add_match') }}" class="btn btn-primary">Create Match</a></div>
                    <button style="margin-top: 10px" class="btn btn-danger delete_all" data-url="{{ url('admin/match/delete_all_match') }}">Delete All Selected</button>
                </div>
            </div>
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
                                <th width="50px"><input type="checkbox" id="master"></th>
                                <th>ID</th>
                                <th>League</th>
                                <th>Division</th>
                                <th>Home Team</th>
                                <th>Away Team</th>
                                <th>Match Date</th>
                                <th>Match Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                            @if(!empty($data))
                                @foreach($data as $d)
                                    <tr>
                                        <td><input type="checkbox" class="sub_chk" data-id="{{$d->id}}"></td>
                                        <td>{{ $d->id }}</td>
                                        <td>{{ $d->getLeague->name }}</td>
                                        <td>
                                            @if(!empty($d->getDivision))
                                                {{ $d->getDivision->divisions_name }}
                                            @endif
                                        </td>
                                        <td>
                                            @if(!empty($d->getTeamOne))
                                                {{ $d->getTeamOne->team_name }}
                                            @endif
                                        </td>
                                        <td>
                                            @if(!empty($d->getTeamTwo))
                                                {{ $d->getTeamTwo->team_name }}
                                            @endif
                                        </td>
                                        <td>{{ $d->match_start_date }}</td>

                                        <td>{{ $d->match_status }}</td>
                                        <td>
                                            <div class="btn-group" role="group" aria-label="...">
                                                <a href="{{ url('/admin/match/delete') }}?league_id={{ $d->league_id }}&match_id={{ $d->id }}" class="btn btn-danger">
                                                    <i class="glyphicon glyphicon-trash"></i>
                                                </a>
                                                <a href="{{ url('/admin/match/edit') }}?league_id={{ $d->league_id }}&match_id={{ $d->id }}" class="btn btn-warning">
                                                    <i class="glyphicon glyphicon-pencil"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                        <tbody>
                        </tbody>
                    </table>
                </div>
                <div class="box-footer clearfix">
                    @if(!empty($data))
                    {{ $data->appends(request()->input())->links() }}
                    <ul class="pagination pagination-sm no-margin pull-right">
                        <li>Total Records: {{ $data->total() }}</li>
                    </ul>
                    @endif
                </div>
			</div>
		</div>
	</div>
</section>

<script type="text/javascript">
    $(document).ready(function () {


        $('#master').on('click', function(e) {
         if($(this).is(':checked',true))
         {
            $(".sub_chk").prop('checked', true);
         } else {
            $(".sub_chk").prop('checked',false);
         }
        });


        $('.delete_all').on('click', function(e) {


            var allVals = [];
            $(".sub_chk:checked").each(function() {
                allVals.push($(this).attr('data-id'));
            });


            if(allVals.length <=0)
            {
                alert("Please select row.");
            }  else {


                var check = confirm("Are you sure you want to delete this row?");
                if(check == true){


                    var join_selected_values = allVals.join(",");


                    $.ajax({
                        url: $(this).data('url'),
                        type: 'POST',
                        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                        data: 'ids='+join_selected_values,
                        dataType: "json",
                        success: function (data) {
                            if (data['class']) {
                                $(".sub_chk:checked").each(function() {
                                    $(this).parents("tr").remove();
                                });
                                alert(data['message']);
                            }else {
                                alert('Whoops Something went wrong!!');
                            }
                        },
                        error: function (data) {
                            alert(data.responseText);
                        }
                    });


                  $.each(allVals, function( index, value ) {
                      $('table tr').filter("[data-row-id='" + value + "']").remove();
                  });
                }
            }
        });
    });
</script>
@endsection