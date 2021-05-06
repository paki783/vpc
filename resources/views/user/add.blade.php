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
        <form autocomplete="off" class="validate ajaxForm" action="{{ url('admin/user/saveUser') }}" method="post" enctype="multipart/form-data">
			<div class="box">
			    <!-- /.box-header -->
			    <div class="box-body">
                    @include("include.message")
                    <div class="form-group">
                        <label>User Name</label>
                        <input type="text" class="form-control" required name="user_name" value="" />
                    </div>
                    <div class="form-group">
                        <label>First Name</label>
                        <input type="text" class="form-control" required name="first_name" value="" />
                    </div>
                    <div class="form-group">
                        <label>Last Name</label>
                        <input type="text" class="form-control" required name="last_name" value="" />
                    </div>
                    <div class="form-group">
                        <label>Email Address</label>
                        <input type="text" class="form-control" required name="email" value="" />
                    </div>
                    <div class="form-group">
                        <label>Password</label>
                        <input type="password" class="form-control" id="password" required name="password" />
                    </div>
                    <div class="form-group">
                        <label>Confirm Password</label>
                        <input type="password" class="form-control" required name="confirm_password" />
                    </div>
                    <div class="row">
                        <div class="form-group col-md-8">
                            <label>Image</label>
                            <input type="file" name="profile_image" class="form-control" />
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-4">
                            <label>Facebook URL</label>
                            <input type="text" class="form-control" name="facebook_link" value="" />
                        </div>
                        <div class="form-group col-md-4">
                            <label>Twitter URL</label>
                            <input type="text" class="form-control" name="twitter_link" value="" />
                        </div>
                        <div class="form-group col-md-4">
                            <label>Youtube URL</label>
                            <input type="text" class="form-control" name="youtube_link" value="" />
                        </div>
                    </div>
                    <div class="row">                        
                        <div class="form-group col-md-6">
                            <label>Playstation Tag</label>
                            <input type="text" class="form-control" name="playstationtag" value="" />
                        </div>
                        <div class="form-group col-md-6">
                            <label>Xbox Tag</label>
                            <input type="text" class="form-control" name="xboxtag" value="" />
                        </div>
                    </div>
                    <div class="row">                        
                        <div class="form-group col-md-6">
                            <label>Origin Tag</label>
                            <input type="text" class="form-control" name="origin_account" value="" />
                        </div>
                        <div class="form-group col-md-6">
                            <label>Stream ID</label>
                            <input type="text" class="form-control" name="streamid" value="" />
                        </div>
                    </div>
                    <div class="row">                        
                        <div class="form-group col-md-12">
                            <label>BIO</label>
                            <textarea class="form-control" name="bio"></textarea>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label>Select Country</label>
                            <select class="form-control" name="country_id">
                                @if(!empty($countries))
                                    @foreach($countries as $c)
                                        <option value="{{ $c->id }}">{{ $c->name }}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                        <div class="form-group col-md-6">
                            <label>Select Team</label>
                            <select class="form-control" id="selected_team" name="selected_team">
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4 form-group">
                            <label>Select Position</label>
                            <select class="form-control" id="position_id" name="position_id">
                            </select>
                        </div>
                        <div class="col-md-4 form-group">
                            <label>Select Mode</label>
                            <select class="form-control" id="mode_id" name="mode_id">

                            </select>
                        </div>
                        <div class="col-md-4 form-group">
                            <label>Select Status</label>
                            <select class="form-control" id="status" name="status">
                                <option value="enabled">Enabled</option>
                                <option value="disabled">Disabled</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="box-footer">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                    <input type="submit" class="btn btn-primary" value="Save User" />
                </div>
            </div>
        </form>
		</div>
	</div>
</section>
@include('include.filterjs')

<script>
$(document).ready(function(){
    $("form.validate").validate({
      rules: {
        user_name:{
          required: true
        },
        first_name : {
            required : true,
        },
        last_name : {
            required : true,
        },
        email : {
            email: true,
            required : true,
        },
        password : {
            required : true,
        },
        confirm_password: {
            required: true,
            equalTo : "#password"
        },
        country_id : {
            required : true,
        },
        selected_team : {
            required : true,
        },
        position_id : {
            required : true,
        },
        mode_id : {
            required : true,
        }
      }, 
      messages: {
        user_name: "This field is required.",
        first_name : "This field is required.",
        last_name : "This field is required.",
        email : "This field is required.",
        password : "This field is required.",
        confirm_password  : "This field is required.",
        country_id : "This field is required.",
        selected_team : "This field is required.",
        position_id : "This field is required.",
        mode_id : "This field is required.",
      },
      invalidHandler: function (event, validator) {
        //display error alert on form submit    
        },
        errorPlacement: function (label, element) { // render error placement for each input type   
          var icon = $(element).parent('.input-with-icon').children('i');
            icon.removeClass('fa fa-check').addClass('fa fa-exclamation');  

          $('<span class="error"></span>').insertAfter(element).append(label);
          var parent = $(element).parent('.input-with-icon');
          parent.removeClass('success-control').addClass('error-control');  
        },
        highlight: function (element) { // hightlight error inputs
          var icon = $(element).parent('.input-with-icon').children('i');
            icon.removeClass('fa fa-check').addClass('fa fa-exclamation');  

          var parent = $(element).parent();
          parent.removeClass('success-control').addClass('error-control'); 
        },
        unhighlight: function (element) { // revert the change done by hightlight
          var icon = $(element).parent('.input-with-icon').children('i');
      icon.removeClass("fa fa-exclamation").addClass('fa fa-check');

          var parent = $(element).parent();
          parent.removeClass('error-control').addClass('success-control'); 
        },
        success: function (label, element) {
          var icon = $(element).parent('.input-with-icon').children('i');
      icon.removeClass("fa fa-exclamation").addClass('fa fa-check');

          var parent = $(element).parent('.input-with-icon');
          parent.removeClass('error-control').addClass('success-control');

          
        }
        // submitHandler: function (form) {

        // }
      });
    getPosition("#position_id");
    getTeam("#selected_team");
    getMode("#mode_id");
});
</script>
@endsection