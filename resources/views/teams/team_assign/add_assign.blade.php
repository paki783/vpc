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
			<div class="box">
            <form action="{{ url('admin/teams/save_assign_team') }}" method="post" enctype="multipart/form-data">
			    <!-- /.box-header -->
			    <div class="box-body">
                    @include("include.message")
                    <div class="form-group">
                        <label>League</label>
                        <select name="league_id" onchange="getSeason(this.value)" id="league_id" class="form-control">
                            
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Select Division</label>
                        <select name="division_id" id="division_id" class="form-control search_select">
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Select Team</label>
                        <select name="team_id[]" class="form-control" id="team_id" multiple></select>
                    </div>
                    <div class="form-group" id="seasons" style="display: none">
                        <label>Current Season</label>
                        <select name="current_Season" id="current_Season" class="form-control">
                            
                        </select>
                    </div>
                </div>
                <div class="box-footer clearfix">
					<input type="hidden" value="0" name="id" />
					<input type="hidden" value="{{ csrf_token() }}" name="_token" />
                    <input type="submit" class="btn btn-primary" value="Assign Teams" />
                </div>
            </form>
			</div>
		</div>
	</div>
</section>

<script>
    $(document).ready(function () {
        $("form").submit(function(e){
            if($("#league_id").val() == 0){
                alert("Kindly select league");
                e.preventDefault();
                return false;
            }
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
    function getSeason(id){
        $.ajax({
            url : "{{ url('/admin/league/edit') }}",
            method : "get",
            data : {
                id : id,
                is_api : 1,
            },
            success : function (res){
                $("#current_Season").html('');
                $("#seasons").show();
                var data = [];
                console.log(res.data.data.get_seasons);
                if(res.data.data.get_seasons.length > 0){
                    $.each(res.data.data.get_seasons, function(key, value){
                        var data = {
                            id : value.season,
                            text : value.season,
                        };
                        var newOption = new Option(data.text, data.id, false, false);
                        $('#current_Season').append(newOption).trigger('change');
                    })
                }
            }
        });
        $.ajax({
            url : "{{ url('/admin/division/getDivisionbyleague') }}",
            method : "post",
            data : {
                league_id : id,
                is_api : 1,
                _token : "{{ csrf_token() }}"
            },
            success : function (res){
                var data = [];
                $('#division_id').html('').trigger('change');
                $.each(res.data, function(key, value){
                    var data = {
                        id : value.id,
                        text : value.divisions_name,
                    };
                    var newOption = new Option(data.text, data.id, false, false);
                    $('#division_id').append(newOption).trigger('change');
                })
            }
        });
    }
</script>
@endsection