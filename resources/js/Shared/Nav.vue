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

<!--                    <li class="nav-item"
                        :class="{ active : $page.url.startsWith('/patients') }"
                        v-if="$page.props.shared.userPermissions.includes('read_all_patients')">
                        <Link :href="route('patients.all')" class="nav-link">
                            Дерматопатология
                        </Link>
                    </li>-->

                    <li class="nav-item dropdown">
                        <a href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="nav-link dropdown-toggle">Справочник</a>
                        <ul aria-labelledby="dropdownSubMenu1" class="dropdown-menu border-0 shadow" style="left: 0px; right: inherit;">
                            <li>
                                <Link :href="route('storehouses.index')" class="nav-link">
                                    Склады
                                </Link>
                            </li>
                        </ul>
                    </li>

                    <li class="nav-item dropdown"
                        :class="{ active : $page.url.startsWith('/doctors') || $page.url.startsWith('/users') || $page.url.startsWith('/roles') }"
                        v-if="$page.props.shared.userPermissions.includes('read_doctors') || $page.props.shared.userPermissions.includes('read_users') || $page.props.shared.userPermissions.includes('read_roles')"
                    >
                        <a id="dropdownSubMenu1" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="nav-link dropdown-toggle">Управление</a>
                        <ul aria-labelledby="dropdownSubMenu1" class="dropdown-menu border-0 shadow" style="left: 0px; right: inherit;">
                            <li v-if="$page.props.shared.userPermissions.includes('read_users')"
                                :class="{ active : $page.url.startsWith('/users')}"
                            >
                                <Link :href="route('users.index')" class="nav-link">
                                    Пользователи
                                </Link>
                            </li>
                            <li v-if="$page.props.shared.userPermissions.includes('read_roles')"
                                :class="{ active : $page.url.startsWith('/roles')}"
                            >
                                <Link :href="route('roles.index')" class="nav-link">
                                    Роли
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
