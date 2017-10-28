<div class="col-xs-12">
<a class="btn btn-default" data-toggle="collapse" href="#collapseExample">
    条件検索
</a>
@if(Request::is('search'))
{!! Form::open(['route'=>'break_condition','style'=>'display:inline;']) !!}
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
        <li><a href="#tab2" data-toggle="tab">時期</a></li>
        <li><a href="#tab3" data-toggle="tab">地域</a></li>
        <li><a href="#tab4" data-toggle="tab">ジャンル</a></li>
    </ul>
    {!! Form::open(['route'=>'show_items_search']) !!}
    <input type="hidden" name="_token" value="{{ csrf_token() }}">
    <div class="tab-content tabContents">
        <div class="tab-pane active" id="tab1">
            <div class="col-xs-10 col-xs-offset-1 searchArea">
            <div class="form-group">
                {!! Form::label('keywords','キーワード') !!}
                {!! Form::text('keywords',old('keywords'),['class'=>'form-control','placeholder'=>'キーワード']) !!}
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
            <div class="col-xs-12 col-sm-10 col-md-8 col-lg-6">
              <input type="hidden" name="lat" id="centerLat" value="{{old('lat')}}">
              <input type="hidden" name="lng" id="centerLng" value="{{old('lng')}}">
              <input type="hidden" name="radius" id="circleRadius" value="{{old('radius')}}">
              <div id="searchMap" class="col-xs-12"></div>
            </div>
        </div>
        <div class="tab-pane" id="tab4">
            <div class="col-xs-10 col-xs-offset-1  searchArea">
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
            </div>
        </div>
    </div>
    {!! Form::submit('検索',['class'=>'btn btn-danger btn-block'])!!}
    {!!Form::close()!!}
</div>
</div>
</div>
