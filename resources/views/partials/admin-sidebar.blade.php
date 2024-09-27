@php
    $current_route=Route::currentRouteName();
    $current_route=str_replace('admin.','',$current_route);
@endphp
<nav id="sidebar">
    <div class="navbar-nav theme-brand flex-row  text-center">
        <div class="nav-logo">
            <div class="nav-item theme-text">
                <a href="{{route('admin.dashboard')}}" class="nav-link"> {{ __('navbar.ecommerce') }}  </a>
            </div>
        </div>
        <div class="nav-item sidebar-toggle">
            <div class="btn-toggle sidebarCollapse">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevrons-left"><polyline points="11 17 6 12 11 7"></polyline><polyline points="18 17 13 12 18 7"></polyline></svg>
            </div>
        </div>
    </div>
    <div class="shadow-bottom"></div>
    <ul class="list-unstyled menu-categories" id="accordionExample">
        <li class="menu {{$current_route=='dashboard'?'active':''}}">
            <a href="{{route('admin.dashboard')}}" class="dropdown-toggle">
                <div class="">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-home"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><polyline points="9 22 9 12 15 12 15 22"></polyline></svg>
                    <span>{{ __('navbar.dashboard') }} </span>
                </div>
            </a>
        </li>
        @can('users')
            <li class="menu {{$current_route=='users.index'?'active':''}}">
                <a href="{{route('admin.users.index')}}" class="dropdown-toggle">
                    <div class="">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-users"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                        <span>{{ __('navbar.users')}}</span>
                    </div>
                </a>
            </li>
        @endcan

        @canany(['categories','sub-categories'])
            <li class="menu {{Str::contains($current_route, ['categories.', 'sub-categories.'])?'active':''}}">
                <a href="#menuLevel1" data-bs-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
                    <div class="">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-list"><line x1="8" y1="6" x2="21" y2="6"></line><line x1="8" y1="12" x2="21" y2="12"></line><line x1="8" y1="18" x2="21" y2="18"></line><line x1="3" y1="6" x2="3.01" y2="6"></line><line x1="3" y1="12" x2="3.01" y2="12"></line><line x1="3" y1="18" x2="3.01" y2="18"></line></svg>
                        <span>{{ __('navbar.categories')}}</span>
                    </div>
                    <div>
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-right"><polyline points="9 18 15 12 9 6"></polyline></svg>
                    </div>
                </a>
                <ul class="collapse submenu list-unstyled {{Str::contains($current_route, ['categories.', 'sub-categories.'])?'show':''}}" id="menuLevel1" data-bs-parent="#accordionExample">
                    @can('categories')
                        <li class="{{$current_route=='categories.index'?'active':''}}">
                            <a href="{{route('admin.categories.index')}}"> {{ __('navbar.list')}}</a>
                        </li>
                        <li class="{{$current_route=='categories.create'?'active':''}}">
                            <a href="{{route('admin.categories.create')}}">{{ __('navbar.add')}}</a>
                        </li>
                    @endcan
                    @can('sub-categories')
                        <li>
                            <a href="#level-three" data-bs-toggle="collapse" aria-expanded="false" class="dropdown-toggle collapsed">{{__('navbar.sub_categories')}} <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-right"><polyline points="9 18 15 12 9 6"></polyline></svg> </a>
                            <ul class="collapse list-unstyled sub-submenu {{Str::contains($current_route, ['blog-categories.'])?'show':''}}" id="level-three" data-bs-parent="#pages">
                                <li class="{{$current_route=='sub-categories.index'?'active':''}}">
                                    <a href="{{route('admin.sub-categories.index')}}"> {{ __('navbar.list')}}</a>
                                </li>
                                <li class="{{$current_route=='sub-categories.create'?'active':''}}">
                                    <a href="{{route('admin.sub-categories.create')}}"> {{ __('navbar.add')}} </a>
                                </li>
                            </ul>
                        </li>
                    @endcan
                </ul>
            </li>
        @endcanany

        @can('tags')
            <li class="menu {{Str::contains($current_route, ['tags.'])?'active':''}}">
                <a href="#menuLevel3" data-bs-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
                    <div class="">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-list"><line x1="8" y1="6" x2="21" y2="6"></line><line x1="8" y1="12" x2="21" y2="12"></line><line x1="8" y1="18" x2="21" y2="18"></line><line x1="3" y1="6" x2="3.01" y2="6"></line><line x1="3" y1="12" x2="3.01" y2="12"></line><line x1="3" y1="18" x2="3.01" y2="18"></line></svg>
                        <span>{{ __('navbar.tags')}}</span>
                    </div>
                    <div>
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-right"><polyline points="9 18 15 12 9 6"></polyline></svg>
                    </div>
                </a>
                <ul class="collapse submenu list-unstyled {{Str::contains($current_route, ['tags.'])?'show':''}}" id="menuLevel3" data-bs-parent="#accordionExample">
                    @can('tags')
                        <li class="{{$current_route=='tags.index'?'active':''}}">
                            <a href="{{route('admin.tags.index')}}"> {{ __('navbar.list')}}</a>
                        </li>
                        <li class="{{$current_route=='tags.create'?'active':''}}">
                            <a href="{{route('admin.tags.create')}}">{{ __('navbar.add')}}</a>
                        </li>
                    @endcan

                </ul>
            </li>
        @endcan

        @can('products')
            <li class="menu {{Str::contains($current_route, ['products.'])?'active':''}}">
                <a href="#menuLevel4" data-bs-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
                    <div class="">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-list"><line x1="8" y1="6" x2="21" y2="6"></line><line x1="8" y1="12" x2="21" y2="12"></line><line x1="8" y1="18" x2="21" y2="18"></line><line x1="3" y1="6" x2="3.01" y2="6"></line><line x1="3" y1="12" x2="3.01" y2="12"></line><line x1="3" y1="18" x2="3.01" y2="18"></line></svg>
                        <span>{{ __('navbar.products')}}</span>
                    </div>
                    <div>
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-right"><polyline points="9 18 15 12 9 6"></polyline></svg>
                    </div>
                </a>
                <ul class="collapse submenu list-unstyled {{Str::contains($current_route, ['products.'])?'show':''}}" id="menuLevel4" data-bs-parent="#accordionExample">
                    @can('tags')
                        <li class="{{$current_route=='products.index'?'active':''}}">
                            <a href="{{route('admin.products.index')}}"> {{ __('navbar.list')}}</a>
                        </li>
                        <li class="{{$current_route=='products.create'?'active':''}}">
                            <a href="{{route('admin.products.create')}}">{{ __('navbar.add')}}</a>
                        </li>
                        <li class="{{ $current_route=='products.import.index' ? 'active' : '' }}">
                            <a href="{{ route('admin.products.import.index') }}">{{ __('navbar.import')}}</a>
                        </li>
                    @endcan

                </ul>
            </li>
        @endcan

        @can('tickets')
            <li class="menu {{Str::contains($current_route, ['tickets.'])?'active':''}}">
                <a href="#menuLevel7" data-bs-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
                    <div class="">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-list"><line x1="8" y1="6" x2="21" y2="6"></line><line x1="8" y1="12" x2="21" y2="12"></line><line x1="8" y1="18" x2="21" y2="18"></line><line x1="3" y1="6" x2="3.01" y2="6"></line><line x1="3" y1="12" x2="3.01" y2="12"></line><line x1="3" y1="18" x2="3.01" y2="18"></line></svg>
                        <span>{{ __('navbar.tickets')}}</span>
                    </div>
                    <div>
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-right"><polyline points="9 18 15 12 9 6"></polyline></svg>
                    </div>
                </a>
                <ul class="collapse submenu list-unstyled {{Str::contains($current_route, ['tickets.'])?'show':''}}" id="menuLevel7" data-bs-parent="#accordionExample">
                    @can('tags')
                        <li class="{{$current_route=='tickets.index'?'active':''}}">
                            <a href="{{route('admin.tickets.index')}}"> {{ __('navbar.list')}}</a>
                        </li>
                        <li class="{{$current_route=='tickets.create'?'active':''}}">
                            <a href="{{route('admin.tickets.create')}}">{{ __('navbar.add')}}</a>
                        </li>

                    @endcan

                </ul>
            </li>
        @endcan
        @can('coupons')
            <li class="menu {{Str::contains($current_route, ['coupons.', 'coupons-categories.'])?'active':''}}">
                <a href="#menuLevel5" data-bs-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
                    <div class="">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-list"><line x1="8" y1="6" x2="21" y2="6"></line><line x1="8" y1="12" x2="21" y2="12"></line><line x1="8" y1="18" x2="21" y2="18"></line><line x1="3" y1="6" x2="3.01" y2="6"></line><line x1="3" y1="12" x2="3.01" y2="12"></line><line x1="3" y1="18" x2="3.01" y2="18"></line></svg>
                        <span>{{ __('navbar.coupons')}}</span>
                    </div>
                    <div>
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-right"><polyline points="9 18 15 12 9 6"></polyline></svg>
                    </div>
                </a>
                <ul class="collapse submenu list-unstyled {{Str::contains($current_route, ['coupon.', 'blog-categories.'])?'show':''}}" id="menuLevel5" data-bs-parent="#accordionExample">
                    @can('coupons')
                        <li class="{{$current_route=='coupons.index'?'active':''}}">
                            <a href="{{route('admin.coupons.index')}}"> {{ __('navbar.list')}}</a>
                        </li>
                        <li class="{{$current_route=='coupons.create'?'active':''}}">
                            <a href="{{route('admin.coupons.create')}}">{{ __('navbar.add')}}</a>
                        </li>
                    @endcan
                </ul>
            </li>
        @endcan


        @can('slider')
            <li class="menu {{Str::contains($current_route, ['slider.'])?'active':''}}">
                <a href="#slider_level" data-bs-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
                    <div class="">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-list"><line x1="8" y1="6" x2="21" y2="6"></line><line x1="8" y1="12" x2="21" y2="12"></line><line x1="8" y1="18" x2="21" y2="18"></line><line x1="3" y1="6" x2="3.01" y2="6"></line><line x1="3" y1="12" x2="3.01" y2="12"></line><line x1="3" y1="18" x2="3.01" y2="18"></line></svg>
                        <span>{{ __('navbar.sliders')}}</span>
                    </div>
                    <div>
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-right"><polyline points="9 18 15 12 9 6"></polyline></svg>
                    </div>
                </a>
                <ul class="collapse submenu list-unstyled {{Str::contains($current_route, ['slider.', 'blog-categories.'])?'show':''}}" id="slider_level" data-bs-parent="#accordionExample">
                    @can('slider')
                        <li class="{{$current_route=='slider.index'?'active':''}}">
                            <a href="{{route('admin.slider.index')}}"> {{ __('navbar.list')}}</a>
                        </li>
                        <li class="{{$current_route=='slider.create'?'active':''}}">
                            <a href="{{route('admin.slider.create')}}">{{ __('navbar.add')}}</a>
                        </li>
                    @endcan
                </ul>
            </li>
        @endcan


        @can('reviews')
            <li class="menu {{Str::contains($current_route, ['reviews.'])?'active':''}}">
                <a href="#menuLevel6" data-bs-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
                    <div class="">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-list"><line x1="8" y1="6" x2="21" y2="6"></line><line x1="8" y1="12" x2="21" y2="12"></line><line x1="8" y1="18" x2="21" y2="18"></line><line x1="3" y1="6" x2="3.01" y2="6"></line><line x1="3" y1="12" x2="3.01" y2="12"></line><line x1="3" y1="18" x2="3.01" y2="18"></line></svg>
                        <span>{{ __('navbar.reviews')}}</span>
                    </div>
                    <div>
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-right"><polyline points="9 18 15 12 9 6"></polyline></svg>
                    </div>
                </a>
                <ul class="collapse submenu list-unstyled {{Str::contains($current_route, ['reviews.', 'blog-categories.'])?'show':''}}" id="menuLevel6" data-bs-parent="#accordionExample">
                    @can('reviews')
                        <li class="{{$current_route=='reviews.index'?'active':''}}">
                            <a href="{{route('admin.reviews.index')}}"> {{ __('navbar.list')}}</a>
                        </li>
                    @endcan
                </ul>
            </li>
        @endcan
        @can('orders')
            <li class="menu {{Str::contains($current_route, ['orders.'])?'active':''}}">
                <a href="#menuLevel61" data-bs-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
                    <div class="">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-list"><line x1="8" y1="6" x2="21" y2="6"></line><line x1="8" y1="12" x2="21" y2="12"></line><line x1="8" y1="18" x2="21" y2="18"></line><line x1="3" y1="6" x2="3.01" y2="6"></line><line x1="3" y1="12" x2="3.01" y2="12"></line><line x1="3" y1="18" x2="3.01" y2="18"></line></svg>
                        <span>{{ __('navbar.orders')}}</span>
                    </div>
                    <div>
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-right"><polyline points="9 18 15 12 9 6"></polyline></svg>
                    </div>
                </a>
                <ul class="collapse submenu list-unstyled {{Str::contains($current_route, ['orders.', 'blog-categories.'])?'show':''}}" id="menuLevel61" data-bs-parent="#accordionExample">

					<li class="{{$current_route=='orders.index'?'active':''}}">
						<a href="{{route('admin.orders.index')}}"> {{ __('navbar.list')}}</a>
					</li>

                </ul>
            </li>
        @endcan

        <!-- @canany(['blogs','blog-categories'])
            <li class="menu {{Str::contains($current_route, ['blogs.', 'blog-categories.'])?'active':''}}">
                <a href="#menuLevel1" data-bs-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
                    <div class="">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-list"><line x1="8" y1="6" x2="21" y2="6"></line><line x1="8" y1="12" x2="21" y2="12"></line><line x1="8" y1="18" x2="21" y2="18"></line><line x1="3" y1="6" x2="3.01" y2="6"></line><line x1="3" y1="12" x2="3.01" y2="12"></line><line x1="3" y1="18" x2="3.01" y2="18"></line></svg>
                        <span>Blogs</span>
                    </div>
                    <div>
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-right"><polyline points="9 18 15 12 9 6"></polyline></svg>
                    </div>
                </a>
                <ul class="collapse submenu list-unstyled {{Str::contains($current_route, ['blogs.', 'blog-categories.'])?'show':''}}" id="menuLevel1" data-bs-parent="#accordionExample">
                    @can('blogs')
                        <li class="{{$current_route=='blogs.index'?'active':''}}">
                            <a href="{{route('admin.blogs.index')}}"> List</a>
                        </li>
                        <li class="{{$current_route=='blogs.create'?'active':''}}">
                            <a href="{{route('admin.blogs.create')}}">Add</a>
                        </li>
                    @endcan
                    @can('blog-categories')
                        <li>
                            <a href="#level-three" data-bs-toggle="collapse" aria-expanded="false" class="dropdown-toggle collapsed"> Categories <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-right"><polyline points="9 18 15 12 9 6"></polyline></svg> </a>
                            <ul class="collapse list-unstyled sub-submenu {{Str::contains($current_route, ['blog-categories.'])?'show':''}}" id="level-three" data-bs-parent="#pages">
                                <li class="{{$current_route=='blog-categories.index'?'active':''}}">
                                    <a href="{{route('admin.blog-categories.index')}}"> List</a>
                                </li>
                                <li class="{{$current_route=='blog-categories.create'?'active':''}}">
                                    <a href="{{route('admin.blog-categories.create')}}"> Add </a>
                                </li>
                            </ul>
                        </li>
                    @endcan
                </ul>
            </li>
        @endcanany -->
        <!-- @canany(['skills','skill-categories'])
            <li class="menu {{Str::contains($current_route, ['skills.', 'skill-categories.'])?'active':''}}">
                <a href="#skills" data-bs-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
                    <div class="">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-layers"><polygon points="12 2 2 7 12 12 22 7 12 2"></polygon><polyline points="2 17 12 22 22 17"></polyline><polyline points="2 12 12 17 22 12"></polyline></svg>
                        <span>Skills</span>
                    </div>
                    <div>
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-right"><polyline points="9 18 15 12 9 6"></polyline></svg>
                    </div>
                </a>
                <ul class="collapse submenu list-unstyled {{Str::contains($current_route, ['skills.', 'skill-categories.'])?'show':''}}" id="skills" data-bs-parent="#accordionExample">
                    @can('skills')
                        <li class="{{$current_route=='skills.index'?'active':''}}">
                            <a href="{{route('admin.skills.index')}}"> List</a>
                        </li>
                        <li class="{{$current_route=='skills.create'?'active':''}}">
                            <a href="{{route('admin.skills.create')}}">Add</a>
                        </li>
                    @endcan
                    @can('skill-categories')
                        <li>
                            <a href="#skill-categories" data-bs-toggle="collapse" aria-expanded="false" class="dropdown-toggle collapsed"> Categories <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-right"><polyline points="9 18 15 12 9 6"></polyline></svg> </a>
                            <ul class="collapse list-unstyled sub-submenu {{Str::contains($current_route, ['skill-categories.'])?'show':''}}" id="skill-categories" data-bs-parent="#pages">
                                <li class="{{$current_route=='skill-categories.index'?'active':''}}">
                                    <a href="{{route('admin.skill-categories.index')}}"> List</a>
                                </li>
                                <li class="{{$current_route=='skill-categories.create'?'active':''}}">
                                    <a href="{{route('admin.skill-categories.create')}}"> Add </a>
                                </li>
                            </ul>
                        </li>
                    @endcan
                </ul>
            </li>
        @endcanany -->
        <?php
        // @can('orders')
        //     <li class="menu {{$current_route=='orders.index'?'active':''}}">
        //         <a href="{{route('admin.orders.index')}}" class="dropdown-toggle">
        //             <div class="">
        //                 <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-clipboard">
        //                     <path d="M16 2H8a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2V4a2 2 0 0 0-2-2z"></path>
        //                     <rect x="9" y="2" width="6" height="4" rx="1" ry="1"></rect>
        //                 </svg>
        //                 <span>Orders</span>
        //             </div>
        //         </a>
        //     </li>
        // @endcan
        ?>

        @can('payment-gateways')
            <li class="menu {{Str::contains($current_route, ['payment-gateways.'])?'active':''}}">
                <a href="#menuLevelPayment" data-bs-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
                    <div class="">

                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-dollar-sign"><line x1="12" y1="1" x2="12" y2="23"></line><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path></svg>
                        <span>{{ __('navbar.payments')}}</span>
                    </div>
                    <div>
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-right"><polyline points="9 18 15 12 9 6"></polyline></svg>
                    </div>
                </a>
                <ul class="collapse submenu list-unstyled {{Str::contains($current_route, ['payment-gateways.'])?'show':''}}" id="menuLevelPayment" data-bs-parent="#accordionExample">
                    @can('payment-gateways')
                        <li class="{{$current_route=='payment-gateways.index'?'active':''}}">
                            <a href="{{route('admin.payment-gateways.index')}}"> {{ __('navbar.list')}}</a>
                        </li>
                        <li class="{{$current_route=='payment-gateways.create'?'active':''}}">
                            <a href="{{route('admin.payment-gateways.create')}}">{{ __('navbar.add')}}</a>
                        </li>
                    @endcan

                </ul>
            </li>
        @endcan
        @can('email-templates')
            <li class="menu {{Str::contains($current_route, ['email-templates.'])?'active':''}}">
                <a href="#menuLevelEmail" data-bs-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
                    <div class="">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-list"><line x1="8" y1="6" x2="21" y2="6"></line><line x1="8" y1="12" x2="21" y2="12"></line><line x1="8" y1="18" x2="21" y2="18"></line><line x1="3" y1="6" x2="3.01" y2="6"></line><line x1="3" y1="12" x2="3.01" y2="12"></line><line x1="3" y1="18" x2="3.01" y2="18"></line></svg>
                        <span>{{ __('navbar.email-templates')}}</span>
                    </div>
                    <div>
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-right"><polyline points="9 18 15 12 9 6"></polyline></svg>
                    </div>
                </a>
                <ul class="collapse submenu list-unstyled {{Str::contains($current_route, ['email-templates.'])?'show':''}}" id="menuLevelEmail" data-bs-parent="#accordionExample">
                    @can('email-templates')
                        <li class="{{$current_route=='email-templates.index'?'active':''}}">
                            <a href="{{route('admin.email-templates.index')}}"> {{ __('navbar.list')}}</a>
                        </li>
                        <li class="{{$current_route=='email-templates.create'?'active':''}}">
                            <a href="{{route('admin.email-templates.create')}}">{{ __('navbar.add')}}</a>
                        </li>
                    @endcan

                </ul>
            </li>
        @endcan
        @can('send-emails')
            <li class="menu {{Str::contains($current_route, ['send-emails.'])?'active':''}}">
                <a href="#menuLevelSendEmail" data-bs-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
                    <div class="">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-send"><line x1="22" y1="2" x2="11" y2="13"></line><polygon points="22 2 15 22 11 13 2 9 22 2"></polygon></svg>
                        <span>{{ __('navbar.send-emails')}}</span>
                    </div>
                    <div>
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-right"><polyline points="9 18 15 12 9 6"></polyline></svg>
                    </div>
                </a>
                <ul class="collapse submenu list-unstyled {{Str::contains($current_route, ['send-emails.'])?'show':''}}" id="menuLevelSendEmail" data-bs-parent="#accordionExample">
                    @can('send-emails')
                        <li class="{{$current_route=='send-emails.index'?'active':''}}">
                            <a href="{{route('admin.send-emails.index')}}">{{ __('navbar.email-logs')}}</a>
                        </li>
                        <li class="{{$current_route=='send-emails.create'?'active':''}}">
                            <a href="{{route('admin.send-emails.create')}}">{{ __('navbar.send')}}</a>
                        </li>
                    @endcan

                </ul>
            </li>
        @endcan
        @can('admin-management')
            <li class="menu {{Str::contains($current_route, ['sub-admins.', 'roles.'])?'active':''}}">
                <a href="#subadmins" data-bs-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
                    <div class="">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-users"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                        <span>{{ __('navbar.sub-admins')}}</span>
                    </div>
                    <div>
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-right"><polyline points="9 18 15 12 9 6"></polyline></svg>
                    </div>
                </a>
                <ul class="collapse submenu list-unstyled {{Str::contains($current_route, ['sub-admins.', 'roles.'])?'show':''}}" id="subadmins" data-bs-parent="#accordionExample">
                    <li class="{{$current_route=='sub-admins.index'?'active':''}}">
                        <a href="{{route('admin.sub-admins.index')}}"> {{ __('navbar.list')}}</a>
                    </li>
                    <li class="{{$current_route=='sub-admins.create'?'active':''}}">
                        <a href="{{route('admin.sub-admins.create')}}">{{ __('navbar.add')}}</a>
                    </li>

                    <li>
                        <a href="#roles" data-bs-toggle="collapse" aria-expanded="false" class="dropdown-toggle collapsed"> {{ __('navbar.roles')}} <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-right"><polyline points="9 18 15 12 9 6"></polyline></svg> </a>
                        <ul class="collapse list-unstyled sub-submenu {{Str::contains($current_route, ['roles.'])?'show':''}}" id="roles" data-bs-parent="#pages">
                             <li class="{{$current_route=='roles.index'?'active':''}}">
                                <a href="{{route('admin.roles.index')}}"> {{ __('navbar.list')}}</a>
                            </li>
                             <li class="{{$current_route=='roles.create'?'active':''}}">
                                <a href="{{route('admin.roles.create')}}"> {{ __('navbar.add')}} </a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </li>
        @endcan

        <?php
        // <li class="menu {{Str::contains($current_route, ['email-templates.']) ? 'active' : ''}}">
        //     <a href="#menuLevel-email-templates" data-bs-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
        //         <div class="">
        //             <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-list"><line x1="8" y1="6" x2="21" y2="6"></line><line x1="8" y1="12" x2="21" y2="12"></line><line x1="8" y1="18" x2="21" y2="18"></line><line x1="3" y1="6" x2="3.01" y2="6"></line><line x1="3" y1="12" x2="3.01" y2="12"></line><line x1="3" y1="18" x2="3.01" y2="18"></line></svg>
        //             <span>Email Templates</span>
        //         </div>
        //         <div>
        //             <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-right"><polyline points="9 18 15 12 9 6"></polyline></svg>
        //         </div>
        //     </a>
        //     <ul class="collapse submenu list-unstyled {{Str::contains($current_route, ['email-templates.']) ? 'show' : ''}}" id="menuLevel-email-templates" data-bs-parent="#accordionExample">
        //         <li class="{{$current_route=='email-templates.index'?'active':''}}">
        //             <a href="{{route('admin.email-templates.index')}}"> List</a>
        //         </li>
        //     </ul>
        // </li>
        ?>


        <li class="menu {{$current_route=='profile'?'active':''}}">
            <a href="{{route('admin.profile')}}" class="dropdown-toggle">
                <div class="">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 101 101" id="user"><path d="M50.4 54.5c10.1 0 18.2-8.2 18.2-18.2S60.5 18 50.4 18s-18.2 8.2-18.2 18.2 8.1 18.3 18.2 18.3zm0-31.7c7.4 0 13.4 6 13.4 13.4s-6 13.4-13.4 13.4S37 43.7 37 36.3s6-13.5 13.4-13.5zM18.8 83h63.4c1.3 0 2.4-1.1 2.4-2.4 0-12.6-10.3-22.9-22.9-22.9H39.3c-12.6 0-22.9 10.3-22.9 22.9 0 1.3 1.1 2.4 2.4 2.4zm20.5-20.5h22.4c9.2 0 16.7 6.8 17.9 15.7H21.4c1.2-8.9 8.7-15.7 17.9-15.7z"></path></svg>
                    <span>{{ __('navbar.profile')}}</span>
                </div>
            </a>
        </li>
        @can('settings')
            <li class="menu {{Str::contains($current_route, ['settings'])?'active':''}}">
                <a href="#menuLevelSettings" data-bs-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
                    <div class="">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-settings"><circle cx="12" cy="12" r="3"></circle><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"></path></svg>
                        <span>{{ __('navbar.settings')}}</span>
                    </div>
                    <div>
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-right"><polyline points="9 18 15 12 9 6"></polyline></svg>
                    </div>
                </a>
                <ul class="collapse submenu list-unstyled {{Str::contains($current_route, ['settings'])?'show':''}}" id="menuLevelSettings" data-bs-parent="#accordionExample">
                    <li class="{{$current_route=='settings'?'active':''}}">
                        <a href="{{route('admin.settings')}}"> {{ __('navbar.site-settings')}}</a>
                    </li>
                    <li class="{{$current_route=='settings.currency.index'?'active':''}}">
                        <a href="{{route('admin.settings.currency.index')}}">{{ __('navbar.currency')}}</a>
                    </li>
                </ul>
            </li>
        @endcan
    </ul>
</nav>
