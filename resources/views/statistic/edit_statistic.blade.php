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
        <form action="{{ url('/admin/statistic/saveStatistic') }}" method="post" enctype="multipart/form-data">
			<div class="box">
			    <!-- /.box-header -->
			    <div class="box-body">
                    @include("include.message")
                   <div class="form-group">
                       <label>Statistic Name</label>
                       <input type="text" class="form-control" value="{{ $data->name }}" name="name" />
                   </div>
                   <div class="form-group">
                       <label>Statistic Abr</label>
                       <input type="text" class="form-control" value="{{ $data->statistic_abr }}" name="statistic_abr" />
                   </div>
                   <div class="form-group">
                       <label>Select Games</label>
                       <select class="form-control search_select" name="game_id">
                           @if(!empty($games))
                                @foreach($games as $d)
                                    <option value="{{ $d->id }}" @if($data->game_id == $d->id) selected @endif>{{ $d->game_name }}</option>
                                @endforeach
                           @endif
                       </select>
                   </div>
                   <div class="form-group">
                       <label>Weight</label>
                       <input type="text" class="form-control" value="{{ $data->weight }}" name="weight" />
                   </div>
                   <div class="form-group">
                       <label>Multiplication</label>
                       <select class="form-control" name="multi">
                           <option value="yes" @if($d->multiplication == "yes") selected @endif>Yes</option>
                           <option value="no" @if($d->multiplication == "no") selected @endif>No</option>
                       </select>
                   </div>
                </div>
                <div class="box-footer">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                    <input type="hidden" name="id" value="{{ $data->id }}" />
                    <input type="submit" class="btn btn-primary" value="Update Statistic" />
                </div>
            </div>
        </form>
		</div>
	</div>
</section>

@endsection