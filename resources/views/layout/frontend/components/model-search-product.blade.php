<div class="modal fade" id="filterModal" tabindex="-1" aria-labelledby="filterModalLabel" aria-hidden="true" data-bs-backdrop="true"> 
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="filterModalLabel"> </h5>
                <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
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
        </div>
    </div>
</div>