@extends('layouts.app')

@section('content')

<div class="form-group" id="datepicker-inline">
    <label class="col-sm-3 control-label">Embedded / inline</label>
    <div class="col-sm-9 form-inline">
        <div class="in-line" data-date="2010年10月26日"></div>
        <input type="text" id="my_hidden_input">
    </div>
</div>

<script>
    $(function(){
        $('#datepicker-inline .in-line').datepicker({
            format: "yyyy年mm月dd日",
            language: "ja"
          });
    $('.in-line').on('changeDate', function() {
        $('#my_hidden_input').val(
            $('.in-line').datepicker('getFormattedDate')
        );
    });
});
</script>
@endsection
