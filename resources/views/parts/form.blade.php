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
  <div class="form-group">
    {!! Form::submit('Add Article', ['class' => 'btn btn-primary form-control']) !!}
  </div>
{!! Form::close() !!}
