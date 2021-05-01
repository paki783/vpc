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
                    <form action="{{ url('/admin/tournament/saveGenGroup') }}" method="post" enctype="multipart/form-data">
                        <!-- /.box-header -->
                        <div class="box-header">
                            <b>Generate Group Matches</b>
                        </div>
                        <div class="box-body">
                            @include("include.message")
                            <div class="row">
                                <div class="col-md-4 form-group">
                                    <label>Start Date</label>
                                    <input type="text" required class="form-control single_datepicker" name="single_datepicker" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Days Interval</label>
                                <input type="number" class="form-control" name="days_interval" value="1" required />
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
                            <input type="hidden" name="tournament_id" value="{{ $tournament_id }}" />
                            <input type="submit" class="btn btn-primary" value="Save Changes" />
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>

@endsection