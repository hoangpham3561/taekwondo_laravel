<div class="iq-sidebar">
    <div id="sidebar-scrollbar">
        <nav class="iq-sidebar-menu">
            <ul id="iq-sidebar-toggle" class="iq-menu">
                <li class="@if(request()->is('/')) active @endif">
                    <a href="{{ route('.index') }}" class="iq-waves-effect">
                        <div class="icon-thumb">
                            <div class="basice"><img src="{{ asset('frontend/images/menu/1-1.svg') }}" alt=""></div>
                            <div class="hover"><img src="{{ asset('frontend/images/menu/1-2.svg') }}" alt=""></div>
                        </div>
                        <span>Dashboard</span>
                    </a>
                </li>
                <li class="@if(request()->is('wallet')) active @endif">
                    <a href="{{ route('.wallet') }}" class="iq-waves-effect">
                        <div class="icon-thumb">
                            <div class="basice"><img src="{{ asset('frontend/images/menu/2-1.svg') }}" alt=""></div>
                            <div class="hover"><img src="{{ asset('frontend/images/menu/2-2.svg') }}" alt=""></div>
                        </div>
                        <span>Wallet</span>
                    </a>
                </li>
                <li class="@if(request()->is('manual-trade')) active @endif">
                    <a href="{{ route('.manual-trade') }}" class="iq-waves-effect">
                        <div class="icon-thumb">
                            <div class="basice"><img src="{{ asset('frontend/images/menu/3-1.svg') }}" alt=""></div>
                            <div class="hover"><img src="{{ asset('frontend/images/menu/3-2.svg') }}" alt=""></div>
                        </div>
                        <span>Manual trade</span>
                    </a>
                </li>
                <li class="@if(request()->is('network')) active @endif">
                    <a href="{{ route('.network') }}" class="iq-waves-effect">
                        <div class="icon-thumb">
                            <div class="basice"><img src="{{ asset('frontend/images/menu/4-1.svg') }}" alt=""></div>
                            <div class="hover"><img src="{{ asset('frontend/images/menu/4-2.svg') }}" alt=""></div>
                        </div>
                        <span>Network</span>
                    </a>
                </li>
                <li class="@if(request()->is('settings')) active @endif">
                    <a href="{{ route('.settings') }}" class="iq-waves-effect">
                        <div class="icon-thumb">
                            <div class="basice"><img src="{{ asset('frontend/images/menu/5-1.svg') }}" alt=""></div>
                            <div class="hover"><img src="{{ asset('frontend/images/menu/5-2.svg') }}" alt=""></div>
                        </div>
                        <span>Settings</span>
                    </a>
                </li>
                <li class="li-bottom">
                    <a href="{{ route('.logout') }}" class="iq-waves-effect">
                        <div class="icon-thumb">
                            <div class="basice"><img src="{{ asset('frontend/images/menu/6-1.svg') }}" alt=""></div>
                            <div class="hover"><img src="{{ asset('frontend/images/menu/6-2.svg') }}" alt=""></div>
                        </div>
                        <span>Logout</span>
                    </a>
                </li>
            </ul>
        </nav>
    </div>

</div>
