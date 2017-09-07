@if(isset($text))
    @if(!isset($text['default']))
        <?php $defaultval=null; ?>
    @elseif($text['default']=='*')
        <?php $defaultval='old(\'' . $text['val'] . '\')'; ?>
    @else
        <?php $defaultval=$text['default']; ?>
    @endif

    @if(!isset($text['option']['class']))
        <?php $text['option']['class']='form-control'; ?>
    @else
        <?php $text['option']['class']=$text['option']['class'] . ' form-control'; ?>
    @endif
    <div class="form-group">
        @if(isset($text['style']))
            <div class="{{$text['style']}}">
        @endif
            @if(isset($text['label']))
                {!! Form::label($text['var'], $text['label']) !!}
            @endif
            {!! Form::text($text['var'], $defaultval, $text['option']) !!}
        @if(isset($text['style']))
            </div>
        @endif
    </div>
@endif
@if(isset($hidden))
    @if(!isset($hidden['option']))
        <?php $hidden['option']=[]; ?>
    @endif
    {!! Form::hidden($hidden['var'],$hidden['val'],$hidden['option']) !!}
@endif
@if(isset($textarea))
    <div class="form-group">
      @if(isset($text['style']))
          <div class="{{$text['style']}}">
      @endif
        @if(isset($textarea['label']))
            {!! Form::label($textarea['var'], $textarea['label']) !!}
        @endif
        @if(!isset($textarea['default']))
            <?php $textarea['default']=null; ?>
        @elseif($textarea['default']=="*")
            <?php $textarea['default']='old(\''. $textarea['var'] .'\)'; ?>
        @endif
        @if(!isset($textarea['option']['class']))
            <?php $textarea['option']['class']='form-control'; ?>
        @else
            <?php $textarea['option']['class']=$textarea['option']['class'] . ' form-control'; ?>
        @endif
        {!! Form::textarea($textarea['var'],$textarea['default'],$textarea['option']) !!}
        @if(isset($text['style']))
            </div>
        @endif
    </div>
@endif
@if(isset($file))
    <div class="form-group">
        @if(!isset($file['option']))
            <?php $file['option']=[]; ?>
        @endif
        {!! Form::file($file['var'],$file['option']) !!}
    </div>
@endif
@if(isset($submit))
    @if(!isset($submit['option']['class']))
        <?php $submit['option']['class']='btn'; ?>
    @else
        <?php $submit['option']['class']='btn ' . $submit['option']['class']; ?>
    @endif
        {!! Form::submit($submit['label'],$submit['option']) !!}
@endif
