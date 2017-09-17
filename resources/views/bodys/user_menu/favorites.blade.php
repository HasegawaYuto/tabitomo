@extends('layouts.app')

@section('content')
<div class="row">
    @include('bodys.user_menu.contents_menu',['user'=>$user])
    <div class="col-xs-9">
        <div class="panel panel-info">
            <div class="panel panel-heading text-center">
                お気に入り
            </div>
                @include('parts.tabs',['tab_names'=>['シーン','ユーザー'],'class'=>'nav-tabs nav-justified','activetab'=>1])
                <div class="tab-content">
                    <div class="tab-pane active" id="tab1-1">
                      <div class="panel-body">
                        @for($i=1;$i<6;$i++)
                            <div class="col-xs-4">
                                <div class="panel panel-primary">
                                    <div class="panel-heading">
                                        <span class="glyphicon glyphicon-heart"></span>
                                        シーンbyユーザー
                                    </div>
                                    <div class="panel-body">
                                        <div class="text-center">
                                            アバター
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endfor
                      </div>
                    </div>
                    <div class="tab-pane " id="tab1-2">
                        <div class="panel-body">
                              @include('parts.tabs',['tab_names'=>['ともだち','フォロー','フォロワー'],'activetab'=>'1','nest'=>'2'])
                              <div class="tab-content">
                                  <div class="tab-pane active" id="tab2-1">
                                    @for($i=1;$i<10;$i++)
                                        <div class="col-xs-4">
                                            <div class="panel panel-primary">
                                                <div class="panel-heading">
                                                    <span class="glyphicon glyphicon-link"></span>
                                                    ユーザー名
                                                </div>
                                                <div class="panel-body">
                                                    <div class="text-center">
                                                        アバター
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endfor
                                  </div>
                                  <div class="tab-pane" id="tab2-2">
                                    @for($i=1;$i<10;$i++)
                                        <div class="col-xs-4">
                                            <div class="panel panel-primary">
                                                <div class="panel-heading">
                                                    <span class="glyphicon glyphicon-heart"></span>
                                                    ユーザー名
                                                </div>
                                                <div class="panel-body">
                                                    <div>
                                                        アバター
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endfor
                                  </div>
                                  <div class="tab-pane" id="tab2-3">
                                    @for($i=1;$i<10;$i++)
                                        <div class="col-xs-4">
                                            <div class="panel panel-primary">
                                                <div class="panel-heading">
                                                    <span class="glyphicon glyphicon-heart-empty"></span>
                                                    ユーザー名
                                                </div>
                                                <div class="panel-body">
                                                    <div>
                                                        アバター
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endfor
                                  </div>
                              </div>
                        </div>
                    </div>
                </div>
        </div>
    </div>
</div>
@endsection
