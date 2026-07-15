<aside class="sidebar-wrapper" data-simplebar="true">
    <div class="sidebar-header">
      <div class="logo-icon">
        <img src="{{ asset('template/assets/images/logo-mbi.png') }}" class="logo-img" alt="">
      </div>
      <div class="logo-name flex-grow-1">
        <h5 class="mb-0">DBSurat@mbi</h5>
      </div>
      <div class="sidebar-close">
        <span class="material-icons-outlined">close</span>
      </div>
    </div>
    <div class="sidebar-nav">

        <ul class="metismenu" id="sidenav">

            @foreach($components as $component => $items)

                @php
                    $isOpen = $items->contains(function ($item) {
                        return request()->routeIs($item->route . '*');
                    });
                @endphp

                <li class="{{ $isOpen ? 'mm-active' : '' }}">

                    <a href="javascript:;" class="has-arrow {{ $isOpen ? 'active' : '' }}">

                        <div class="parent-icon">
                            <i class="material-icons-outlined">
                                {{ $items->first()->comp_icon }}
                            </i>
                        </div>

                        <div class="menu-title">
                            {{ $component }}
                        </div>

                    </a>

                    <ul class="{{ $isOpen ? 'mm-show' : '' }}">

                        @foreach($items as $item)

                            @php
                                $isActive = request()->routeIs($item->route . '*');
                            @endphp

                            <li>

                                <a href="{{ route($item->route) }}"
                                    class="{{ $isActive ? 'active' : '' }}">

                                    <i class="material-icons-outlined">
                                        arrow_right
                                    </i>

                                    {{ $item->sub_components_name }}

                                </a>

                            </li>

                        @endforeach

                    </ul>

                </li>

            @endforeach

        </ul>

    </div>
  </aside>