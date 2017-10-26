@extends('layouts.app')

@section('content')
<div class="container-fluid">
<div class="row">
    @include('bodys.user_menu.contents_menu',['user'=>$user])
    <div class="col-xs-12 col-sm- col-md-9 col-lg-6">
        <div class="panel panel-info">
            <div class="panel panel-heading text-center">
                <i class="fa-fw fa fa-heart-o" aria-hidden="true"></i>お気に入り
            </div>
                <ul class="nav nav-tabs nav-justified">
                      <li class="active"><a href="#tab1-1" data-toggle="tab">シーン</a></li>
                      <li><a href="#tab1-2" data-toggle="tab">ユーザー</a></li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane active" id="tab1-1">
                      <div class="panel-body">
                        @if(isset($scenes[0]))
                        @foreach($scenes as $key => $scene)
                            <div class="col-xs-12 col-sm-6 col-md-4 col-lg-4">
                                <div class="panel panel-primary">
                                    <div class="panel-heading overCut">
                                        @if(Auth::user()->id!=$titles[$key]->user_id)
                                            @include('parts.favorite_scene_button',['thescene'=>$scene])
                                        @endif
                                        {{$scene->scene}}
                                    </div>
                                    <div class="panel-body">
                                      <a href="{{route('show_title',['id'=>$titles[$key]->user_id,'title_id'=>$scene->title_id])}}">
                                              @if(isset($thumb[$key]->path))
                                                  <div class="lazyload col-xs-12 favscene" data-bg="{{$thumb[$key]->path}}"></div>
                                              @else
                                                  <div class="lazyload col-xs-12 favscene" data-bg="{{asset('noimage.png')}}"></div>
                                              @endif
                                      </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                        @endif
                      </div>
                    </div>
                    <div class="tab-pane " id="tab1-2">
                        <div class="panel-body">
                            <ul class="nav nav-tabs">
                                  <li class="active"><a href="#tab2-1" data-toggle="tab">フォロー</a></li>
                                  <li><a href="#tab2-2" data-toggle="tab">フォロワー</a></li>
                            </ul>
                              <div class="tab-content">
                                  <div class="tab-pane" id="tab2-1">
                                    @if(isset($following[0]))
                                    {!! $following->render() !!}
                                    @foreach($following as $favuser)
                                        <div class="followImage text-center">
                                            <a href="{{route('show_user_profile',['id'=>$favuser->id])}}">
                                                @include('parts.avatar',['user'=>$favuser,'class'=>'favuser'])
                                                <p class="smallp overCut black">{{isset($favuser->nickname)?$favuser->nickname:'未設定'}}</p>
                                          </a>
                                        @include('parts.follow_button',['user'=>$favuser])
                                        </div>
                                    @endforeach
                                    @endif
                                  </div>
                                  <div class="tab-pane" id="tab2-2">
                                    @if(isset($followed[0]))
                                    {!! $followed->render() !!}
                                    @foreach($followed as $favuser)
                                        <div class="followImage text-center">
                                            <a href="{{route('show_user_profile',['id'=>$favuser->id])}}">
                                                @include('parts.avatar',['user'=>$favuser,'class'=>'favuser'])
                                                <p class="smallp overCut black">{{isset($favuser->nickname)?$favuser->nickname:'未設定'}}</p>
                                          </a>
                                        @include('parts.follow_button',['user'=>$favuser])
                                        </div>
                                    @endforeach
                                    @endif
                                  </div>
                              </div>
                        </div>
                    </div>
                </div>
        </div>
    </div>
</div>
</div>
@endsection
