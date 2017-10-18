@if(isset($users[0]))
<div class="modal fade" id="modalFavoriteUsers{{$key}}">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4>お気に入りしているユーザー</h4>
            </div>
            <div class="modal-body">
                @foreach($users as $thefavuser)
                <a href="{{route('show_user_profile',['id'=>$thefavuser->id])}}">
                <div style="width:50px;display:inline-block;margin:5px;" class="text-center">
                    @include('parts.avatar',['user'=>$thefavuser,'class'=>'avatarInner'])
                    <p class="smallp overCut black">{{isset($thefavuser->nickname)?$thefavuser->nickname:'未設定'}}</p>
                </div>
                </a>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endif