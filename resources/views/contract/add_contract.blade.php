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
        <form action="{{ url('admin/contract/saveContract') }}" class="validate" method="post" enctype="multipart/form-data">
			<div class="box">
			    <!-- /.box-header -->
			    <div class="box-body">
                    @include("include.message")
                    <div class="form-group">
						<label>Select User</label>
						<select name="user_id" class="form-control user_id"></select>
					</div>
					<div class="form-group">
						<label>Select League</label>
						<select name="league_id" onchange="getdivisionbyleague(this.value, '#division_id')" class="form-control" id="league_id"></select>
					</div>
					<div class="form-group">
						<label>Select Division</label>
						<select name="division_id" class="form-control" id="division_id"></select>
					</div>
					<div class="form-group">
						<label>Select Team</label>
						<select name="team_id" onchange="getManagerbyTeam(this.value, '#manager_id')" class="team_id form-control"></select>
					</div>
					<div class="form-group">
						<label>Select Manager</label>
						<select name="manager_id" id="manager_id" class="form-control"></select>
					</div>
					<div class="form-group">
						<label>Wage</label>
						<input type="text" class="form-control" name="wage" />
					</div>
					<div class="form-group">
						<label>Release Clause</label>
						<input type="text" class="form-control" name="release_clause" />
					</div>
					<div class="form-group">
						<label>Total Matches</label>
						<input type="text" class="form-control" name="total_matches" />
					</div>
					<div class="form-group">
						<label>Matches Played</label>
						<input type="text" class="form-control" name="matches_played" />
					</div>
                </div>
                <div class="box-footer">
					<input type="hidden" name="_token" value="{{ csrf_token() }}" />
                    <input type="hidden" name="id" value="0" />
                    <input type="submit" class="btn btn-primary" value="Add Contract" />
                </div>
            </div>
        </form>
		</div>
	</div>
</section>

@include('include.filterjs')
<script>
	$(document).ready(function () {
		$("form.validate").validate({
      rules: {
        user_id:{
          required: true
        },
        league_id : {
            required : true,
        },
        division_id : {
            required : true,
        },
        team_id : {
            required : true,
        },
        manager_id : {
            required : true,
        },
        wage: {
            required: true,
			format: {
				pattern: "[0-9]+",
				flags: "i",
				message: "can only contain 0-9"
			}
        },
        release_clause: {
            required: true,
			format: {
				pattern: "[0-9]+",
				flags: "i",
				message: "can only contain 0-9"
			}
        },
        total_matches: {
            required: true,
			format: {
				pattern: "[0-9]+",
				flags: "i",
				message: "can only contain 0-9"
			}
        },
        matches_played: {
            required: true,
			format: {
				pattern: "[0-9]+",
				flags: "i",
				message: "can only contain 0-9"
			}
        },
      }, 
      messages: {
		user_id : "Select User",
		league_id : "Select League",
		division_id : "Select Division",
        team_id : "Select Team",
        manager_id : "Select manager.",
        wage  : "Number only.",
		release_clause  : "Number only.",
        total_matches  : "Number only.",
        matches_played  : "Number only.",
      },
      invalidHandler: function (event, validator) {
        //display error alert on form submit    
        },
        errorPlacement: function (label, element) { // render error placement for each input type   
          var icon = $(element).parent('.input-with-icon').children('i');
            icon.removeClass('fa fa-check').addClass('fa fa-exclamation');  

          $('<span class="error"></span>').insertAfter(element).append(label);
          var parent = $(element).parent('.input-with-icon');
          parent.removeClass('success-control').addClass('error-control');  
        },
        highlight: function (element) { // hightlight error inputs
          var icon = $(element).parent('.input-with-icon').children('i');
            icon.removeClass('fa fa-check').addClass('fa fa-exclamation');  

          var parent = $(element).parent();
          parent.removeClass('success-control').addClass('error-control'); 
        },
        unhighlight: function (element) { // revert the change done by hightlight
          var icon = $(element).parent('.input-with-icon').children('i');
      icon.removeClass("fa fa-exclamation").addClass('fa fa-check');

          var parent = $(element).parent();
          parent.removeClass('error-control').addClass('success-control'); 
        },
        success: function (label, element) {
          var icon = $(element).parent('.input-with-icon').children('i');
      icon.removeClass("fa fa-exclamation").addClass('fa fa-check');

          var parent = $(element).parent('.input-with-icon');
          parent.removeClass('error-control').addClass('success-control');

          
        }
        // submitHandler: function (form) {

        // }
      });
		getleague("#league_id");
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