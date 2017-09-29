@extends('layouts.app')

@section('content')
<div class="container">
<div class="row">
@include('bodys.user_menu.contents_menu',['user'=>$user])
<div class="col-md-9">
    <div class="panel panel-info">
        <div class="panel-heading titleString">
                {!! Link_to_route('show_user_items','マイログ',['id'=>$id]) !!}
                &nbsp;&nbsp; ≫
                @if(Auth::user()->id == $id)
                    &nbsp;&nbsp;
                    <button type="button" class="btn btn-warning btn-xs" data-toggle="modal" data-target="#fixTitle">編集</button>
                @else
                    「お気に入ボタン」
                @endif
                &nbsp;&nbsp;
                {{$title->title}}

@if(Auth::user()->id == $id)
<div class="modal fade" id="fixTitle">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">タイトルの編集</h4>
      </div>
      <div class="modal-body">
          {!! Form::open(['route'=>['edit_title','id'=>Auth::user()->id,'title_id'=>$title->title_id]]) !!}
          <div class="form-group form-inline">
              {!! Form::label('NewTitle','タイトル：') !!}
              {!! Form::text('NewTitle',$title->title,['class'=>'form-control']) !!}
          </div>
          <div class="form-group form-inline">
              <label>期間：</label>
          <?php
                $today = Carbon\Carbon::now()->format('Y年m月d日');
                $oldfirstday = new Carbon\Carbon($title->firstday);
                $oldlastday = new Carbon\Carbon($title->lastday);
                $OldFirstday = $oldfirstday->format('Y年m月d日');
                $OldLastday = $oldlastday->format('Y年m月d日');
          ?>
              {!! Form::text('firstday',$OldFirstday,['class'=>'form-control datepicker','id'=>'firstday','style'=>'width:40%;']) !!}
              <label>～</label>
              {!! Form::text('lastday',$OldLastday,['class'=>'form-control datepicker','id'=>'lastday','style'=>'width:40%;']) !!}
          </div>
          {!! Form::submit('更新',['class'=>'btn btn-primary btn-xs']) !!}
          {!! Form::close() !!}
      </div>
    </div>
  </div>
</div>
@endif


        </div>
        <div class="panel-body">
            <?php
                $firstdayarray = explode('-',$title->firstday);
                $lastdayarray = explode('-',$title->lastday);
            ?>
        <div class="title_detail">
            <div class="col-xs-5">
                <label>期間</label><br>
                {{$firstdayarray[0]}}年{{(int)$firstdayarray[1]}}月{{(int)$firstdayarray[2]}}日～
                {{$lastdayarray[0]==$firstdayarray[0] ? '':$lastdayarray[0].'年'}}{{(int)$lastdayarray[1]}}月{{(int)$lastdayarray[2]}}日
            </div>
            <div class="col-xs-4">
                <label>おすすめ</label><br>
                <div id="showRatyAveDiv" class="showRatyAve">
                    {{(int)($scoreAve*2)/2}}
                    @if($scoreAve !="")
                        <input type="hidden" value="{{$scoreAve}}" id="showRatyAve" />
                    @else
                        <input type="hidden" value="0" id="showRatyAve" />
                    @endif
                </div>
            </div>
            <div class="col-xs-3">
                <label>お気に入り</label>〇
            </div>
        </div>

            {!! $scenes->render() !!}

            @foreach($scenes as $key => $scene)
            <?php
                $thedayarray = explode('-',$scene->theday);
            ?>
                <div class="col-xs-12">
                    <div class="panel panel-primary">
                        <div class="panel-heading" style="text-overflow:ellipsis;overflow: hidden;white-space: nowrap;">
                          @if(Auth::user()->id == $id)
                            <button type="button" class="btn btn-warning btn-xs"  data-toggle="modal" data-target="#fixScene0" data-scene="{{$scene->scene}}"
                            data-lat="{{$scene->lat}}"
                            data-lng="{{$scene->lng}}"
                            data-score="{{$scene->score}}"
                            data-comment="{{$scene->comment}}"
                            data-oldtheday="{{$thedayarray[0]}}年{{$thedayarray[1]}}月{{$thedayarray[2]}}日"
                            data-sceneid="{{$scene->scene_id}}"
                            data-publish="{{$scene->publish}}"
                              >編集</button>
                          @else
                            「お気に入りボタン」
                          @endif
                            &nbsp;&nbsp;{{$scene->scene =="" ? 'No Title':$scene->scene}}
                        </div>
                        <div class="panel-body">
                            <div class="text-center">
                                <dvi class="col-xs-4">
                                    @if(isset($thumb[$key]->data))
                                    <?php
                                      $mime = $thumb[$key]->mime;
                                      $dataImage = base64_encode($thumb[$key]->data);
                                    ?>
                                    <a href="#modal_carousel{{$scene->scene_id}}" data-toggle="modal" data-local="#myCarousel{{$scene->scene_id}}"><img class="img-responsive showPhotos lazyload" data-src="data:{{$mime}};base64,{{$dataImage}}" style="height:25vh;" sceneID="{{$scene->scene_id}}"
                                    sceneStr="{{$scene->scene}}"
                                    titleStr="{{$scene->title}}" /></a>
                                    <small>↑クリック</small>
                                    @endif
                                </div>
                                    <input type="hidden" value="{{$scene->lat}}" id="googlemapLat{{$key}}" />
                                    <input type="hidden" value="{{$scene->lng}}" id="googlemapLng{{$key}}" />
                                <div class="googlemapSpot col-xs-4" style="height:25vh;" id="googlemapSpotID{{$key}}">
                                </div>
                                <div class="col-xs-4">
                                    <label>日付</label>
                                    <div>
                                        {{$thedayarray[0]}}年
                                        {{(int)$thedayarray[1]}}月
                                        {{(int)$thedayarray[2]}}日
                                    </div><br>
                                    <label>おすすめ</label>
                                    <div class="showRaty" id="showRatyDiv{{$key}}">
                                        @if($scene->score !="")
                                            <input type="hidden" value="{{$scene->score}}" id="showRaty{{$key}}" />
                                        @else
                                            <input type="hidden" value="0" id="showRaty{{$key}}" />
                                        @endif
                                    </div><br>
                                    <div>
                                        <label>お気に入り</label>〇
                                    </div>
                                </div>
                                <div class="col-xs-12">
                                    <label>コメント</label><br>
                                    {{$scene->comment != "" ? $scene->comment: 'No comment'}}
                                </div>
                            <div>
                                <div id="demo{{$key}}" class="collapse col-xs-12">
                                    <p>他のユーザのコメント</p>
                                </div>
                                <button type="button" class="btn btn-block" data-toggle="collapse" data-target="#demo{{$key}}"><span class="caret"></span></button>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
</div>
</div>


<div class="modal fade" id="fixScene0">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
          <h4 class="modal-title">シーンの編集</h4>
      </div>
      <div class="modal-body">
          {!! Form::open(['route'=>['edit_scene','id'=>Auth::user()->id,'title_id'=>$title->title_id,'scene_id'=>'sceneID'],'files'=>'true','id'=>'myLogForm0','class'=>'myLogForm']) !!}
          {!! csrf_field() !!}
          {!! Form::hidden('title',$title->title) !!}
          {!! Form::hidden('title_id',$title->title_id,['id'=>'titleIdAction']) !!}
          <div class="form-group form-inline">
              {!! Form::label('NewScene','シーン：') !!}
              {!! Form::text('NewScene','hoge',['class'=>'form-control','id'=>'NewScene0']) !!}
          </div>
          <div class="form-group form-inline">
          <?php
                $today = Carbon\Carbon::now()->format('Y年m月d日');
                $oldfirstday = new Carbon\Carbon($title->firstday);
                $oldlastday = new Carbon\Carbon($title->lastday);
                //$oldlastday = new Carbon\Carbon($title->theday);
                $OldFirstday = $oldfirstday->format('Y年m月d日');
                $OldLastday = $oldlastday->format('Y年m月d日');
                //$OldTheday = $oldtheday->format('Y/m/d');
          ?>
              {!! Form::hidden('firstday',$OldFirstday,['id'=>'firstday0']) !!}
              {!! Form::hidden('lastday',$OldLastday,['id'=>'lastday0']) !!}
              {!! Form::hidden('oldtheday',$today,['id'=>'edittheday0']) !!}
                {!! Form::label('theday','日付：') !!}
                <select id="theday0" class="form-control theday" name="theday">
                </select>
          </div>
          @if(isset($photos))
              <div class="col-xs-12" id="photosField">
                  <p>消去する画像をクリックして選択してください</p>
                  @foreach($photos as $photo)
                      <img class="img-responsive imgPhotos lazyload" data-src="data:{{$photo->mime}};base64,{{base64_encode($photo->data)}}" sceneID="{{$photo->scene_id}}" photoID="{{$photo->id}}" />
                      <input type=hidden name="deletePhotoNo[{{$photo->id}}]" value="false" id="deletePhotoNo{{$photo->id}}">
                      <div id="deletePhotoDivNo{{$photo->id}}"></div>
                  @endforeach
              </div>
          @endif
          <div class="form-group form-inline">
                {!! Form::label('publish','公開設定') !!}
                {!! Form::radio('publish','public',true,['id'=>'radioPublic']) !!}
                <label>公開</label>
                {!! Form::radio('publish','private',false,['id'=>'radioPrivate']) !!}
                <label>非公開</label>
          </div>
          <div class="form-group">
                {!! Form::file('image[]',['multiple'=>'multiple','accept'=>'image/*']) !!}
          </div>
          <div id="imageThumbnailField0" class="col-xs-12 imageThumbnailField">
          </div>
          <div class="form-group">
              <label>スポット</label>
              <!--div class="row"-->
                  {!! Form::hidden('spotNS', 30, ['id' => 'ido0']) !!}
                  {!! Form::hidden('spotEW', 135, ['id' => 'keido0']) !!}
                  {!! Form::hidden('mapzoom', 8, ['id' => 'mapzoom0']) !!}
                <div id="editPhotoSpotSetArea0" class="col-xs-12 photoSpotSetArea">
                </div>
              <!--/div-->
          </div>
          <div class="form-group">
              <label>おすすめ度</label>
              {!! Form::hidden('score',2,['id'=>'oldScore']) !!}
              <div id="editRateField0" class="rateField">
              </div>
          </div>
          <div class="form-group">
                {!! Form::label('comment','コメント') !!}
                {!! Form::textarea('comment',null,['class'=>"form-control",'rows'=>'3','id'=>'comment0']) !!}
          </div>
          {!! Form::submit('更新',['class'=>'btn btn-primary btn-xs']) !!}
          {!! Form::close() !!}
      </div>
    </div>
  </div>
</div>


@if(isset($photos))
@foreach($scenes as $scene)
<div class="modal fade modal-fullscreen force-fullscreen" id="modal_carousel{{$scene->scene_id}}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel{{$scene->scene_id}}" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
          {{$scene->scene}}
      </div>
      <div class="modal-body">
        <div id="myCarousel{{$scene->scene_id}}" class="carousel slide carousel-fit" data-ride="carousel">
          <!-- Indicators -->
          <ol class="carousel-indicators">
            <?php $cnt = -1; ?>
            @foreach($photos as $photo)
            @if($photo->scene_id == $scene->scene_id)
              <?php $cnt++; ?>
              <li data-target="#myCarousel{{$scene->scene_id}}" data-slide-to="{{$cnt}}" {{ $cnt == 0 ? 'class="active"' : ''}}></li>
            @endif
            @endforeach
          </ol>

          <!-- Wrapper for slides -->
          <div class="carousel-inner">
            <?php $cnt = -1; ?>
            @foreach($photos as $photo)
            @if($photo->scene_id == $scene->scene_id)
            <?php
              $dataPhoto = base64_encode($photo->data);
              $cnt++;
            ?>
            <div class="item {{ $cnt == 0 ? 'active':''}}">
              <img data-src="data:{{$photo->mime}};base64,{{$dataPhoto}}" class="lazyload img-responsive">
            </div>
            @endif
            @endforeach
          </div>
          <!-- Controls -->
          <a class="left carousel-control" href="#myCarousel{{$scene->scene_id}}" data-slide="prev">
            <span class="glyphicon glyphicon-chevron-left"></span>
          </a>
          <a class="right carousel-control" href="#myCarousel{{$scene->scene_id}}" data-slide="next">
            <span class="glyphicon glyphicon-chevron-right"></span>
          </a>
        </div>

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
@endforeach
@endif

@endsection
