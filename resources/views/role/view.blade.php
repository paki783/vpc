@extends('include.main')
@section('content')
	<section class="content-header">
		<div class="pull-left">
			<h1>
				{{ $title }}
			</h1>
		</div>
        <div class="pull-right">
            <a href="{{ url($add_product) }}" class="btn btn-primary">Add {{$page_heading}}</a>
        </div>
	</section>
	<section class="content">
	<div class="row">
		<div class="col-xs-12">
			<div class="box">
                <!-- /.box-header -->
                {{-- @include("include.searchform",["placeholder" => "Search by Role"]) --}}
			    <div class="box-body">
                    @include("include.message")
                    <table class="example3 datatable table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if(!empty($data) && count($data) > 0){
                                foreach ($data as $k => $v) {
                            ?>
                            <tr>
                                <td>{{ $v->id }}</td>
                                <td>{{ ucfirst($v->name) }}</td>
                                <td>
                                    <a href="<?php echo URL::to($edit_product.'/'.$v->id)?>" class="btn btn-warning"><i class="fa fa-pencil"></i></a>
                                    <a href="<?php echo URL::to($delete_product.'/'.$v->id)?>" rel="delete" class="btn btn-danger ajaxBtnAlter"><i class="fa fa-trash"></i></a>
                                </td>
                            </tr>
                            <?php
                                }
                            }
                            ?>
                            
                        </tbody>
                    </table>
                </div>
			</div>
		</div>
	</div>
</section>
<script>
    $(document).ready(function () {
        var options = {

        url: "{{ url('/admin/modes/all_mode') }}",
        ajaxSettings: {
            dataType: "json",
            method: "get",
            data: {
                dataType: "json",
                is_api : 1,
                search_now : true,
            }
        },
        getValue: "mode_name",

        list: {
            match: {
            enabled: true
            }
        },

        theme: "square"
        };

        $("#mode_name").easyAutocomplete(options);
    });
</script>
@endsection