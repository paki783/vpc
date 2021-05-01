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
                       <input type="text" class="form-control" value="{{ old('name') }}" name="name" />
                   </div>
                   <div class="form-group">
                       <label>Statistic Abr</label>
                       <input type="text" class="form-control" value="{{ old('statistic_abr') }}" name="statistic_abr" />
                   </div>
                   <div class="form-group">
                       <label>Select Games</label>
                       <select class="form-control search_select" name="game_id">
                           @if(!empty($data))
                                @foreach($data as $d)
                                    <option value="{{ $d->id }}" @if(old('game_id') == $d->id) selected @endif>{{ $d->game_name }}</option>
                                @endforeach
                           @endif
                       </select>
                   </div>
                   <div class="form-group">
                       <label>Weight</label>
                       <input type="text" class="form-control" value="{{ old('weight') }}" name="weight" />
                   </div>
                   <div class="form-group">
                       <label>Multiplication</label>
                       <select class="form-control" name="multi">
                           <option value="yes">Yes</option>
                           <option value="no">No</option>
                       </select>
                   </div>
                </div>
                <div class="box-footer">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                    <input type="hidden" name="id" value="0" />
                    <input type="submit" class="btn btn-primary" value="Save Statistic" />
                </div>
            </div>
        </form>
		</div>
	</div>
</section>

@endsection