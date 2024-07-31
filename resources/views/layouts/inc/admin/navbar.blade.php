<nav class="navbar col-lg-12 col-12 p-0 fixed-top d-flex flex-row">
    <div class="navbar-brand-wrapper d-flex justify-content-center">
        <div class="navbar-brand-inner-wrapper d-flex justify-content-between align-items-center w-100">
            <a class="navbar-brand brand-logo" href="#">Society Mitra</a>
            <a class="navbar-brand brand-logo-white" href="#"></a>
            <a class="navbar-brand brand-logo-mini" href="#"></a>
            <button class="navbar-toggler navbar-toggler align-self-center" type="button" data-toggle="minimize">
                <span class="mdi mdi-sort-variant"></span>
            </button>
        </div>
    </div>
    <div class="navbar-menu-wrapper d-flex align-items-center justify-content-end flex-grow-1">
        <!-- Heading for larger screens -->
        <h3 class="navbar-heading navbar-heading-large">Silicon La Vista Co Operative Housing and Commercial Service Society</h3>
        <!-- Heading for smaller screens -->
        <h3 class="navbar-heading navbar-heading-small">Silicon La Vista</h3>
        <ul class="navbar-nav navbar-nav-right">
            <li class="nav-item nav-profile dropdown" style="margin-right: 10px;">
                <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" data-bs-toggle="dropdown" id="profileDropdown">
                    {{-- <img src="{{ Auth::user()->profile_picture_url ?? asset('images/OIP.jpeg') }}" alt="Profile Picture" class="rounded-circle me-2" style="width: 30px; height: 30px;"> --}}

                    <span class="nav-profile-name">{{ Auth::user()->name }}</span>
                </a>                
                <div class="dropdown-menu dropdown-menu-right navbar-dropdown" aria-labelledby="profileDropdown">
                    <a class="dropdown-item" href="{{ route('logout') }}"
                        onclick="event.preventDefault();
                          document.getElementById('logout-form').submit();">
                        <i class="mdi mdi-logout text-primary"></i>{{ __('Logout') }}
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                </div>
            </li>
        </ul>
        <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button" data-toggle="offcanvas">
            <span class="mdi mdi-menu"></span>
        </button>
    </div>
</nav>

<style>
     .navbar {
        background-color: white;
        padding: 10px 15px;
        align-items: flex-start;
        min-height: 60px;
    }

    .navbar-menu-wrapper {
        flex-grow: 1;
        padding-left: 15px;
        padding-right: 15px;
        display: flex;
        justify-content: flex-start; /* Ensure left alignment */
    }

    .navbar-heading {
        padding-top: 10px;
        color: #333;
        font-size: 23px;
        font-weight: bold;
        text-shadow: 1px 1px 1px #ccc;
        margin-right: auto;
        text-align: center;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .navbar-heading-small {
        display: none;
    }

    @media (max-width: 768px) {
        .navbar {
            padding: 15px;
            flex-direction: column;
            align-items: flex-start;
        }

        .navbar-heading-large {
            display: none;
        }

        .navbar-heading-small {
            display: block;
            font-size: 18px;
            white-space: normal;
            text-align: center;
            width: 100%;
            padding-top: 0;
            margin-bottom: 10px;
        }

        .navbar-menu-wrapper {
            justify-content: flex-start; /* Ensure left alignment */
            padding-left: 0;
            padding-right: 0;
        }

        .navbar-nav-right {
            flex-direction: row;
            justify-content: flex-start;
        }
    }
</style>
