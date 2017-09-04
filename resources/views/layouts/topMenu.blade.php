<ul class="nav navbar-right top-nav">
    @if(Auth::user())
        @if (Auth::check() && Session::has('auth_from_admin_asd'))
            <li><a href = "{{config('ems.prefix') . $enterprise->namespace}}/user/list/gback">Go back to my profile</a></li>
        @endif
        @if (Auth::check() && Session::has('old_adm_namespace'))
            <li><a href = "{{config('ems.prefix') . session('old_adm_namespace')}}/">Go back to my organization</a></li>
        @endif
        <li><a>{{Auth::user()->is_superadmin ? 'You are SuperAdmin!': ''}}</a></li>
        <li class="dropdown">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown" title="{{Auth::user()->is_active? '': 'this user is not active'}}">
                @if (Auth::user()->avatar)
                    <img src="{{config('ems.prefix') . $enterprise->namespace}}/user/avatar" class="img-circle user-avatar" alt="">
                @else
                <i class="fa fa-user {{Auth::user()->is_active? '': 'user-not-active'}}"></i>
                @endif
                {{Auth::user()->first_name}} {{Auth::user()->last_name}} <b class="caret"></b>
            </a>
            <ul class="dropdown-menu">
                @if(!session('security_code'))
                <li>
                    <a href="{{config('ems.prefix') . $enterprise->namespace}}/Users/Settings/userProfile"><i class="fa fa-fw fa-user"></i>Profile</a>
                </li>
                <li>
                    <a href="{{config('ems.prefix') . $enterprise->namespace}}/user/changePassword"><i class="fa fa-fw fa-key"></i>Change password</a>
                </li>
                <li class="divider"></li>
                @endif
                <li>
                    <a href="{{ route('logout', ['namespace'=>$enterprise->namespace]) }}"
                       onclick="event.preventDefault();
                                                         document.getElementById('logout-form').submit();">
                        <i class="fa fa-fw fa-power-off"></i> Logout
                    </a>

                    <form id="logout-form" action="{{ route('logout', ['namespace'=>$enterprise->namespace]) }}" method="POST" style="display: none;">
                        {{ csrf_field() }}
                    </form>
                </li>
            </ul>
        </li>
    @endif
</ul>