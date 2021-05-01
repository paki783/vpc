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
				<form action="{{ url('/admin/tournament/saveSetGroup') }}" method="post" enctype="multipart/form-data">
					<!-- /.box-header -->
					<div class="box-header">
						<b>Set Group</b>
					</div>
					<div class="box-body" id="setgroup">
						@include("include.message")
						<?php $groupcontainer = 0; ?>
						<?php $teamcount = 0; ?>
						@if(!empty($data->getTournamentGroupbyTeam))
							@foreach($data->getTournamentGroupbyTeam as $gt)
								<?php $groupcontainer++; ?>
								<div class="col-md-6">
									<div class="team_container_<?= $groupcontainer; ?>">
										<div id="group_<?= $groupcontainer; ?>">
											<h4>Group <?= $groupcontainer; ?> <a href="javascript:void(0)" class="label label-danger" onclick="delete_group('<?= $groupcontainer; ?>')">x</a></h4>
											<?php $teamcount = 0; ?>
											@if(!empty($gt->getGroupsTeam))
												@foreach($gt->getGroupsTeam as $tt)
													<?php $teamcount++; ?>
													<div class="teamset_<?= $groupcontainer; ?>">
														<div class="form-group">
															<label>Team <?= $teamcount; ?></label>
															<select class="form-control teamcontainer_<?= $teamcount; ?>" name="team[G<?= $groupcontainer; ?>][]">
																@if(!empty($teams))
																	@foreach($teams as $t1)
																		<option value="{{ $t1->getTeam->id }}" @if($tt->team_id == $t1->getTeam->id) selected @endif>{{ $t1->getTeam->team_name }}</option>
																	@endforeach
																@endif
															</select>
														</div>
													</div>
												@endforeach
											@endif
										</div>
										<a href="javascript:void(0)" class="btn btn-block btn-primary" onclick="addteam(<?= $groupcontainer; ?>)">Add Team</a>
									</div>
								</div>
							@endforeach
						@endif
					</div>
					<div class="box-footer">
						<input type="hidden" name="_token" value="{{ csrf_token() }}" />
						<a href="javascript:void(0)" onclick="addgroup()" class="btn btn-primary btn-block">Add Group</a>
						<input type="hidden" name="tournament_id" value="{{ $tournament_id }}" />
						<input type="submit" class="btn btn-success btn-block" value="Save Changes" />
					</div>
				</form>
			</div>
		</div>
	</div>
</section>

<?php $teams = json_encode($teams); ?>
<script>
	function delete_group(groupid){
		groupcontainer--;
		teamcountid--;
		$(".team_container_"+groupid).remove();
	}
	function addteam(id, selected){
		var team_count = $("#group_"+id).find('.teamset_'+id).length;
		if({!! $teams !!}.length > 0){
            team_count++;
            var param = {
                teamcount : team_count,
                id : id,
            };
			
            var html = {"<>":"div","class":"teamset_${id}","html":[
				{"<>":"div","class":"form-group","html":[
                	{"<>":"label","html":"Team ${teamcount}"},
                	{"<>":"select","class":"form-control teamcontainer_${teamcount}","name":"team[G${id}][]","html":""}
				]},
            ]};
            $("#group_"+id).json2html(param, html);
            $.each({!! $teams !!}, function(key, value){
				console.log(value);
                $(".teamcontainer_"+param.teamcount).append("<option value='"+value.get_team.id+"'>"+value.get_team.team_name+"</option>")
                if(selected != undefined){
                    $(".teamcontainer_"+param.teamcount).val(selected);
                }
            });
		}
	}
	function addgroup(){
		groupcontainer++;
        teamcountid++;
		var param = {
            count : groupcontainer,
            teamcountid : teamcountid,
        };
		var html = {"<>":"div","class":"col-md-6","html":[
			{"<>":"div","class":"team_container_${count}","html":[
				{"<>":"div","id":"group_${count}","html":[
					{"<>":"h4","html":[
						{"<>":"span","html":"Group ${count}"},
						{"<>":"a","href":"javascript:void(0)","class":"label label-danger", "onclick":function(){
							delete_group(param.count);
						},"html":"x"}
					]},
					{"<>":"div","id":"teamset_${teamcountid}","html":""},
				]},
				{"<>":"a","href":"javascript:void(0)","class":"btn btn-primary btn-block","onclick":function(){
					addteam(param.teamcountid);
				},"html":"Add Team"}
			]}
		]};
		$("#setgroup").json2html(param, html);
	}
    var groupcontainer = <?= $groupcontainer; ?>;
    var teamcountid = <?= $groupcontainer; ?>;
    /*function addgroup(){
		console.log(groupcontainer);
        groupcontainer++;
        teamcountid++;
        var param = {
            count : groupcontainer,
            teamcountid : teamcountid,
        };
        var html = {"<>":"div","class":"col-md-6","id":"group_${count}","html":[
            {"<>":"h4","html":[
				{"<>":"span","html":"Group ${count}"},
				{"<>":"a","href":"javascript:void(0)","class":"label label-danger","onclick":function(){
                	delete_group(param.count);
            	},"html":"x"}
			]},
            {"<>":"div","id":"teamset_${teamcountid}","html":""},
            {"<>":"a","href":"javascript:void(0)","class":"btn btn-primary btn-block","onclick":function(){
                addTeam(param.teamcountid);
            },"html":"Add Team"}
        ]}
        $("#setgroup").json2html(param, html);
    }
    function addTeam(id, selected){
        var teamcount = $("#teamset_"+id).children().length;
		teamcount++;
		console.log(teamcount);
		/*console.log(teamcount);
        var data = [];
        if({!! $teams !!}.length > 0){
            teamcount++;
            var param = {
                teamcount : teamcount,
                id : id,
            };
            let transform = {'<>':'option','value':'${id}',"html":"${text}"};
            var html = {"<>":"div","class":"form-group","html":[
                {"<>":"label","html":"Team ${teamcount}"},
                {"<>":"select","class":"form-control teamcontainer_${teamcount}","name":"team[G${id}][]","html":""}
            ]};
            $(".teamset_"+id).json2html(param, html);
            $.each({!! $teams !!}, function(key, value){
                $(".teamcontainer_"+param.teamcount).append("<option value='"+value.get_team.id+"'>"+value.get_team.team_name+"</option>")
                if(selected != undefined){
                    $(".teamcontainer_"+param.teamcount).val(selected);
                }
            });
        }else{ 
            alert("No Teams found");
        }
    }*/
    $(document).ready(function () {

    });
</script>
@endsection