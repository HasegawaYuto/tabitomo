<div class="modal fade" id="fixScene0">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
          <h4 class="modal-title">シーンの編集<button type="button" class="btn btn-primary btn-xs" data-dismiss="modal">閉じる</button></h4>
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
          <div class="form-group">
                              {!! Form::label('genre','ジャンル') !!}<br>
                              <div class="chdiv">
                              {!! Form::checkbox('genre[]','A') !!}
                              <label>
                                  <div class="white chlbl" style="background-color:#228b22;">
                                      自然<i class="fa fa-leaf fa-lg" aria-hidden="true"></i>
                                  </div>
                              </label>
                              </div>
                              <div class="chdiv">
                              {!! Form::checkbox('genre[]','B') !!}
                              <label>
                                  <div class="black chlbl" style="background-color:#ffff00;">
                                      歴史・人物<i class="fa fa-history fa-lg" aria-hidden="true"></i>
                                  </div>
                              </label>
                              </div>
                              <div class="chdiv">
                              {!! Form::checkbox('genre[]','C') !!}
                              <label>
                                  <div class="white chlbl" style="background-color:#a0522d;">
                                      建物<i class="fa fa-university fa-lg" aria-hidden="true"></i>
                                  </div>
                              </label>
                              </div>
                              <div class="chdiv">
                              {!! Form::checkbox('genre[]','D') !!}
                              <label>
                                  <div class="black chlbl" style="background-color:#ff69b4;">
                                      食べる<i class="fa fa-cutlery fa-lg" aria-hidden="true"></i>
                                  </div>
                              </label>
                              </div>
                              <div class="chdiv">
                              {!! Form::checkbox('genre[]','E') !!}
                              <label>
                                  <div class="black chlbl" style="background-color:#00ffff;">
                                      買う<i class="fa fa-shopping-cart fa-lg" aria-hidden="true"></i>
                                  </div>
                              </label>
                              </div>
                              <div class="chdiv">
                              {!! Form::checkbox('genre[]','F') !!}
                              <label>
                                  <div class="black chlbl" style="background-color:#ffffff;border:solid 0.5px #000000;">
                                      遊ぶ<i class="fa fa-futbol-o fa-lg" aria-hidden="true"></i>
                                  </div>
                              </label>
                              </div>
                        </div>
          @if(isset($photos[0]->data))
              <div class="col-xs-12" id="photosField">
                  @foreach($photos as $photo)
                      <img class="img-responsive imgPhotos lazyload" data-src="{{$photo->path}}" sceneID="{{$photo->scene_id}}" photoID="{{$photo->id}}" />
                      <input type=hidden name="deletePhotoNo[{{$photo->id}}]" value="false" id="deletePhotoNo{{$photo->id}}">
                  @endforeach
              </div>
          @endif
          <div class="form-group form-inline">
                {!! Form::label('publish','公開設定') !!}
                {!! Form::radio('publish','public') !!}
                <label>公開</label>
                {!! Form::radio('publish','friend') !!}
                <label>フォロワー</label>
                {!! Form::radio('publish','private') !!}
                <label>非公開</label>
          </div>
          <div class="form-group">
                <label>
                <span class="btn btn-default">
                  追加画像選択
                {!! Form::file('image[]',['multiple'=>'multiple','accept'=>'image/*','style'=>'display:none;']) !!}
                </span>
                </label>
          </div>
          <div id="imageThumbnailField0" class="col-xs-12 imageThumbnailField">
          </div>
          <div class="form-group">
              <label>スポット</label>
              <!--div class="row"-->
                  {!! Form::hidden('spotNS', 30, ['id' => 'ido0']) !!}
                  {!! Form::hidden('spotEW', 135, ['id' => 'keido0']) !!}
                  {!! Form::hidden('mapzoom', 8, ['id' => 'mapzoom0']) !!}
                <div>
                <div id="editPhotoSpotSetArea0" class="photoSpotSetArea">
                </div>
                </div>
          </div>
          <div class="form-group clearfix">
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
