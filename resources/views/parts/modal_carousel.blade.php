<div class="modal fade" id="modal_carousel{{$scene->scene_id}}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel{{$scene->user_id}}-{{$scene->title_id}}-{{$scene->scene_id}}" aria-hidden="true">
    <div class="modal-content" id="modal-carousel-content">
      <div class="modal-header overCut" style="height:50px;">
          <button type="button" class="btn btn-primary btn-xs" data-dismiss="modal">閉じる</button>【{{$title->title}}】{{$scene->scene}}
      </div>
      <div class="modal-body" style="padding:0" id="modal-carousel-body">
        <div id="myCarousel{{$scene->scene_id}}" class="carousel slide carousel-fit" data-ride="carousel">
          <ol class="carousel-indicators">
              @if(isset($photos[0]->path))
                  <?php $cnt = -1; ?>
                  @foreach($photos as $photo)
                      @if($photo->scene_id == $scene->scene_id)
                      <?php $cnt++; ?>
                      <li data-target="#myCarousel{{$scene->scene_id}}" data-slide-to="{{$cnt}}" {{ $cnt == 0 ? 'class="active"' : ''}}></li>
                      @endif
                  @endforeach
              @else
                  <li data-target="#myCarousel{{$scene->scene_id}}" data-slide-to="0" class="active"></li>
              @endif
          </ol>

          <!-- Wrapper for slides -->
          <div class="carousel-inner">
              @if(isset($photos[0]->path))
                  <?php $cnt = -1; ?>
                  @foreach($photos as $photo)
                      @if($photo->scene_id == $scene->scene_id)
                          <?php
                            $ex = explode('.',$photo->path);
                          ?>
                          <div class="item {{ $cnt == 0 ? 'active':''}}">
                              <a href="{{$photo->path}}" download="image.{{end($ex)}}"><img data-src="{{$photo->path}}" class="lazyload" style="margin:auto;"></a>
                          </div>
                      @endif
                  @endforeach
            @else
                <div class="item active">
                    <img data-src="http://placehold.it/640x640/27709b/ffffff" class="lazyload" data-sizes="auto" style="margin:auto;">
                </div>
            @endif
          </div>
          <a class="left carousel-control" href="#myCarousel{{$scene->scene_id}}" data-slide="prev">
            <span class="glyphicon glyphicon-chevron-left"></span>
          </a>
          <a class="right carousel-control" href="#myCarousel{{$scene->scene_id}}" data-slide="next">
            <span class="glyphicon glyphicon-chevron-right"></span>
          </a>
        </div>
      </div>
    </div>
</div>
