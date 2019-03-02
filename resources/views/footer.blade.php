<!--footer-->
<div class="footer">
    <div class="foot-bots">
        <div class="bots-box">
            @isset($footer['navmenus'])
            <div id="navmenus-boxs" class="bots-box-top">
                @foreach ($footer['navmenus'] as $item)
                <div class="ft-ul @if($loop->last) last-uls @endif">
                    <a class="ft-li">{{$item->title}}</a>
                    @foreach ($item->childs as $_item)
                        @if ($_item->type =='default')
                        <a href="javascript:void(0);" class="ft-li">{{$_item->title}}</a>
                        @elseif($_item->type =='jump')
                        <a href="{{$_item->linkuri}}" class="ft-li">{{$_item->title}}</a>
                        @elseif($_item->type =='image')
                        <a class="ft-li mr-shows ew-hov">
                            {{$_item->title}}
                            <div class="ewm-box">
                                <img src="{{$_item->linkuri}}" alt="{{$_item->title}}">
                            </div>
                        </a>
                        @endif
                    @endforeach
                </div>
                @endforeach
            
                @if (count($footer['configs']))
                <div class="map-wrap">
                    <div class="map-img" style="background:url({{$footer['configs']['thumb']}}) no-repeat center"></div>
                    <div class="map-txts">
                        <div class="fwrs">服务热线：<span>{{$footer['configs']['service_hotline']}}</span></div>
                        <div class="map-txt-title">顶呱呱已覆盖主要城市</div>
                        <p class="map-txt-cont">{{$footer['configs']['service_citys']}}</p>
                    </div>
                </div> 
                @endif
            </div>
            @endisset

            @isset($footer['friendly'])
            <div class="bots-box-mid">
                @isset($footer['friendly']['group']))
                    <p style="margin-bottom: 14px;">
                        <span>集团站群：</span>
                        @foreach ($footer['friendly']['group'] as $item)
                            <a href="{{$item['linkuri']}}">{{$item['title']}}</a>
                        @endforeach
                    </p>
                @endisset
                @isset($footer['friendly']['other'])
                    <p>
                        <span>友情链接：</span>
                        @foreach ($footer['friendly']['other'] as $item)
                            <a href="{{$item['linkuri']}}">{{$item['title']}}</a>
                        @endforeach
                    </p> 
                @endisset
            </div>
            @endisset
            <div class="bots-box-bot">
                <p>{{$footer['configs']['copyright'] ?? ''}}&nbsp;&nbsp;{{$footer['configs']['icp'] ?? ''}}</p>
            </div>
        </div>
    </div>
</div>
<!--/footer-->