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
        <form action="{{ url('admin/match/createMatch') }}" method="post" enctype="multipart/form-data">
			<div class="box">
			    <!-- /.box-header -->
			    <div class="box-body">
                    @include("include.message")
                    <div class="form-group">
                        <label>League</label>
                        <select name="league_id" class="form-control search_select" onchange="getdivisionbyleague(this.value, '#division_id')">
                            <option value="0">Select League</option>
                            @if(!empty($league))
                                @foreach($league as $l)
                                    <option value="{{ $l->getLeagues->id }}" @if(old('league_id') == $l->getLeagues->id) selected @endif>{{ $l->getLeagues->name }}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Select Division</label>
                        <select class="form-control" id="division_id" name="division_id"></select>
                    </div>
                    <div class="row">
                        <div class="col-md-4 form-group">
                            <label>Start Date</label>
                            <input type="text" class="form-control single_datepicker" name="single_datepicker"/>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Days Interval</label>
                        <input type="text" class="form-control" required name="days_interval" />
                    </div>
                    <div class="form-group">
                        <label>How Many Matches Per Day</label>
                        <input type="text" class="form-control" required name="matches_per_day" />
                    </div>
                    <div class="form-group">
                        <label>Time Interval</label>
                        <div class="input-group">
                            <input type="text" class="form-control" required name="matches_time_interval" />
                            <div class="input-group-addon">- mins</div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="no_reverse">
                            <input type="checkbox" id="no_reverse" name="no_reverse" class="minimal" style="position: relative;top: 1px;" value="1">
                            Play Once
                        </label>
                    </div>
                </div>
                <div class="box-footer">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                    <input type="hidden" name="id" value="0" />
                    <input type="submit" class="btn btn-primary" value="Create Match" />
                </div>
            </div>
        </form>
		</div>
	</div>
</section>
@include('include.filterjs')
@endsection