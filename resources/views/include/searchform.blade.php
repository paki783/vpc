<?php $placeholder = empty($placeholder) ? "search by name or id" : $placeholder; ?>

<div class="box_header">
    <form action="" method="get" class="form-inline">
        <input type="text" class="form-control" name="searhitems" value="{{ app('request')->input('searhitems') }}" placeholder="{{ $placeholder }}...">
        <input type="hidden" name="_token" value="{{ csrf_token() }}" />
        <button type="submit" class="btn btn-default">Search Now</button>
    </form>
</div>