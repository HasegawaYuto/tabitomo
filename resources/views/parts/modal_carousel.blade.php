@if(isset($photos))
@foreach($scenes as $scene)
<div class="modal fade" id="modal_carousel{{$scene->scene_id}}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel{{$scene->scene_id}}" aria-hidden="true">
  <!--div class="modal-dialog"-->
    <div class="modal-content" style="height:100vh;width:100vw;">
      <div class="modal-header" style="background-color:red;">
          【{{$scene->title}}】{{$scene->scene}}
          <button type="button" class="btn btn-default close" data-dismiss="modal"><span aria-hidden="true">&times;</span></button>
      </div>
      <div class="modal-body" style="background-color:blue;">
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
            <div class="item {{ $cnt == 0 ? 'active':''}}" style="height:80vh;width:100vw;text-align:center;">
              <img data-src="data:{{$photo->mime}};base64,{{$dataPhoto}}" class="lazyload img-responsive" style="max-height:100%;max-width:100%;height:100%;margin:auto;">
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
    </div><!-- /.modal-content -->
  <!--/div--><!-- /.modal-dialog -->
</div><!-- /.modal -->
@endforeach
@endif
