@extends('layouts.app')

@section('content')
<div class="container-fluid">
<div class="row">
    @include('bodys.user_menu.contents_menu',['user'=>$user])
    <div class="col-md-9">
        <div class="panel panel-info">
            <div class="panel panel-heading text-center">
                <i class="fa-fw fa fa-heart-o" aria-hidden="true"></i>お気に入り
            </div>
                @include('parts.tabs',['tab_names'=>['シーン','ユーザー'],'class'=>'nav-tabs nav-justified','activetab'=>1])
                <div class="tab-content">
                    <div class="tab-pane active" id="tab1-1">
                      <div class="panel-body">
                        @if(isset($scenes))
                        @foreach($scenes as $key => $scene)
                            <div class="col-xs-4">
                                <div class="panel panel-primary">
                                    <div class="panel-heading">
                                        @include('parts.favorite_scene_button',['scene'=>$scene])
                                        {{$scene->scene}}
                                    </div>
                                    <div class="panel-body">
                                      <a href="{{route('show_title',['id'=>$scene->user_id,'title_id'=>$scene->title_id])}}">
                                              @if(isset($thumb[$key]->data))
                                                  <?php
                                                      $mime = $thumb[$key]->mime;
                                                      $dataImage = base64_encode($thumb[$key]->data);
                                                  ?>
                                                  <div class="lazyload col-xs-12 favscene" data-bg="data:{{$mime}};base64,{{$dataImage}}"></div>
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
                              @include('parts.tabs',['tab_names'=>['ともだち','フォロー','フォロワー'],'activetab'=>'1','nest'=>'2'])
                              <div class="tab-content">
                                  <div class="tab-pane active" id="tab2-1">
                                    @if(isset($mutual))
                                    @foreach($mutual as $favuser)
                                        <div class="col-xs-4">
                                            @if(isset($favuser->data))
                                                <?php
                                                    $mime = $favuser->mime;
                                                    $dataImage = base64_encode($favuser->data);
                                                ?>
                                                <div class="lazyload favuser" data-bg="data:{{$mime}};base64,{{$dataImage}}"></div>
                                            @else
                                                <div class="lazyload favuser" data-bg="{{asset('noimage.png')}}"></div>
                                            @endif
                                        </div>
                                    @endforeach
                                    @endif
                                  </div>
                                  <div class="tab-pane" id="tab2-2">
                                    @if(isset($following))
                                    @foreach($following as $favuser)
                                        <div class="col-xs-4">
                                            @if(isset($favuser->data))
                                                <?php
                                                    $mime = $favuser->mime;
                                                    $dataImage = base64_encode($favuser->data);
                                                ?>
                                                <div class="lazyload favuser" data-bg="data:{{$mime}};base64,{{$dataImage}}"></div>
                                            @else
                                                <div class="lazyload favuser" data-bg="{{asset('noimage.png')}}"></div>
                                            @endif
                                        </div>
                                    @endforeach
                                    @endif
                                  </div>
                                  <div class="tab-pane" id="tab2-3">
                                    @if(isset($followed))
                                    @foreach($followed as $favuser)
                                        <div class="col-xs-4">
                                            @if(isset($favuser->data))
                                                <?php
                                                    $mime = $favuser->mime;
                                                    $dataImage = base64_encode($favuser->data);
                                                ?>
                                                <div class="lazyload favuser" data-bg="data:{{$mime}};base64,{{$dataImage}}"></div>
                                            @else
                                                <div class="lazyload favuser" data-bg="{{asset('noimage.png')}}"></div>
                                            @endif
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
