<nav class="sidebar sidebar-offcanvas" id="sidebar">
    <ul class="nav">
        <li class="nav-item">
            <a class="nav-link" data-bs-toggle="collapse" href="#selectHousing" aria-expanded="false"
                aria-controls="selectHousing">
                <i class="mdi mdi-home-search menu-icon"></i>
                <span class="menu-title">Select Housing</span>
                <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="selectHousing">
                <ul class="nav flex-column sub-menu" id="selectHousing">
                    @foreach ($housings as $housing)
                        <li class="nav-item">
                            <a class="nav-link select-housing"
                                href="{{ route('superadmin.dashboard', ['housingId' => $housing->id, 'housingName' => $housing->housing_name]) }}"
                                data-housing-id="{{ $housing->id }}" data-housing-name="{{ $housing->housing_name }}">
                                {{ $housing->housing_name }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>
        </li>

        <li class="nav-item">
            @if (auth()->user() && auth()->user()->role_as == 0)
                <a class="nav-link" data-bs-toggle="collapse" href="#manageHousing" aria-expanded="false"
                    aria-controls="manageHousing">
                    <i class="mdi mdi-home-edit menu-icon"></i>
                    <span class="menu-title">Manage Housing</span>
                    <i class="menu-arrow"></i>
                </a>
                <div class="collapse" id="manageHousing">
                    <ul class="nav flex-column sub-menu">
                        @foreach ($housings as $housing)
                            <li class="nav-item">
                                <a class="nav-link manage-housing"
                                    href="{{ route('admin.manageHousing', ['housingId' => $housing->id, 'housingName' => $housing->housing_name]) }}"
                                    data-housing-id="{{ $housing->id }}"
                                    data-housing-name="{{ $housing->housing_name }}">
                                    {{ $housing->housing_name }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </li>

        <li class="nav-item">
            @if (auth()->user() && auth()->user()->role_as == 0)
                <a class="nav-link" data-bs-toggle="collapse" href="#addHousing" aria-expanded="false"
                    aria-controls="addHousing">
                    <i class="mdi mdi-home-plus menu-icon"></i>
                    <span class="menu-title">Add New Housing</span>
                    <i class="menu-arrow"></i>
                </a>
                <div class="collapse" id="addHousing">
                    <ul class="nav flex-column sub-menu">
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('admin.import') }}">Import Excel</a>
                        </li>
                    </ul>
                </div>
            @endif
        </li>

        <li class="nav-item">
            @if (auth()->user() && auth()->user()->role_as == 0)
                <a class="nav-link" data-bs-toggle="collapse" href="#manageAdmins" aria-expanded="false"
                    aria-controls="manageAdmins">
                    <i class="mdi mdi-account-plus menu-icon"></i>
                    <span class="menu-title">Create Admins</span>
                    <i class="menu-arrow"></i>
                </a>
                <div class="collapse" id="manageAdmins">
                    <ul class="nav flex-column sub-menu">
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('admin.index') }}">Manage Admins</a>
                        </li>
                    </ul>
                </div>
            @endif
        </li>
        <li class="nav-item">
            @if (auth()->user() && auth()->user()->role_as == 0)
                <a class="nav-link" href="{{ route('admin.report') }}">
                    <i class="mdi mdi-chart-bar menu-icon"></i>
                    <span class="menu-title">Display Reports</span>
                </a>
            @endif
        </li>
    </ul>
</nav>
