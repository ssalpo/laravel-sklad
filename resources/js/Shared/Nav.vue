<template>
    <!-- Navbar -->
    <nav class="main-header navbar navbar-expand-md navbar-light navbar-white">
        <div class="container">
            <button class="navbar-toggler p-0 px-1" type="button" data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div v-if="$page.props.shared.isAuth" class="collapse navbar-collapse order-3" id="navbarCollapse">
                <!-- Left navbar links -->
                <ul class="navbar-nav" v-if="!$page.props.shared.isAdmin">
                    <li class="nav-item">
                        <Link :href="route('dashboard.index')" class="nav-link">
                            <i class="fa fa-list"></i> Мои заявки
                        </Link>
                    </li>

                    <li class="nav-item">
                        <Link :href="route('my.client-debts.index')" class="nav-link">
                            <i class="fa fa-money-bill"></i> Долги
                        </Link>
                    </li>

                    <li class="nav-item">
                        <Link :href="route('my.clients.create')" class="nav-link">
                            <i class="fa fa-users"></i> Добавить клиента
                        </Link>
                    </li>

                    <li class="nav-item">
                        <Link :href="route('my.orders.create')" class="nav-link text-success">
                            <i class="fa fa-plus-circle"></i> Новая заявка
                        </Link>
                    </li>
                </ul>

                <ul class="navbar-nav" v-if="$page.props.shared.isAdmin">

                    <li class="nav-item">
                        <Link :href="route('dashboard.index')" class="nav-link">
                            <i class="fa fa-chart-pie d-md-none d-lg-inline-block"></i> Аналитика
                        </Link>
                    </li>

                    <li class="nav-item">
                        <Link :href="route('orders.index')" class="nav-link">
                            <i class="fa fa-clipboard-list d-md-none d-lg-inline-block"></i> Заявки
                        </Link>
                    </li>

                    <li class="nav-item">
                        <Link :href="route('storehouses.index')" class="nav-link">
                            <i class="fa fa-warehouse d-md-none d-lg-inline-block"></i>  Остатки
                        </Link>
                    </li>

                    <li class="nav-item">
                        <Link :href="route('all-client-debts')" class="nav-link">
                            <i class="fa fa-money-bill d-md-none d-lg-inline-block"></i> Долги
                        </Link>
                    </li>

                    <li class="nav-item dropdown">
                        <a href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="nav-link dropdown-toggle">
                            Склад
                        </a>

                        <ul aria-labelledby="dropdownSubMenu1" class="dropdown-menu border-0 shadow">
                            <li>
                                <Link :href="route('nomenclature-arrivals.index')" class="dropdown-item">
                                    Приход
                                </Link>
                            </li>

                            <li>
                                <Link :href="route('nomenclature-operations.index-withdraw')" class="dropdown-item">
                                    Списание
                                </Link>
                            </li>
                        </ul>
                    </li>

                    <li class="nav-item dropdown">
                        <a href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="nav-link dropdown-toggle">Справочник</a>
                        <ul aria-labelledby="dropdownSubMenu1" class="dropdown-menu border-0 shadow">

                            <li>
                                <Link :href="route('clients.index')" class="dropdown-item">
                                    Клиенты
                                </Link>
                            </li>

                            <li>
                                <Link :href="route('nomenclatures.index')" class="dropdown-item">
                                    Номенклатура
                                </Link>
                            </li>

                            <li class="nav-item">
                                <Link :href="route('mixture-compositions.index')" class="dropdown-item">
                                    Состав продуктов
                                </Link>
                            </li>

                            <li>
                                <Link :href="route('users.index')" class="dropdown-item">
                                    Сотрудники
                                </Link>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>

            <!-- Right navbar links -->
            <ul class="order-1 order-md-3 navbar-nav navbar-no-expand ml-auto">
                <li class="nav-item d-block d-sm-none" v-if="!$page.props.shared.isAdmin">
                    <Link :href="route('my.orders.create')" class="btn btn-link text-success">
                        <i class="fa fa-plus-circle"></i>
                    </Link>
                </li>
                <li class="nav-item d-block d-sm-none" v-if="!$page.props.shared.isAdmin">
                    <Link :href="route('my.clients.create')" class="btn btn-link text-dark">
                        <i class="fa fa-users"></i>
                    </Link>
                </li>
                <li class="nav-item d-block d-sm-none" v-if="!$page.props.shared.isAdmin">
                    <Link :href="route('my.client-debts.index')" class="nav-link">
                        <i class="fa fa-money-bill"></i>
                    </Link>
                </li>
                <li class="nav-item">
                    <Link :href="route('logout')" method="delete" class="btn btn-link" as="button">
                        <i class="fa fa-sign-out-alt"></i>&nbsp;
                        <span class="d-none d-sm-inline">Выйти</span>
                    </Link>
                </li>
            </ul>
        </div>
    </nav>
    <!-- /.navbar -->
</template>

<script>
import {Link} from "@inertiajs/inertia-vue3";

export default {
    components: {Link}
}
</script>
