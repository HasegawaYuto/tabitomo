{{--
  ~/composer.json
    "require": {
        "laravelcollective/html": "5.1.*"
      }

  $ composer update

  ~/config/app.php
    'providers' => [
      Collective\Html\HtmlServiceProvider::class,
      ],
    'aliases' => [
      'Form' => Collective\Html\FormFacade::class,
      'Html' => Collective\Html\HtmlFacade::class,
      ],
 --}}
@if(isset($config))
  {!! Form::open($config) !!}
@else
  {!! Form::open()!!}
@endif
@foreach($forms as $form)
  <?php $temp = explode(":",$form); ?>
  <div class="form-group">
    @if($temp[0]=="text")
      {!! Form::label($temp[2], $temp[1]) !!}
      @if(!isset($temp[3]))
        <?php $defaultval=null; ?>
      @elseif($temp[3]=="old")
        <?php $defaultval='old(\''. $temp[2] .'\')'; ?>
      @else
        <?php $defaultval=$temp[3]; ?>
      @endif
      @if(isset($temp[4]))
        <?php
          $arraystr = rtrim(ltrim($temp[4],"["),"]");
          parse_str($arraystr,$option);
          if(strpos($arraystr,"class=")){
            $option['class'] = $option['class'] . " form-control";
          }else{
            $option['class'] = " form-control";
          }
        ?>
      @else
        <?php
          $option['class']="form-control";
        ?>
      @endif
      {!! Form::text($temp[2], $defaultval, $option) !!}
    @endif
    {!! Form::submit('Add Article', ['class' => 'btn btn-primary form-control']) !!}
  </div>
@endforeach
{!! Form::close() !!}
