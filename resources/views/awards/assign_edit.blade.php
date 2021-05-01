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
        <form action="{{ url('admin/awards/save_AssignAward') }}" method="post" enctype="multipart/form-data">
			<div class="box">
			    <!-- /.box-header -->
			    <div class="box-body">
                    @include("include.message")
                    <div class="form-group">
						<label>Select League</label>
						<select class="form-control" name="league_id" id="league_id">
							<option value="{{ $data->getLeague->id }}" selected>{{ $data->getLeague->name }}</option>
						</select>
					</div>
					<div class="form-group teams">
						<label>Select Division</label>
						<select class="form-control division_id" id="division_id" name="division_id">
							<?php
							if (isset($division) && @$division != '') {
								foreach ($division as $k => $v) {
							?>
								<option value="{{ $v->id }}" <?=  ($data->getDivision->id == $v->id)?'selected':''; ?>>{{ $v->divisions_name }}</option>
							<?php
								}
							}
							?>
							
						</select>
					</div>
					<div class="form-group">
                        <label>Select Team</label>
                        <select name="team_id" id="team_id" class="form-control">
							@if(!empty($data->getTeams))
								<option value="{{ $data->getTeams->id }}" <?= ($data->getTeams->id == $data->team_id)?'selected':''; ?>>{{ $data->getTeams->team_name }}</option>
                            @endif
                        </select>
                    </div>
					<div class="form-group">
						<label>Award</label>
						<select class="form-control" id="awards" name="awards">
							<option value="{{ $data->getAward->id }}" selected>{{ $data->getAward->achievement_name }}</option>
						</select>
					</div>
                </div>
                <div class="box-footer">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                    <input type="hidden" name="id" value="{{ $data->id }}" />
                    <input type="submit" class="btn btn-primary" value="Assign Award" />
                </div>
            </div>
        </form>
		</div>
	</div>
</section>

<script>
    $(document).ready(function () {
		$("#awards").select2({
			ajax: {
				url: "{{ url('/admin/awards/all_awards') }}",
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

		$("#team_id").select2({
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
    });
</script>
@endsection