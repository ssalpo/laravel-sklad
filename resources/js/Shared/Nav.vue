<template>
    <!-- Navbar -->
    <nav class="main-header navbar navbar-expand-md navbar-light navbar-white">
        <div class="container">
            <button class="navbar-toggler order-1" type="button" data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div v-if="$page.props.shared.isAuth" class="collapse navbar-collapse order-3" id="navbarCollapse">
                <!-- Left navbar links -->
                <ul class="navbar-nav">

                    <li class="nav-item">
                        <Link :href="route('dashboard.index')" class="nav-link">
                            Аналитика
                        </Link>
                    </li>

                    <li class="nav-item">
                        <Link :href="route('orders.index')" class="nav-link">
                            Заявки
                        </Link>
                    </li>

                    <li class="nav-item">
                        <Link :href="route('nomenclature-arrivals.index')" class="nav-link">
                            Приход
                        </Link>
                    </li>

                    <li class="nav-item">
                        <Link :href="route('clients.index')" class="nav-link">
                            Клиенты
                        </Link>
                    </li>

                    <li class="nav-item">
                        <Link :href="route('mixture-compositions.index')" class="nav-link">
                            Состав продуктов
                        </Link>
                    </li>

                    <li class="nav-item dropdown">
                        <a href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="nav-link dropdown-toggle">Справочник</a>
                        <ul aria-labelledby="dropdownSubMenu1" class="dropdown-menu border-0 shadow" style="left: 0px; right: inherit;">
                            <li>
                                <Link :href="route('nomenclatures.index')" class="nav-link">
                                    Номенклатура
                                </Link>
                            </li>
                            <li>
                                <Link :href="route('users.index')" class="nav-link">
                                    Пользователи
                                </Link>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>

            <!-- Right navbar links -->
            <ul class="order-1 order-md-3 navbar-nav navbar-no-expand ml-auto">
                <li class="nav-item">
                    <Link :href="route('logout')" method="delete" class="btn btn-link" as="button">
                        Выйти
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
