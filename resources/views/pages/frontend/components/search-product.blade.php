<div class="col-lg-2 d-none d-lg-block">
    <div class="menu-sidebar">
        <form method="GET" action="{{ route($userPrefix . '.index') }}" id="filterForm">
            <div class="filter-section">
                <div class="filter-title">Quốc Gia</div>
                <ul class="list-unstyled">
                    @if(!empty($countries) && (is_array($countries) ? count($countries) > 0 : $countries->count() > 0))
                        <li>
                            <div class="form-check">
                                <input class="form-check-input" 
                                    type="radio" 
                                    id="country-all" 
                                    name="country_id" 
                                    value="all" 
                                    {{ (empty(request('country_id')) || request('country_id') == 'all') ? 'checked' : '' }}
                                    onchange="this.form.submit()"> 
                                Tất cả
                            </div>
                        </li>
                        @foreach($countries ?? [] as $country)
                        <li>
                            <div class="form-check">
                                <input class="form-check-input" 
                                        type="radio" 
                                        name="country_id" 
                                        value="{{ $country['id'] }}"
                                        id="country-{{ $country['id'] }}"
                                        {{ request('country_id') == $country['id'] ? 'checked' : '' }}
                                        onchange="this.form.submit()">
                                {{ $country['name'] }}
                            </div>
                        </li>
                        @endforeach
                    @else
                        <li class="text-muted small text-center">Chưa có quốc gia nào</li>
                    @endif
                </ul>
            </div>
            
            <div class="filter-section">
                <div class="filter-title">Danh Mục</div>
                <ul class="list-unstyled">
                    @if(!empty($categories) && (is_array($categories) ? count($categories) > 0 : $categories->count() > 0))
                        <li>
                            <div class="form-check">
                                <input class="form-check-input" 
                                    type="radio" 
                                    name="category_id" 
                                    value="all"
                                    id="category-all"
                                    {{ (empty(request('category_id')) || request('category_id') == 'all') ? 'checked' : '' }}
                                    onchange="this.form.submit()">
                                Tất cả
                            </div>
                        </li>

                        @foreach($categories ?? [] as $category)
                        <li>
                            <div class="form-check">
                                <input class="form-check-input" 
                                        type="radio" 
                                        name="category_id" 
                                        value="{{ $category['id'] }}"
                                        id="category-{{ $category['id'] }}"
                                        {{ request('category_id') == $category['id'] ? 'checked' : '' }}
                                        onchange="this.form.submit()">
                                {{ $category['name'] }}
                            </div>
                        </li>
                        @endforeach
                    @else
                        <li class="text-muted small text-center">Chưa có danh mục nào</li>
                    @endif
                </ul>
            </div>
        </form>
    </div>
</div>
<div class="col-6 d-lg-none mb-3 col-sm-6 d-flex text-end justify-content-end">
    <button class="btn text-bnt border-0 btn-filter-mobile border" type="button" data-bs-toggle="modal" data-bs-target="#filterModal"><i class="fa-solid fa-filter fs-3"></i></button>
</div>