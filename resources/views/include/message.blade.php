@if(Session::has('message'))
    <div class="alert alert-{{ Session::get('class') }} alert-dismissible" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        {{ Session::get('message') }}
    </div>
@endif
@if ($errors->any())
    <div class="alert alert-danger" role="alert">
        <ul>
        @foreach ($errors->all() as $error)
            <li>
                {{$error}}
            </li>
        @endforeach
        </ul>
    </div>
 @endif
<script>
    $(document).ready(function() {
        $(".alert").fadeTo(15000, 500).slideUp(500, function(){
            $(".alert").slideUp(500);
        });
    });
</script>