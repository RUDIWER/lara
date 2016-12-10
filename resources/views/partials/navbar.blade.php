<nav class="navbar navbar-default navbar-inverse navbar-fixed-top" id="my-navbar">
    <div class="container-fluid">
        <div class="navbar-header">

            <!-- Collapsed Hamburger -->
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-navbar-collapse">
                <span class="sr-only">Toggle Navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>

            <!-- Branding Image -->
            <a class="navbar-brand" href="{{ url('/home') }}">
                {{ config('app.name', 'Lara') }}
            </a>
        </div>

        <div class="collapse navbar-collapse" id="app-navbar-collapse">
            <!-- Left Side Of Navbar -->
            @if (!Auth::guest())
                <ul class="nav navbar-nav">
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Basisbestanden<span class="caret"></span></a>
                        <ul class="dropdown-menu" role="menu">
                            <li><a class="dropdown-item" href="{{ url('/producten') }}">Producten</a></li>
                            <li><a class="dropdown-item" href="{{ url('/klanten') }}">Klanten</a></li>
                            <li><a class="dropdown-item" href="{{ url('/leveranciers') }}">Leveranciers</a></li>
                        </ul>
                    </li>
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">In Behandeling<span class="caret"></span></a>
                        <ul class="dropdown-menu" role="menu">
                            <li><a class="dropdown-item" href="{{ url('/bol/test') }}">Bol Test</a></li>
                            <li><a class="dropdown-item" href="{{ url('/bol/test/get-offers') }}">Bol Get Offers</a></li>
                             <li><a class="dropdown-item" href="{{ url('/bol/test/del-offers') }}">Bol del Offers</a></li>
                            <li><a class="dropdown-item" href="{{ url('/cz/verkopen/nieuwe-orders') }}">Te verwerken orders CZ</a></li>
                            <li><a class="dropdown-item" href="{{ url('/bol-be/verkopen/nieuwe-orders') }}">Te verwerken orders BOL-BE</a></li>
                            <li><a class="dropdown-item" href="{{ url('#') }}">Te verwerken orders BOL-NL</a></li>
                        </ul>
                    </li>
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Verkopen<span class="caret"></span></a>
                        <ul class="dropdown-menu" role="menu">
                            <li><a class="dropdown-item" href="{{ url('/verkopen/facturen') }}">facturen</a></li>
                        </ul>
                    </li>


                </ul>
            @endif

            <!-- Right Side Of Navbar -->
            <ul class="nav navbar-nav navbar-right">
                <!-- Authentication Links -->
                @if (Auth::guest())
                    <li><a href="{{ url('/login') }}">Login</a></li>
        <!--            <li><a href="{{ url('/register') }}">Register</a></li> -->
                @else
                    <li><a href="{{ url('/parameters') }}"><span class="glyphicon glyphicon-cog"></span></a></li>
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                            {{ Auth::user()->name }} <span class="caret"></span>
                        </a>

                        <ul class="dropdown-menu" role="menu">
                            <li>
                                <a href="{{ url('/logout') }}"
                                    onclick="event.preventDefault();
                                             document.getElementById('logout-form').submit();">
                                    Logout
                                </a>

                                <form id="logout-form" action="{{ url('/logout') }}" method="POST" style="display: none;">
                                    {{ csrf_field() }}
                                </form>
                            </li>
                        </ul>
                    </li>
                @endif
            </ul>
        </div>
    </div>
</nav>
