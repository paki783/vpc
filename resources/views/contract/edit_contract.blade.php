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
        <form action="{{ url('admin/contract/saveContract') }}" method="post" enctype="multipart/form-data">
			<div class="box">
			    <!-- /.box-header -->
			    <div class="box-body">
                    @include("include.message")
                    <div class="form-group">
						<label>Select User</label>
						<select name="user_id" class="form-control user_id">
                            <option value="{{ $data->getUser->id }}">{{ $data->getUser->email }}</option>
                        </select>
					</div>
					<div class="form-group">
						<label>Select VPC System</label>
						<select name="vpc_system_id" class="form-control vpc_system_id">
                            <option value="{{ $data->getVPCSystem->id }}">{{ $data->getVPCSystem->syste_name }}</option>
                        </select>
					</div>
					<div class="form-group">
						<label>Select Team</label>
                        <select name="team_id" onchange="get_manager(this.value)" class="team_id form-control">
                            <option value="{{ $data->getTeam->id }}">{{ $data->getTeam->team_name }}</option>
                        </select>
					</div>
					<div class="form-group">
						<label>Wage</label>
						<input type="text" class="form-control" value="{{ $data->wage }}" name="wage" />
					</div>
					<div class="form-group">
						<label>Release Clause</label>
						<input type="text" class="form-control" value="{{ $data->release_clause }}" name="release_clause" />
					</div>
					<div class="form-group">
						<label>Total Matches</label>
						<input type="text" class="form-control" value="{{ $data->total_matches }}" name="total_matches" />
					</div>
					<div class="form-group">
						<label>Matches Played</label>
						<input type="text" class="form-control" name="matches_played" value="{{ $data->matches_played }}" />
					</div>
					<div class="form-group">
						<label>Select Manager</label>
						<select name="manager_id" class="manager_id form-control">
                            <option value="{{ $data->getManager->id }}">{{ $data->getManager->email }}</option>
                        </select>
					</div>
                </div>
                <div class="box-footer">
					<input type="hidden" name="_token" value="{{ csrf_token() }}" />
					<input type="hidden" name="manager_id" id="manager_id" value="" />
                    <input type="hidden" name="id" value="0" />
                    <input type="submit" class="btn btn-primary" value="Save Division" />
                </div>
            </div>
        </form>
		</div>
	</div>
</section>


<script>
	$(document).ready(function () {
		$(".user_id").select2({
			ajax: {
				url: "{{ url('admin/contract/uncontractuser') }}",
				dataType: 'json',
				method : "post",
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
								text: item.user_name,
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
		$(".manager_id").select2({
			ajax: {
				url: "{{ url('admin/teams/edit') }}",
				dataType: 'json',
				method : "get",
				delay: 250,
				data: function (params) {
					return {
						q: params.term, // search term
						page: params.page,
						_token : '{{ csrf_token() }}',
						is_api : 1,
						user_type : "manager",
						id : $(".team_id").val(),
					};
				},
				processResults: function (data, params) {
					return {
						results: $.map(data.data.data.data, function (item) {
							return {
								text: item.user_name,
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
	function get_manager(team_id){
		$.ajax({
			url: "{{ url('admin/teams/edit') }}",
			dataType: 'json',
			method : "get",
			delay: 250,
			data : {
				_token : '{{ csrf_token() }}',
				is_api : 1,
				id : team_id,
			},
			success : function(data){
				if(data.selectedIDS.length > 0){
					$("#manager_id").val(data.selectedIDS[0]);
				}else{
					alert("No Manager for this team found, kindly select another team");
				}
			}
		});
	}
</script>
@endsection