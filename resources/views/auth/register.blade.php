@extends('layouts.app')

@section('content')
    <div class="text-center col-md-6 col-md-offset-3 col-xs-12 col-sm-12">
        <h4>ユーザー登録</h4>
    </div>

    <div class="row">
        <div class="col-md-6 col-md-offset-3 col-xs-12 col-sm-12">

            {!! Form::open(['route' => 'signup.post']) !!}
                <div class="form-group">
                    {!! Form::label('name', '名前') !!}
                    {!! Form::text('name', old('name'), ['class' => 'form-control']) !!}
                </div>

                <div class="form-group">
                    {!! Form::label('email', 'メールアドレス') !!}
                    {!! Form::email('email', old('email'), ['class' => 'form-control']) !!}
                </div>

                <div class="form-group">
                    {!! Form::label('password', 'パスワード') !!}
                    {!! Form::password('password', ['class' => 'form-control']) !!}
                </div>

                <div class="form-group">
                    {!! Form::label('password_confirmation', '確認用パスワード') !!}
                    {!! Form::password('password_confirmation', ['class' => 'form-control']) !!}
                </div>

                {!! Form::submit('登録', ['class' => 'btn btn-primary btn-block']) !!}
            {!! Form::close() !!}
            
            <p>他のサービスのログイン情報を利用する</p>
            <a class="btn btn-block btn-primary" href="auth/login/facebook">
                <span class="fa fa-facebook"></span>Facebookのアカウントを利用
            </a>
            <a class="btn btn-block btn-danger"  href="auth/login/google">
                <span class="fa fa-google"></span>Google+のアカウントを利用
            </a>
        </div>
    </div>
@endsection
