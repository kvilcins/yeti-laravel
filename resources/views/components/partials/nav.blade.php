<nav class="nav nav__categories">
    <div class="container">
        <ul class="nav__list">
            @foreach ($categories as $category)
                <li class="nav__item">
                    <a href="{{ route('category.show', ['slug' => $category->slug]) }}">{{ $category->name }}</a>
                </li>
            @endforeach
        </ul>
    </div>
</nav>
