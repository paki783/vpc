<script>
    function getleague(selected){
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
                    if(selected != undefined){
                        $("#league_id").val(selected);
                    }
				},
				cache: true
			},
			minimumInputLength: 2,
		});
    }
    function getLeaguebyid_forseason(id, selector){
        $.ajax({
            url : "{{ url('/admin/league/edit') }}",
            method : "get",
            data : {
                id : id,
                is_api : 1,
            },
            success : function (res){
                $(selector).html('');
                var data = [];
                console.log(res.data.data.get_seasons);
                if(res.data.data.get_seasons.length > 0){
                    $.each(res.data.data.get_seasons, function(key, value){
                        var data = {
                            id : value.season,
                            text : value.season,
                        };
                        var newOption = new Option(data.text, data.id, false, false);
                        $(selector).append(newOption).trigger('change');
                    })
                }
            }
        });
    }
    function getdivisionbyleague(id, selector, selected){
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
                $(selector).html('').trigger('change');
                $.each(res.data, function(key, value){
                    var data = {
                        id : value.id,
                        text : value.divisions_name,
                    };
                    var newOption = new Option(data.text, data.id, false, false);
                    $(selector).append(newOption).trigger('change');
                });
                if(selected != undefined){
                    $(selector).val(selected).trigger('change');
                }
            }
        });
    }
    function getTeam(selector){
        $(selector).select2({
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
								text: item.team_name+" - "+item.get_country.name,
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
    }
	function getMode(selector){
		$(selector).select2({
			ajax: {
				url: "{{ url('/admin/ajax/searchMode') }}",
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
								text: item.mode_name,
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
	}
	function getStatic(selector){
        $(selector).select2({
			ajax: {
				url: "{{ url('/admin/statistic/all_statistic') }}",
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
    }
	function getPosition(selector){
        $(selector).select2({
			ajax: {
				url: "{{ url('/admin/position/all_position') }}",
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
    }
	function getVPCSystem(selector){
        $(selector).select2({
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
                        search_now : true,
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
    }
</script>