@extends('layouts.app')

@section('content')
    <div class="text-center">
        <h3>ログイン</h3>
    </div>

    <div class="row">
        <div class="col-md-6 col-md-offset-3">

            {!! Form::open(['route' => 'login.post']) !!}
                <div class="form-group">
                    {!! Form::label('email', 'メールアドレス') !!}
                    {!! Form::email('email', old('email'), ['class' => 'form-control']) !!}
                </div>

                <div class="form-group">
                    {!! Form::label('password', 'パスワード') !!}
                    {!! Form::password('password', ['class' => 'form-control']) !!}
                </div>
                {!! Form::checkbox('remember') !!}
                {!! Form::label('remember', '次回から省略する') !!}
                {!! Form::submit('ログイン', ['class' => 'btn btn-primary btn-block']) !!}
            {!! Form::close() !!}

            <p>ユーザー登録がまだの人は {!! link_to_route('signup.get', 'こちら') !!}から</p>
        </div>
    </div>
@endsection
