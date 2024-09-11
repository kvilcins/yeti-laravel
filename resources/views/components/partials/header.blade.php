<header>
    <div class="main-header__container container">
        <h1 class="visually-hidden">YetiCave</h1>
        <a class="main-header__logo" href="{{ route('home') }}">
            <img src="{{ asset('img/logo.svg') }}" width="160" height="39" alt="Логотип компании YetiCave">
        </a>
        <form class="main-header__search" method="get" action="https://echo.htmlacademy.ru">
            <input type="search" name="search" placeholder="Поиск лота">
            <input class="main-header__search-btn" type="submit" name="find" value="Найти">
        </form>
        <nav class="user-menu">
            @if($is_auth)
                <!-- Если пользователь авторизован -->
                <a class="main-header__add-lot button" href="{{ route('add.form') }}">Добавить лот</a>
                <div class="user-menu__image">
                    <img src="{{ asset($user_avatar) }}" width="40" height="40" alt="Пользователь">
                </div>
                <div class="user-menu__logged">
                    <p>{{ $user_name }}</p>
                </div>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="button">Выйти</button>
                </form>
            @else
                <!-- Если пользователь не авторизован -->
                <ul class="user-menu__list">
                    <li class="user-menu__item">
                        <a href="{{ route('login') }}">Войти</a>
                    </li>
                    <li class="user-menu__item">
                        <a href="{{ route('register') }}">Регистрация</a>
                    </li>
                </ul>
            @endif
        </nav>
    </div>
</header>
