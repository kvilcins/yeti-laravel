<div class="filters">
    <form method="GET" action="{{ request()->url() }}" class="filters__form" id="filtersForm">
        <div class="filters__row">

            <div class="filters__group">
                <label for="sort" class="filters__label">Sort by:</label>
                <select name="sort" id="sort" class="filters__select">
                    <option value="time_asc" {{ $filters['sort'] === 'time_asc' ? 'selected' : '' }}>Time (ending soon)</option>
                    <option value="time_desc" {{ $filters['sort'] === 'time_desc' ? 'selected' : '' }}>Time (ending later)</option>
                    <option value="price_asc" {{ $filters['sort'] === 'price_asc' ? 'selected' : '' }}>Price (low to high)</option>
                    <option value="price_desc" {{ $filters['sort'] === 'price_desc' ? 'selected' : '' }}>Price (high to low)</option>
                    <option value="name_asc" {{ $filters['sort'] === 'name_asc' ? 'selected' : '' }}>Name (A-Z)</option>
                    <option value="name_desc" {{ $filters['sort'] === 'name_desc' ? 'selected' : '' }}>Name (Z-A)</option>
                </select>
            </div>

            <div class="filters__group">
                <label for="status" class="filters__label">Status:</label>
                <select name="status" id="status" class="filters__select">
                    <option value="active" {{ $filters['status'] === 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ $filters['status'] === 'inactive' ? 'selected' : '' }}>Inactive</option>
                    <option value="completed" {{ $filters['status'] === 'completed' ? 'selected' : '' }}>Completed</option>
                    <option value="all" {{ $filters['status'] === 'all' ? 'selected' : '' }}>All</option>
                </select>
            </div>

            @if(!isset($category_name))
                <div class="filters__group">
                    <label for="category" class="filters__label">Category:</label>
                    <select name="category" id="category" class="filters__select">
                        <option value="">All categories</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ $filters['category'] == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            @endif
        </div>

        <div class="filters__row">

            <div class="filters__group">
                <label class="filters__label">Price range:</label>
                <div class="filters__price-inputs">
                    <input type="number" name="price_from" placeholder="From" value="{{ $filters['price_from'] }}" class="filters__input filters__input--price">
                    <span class="filters__separator">—</span>
                    <input type="number" name="price_to" placeholder="To" value="{{ $filters['price_to'] }}" class="filters__input filters__input--price">
                </div>
            </div>

            <div class="filters__group">
                <label for="price_range" class="filters__label">Quick price:</label>
                <select name="price_range" id="price_range" class="filters__select">
                    <option value="">Any price</option>
                    @foreach($price_ranges as $key => $label)
                        <option value="{{ $key }}" {{ $filters['price_range'] === $key ? 'selected' : '' }}>
                            {{ $label }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="filters__group filters__group--actions">
                <button type="submit" class="filters__btn filters__btn--apply">Apply</button>
                <a href="{{ request()->url() }}" class="filters__btn filters__btn--reset">Reset</a>
            </div>
        </div>
    </form>
</div>
