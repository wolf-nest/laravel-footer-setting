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
                        <a href="javascript:void(0);" title="{{$_item->title}}" class="ft-li">{{$_item->title}}</a>
                        @elseif($_item->type =='jump')
                        <a target="_blank" title="{{$_item->title}}" href="{{$_item->linkuri}}" class="ft-li">{{$_item->title}}</a>
                        @elseif($_item->type =='image')
                        <a href="javascript:void(0);" title="{{$_item->title}}" class="ft-li mr-shows ew-hov">
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
                    <div class="map-img">
                        @isset($footer['configs']['thumb'])
                            <img src="{{$footer['configs']['thumb']}}" alt="{{$footer['configs']['service_citys'] ?? ''}}"/>
                        @endisset
                    </div>
                    <div class="map-txts">
                        @isset($footer['configs']['service_hotline'])
                            <div class="fwrs">服务热线：<span>{{$footer['configs']['service_hotline']}}</span></div>
                        @endisset
                        @isset($footer['configs']['service_citys'])
                            <div class="map-txt-title">顶呱呱已覆盖主要城市</div>
                            <p class="map-txt-cont">{{$footer['configs']['service_citys']}}</p>
                        @endisset
                    </div>
                </div> 
                @endif
            </div>
            @endisset

            @isset($footer['friendly'])
            <div class="bots-box-mid">
                @isset($footer['friendly']['group'])
                    <p style="margin-bottom: 14px;">
                        <span>集团站群：</span>
                        @foreach ($footer['friendly']['group'] as $item)
                        <a target="_blank" title="{{$item['title']}}" href="{{$item['linkuri']}}">{{$item['title']}}</a>
                        @endforeach
                    </p>
                @endisset
                @isset($footer['friendly']['other'])
                    <p>
                        <span>友情链接：</span>
                        @foreach ($footer['friendly']['other'] as $item)
                        <a target="_blank" title="{{$item['title']}}" href="{{$item['linkuri']}}">{{$item['title']}}</a>
                        @endforeach
                    </p> 
                @endisset
            </div>
            @endisset
            @isset($footer['configs'])
                <div class="bots-box-bot">
                    <p>{{$footer['configs']['copyright'] ?? ''}}&nbsp;&nbsp;{{$footer['configs']['icp'] ?? ''}}</p>
                </div> 
            @endisset
        </div>
    </div>
</div>
<!--/footer-->