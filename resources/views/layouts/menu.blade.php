<nav>
    <div class="nav-button">
        <button class="navigation" type="primary"><span class="fa fa-navicon"></span></button>
    </div>
    <ul class="metismenu" id="side-menu">
        <li><a href="{{ URL::to('pacients') }}"><span class="retain-icon fa fa-user-md"></span> <span class="text">Pacients</span>
                <span class="fa arrow"></span>
                <ul class="nav nav-second-level">
                    <li><a href="{{ URL::to('pacients/nou') }}">Crear nous pacients</a></li>
                </ul>
            </a></li>
        <li><a href="{{ URL::to('histories') }}"><span class="retain-icon fa fa-folder"></span> <span class="text">Històries</span></a>
        </li>
        <li><a href="#"><span class="fa fa-user"></span> <span class="text">Usuaris</span> <span
                        class="fa arrow"></span></a>
            <ul class="nav nav-second-level">
                <li><a href="{{ URL::to('auth/register') }}">Crear nous usuaris</a></li>
            </ul>
        </li>
    </ul>
</nav>