@php
    $route = request()->route()->getName();
@endphp

<aside class="main-sidebar sidebar-dark-primary elevation-4" style="min-height: 100vh;">

    <!-- Brand Logo -->
    <a href="{{ route('backend.admin.dashboard') }}" class="brand-link text-center py-3">
        <img src="{{ assetImage(readConfig('site_logo')) }}" alt="Logo"
            class="brand-image img-circle elevation-3" style="opacity: .9; width: 40px; height: 40px;">
        <span class="brand-text fw-bold ms-2">{{ readConfig('site_name') }}</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- User Info -->
        <div class="user-panel d-flex align-items-center my-3 pb-3 border-bottom text-white">
            <div class="image">
                <img src="{{ auth()->user()->pro_pic ?? asset('assets/images/avatar.png') }}"
                    class="img-circle elevation-2" alt="User Image" style="width: 35px; height: 35px;">
            </div>
            <div class="info ps-2">
                <a href="{{ route('backend.admin.profile') }}" class="d-block text-white fw-semibold">
                    {{ auth()->user()->name }}
                </a>
            </div>
        </div>

        <!-- Sidebar Menu -->
        <nav>
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">

                {{-- Dashboard --}}
                @can('dashboard_view')
                <li class="nav-item">
                    <a href="{{ route('backend.admin.dashboard') }}"
                        class="nav-link {{ $route === 'backend.admin.dashboard' ? 'active' : '' }}">
                        <i class="nav-icon fas fa-gauge"></i>
                        <p>Dashboard</p>
                    </a>
                </li>
                @endcan

                {{-- POS --}}
                @can('sale_create')
                <li class="nav-item">
                    <a href="{{ route('backend.admin.cart.index') }}"
                        class="nav-link {{ $route === 'backend.admin.cart.index' ? 'active' : '' }}">
                        <i class="nav-icon fas fa-cart-shopping"></i>
                        <p>POS</p>
                    </a>
                </li>
                @endcan

                {{-- People --}}
                @if (auth()->user()->hasAnyPermission(['customer_view','supplier_view']))
                <li class="nav-item {{ request()->is('backend/admin/customers*') || request()->is('backend/admin/suppliers*') ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ request()->is('backend/admin/customers*') || request()->is('backend/admin/suppliers*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-users"></i>
                        <p>People<i class="right fas fa-angle-left"></i></p>
                    </a>
                    <ul class="nav nav-treeview">
                        @can('customer_view')
                        <li class="nav-item">
                            <a href="{{ route('backend.admin.customers.index') }}"
                                class="nav-link {{ request()->is('backend/admin/customers*') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Customers</p>
                            </a>
                        </li>
                        @endcan
                        @can('supplier_view')
                        <li class="nav-item">
                            <a href="{{ route('backend.admin.suppliers.index') }}"
                                class="nav-link {{ request()->is('backend/admin/suppliers*') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Suppliers</p>
                            </a>
                        </li>
                        @endcan
                    </ul>
                </li>
                @endif

                {{-- Products --}}
                @if (auth()->user()->hasAnyPermission(['product_view','brand_view','category_view','unit_view']))
                <li class="nav-item {{ request()->is('backend/admin/products*') || request()->is('backend/admin/brands*') || request()->is('backend/admin/categories*') || request()->is('backend/admin/units*') ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ request()->is('backend/admin/products*') || request()->is('backend/admin/brands*') || request()->is('backend/admin/categories*') || request()->is('backend/admin/units*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-boxes-stacked"></i>
                        <p>Products<i class="right fas fa-angle-left"></i></p>
                    </a>
                    <ul class="nav nav-treeview">
                        @can('product_view')
                        <li class="nav-item">
                            <a href="{{ route('backend.admin.products.index') }}"
                                class="nav-link {{ request()->is('backend/admin/products*') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Product List</p>
                            </a>
                        </li>
                        @endcan
                        @can('brand_view')
                        <li class="nav-item">
                            <a href="{{ route('backend.admin.brands.index') }}"
                                class="nav-link {{ request()->is('backend/admin/brands*') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Brands</p>
                            </a>
                        </li>
                        @endcan
                        @can('category_view')
                        <li class="nav-item">
                            <a href="{{ route('backend.admin.categories.index') }}"
                                class="nav-link {{ request()->is('backend/admin/categories*') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Categories</p>
                            </a>
                        </li>
                        @endcan
                        @can('unit_view')
                        <li class="nav-item">
                            <a href="{{ route('backend.admin.units.index') }}"
                                class="nav-link {{ request()->is('backend/admin/units*') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Units</p>
                            </a>
                        </li>
                        @endcan
                    </ul>
                </li>
                @endif

                {{-- Sales --}}
                @can('sale_view')
                <li class="nav-item">
                    <a href="{{ route('backend.admin.orders.index') }}"
                        class="nav-link {{ request()->is('backend/admin/orders*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-receipt"></i>
                        <p>Sales</p>
                    </a>
                </li>
                @endcan

                {{-- Purchases --}}
                @canany(['purchase_create','purchase_view'])
                <li class="nav-item {{ request()->is('backend/admin/purchase*') ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ request()->is('backend/admin/purchase*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-bag-shopping"></i>
                        <p>Purchase<i class="right fas fa-angle-left"></i></p>
                    </a>
                    <ul class="nav nav-treeview">
                        @can('purchase_view')
                        <li class="nav-item">
                            <a href="{{ route('backend.admin.purchase.index') }}"
                                class="nav-link {{ request()->is('backend/admin/purchase') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Purchase List</p>
                            </a>
                        </li>
                        @endcan
                        @can('purchase_create')
                        <li class="nav-item">
                            <a href="{{ route('backend.admin.purchase.create') }}"
                                class="nav-link {{ request()->is('backend/admin/purchase/create') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Add Purchase</p>
                            </a>
                        </li>
                        @endcan
                    </ul>
                </li>
                @endcanany

                {{-- Reports --}}
                @if (auth()->user()->hasAnyPermission(['reports_summary','reports_sales','reports_inventory']))
                <li class="nav-item {{ request()->is('backend/admin/sale*') || request()->is('backend/admin/inventory*') ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ request()->is('backend/admin/sale*') || request()->is('backend/admin/inventory*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-chart-line"></i>
                        <p>Reports<i class="right fas fa-angle-left"></i></p>
                    </a>
                    <ul class="nav nav-treeview">
                        @can('reports_summary')
                        <li class="nav-item">
                            <a href="{{ route('backend.admin.sale.summery') }}"
                                class="nav-link {{ request()->is('backend/admin/sale/summery') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Sales Summary</p>
                            </a>
                        </li>
                        @endcan
                        @can('reports_sales')
                        <li class="nav-item">
                            <a href="{{ route('backend.admin.sale.report') }}"
                                class="nav-link {{ request()->is('backend/admin/sale/report') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Sales Report</p>
                            </a>
                        </li>
                        @endcan
                        @can('reports_inventory')
                        <li class="nav-item">
                            <a href="{{ route('backend.admin.inventory.report') }}"
                                class="nav-link {{ request()->is('backend/admin/inventory/report') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Inventory Report</p>
                            </a>
                        </li>
                        @endcan
                    </ul>
                </li>
                @endif

                {{-- Settings --}}
                @if (auth()->user()->hasAnyPermission(['role_view','user_view','currency_view','website_settings']))
                <li class="nav-header mt-3 text-uppercase text-sm text-muted">Settings</li>
                <li class="nav-item {{ request()->is('backend/admin/settings*') || request()->is('backend/admin/users*') ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ request()->is('backend/admin/settings*') || request()->is('backend/admin/users*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-gears"></i>
                        <p>System Settings<i class="right fas fa-angle-left"></i></p>
                    </a>
                    <ul class="nav nav-treeview">
                        @can('website_settings')
                        <li class="nav-item">
                            <a href="{{ route('backend.admin.settings.website.general') }}"
                                class="nav-link {{ request()->is('backend/admin/settings/website*') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Website Settings</p>
                            </a>
                        </li>
                        @endcan
                        @can('currency_view')
                        <li class="nav-item">
                            <a href="{{ route('backend.admin.currencies.index') }}"
                                class="nav-link {{ request()->is('backend/admin/currencies*') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Currencies</p>
                            </a>
                        </li>
                        @endcan
                        @can('role_view')
                        <li class="nav-item">
                            <a href="{{ route('backend.admin.roles') }}"
                                class="nav-link {{ $route === 'backend.admin.roles' ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Roles</p>
                            </a>
                        </li>
                        @endcan
                        @can('user_view')
                        <li class="nav-item">
                            <a href="{{ route('backend.admin.users') }}"
                                class="nav-link {{ $route === 'backend.admin.users' ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Users</p>
                            </a>
                        </li>
                        @endcan
                    </ul>
                </li>
                @endif
            </ul>
        </nav>
    </div>
</aside>

{{-- Sidebar Active/Expand Script --}}
<script>
document.addEventListener("DOMContentLoaded", function () {
    const activeLink = document.querySelector('.nav-link.active');
    if (activeLink) {
        const parentTreeview = activeLink.closest('.has-treeview, .nav-item.menu-open');
        if (parentTreeview) {
            parentTreeview.classList.add('menu-open');
            const parentLink = parentTreeview.querySelector('> .nav-link');
            if (parentLink) parentLink.classList.add('active');
        }
    }
});
</script>

<style>
    .nav-link.active {
        background-color: #007bff !important;
        color: #fff !important;
        font-weight: 600;
    }
    .nav-link.active i {
        color: #fff !important;
    }
    .nav-treeview .nav-link.active {
        background-color: #0069d9 !important;
        border-radius: 5px;
    }
    .nav-sidebar > .nav-item.menu-open > .nav-link {
        background-color: #343a40;
        color: #fff;
    }
</style>
