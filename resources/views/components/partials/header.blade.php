<header>
    <div class="container">
        <div class="main-header__wrapper">

            <a class="main-header__logo logo" href="{{ route('home') }}">
                <img src="{{ asset('img/logo.svg') }}" width="160" height="39" alt="Логотип компании YetiCave">
            </a>

            <form class="main-header__search search-form" method="get" action="{{ route('search') }}">
                <input type="search" name="search" id="search-input" placeholder="Поиск лота" autocomplete="off" class="search-form__input">
                <ul id="search-suggestions" class="search-form__suggestions"></ul>
                <button type="button" id="search-button" class="search-form__button">Найти</button>
            </form>

            <nav class="main-header__user user-menu">
                @if ($is_auth)
                    <a class="user-menu__add-lot button button--primary" href="{{ route('lot.create') }}">Добавить лот</a>

                    <div class="user-menu__avatar">
                        <img src="{{ asset('storage/' . auth()->user()->avatar) }}" width="40" height="40" alt="Пользователь">
                    </div>

                    <div class="user-menu__dropdown hidden" id="user-dropdown">
                        <ul class="user-menu__list">
                            <li class="user-menu__item">
                                <a href="{{ route('profile') }}" class="user-menu__link">Редактировать профиль</a>
                            </li>
                            <li class="user-menu__item">
                                <a href="{{ route('viewed.lots') }}" class="user-menu__link">Просмотренные лоты</a>
                            </li>
                            <li class="user-menu__item">
                                <button form="logout-form" type="submit" class="user-menu__link">Выйти</button>
                            </li>
                        </ul>
                    </div>

                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="visually-hidden">
                        @csrf
                    </form>

                    <div class="user-menu__name">
                        <p>{{ $user_name }}</p>
                    </div>
                @else
                    <ul class="user-menu__list">
                        <li class="user-menu__item">
                            <a href="{{ route('login') }}" class="user-menu__link">Войти</a>
                        </li>
                        <li class="user-menu__item">
                            <a href="{{ route('register') }}" class="user-menu__link">Регистрация</a>
                        </li>
                    </ul>
                @endif
            </nav>

            <button class="main-header__burger burger" id="burger-toggle" aria-label="Открыть меню">
                <span class="burger__line"></span>
                <span class="burger__line"></span>
                <span class="burger__line"></span>
            </button>

            <div class="main-header__mobile-menu mobile-menu hidden" id="mobile-menu">

                <button class="mobile-menu__close" id="mobile-menu-close" aria-label="Закрыть меню">
                    &times;
                </button>

                @include('components.partials.nav')

                @if($is_auth)
                    <a href="{{ route('lot.create') }}" class="mobile-menu__link">Добавить лот</a>
                    <a href="{{ route('profile') }}" class="mobile-menu__link">Редактировать профиль</a>
                    <a href="{{ route('viewed.lots') }}" class="mobile-menu__link">Просмотренные лоты</a>
                    <form class="mobile-menu__form" action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="mobile-menu__link">Выйти</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="mobile-menu__link">Войти</a>
                    <a href="{{ route('register') }}" class="mobile-menu__link">Регистрация</a>
                @endif
            </div>

        </div>
    </div>
</header>
