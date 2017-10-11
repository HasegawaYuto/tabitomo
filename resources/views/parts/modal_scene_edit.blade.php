<div class="modal fade" id="fixScene0">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
          <h4 class="modal-title">シーンの編集</h4>
      </div>
      <div class="modal-body">
          {!! Form::open(['route'=>['edit_scene','id'=>'USERID','title_id'=>'TITLEID','scene_id'=>'SCENEID'],'files'=>'true','id'=>'myLogForm0','class'=>'myLogForm']) !!}
          {!! csrf_field() !!}
          {!! Form::hidden('title','editTitleName',['id'=>'editTitleName']) !!}
          {!! Form::hidden('editstyle','somestring',['id'=>'editstyle']) !!}
          <div class="form-group form-inline">
              {!! Form::label('scene','シーン：') !!}
              {!! Form::text('scene','hoge',['class'=>'form-control','id'=>'NewScene0']) !!}
          </div>
          <div class="form-group form-inline">
              {!! Form::hidden('firstday','0000-00-00',['id'=>'firstday0']) !!}
              {!! Form::hidden('lastday','0000-00-00',['id'=>'lastday0']) !!}
              {!! Form::hidden('oldtheday','0000-00-00',['id'=>'edittheday0']) !!}
                {!! Form::label('theday','日付：') !!}
                <select id="theday0" class="form-control theday" name="theday">
                </select>
          </div>
          @if(isset($photos))
              <div class="col-xs-12" id="photosField">
                  @foreach($photos as $photo)
                      <img class="img-responsive imgPhotos lazyload" data-src="data:{{$photo->mime}};base64,{{base64_encode($photo->data)}}" sceneID="{{$photo->user_id}}-{{$photo->title_id}}-{{$photo->scene_id}}" photoID="{{$photo->id}}" />
                      <input type=hidden name="deletePhotoNo[{{$photo->id}}]" value="false" id="deletePhotoNo{{$photo->id}}">
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
          {!! Form::submit('更新',['class'=>'btn btn-primary btn-xs','id'=>'sceneEditSubmit']) !!}
          {!! Form::close() !!}
      </div>
    </div>
  </div>
</div>