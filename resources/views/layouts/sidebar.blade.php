<div class="collapse navbar-collapse navbar-ex1-collapse">
    <ul class="nav navbar-nav side-nav">
        @foreach($menu_items as $menu_item)
            @if(!$menu_item->action_id and !$menu_item->parrent_id)
                @if($enterprise->parent_id != 0 and $menu_item->id == 8)
                    @continue
                @endif
                <li>
                    <a href="javascript:;" data-toggle="collapse" data-target="#demo_{{$menu_item->id}}">{{$menu_item->name}} <i class="fa fa-fw fa-caret-down"></i></a>
                    <ul id="demo_{{$menu_item->id}}" class="collapse">
                        @foreach($menu_items as $menu_child)
                            @if($menu_child->parent_id == $menu_item->id)
                                <li>
                                    <a href="{{config('ems.prefix') . $enterprise->namespace . $menu_child->link}}">{{$menu_child->name}}</a>
                                </li>
                            @endif
                        @endforeach
                    </ul>
                </li>
            @elseif($menu_item->parent_id == null)
                <li>
                    <a href="{{config('ems.prefix') . $enterprise->namespace}}{{$menu_item->link}}">{{$menu_item->name}}</a>
                </li>
            @endif
        @endforeach
    </ul>
</div>