<div class="col-xs-12">
<a class="btn btn-default" data-toggle="collapse" href="#collapseExample">
    条件検索
</a>
@if(Request::is('guides/search') || Request::is('guests/search'))
@if(Request::is('guides/search'))
{!! Form::open(['route'=>'break_guides_condition','style'=>'display:inline;']) !!}
@else
{!! Form::open(['route'=>'break_travelers_condition','style'=>'display:inline;']) !!}
@endif
{!! Form::submit('条件取消',['class'=>'btn btn-danger']) !!}
{!! Form::close() !!}
@endif
<div class="collapse" id="collapseExample">
<div class="panel panel-warning">
    <div class="panel-heading">
        探す
    </div>
    <ul class="nav nav-pills tabArea">
        <li class="active"><a href="#tab1" data-toggle="tab">ワード</a></li>
        <li><a href="#tab2" data-toggle="tab">期日</a></li>
        <li><a href="#tab3" data-toggle="tab">地域</a></li>
        <li><a href="#tab4" data-toggle="tab">人物</a></li>
    </ul>
    @if(Request::is('guides'))
    {!! Form::open(['route'=>'search_guides','style'=>'display:inline;']) !!}
    @else
    {!! Form::open(['route'=>'search_travelers','style'=>'display:inline;']) !!}
    @endif
    <div class="tab-content tabContents">
        <div class="tab-pane active" id="tab1">
            <div class="col-xs-10 col-xs-offset-1 searchArea">
            <div class="form-group">
                {!! Form::label('keywords','キーワード') !!}
                {!! Form::text('keywords',null,['class'=>'form-control','placeholder'=>'キーワード']) !!}
            </div>
          </div>
        </div>
        <div class="tab-pane" id="tab2">
            <div class="col-xs-10 col-xs-offset-1 searchArea">
            <?php
                $thisyear=Carbon\Carbon::now()->year;
            ?>
            <label>ワイルドカード方式</label>
            <div class="form-group form-inline">
                <select name="year1" id="yearSelect1" class="form-control">
                  @for($year=$thisyear;$year>=1900;$year--)
                      <option value="{{$year}}" {{old('year1')==$year?'selected':''}}>{{$year}}</option>
                  @endfor
                  <option value="0000" {{old('year1')==null||old('year1')=="0000"?'selected':''}}>----</option>
                </select>
                <label>年</label>
                <select name="month1" id="monthSelect1" class="form-control">
                    @for($month=12;$month>=1;$month--)
                        <?php
                          $month0 = str_pad($month, 2, 0, STR_PAD_LEFT);
                        ?>
                        <option value="{{$month0}}" {{old('month1')==$month0?'selected':''}}>{{$month}}</option>
                    @endfor
                    <option value="00" {{old('month1')==null||old('month1')=="00"?'selected':''}}>--</option>
                </select>
                <label>月</label>
                <select name="day1" id="daySelect1" class="form-control">
                    @for($day=31;$day>=1;$day--)
                        <?php
                            $day0 = str_pad($day, 2, 0, STR_PAD_LEFT);
                        ?>
                        <option data-val="{{$day0}}" value="{{$day0}}"  {{old('day1')==$day0?'selected':''}}>{{$day}}</option>
                    @endfor
                    <option data-val="00" value="00"  {{old('day1')==null||old('day1')=="00"?'selected':''}}>--</option>
                </select>
                <label>日</label>
            </div>
            <div class="wrap smallp" style="margin-bottom:10px;">【例】「2017年8月--日」とした場合、「2017年」、「8月」に該当するものを表示します。<br>
            「----年9月--日」とした場合、「9月」に該当するものを表示します。
            </div>
            <label>範囲指定</label>
            <div class="form-group form-inline">
                <select name="year2" id="yearSelect2" class="form-control">
                  @for($year=$thisyear;$year>=1900;$year--)
                      <option value="{{$year}}" {{old('year2')==$year?'selected':''}}>{{$year}}</option>
                  @endfor
                  <option value="0000" {{old('year2')==null||old('year2')=="0000"?'selected':''}}>----</option>
                </select>
                <label>年</label>
                <select name="month2" id="monthSelect2" class="form-control">
                    @for($month=12;$month>=1;$month--)
                        <?php
                          $month0 = str_pad($month, 2, 0, STR_PAD_LEFT);
                        ?>
                        <option value="{{$month0}}" {{old('month2')==$month0?'selected':''}}>{{$month}}</option>
                    @endfor
                    <option value="00" {{old('month2')==null||old('month2')=="00"?'selected':''}}>--</option>
                </select>
                <label>月</label>
                <select name="day2" id="daySelect2" class="form-control">
                    @for($day=31;$day>=1;$day--)
                        <?php
                            $day0 = str_pad($day, 2, 0, STR_PAD_LEFT);
                        ?>
                        <option data-val="{{$day0}}" value="{{$day0}}" {{old('day2')==$day0?'selected':''}}>{{$day}}</option>
                    @endfor
                    <option data-val="00" value="00" {{old('day2')==null||old('day2')=="00"?'selected':''}}>--</option>
                </select>
                <label>日以降</label>
            </div>
            <div class="form-group form-inline">
                <select name="year3" id="yearSelect3" class="form-control">
                  @for($year=$thisyear;$year>=1900;$year--)
                      <option value="{{$year}}" {{old('year3')==$year?'selected':''}}>{{$year}}</option>
                  @endfor
                  <option value="0000" {{old('year3')==null||old('year3')=="0000"?'selected':''}}>----</option>
                </select>
                <label>年</label>
                <select name="month3" id="monthSelect3" class="form-control">
                    @for($month=12;$month>=1;$month--)
                        <?php
                          $month0 = str_pad($month, 2, 0, STR_PAD_LEFT);
                        ?>
                        <option value="{{$month0}}" {{old('month2')==$month0?'selected':''}}>{{$month}}</option>
                    @endfor
                    <option value="00" {{old('month3')==null||old('month3')=="00"?'selected':''}}>--</option>
                </select>
                <label>月</label>
                <select name="day3" id="daySelect3" class="form-control">
                    @for($day=31;$day>=1;$day--)
                        <?php
                            $day0 = str_pad($day, 2, 0, STR_PAD_LEFT);
                        ?>
                        <option data-val="{{$day0}}" value="{{$day0}}" {{old('day3')==$day0?'selected':''}}>{{$day}}</option>
                    @endfor
                    <option data-val="00" value="00" {{old('day3')==null||old('day3')=="00"?'selected':''}}>--</option>
                </select>
                <label>日まで</label>
            </div>
          </div>
        </div>
        <div class="tab-pane" id="tab3">
            <div class="col-xs-12 col-xm-10 col-md-8 col-lg-6">
              <input type="hidden" name="lat" id="centerLat" value="{{old('lat')!=null?old('lat'):'0'}}">
              <input type="hidden" name="lng" id="centerLng" value="{{old('lng')!=null?old('lat'):'0'}}">
              <input type="hidden" name="radius" id="circleRadius" value="{{old('radius')!=null?old('radius'):'0'}}">
              <div id="searchMap" class="col-xs-12"></div>
            </div>
        </div>
        <div class="tab-pane" id="tab4">
            <div class="col-xs-10 col-xs-offset-1 searchArea">
                <label>性別</label>
                <div class="form-group form-inline">
                  <?php
                  $rdM ='';
                  $rdF='';
                  $rdO='';
                  if(old('sex')!=null&&old('sex')=="男性"){
                      $rdM='true';
                  }
                  if(old('sex')!=null&&old('sex')=="女性"){
                      $rdF='true';
                  }
                  if(old('sex')!=null&&old('sex')=="その他"){
                      $rdO='true';
                  }
                  ?>
                  {!!Form::radio('sex', '男性',$rdM,['class'=>'form-control'])!!}
                  {!!Form::label('sex','男性')!!}
                  {!!Form::radio('sex', '女性',$rdF,['class'=>'form-control'])!!}
                  {!!Form::label('sex','女性')!!}
                  {!!Form::radio('sex', 'その他',$rdO,['class'=>'form-control'])!!}
                  {!!Form::label('sex','その他')!!}
                </div>
                <label>年齢</label>
                <div class="form-group form-inline">
                  <input type="number" name="age" class="form-control" value="{{old('age')!=0?old('age'):'0'}}">
                  {!!Form::label('age','歳')!!}
                  {!!Form::select('agetype', [
                      'e' => '----',
                      'i' => '以上',
                      's' => '以下']
                    ,old('agetype'),['class'=>'form-control'])!!}
                </div>
            </div>
        </div>
    </div>
    {!! Form::submit('検索',['class'=>'btn btn-danger btn-block'])!!}
    {!!Form::close()!!}
</div>
</div>
</div>
