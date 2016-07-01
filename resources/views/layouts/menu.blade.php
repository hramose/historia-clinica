<nav>
    <div class="nav-button">
        <button class="navigation" type="primary"><span class="fa fa-navicon"></span></button>
    </div>
    <div id="menu-header">
        <a href="{{URL::route('home')  }}">
            <img class="main" src="{{ asset("img/logo.png") }}" alt="">
            <img class="responsive" src="{{ asset("img/logo-responsive.png") }}" alt="">
        </a>
    </div>
    <ul class="metismenu" id="side-menu">
        <li><a href="{{ URL::to('pacients') }}"><span class="retain-icon fa fa-user-md"></span> <span class="text">Pacients</span>
                <span class="fa arrow"></span>
                <ul class="nav nav-second-level">
                    <li><a href="{{ URL::to('pacients/nou') }}">Crear nous pacients</a></li>
                    <li><a href="{{ URL::to('pacients/llista') }}">Tots els pacients</a></li>
                </ul>
            </a></li>
        <li><a href="{{ URL::to('curs-clinic') }}"><span class="retain-icon fa fa-folder"></span> <span class="text">Curs clínic</span></a>
        </li>
        <li><a href="#"><span class="fa fa-user"></span> <span class="text">Usuaris</span> <span
                        class="fa arrow"></span></a>
            <ul class="nav nav-second-level">
                <li><a href="{{ URL::to('auth/register') }}">Crear nous usuaris</a></li>
                <li><a href="{{ URL::to('users/llista') }}">Tots els usuaris</a></li>
            </ul>
        </li>
        <li><a href="#"><span class="fa fa-file-text"></span> <span class="text">Facturació</span> <span
                        class="fa arrow"></span></a>
            <ul class="nav nav-second-level">
                <li><a href="{{ URL::route('veureBills') }}">Veure factures</a></li>
                <li><a href="{{ URL::route('ferBills') }}">Crear nova factura</a></li>
            </ul>
        </li>
        <li><a href="#"><span class="fa fa-database"></span> <span class="text">Còpia de seguretat</span> <span
                        class="fa arrow"></span></a>
            <ul class="nav nav-second-level">
                <li><a href="{{ URL::route('backupDb') }}">Realitza còpia ara</a></li>
                <li><a href="{{ URL::route('backupList') }}">Llistat de backups</a></li>
            </ul>
        </li>
    </ul>
</nav>