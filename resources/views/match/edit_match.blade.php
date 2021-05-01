@extends('include.main')
@section('content')
<style>
    .col-md-1 {
        width: 90px;
    }
</style>
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
                <!-- /.box-header -->
                <div class="box-header">
                    Team Detail
                </div>
			    <div class="box-body">
                    <div class="form-group">
                        <label>League</label>
                        <input type="text" disabled class="form-control" value="{{ $league->getLeagues->name }}" />
                    </div>
                    <div class="form-group">
                        <label>Home Team</label>
                        <input type="text" disabled class="form-control" value="{{ $data->getTeamOne->team_name }}" />
                    </div>
                    <div class="form-group">
                        <label>Away Team</label>
                        <input type="text" disabled class="form-control" value="{{ $data->getTeamTwo->team_name }}" />
                    </div>
                </div>
            </div>
		</div>
	</div>
</section>

<section class="content">
	<div class="row">
		<div class="col-xs-12">
        <form action="{{ url('admin/match/updateScore') }}" method="post" enctype="multipart/form-data">
			<div class="box">
                <!-- /.box-header -->
                <div class="box-header">
                    Score
                </div>
			    <div class="box-body">
                    @include("include.message")

                    <div class="form-group">
                        <label>Start Date</label>
                        <input type="text" class="form-control single_datepicker" value="{{ $data->match_start_date }}" name="single_datepicker" />
                    </div>

                    @if(!empty($data->getTeamOne->score))
                        <div class="form-group">
                            {{ $data->getTeamOne->team_name }} - Home Score: {{ $data->getTeamOne->score->home_score }}, Away Score: {{ $data->getTeamOne->score->away_score }}
                            <a href="javascript:void(0)" class="btn btn-primary btn-xs" onclick="scoreProof({{ $data->getTeamOne->score->id }})">View Proof</a>
                        </div>
                    @endif
                    @if(!empty($data->getTeamTwo->score))
                        <div class="form-group">
                            {{ $data->getTeamTwo->team_name }} - Home Score: {{ $data->getTeamTwo->score->home_score }}, Away Score: {{ $data->getTeamTwo->score->away_score }}
                            <a href="javascript:void(0)" class="btn btn-primary btn-xs" onclick="scoreProof({{ $data->getTeamTwo->score->id }})">View Proof</a>
                        </div>
                    @endif
                    <div class="form-group">
                        <label>Home Score</label>
                        <input type="text" class="form-control" value="{{ $data->home_score }}" name="home_score" />
                    </div>
                    <div class="form-group">
                        <label>Away Score</label>
                        <input type="text" class="form-control" value="{{ $data->away_score }}" name="away_score" />
                    </div>
                    <div class="form-group">
                        <label>Score Submit Date</label>
                        <input type="text" class="form-control single_datepicker" name="score_submission" value="{{ $data->match_start_date }}" />
                    </div>
                    <div class="form-group">
                        <label>Status</label>
                        <select class="form-control search_select" name="match_status">
                            <option value="scheduled" @if($data->match_status == "scheduled") selected @endif>{{ ucwords('Scheduled') }}</option>
                            <option value="disputed" @if($data->match_status == "disputed") selected @endif>{{ ucwords('disputed') }}</option>
                            <option value="completed" @if($data->match_status == "completed") selected @endif>{{ ucwords('completed') }}</option>
                            <option value="in progress" @if($data->match_status == "in progress") selected @endif>{{ ucwords('in progress') }}</option>
                            <option value="pending" @if($data->match_status == "pending") selected @endif>{{ ucwords('pending') }}</option>
                        </select>
                    </div>
                </div>
                <div class="box-footer">
                    <input type="hidden" value="{{ csrf_token() }}" name="_token" />
                    <input type="hidden" value="{{ $data->id }}" name="match_id" />
                    <input type="submit" class="btn btn-primary" value="Submit" />
                </div>
            </div>
        </form>
		</div>
	</div>
</section>

<section class="content">
	<div class="row">
		<div class="col-xs-12">
        <form action="{{ url('admin/match/updateScore') }}" method="post" enctype="multipart/form-data">
			<div class="box" style="width: 1270px;">
                <!-- /.box-header -->
                <div class="box-header">
                    Edit Match Data
                </div>
			    <div class="box-body">
                    <div class="row">
                        <div class="col-md-1">
                            <b>Player</b>
                        </div>
                        @if(!empty($statistic)) <?php $count = 1; ?>@foreach($statistic as $s)
                        <div class="col-md-1">
                            {{ $s->statistic_abr}}
                        </div>
                        <?php $count++; ?>
                        @endforeach @endif
                        <div class="col-md-1">
                            Proof
                        </div>
                        <div class="col-md-1">
                            Action
                        </div>
                    </div>
                    <table class="table">
                        <tr>
                            <th class="text-center">{{ $data->getTeamOne->team_name }}</th>
                        </tr>
                    </table>
                    @if(!empty($data->getTeamOne->statistic))
                        @foreach($data->getTeamOne->statistic as $statics)
                        <div class="row">
                            <div class="col-md-1 form-group">
                                <b>{{ $statics->getUser->user_name }}</b>
                            </div>
                            <?php //dd($statics->statistic); ?>
                            @if(count($statics->statistic) <= 0)
                                @for($i = 1; $i<=count($statistic); $i++)
                                    <div class="col-md-1">
                                        <input type="number" value="" class="form-control" />
                                    </div>
                                @endfor
                                <div class="col-md-1">
                                    <a href="javascript:void(0)" class="btn btn-link">
                                        <i class="fa fa-eye" aria-hidden="true"></i>
                                    </a>
                                </div>
                                <div class="col-md-1">
                                    <a href="javascript:void(0)" class="btn btn-primary">Action</a>
                                </div>
                            @else
                                @foreach($statics->statistic as $ps)
                                    <div class="col-md-1 form-group">
                                        <input type="number" value="{{ $ps->score }}" class="form-control" />
                                    </div>
                                @endforeach
                                <div class="col-md-1">
                                    <a href="javascript:void(0)" class="btn btn-link">
                                        <i class="fa fa-eye" aria-hidden="true"></i>
                                    </a>
                                </div>
                                <div class="col-md-1">
                                    <a href="javascript:void(0)" class="btn btn-primary">Action</a>
                                </div>
                            @endif
                        </div>
                        @endforeach                    
                    @endif
                    <table class="table">
                        <tr>
                            <th class="text-center">{{ $data->getTeamTwo->team_name }}</th>
                        </tr>
                    </table>
                    @if(!empty($data->getTeamTwo->statistic))
                        @foreach($data->getTeamTwo->statistic as $statics)
                        <div class="row">
                            <div class="col-md-1 form-group">
                                <b>{{ $statics->getUser->user_name }}</b>
                            </div>
                            <?php //dd($statics->statistic); ?>
                            @if(count($statics->statistic) <= 0)
                                @for($i = 1; $i<=count($statistic); $i++)
                                    <div class="col-md-1">
                                        <input type="number" value="" class="form-control" />
                                    </div>
                                @endfor
                                <div class="col-md-1">
                                    <a href="javascript:void(0)" class="btn btn-link">
                                        <i class="fa fa-eye" aria-hidden="true"></i>
                                    </a>
                                </div>
                                <div class="col-md-1">
                                    <a href="javascript:void(0)" class="btn btn-primary">Action</a>
                                </div>
                            @else
                                @foreach($statics->statistic as $ps)
                                    <div class="col-md-1 form-group">
                                        <input type="number" value="{{ $ps->score }}" class="form-control" />
                                    </div>
                                @endforeach
                                <div class="col-md-1">
                                    <a href="javascript:void(0)" class="btn btn-link">
                                        <i class="fa fa-eye" aria-hidden="true"></i>
                                    </a>
                                </div>
                                <div class="col-md-1">
                                    <a href="javascript:void(0)" class="btn btn-primary">Action</a>
                                </div>
                            @endif
                        </div>
                        @endforeach                    
                    @endif
                </div>
                <div class="box-footer">
                    
                </div>
            </div>
        </form>
		</div>
	</div>
</section>
@include('include.modal')

<script>
    function scoreProof(proofid){
        $(".modal-title").html("Loading Score Proof");
        $(".modal-body").html("Loading");
        $(".modal-footer").remove();
        $("#popup").modal("show");
        $.ajax({
            "url" : '{{ url("/admin/match/scoreProof") }}',
            'method' : "post",
            'data' : {
                _token : '{{ csrf_token() }}',
                proofid : proofid,
                is_api : 1,
            },
            success : function (res){
                if(res.status == "error"){
                    $("#popup").modal("hide");
                    swal.fire(res.status.toUpperCase(), res.message, res.status);
                }else{
                    $(".modal-title").html("Score Proof");
                    var html = [
                        {"<>":"div","class":"form-group","html":[
                            {"<>":"label","html":"Proof"},
                            {"<>":"img","src":"${photo}","class":"img-responsive","html":""}
                        ]},
                        {"<>":"div","class":"form-group","html":[
                            {"<>":"label","html":"Video Url"},
                            {"<>":"input","type":"text","class":"form-control","disabled":"true","value":"${video_url}","html":""}
                        ]}
                    ];
                    $(".modal-body").html("").json2html(res.data, html);
                    var footer = {"<>":"div","class":"modal-footer","html":[
                        {"<>":"button","type":"button","class":"btn btn-default","data-dismiss":"modal","html":"Close"},
                    ]};
                    $(".modal-content").json2html({}, footer);
                }
            }
        })
    }
</script>
@endsection