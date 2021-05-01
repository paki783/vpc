@extends('include.main')
@section('content')
    <section class="content-header">
        <div class="pull-left">
            <h1>
                {{ $title }}
            </h1>
        </div>
        <div class="pull-right">
        <a href="{{ url('/admin/tournament/bracket/match/delete') }}?id=<?= @$_GET['id'] ?>" class="btn btn-danger">
            Delete All Bracket And Match <i class="glyphicon glyphicon-trash"></i>
        </a>
        </div>
    </section>
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <!-- /.box-header -->
                    @include("include.searchform", ["placeholder" => "Search by tournament or vpc system"])
                    <div class="box-body">
                        @include("include.message")
                        <table class="table table-striped">
                            <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Round</th>
                                <th>Round Stage</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                                @if(!empty($data))
                                    @foreach($data as $d)
                                        <tr>
                                            <td>{{ $d->id }}</td>
                                            <td>{{ $d->round_name }}</td>
                                            <td>{{ $d->round }}</td>
                                            <td>{{ $d->round_stage }} ({{ roundName($d->round) }})</td>
                                            <td>
                                                <div class="btn-group" role="group" aria-label="...">
                                                    <!-- <a href="{{ url('/admin/tournament/bracketMatchdelete') }}?id={{ $d->id }}" class="btn btn-danger">
                                                        <i class="glyphicon glyphicon-trash"></i>
                                                    </a> -->

                                                    <a href="{{ url('/admin/tournament/bracketMatchStagView') }}?id={{ $d->id }}&tournament={{ $d->tournament_id }}" class="btn btn-primary" target="_blank">
                                                        <i class="glyphicon glyphicon-eye-open"></i>
                                                    </a>

                                                    <!-- <a href="{{ url('/admin/tournament/bracketMatchdelete') }}?id={{ $d->id }}" class="btn btn-warning">
                                                        <i class="glyphicon glyphicon-pencil"></i>
                                                    </a> -->
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
                    <div class="box-footer clearfix">
                        @if(!empty($data))
                            {{ $data->links() }}
                            <ul class="pagination pagination-sm no-margin pull-right">
                                <li>Total Records: {{ $data->total() }}</li>
                            </ul>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection