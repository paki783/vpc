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
        <form class="ajaxForm validate" action="{{ url($add_product) }}" method="post" enctype="multipart/form-data">
			<div class="box">
			    <!-- /.box-header -->
			    <div class="box-body">
                    <div class="form-group">
						<label>Name</label>
                        <input type="text" class="form-control" value="<?= @$data->name; ?>" name="name" />
					</div>
                </div>
                <div class="box-footer">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}" />
					<input type="hidden" name="id" value="<?= @$data->id; ?>" />
					<input type="hidden" name="guard_name" value="<?= @$data->guard_name; ?>" />
					<button type="submit" class="btn btn-primary ajaxFormSubmitAlter">Submit</button>
                </div>
            </div>
        </form>
		</div>
	</div>
</section>

<script>
  $(document).ready(function() {
    
    $("form.validate").validate({
      rules: {
        name:{
          required: true
        },
      }, 
      messages: {
        name: "This field is required."
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

    $('.select2', "form.validate").change(function () {
        $('form.validate').validate().element($(this)); //revalidate the chosen dropdown value and show error or success message for the input
    });

  });
</script>

@endsection