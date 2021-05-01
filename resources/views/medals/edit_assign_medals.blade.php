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
        <form action="{{ url('admin/medals/saveAssignMedal') }}" method="post" enctype="multipart/form-data">
			<div class="box">
			    <!-- /.box-header -->
			    <div class="box-body">
					@include("include.message")
					<div class="form-group">
						<label>Select League</label>
						<select class="form-control" name="league_id" id="league_id">
							<?php
							if (isset($data->getLeague) && count($data->getLeague) > 0) {
							?>
							<option value="{{ @$data->getLeague->id }}">{{ @$data->getLeague->name }}</option>
							<?php
							}
							?>
						</select>
					</div>
					<div class="form-group">
                        <label>Select Division</label>
						<select class="form-control division_id" id="division_id" name="division_id">
						<?php
						if (isset($data->divisions) && count($data->divisions) > 0) {
							foreach ($data->divisions as $k => $v) {
							?>
								<option value="{{ @$v->id }}" <?= ($v->id == @$data->division_id)?'selected':''; ?>>{{ @$v->divisions_name }}</option>
							<?php
							}
						}
						?>
						</select>
                    </div>
                    <div class="form-group">
                        <label>Select Team</label>
                        <select class="form-control" name="team_id" id="getTeam">
                            <option value="{{ $data->team_id }}">{{ $data->getTeam->team_name }}</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Select Medal</label>
                        <select class="form-control" name="medal_id" id="medal_id">
                            <option value="{{ $data->medal_id }}">{{ $data->getMedal->achievement_name }}</option>
                        </select>
                    </div>
                    <div class="form-group selectuser">
                        <label>Select User</label>
                        <select class="form-control search_select" name="user_id">
                            @if(!empty($data->users))
                                @foreach($data->users as $u)
                                    <option value="{{ $data->getallUser->id }}" selected>{{ $data->getallUser->user_name }}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                </div>
                <div class="box-footer">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                    <input type="hidden" name="id" value="{{ $data->id }}" />
                    <input type="submit" class="btn btn-primary" value="Update Medal" />
                </div>
            </div>
        </form>
		</div>
	</div>
</section>

<script>
    $(document).ready(function () {
		$("#league_id").change(function(){
			$.ajax({
				url : "{{ url('/admin/division/getDivisionbyleague') }}",
				method : "POST",
				data : {
					league_id : $(this).val(),
					is_api : 1,
                	_token : "{{ csrf_token() }}"
				},
				success : function(res){
					$(".division_id").html('').trigger('change');
					$.each(res.data, function(key, value){
						var data = {
							id : value.id,
							text : value.divisions_name,
						};
						var newOption = new Option(data.text, data.id, true, true);
						$(".division_id").append(newOption).trigger('change');
					});
				}
			});
		});
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

        $("#getTeam").change(function(){
            $.ajax({
				url : "{{ url('/admin/contract/getContractByTeams') }}",
				method : "post",
				data : {
					team_id : $(this).val(),
					_token : "{{ csrf_token() }}",
					is_api : 1,
                    print_all : true,
				},
				beforeSend : function(){
					$(".selectuser").hide();
					$(".selectuser select").html("").trigger('change');
				},
				success : function(res){
					$(".selectuser").show();
					$.each(res.data, function(key, value){
						var data = {
							id : value.get_user.id,
							text : value.get_user.user_name,
						};
						var newOption = new Option(data.text, data.id, true, true);
						$(".selectuser select").append(newOption).trigger('change');
					});
				}
			});
        });
        $("#getTeam").select2({
            ajax: {
				url: "{{ url('/admin/teams/all') }}",
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
                        search_now : true,
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
        $("#medal_id").select2({
            ajax: {
				url: "{{ url('/admin/medals/all_medals') }}",
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
						results: $.map(data.data.data, function (item) {
							return {
								text: item.achievement_name,
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