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
        <form action="{{ url('admin/user/updateUser') }}" method="post" enctype="multipart/form-data">
			<div class="box">
			    <!-- /.box-header -->
			    <div class="box-body">
                    @include("include.message")
                    <div class="form-group">
                        <label>User Name</label>
                        <input type="text" class="form-control" required name="user_name" value="{{ $data->user_name }}" />
                    </div>
                    <div class="form-group">
                        <label>First Name</label>
                        <input type="text" class="form-control" required name="first_name" value="{{ $data->first_name }}" />
                    </div>
                    <div class="form-group">
                        <label>Last Name</label>
                        <input type="text" class="form-control" required name="last_name" value="{{ $data->last_name }}" />
                    </div>
                    <div class="form-group">
                        <label>Email Address</label>
                        <input type="text" class="form-control" required name="email" value="{{ $data->email }}" />
                    </div>
                    <div class="form-group">
                        <label>Password</label>
                        <input type="password" class="form-control" name="password" />
                    </div>
                    <div class="form-group">
                        <label>Confirm Password</label>
                        <input type="password" class="form-control" name="confirm_password" />
                    </div>
                    <div class="row">
                        <div class="form-group col-md-8">
                            <label>Image</label>
                            <input type="file" name="profile_image" class="form-control" />
                        </div>
                        <div class="form-group col-md-4">
                            @if(!empty($data->profile_image))
                                <img src="{{ $data->profile_image }}" style="width:100%" /> 
                            @endif
                        </div>
                    </div>
                </div>
                <div class="box-footer">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                    <input type="hidden" name="id" value="{{ $data->id }}" />
                    <input type="submit" class="btn btn-primary" value="Update User" />
                </div>
            </div>
        </form>
		</div>
	</div>
</section>
@include('include.filterjs')
@endsection