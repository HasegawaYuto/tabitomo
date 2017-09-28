<div class="modal fade" id="modal_carousel">
  <div class="modal-dialog">
    <div class="modal-content">
      <!--div id="carousel-modal-demo" class="carousel slide" data-ride="carousel"-->
        <!--div class="carousel-inner"-->
          @foreach($photos as $photo)
          <?php
            $dataImage = base64_encode($photo->data);
          ?>
          <div class="item">
            <img src="data:{{$photo->mime}};base64,{{$dataImage}}"/>
          </div>
          @endforeach
        <!--/div-->
      <!--/div-->
    </div>
  </div>
</div>
