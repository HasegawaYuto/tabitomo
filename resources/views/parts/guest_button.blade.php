@if(!Auth::user()->is_recruite($recruitment->id))
    {!! Form::open(['route'=>['guest_candidate','guest_id'=>$recruitment->id]]) !!}
    {!! Form::submit('応募する',['class'=>'btn btn-warning btn-block']) !!}
    {!! Form::close() !!}
@else
    {!! Form::open(['route'=>['guest_uncandidate','guest_id'=>$recruitment->id]]) !!}
    {!! Form::submit('応募をやめる',['class'=>'btn btn-default btn-block']) !!}
    {!! Form::close() !!}
@endif
