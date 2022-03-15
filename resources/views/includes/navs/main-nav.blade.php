<style>
    /* Fixes dropdown menus placed on the right side */
    .ml-auto .dropdown-menu {
      left: auto !important;
      right: 0px;
    }
</style>


<nav class="navbar navbar-expand-lg navbar-dark mb-2">
        @auth
            <a class="navbar-brand" href="{{ url('/home') }}">{{ config('app.name', 'Laravel') }}</a>
        @endauth
        @guest
            <a class="navbar-brand" href="{{ url('/') }}">{{ config('app.name', 'Laravel') }}</a>
        @endguest
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNavDropdown">

            <ul class="navbar-nav">

            @guest
            <!-- If the person not registrated he can do that by clicking the registration button -->
            <!-- But if the perseon already registrated he can click on the login -->
            <!-- This part of the menu can be seen by not logging in -->
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                </li>
            <!-- -->
            @endguest

            @auth
            <!-- This part of the menu avabile for admins, authors and users -->

                <!-- Link that redirect back to homepage--> 
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('home') }}">
                    <svg style="width:24px;height:24px;" viewBox="0 0 24 24">
                        <path fill="white" d="M10,20V14H14V20H19V12H22L12,3L2,12H5V20H10Z" />
                    </svg>
                        Home
                    </a>
                </li>
                <!--  -->

                <!-- Links so we can read news-->  
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('posts') }}">
                        <svg style="width:24px;height:24px" viewBox="0 0 24 24">
                            <path fill="white" d="M16,15H9V13H16V15M19,11H9V9H19V11M19,7H9V5H19V7M3,5V21H19V23H3A2,2 0 0,1 1,21V5H3M21,1A2,2 0 0,1 23,3V17C23,18.11 22.11,19 21,19H7A2,2 0 0,1 5,17V3C5,1.89 5.89,1 7,1H21M7,3V17H21V3H7Z" />
                        </svg>
                        News
                    </a>
                </li>
                <!--  -->

            <!--  -->
                @can('author-menu')

                <!-- This part of the menu avabile for admins and authors -->

                <!-- Links so we can handel posts-->  
                <div class="dropdown mr-1">
                    <button type="button" class="btn nav-link btn-transparent dropdown-toggle" id="dropdownMenuOffset" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" data-offset="10,10">
                        <svg style="width:24px;height:24px" viewBox="0 0 24 24">
                            <path fill="white" d="M2,6V8H14V6H2M2,10V12H14V10H2M20.04,10.13C19.9,10.13 19.76,10.19 19.65,10.3L18.65,11.3L20.7,13.35L21.7,12.35C21.92,12.14 21.92,11.79 21.7,11.58L20.42,10.3C20.31,10.19 20.18,10.13 20.04,10.13M18.07,11.88L12,17.94V20H14.06L20.12,13.93L18.07,11.88M2,14V16H10V14H2Z" />
                        </svg>
                        Handle Posts
                    </button>
                    <div class="dropdown-menu" aria-labelledby="dropdownMenuOffset">
                        <a class="dropdown-item" href="{{ route('author.posts.index') }}">My Post</a>
                        <a class="dropdown-item" href="{{ route('author.posts.create') }}">Create Post</a>
                        
                        @can('admin-menu')
                        
                        <!-- Link where the eadmin can handel all the post not only he owns -->
                        <a class="dropdown-item" href="{{ route('admin.posts.index') }}">List of Post</a>
                        <!-- -->

                        @endcan
                        
                        <a class="dropdown-item" href="#">Search Post</a>
                    </div>
                </div>
                <!--  -->

                <!--  -->

                @endcan

                @can('admin-menu')

                <!-- This part of the menu only avabile for admins -->

                <!-- Links so we can handel boats-->  
                <div class="dropdown mr-1">
                    <button type="button" class="btn nav-link btn-transparent dropdown-toggle" id="dropdownMenuOffset" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" data-offset="10,10">
                        <svg style="width:24px;height:24px" viewBox="0 0 24 24">
                            <path fill="white" d="M6,6H18V9.96L12,8L6,9.96M3.94,19H4C5.6,19 7,18.12 8,17C9,18.12 10.4,19 12,19C13.6,19 15,18.12 16,17C17,18.12 18.4,19 20,19H20.05L21.95,12.31C22.03,12.06 22,11.78 21.89,11.54C21.76,11.3 21.55,11.12 21.29,11.04L20,10.62V6C20,4.89 19.1,4 18,4H15V1H9V4H6A2,2 0 0,0 4,6V10.62L2.71,11.04C2.45,11.12 2.24,11.3 2.11,11.54C2,11.78 1.97,12.06 2.05,12.31M20,21C18.61,21 17.22,20.53 16,19.67C13.56,21.38 10.44,21.38 8,19.67C6.78,20.53 5.39,21 4,21H2V23H4C5.37,23 6.74,22.65 8,22C10.5,23.3 13.5,23.3 16,22C17.26,22.65 18.62,23 20,23H22V21H20Z" />
                        </svg>
                        Handle Boats
                    </button>
                    <div class="dropdown-menu" aria-labelledby="dropdownMenuOffset">
                        <a class="dropdown-item" href="{{ route('admin.boats.index') }}">List of Boats</a>
                        <a class="dropdown-item" href="{{ route('admin.boats.create') }}">Create Boat</a>
                    </div>
                </div>
                <!--  -->

                <!-- Links so we can handel Users-->  
                <div class="dropdown mr-1">
                    <button type="button" class="btn nav-link btn-transparent dropdown-toggle" id="dropdownMenuOffset" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" data-offset="10,10">
                        <svg style="width:24px;height:24px" viewBox="0 0 24 24">
                            <path fill="white" d="M16,13C15.71,13 15.38,13 15.03,13.05C16.19,13.89 17,15 17,16.5V19H23V16.5C23,14.17 18.33,13 16,13M8,13C5.67,13 1,14.17 1,16.5V19H15V16.5C15,14.17 10.33,13 8,13M8,11A3,3 0 0,0 11,8A3,3 0 0,0 8,5A3,3 0 0,0 5,8A3,3 0 0,0 8,11M16,11A3,3 0 0,0 19,8A3,3 0 0,0 16,5A3,3 0 0,0 13,8A3,3 0 0,0 16,11Z" />
                        </svg>
                        Handle Users
                    </button>
                    <div class="dropdown-menu" aria-labelledby="dropdownMenuOffset">
                        <a class="dropdown-item" href="{{ route('admin.users.index') }}">List of Users</a>
                        <a class="dropdown-item" href="{{ route('admin.users.search') }}">Search Users</a>
                    </div>
                </div>
                <!--  -->

                <!--  -->
                @endcan

            @endauth
            </ul>

        </div>
        @auth

        <!-- For every user so he can handel his profile-->  
        <div class="form-inline">
            <div class="dropdown">
                <a class="btn dropdown-toggle btn-orange text-white" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                <svg style="width:24px;height:24px" viewBox="0 0 24 24">
                    <path fill="white" d="M7.5,15C8.63,15 9.82,15.26 11.09,15.77C12.35,16.29 13,16.95 13,17.77V20H2V17.77C2,16.95 2.65,16.29 3.91,15.77C5.18,15.26 6.38,15 7.5,15M13,13H22V15H13V13M13,9H22V11H13V9M13,5H22V7H13V5M7.5,8A2.5,2.5 0 0,1 10,10.5A2.5,2.5 0 0,1 7.5,13A2.5,2.5 0 0,1 5,10.5A2.5,2.5 0 0,1 7.5,8Z" />
                </svg>
                    {{ Auth::user()->first_name }}
                </a>
                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuLink">

                    <!-- Clicking on this button user can check his profile and change profile data ... if i start doing it .... -->
                    <a class="dropdown-item" href="{{ route('user.index') }}">
                        <svg style="width:24px;height:24px" viewBox="0 0 24 24">
                            <path fill="black" d="M12,4A4,4 0 0,1 16,8A4,4 0 0,1 12,12A4,4 0 0,1 8,8A4,4 0 0,1 12,4M12,14C16.42,14 20,15.79 20,18V20H4V18C4,15.79 7.58,14 12,14Z" />
                        </svg>
                        Profile
                    </a>
                    <!-- -->

                    <!-- By clicking this button you can logout -->
                    <a class="dropdown-item" id="logoutbutton" href="{{ route('logout') }}">
                        <svg style="width:24px;height:24px" viewBox="0 0 24 24">
                            <path fill="black" d="M16.56,5.44L15.11,6.89C16.84,7.94 18,9.83 18,12A6,6 0 0,1 12,18A6,6 0 0,1 6,12C6,9.83 7.16,7.94 8.88,6.88L7.44,5.44C5.36,6.88 4,9.28 4,12A8,8 0 0,0 12,20A8,8 0 0,0 20,12C20,9.28 18.64,6.88 16.56,5.44M13,3H11V13H13" />
                        </svg>
                        {{ __('Logout') }}
                    </a>
                    <!-- -->

                    <!-- This form is used for logout called by clicking on logout button, submitting by click event listener -->
                    <form id="logoutform" action="{{ route('logout') }}" method="POST" style="display:none;">
                        @csrf
                        @method('POST')
                    </form>
                    <!-- -->

            </div>
        </div>
        <!-- -->
        @endauth

        <script>
            //Find the logout form by id and attach eventlistener to it on click
            document.getElementById('logoutbutton').addEventListener("click", function (event) {
                // preventing from default
                event.preventDefault();
                //Finding logout form by id and submit it
                document.getElementById('logoutform').submit();
            });
        </script>
    </nav>