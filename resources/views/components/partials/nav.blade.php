<nav class="nav">
    <ul class="nav__list container">
        @foreach ($categories as $category)
            <li class="nav__item">
                <a href="{{ route('category.show', ['categoryId' => $category->id]) }}">{{ htmlspecialchars($category['name']) }}</a>
            </li>
        @endforeach
    </ul>
</nav>
