<div class="sidebar">
      <div class="sidebar-wrapper">
        <!-- <div class="logo">
          <a href="javascript:void(0)" class="simple-text logo-mini">
            CT
          </a>
          <a href="javascript:void(0)" class="simple-text logo-normal">
            Survey
          </a>
        </div> -->
        @if(auth()->user()->role == 'admin')
        <ul class="nav">
          <li class="{{request()->is('home') ? 'active' : ''}}">
            <a href="{{route('home')}}">
              <i class="tim-icons icon-chart-pie-36"></i>
              <p>Dashboard</p>
            </a>
          </li>
          <li class="{{request()->is('users') ? 'active' : ''}}">
            <a href="{{route('admin.users.index')}}">
              <i class="tim-icons icon-chart-pie-36"></i>
              <p>Users</p>
            </a>
          </li>

          <li class="{{request()->is('surveys') ? 'active' : ''}}">
            <a href="{{route('admin.surveys.index')}}">
              <i class="tim-icons icon-chart-pie-36"></i>
              <p>Surveys</p>
            </a>
          </li>

          <li class="{{request()->is('withdrawals') ? 'active' : ''}}">
            <a href="{{route('admin.withdrawals.withdrawal_requests')}}">
              <i class="tim-icons icon-chart-pie-36"></i>
              <p>Withdrawals</p>
            </a>
          </li>
<!-- 
          <li>
            <a href="./icons.html">
              <i class="tim-icons icon-atom"></i>
              <p>Icons</p>
            </a>
          </li>
          <li>
            <a href="./map.html">
              <i class="tim-icons icon-pin"></i>
              <p>Maps</p>
            </a>
          </li>
          <li>
            <a href="./notifications.html">
              <i class="tim-icons icon-bell-55"></i>
              <p>Notifications</p>
            </a>
          </li>
          <li>
            <a href="./user.html">
              <i class="tim-icons icon-single-02"></i>
              <p>User Profile</p>
            </a>
          </li>
          <li>
            <a href="./tables.html">
              <i class="tim-icons icon-puzzle-10"></i>
              <p>Table List</p>
            </a>
          </li>
          <li>
            <a href="./typography.html">
              <i class="tim-icons icon-align-center"></i>
              <p>Typography</p>
            </a>
          </li>
          <li>
            <a href="./rtl.html">
              <i class="tim-icons icon-world"></i>
              <p>RTL Support</p>
            </a>
          </li>
          <li class="active-pro">
            <a href="./upgrade.html">
              <i class="tim-icons icon-spaceship"></i>
              <p>Upgrade to PRO</p>
            </a>
          </li> -->
        </ul>
        @endif


        @if(auth()->user()->role == 'user')
        <ul class="nav">
          <li class="{{request()->is('home') ? 'active' : ''}}">
            <a href="{{route('home')}}">
              <i class="tim-icons icon-chart-pie-36"></i>
              <p>Dashboard</p>
            </a>
          </li>

          <li class="{{request()->is('available_surveys') ? 'active' : ''}}">
            <a href="{{route('user.available_surveys')}}">
              <i class="tim-icons icon-chart-pie-36"></i>
              <p>Available Surveys</p>
            </a>
          </li>

          <li class="{{request()->is('earnings') ? 'active' : ''}}">
            <a href="{{route('user.earnings')}}">
              <i class="tim-icons icon-chart-pie-36"></i>
              <p>Earnings</p>
            </a>
          </li>
        </ul>
        @endif
      </div>
    </div>