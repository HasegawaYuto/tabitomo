@if(!Auth::user()->is_recruite($recruitment->id))
    {!! Form::open(['route'=>['guide_candidate','guide_id'=>$recruitment->id]]) !!}
    {!! Form::submit('応募する',['class'=>'btn btn-success btn-block']) !!}
    {!! Form::close() !!}
@else
    {!! Form::open(['route'=>['guide_uncandidate','guide_id'=>$recruitment->id]]) !!}
    {!! Form::submit('応募をやめる',['class'=>'btn btn-default btn-block']) !!}
    {!! Form::close() !!}
@endif
