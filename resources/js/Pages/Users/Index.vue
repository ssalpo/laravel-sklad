<template>
    <Head>
        <title>Сотрудники</title>
    </Head>

    <div class="content-header">
        <div class="container-fluid">
            <h5 class="m-0">Сотрудники</h5>
        </div>
    </div>

    <div class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-header">
                    <div class="card-tools">
                        <Link :href="route('users.create')" class="btn btn-success btn-sm px-3">
                            Новый сотрудник
                        </Link>
                    </div>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered  text-nowrap">
                            <thead>
                            <tr>
                                <th style="width: 10px">#</th>
                                <th>Имя</th>
                                <th>Логин</th>
                                <th>Дата создания</th>
                                <th>Активность</th>
                                <th>Админ</th>
                                <th width="50">Телеграм</th>
                                <th width="40"></th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="(user, index) in users.data">
                                <td>{{ ((users.current_page - 1) * users.per_page) + index + 1 }}</td>
                                <td>{{ user.name }}</td>
                                <td>{{ user.username }}</td>
                                <td>{{ user.created_at }}</td>
                                <td>
                                    <input type="checkbox" :checked="user.is_active" @change="toggleActivity(user.id)">
                                </td>
                                <td>
                                    <input type="checkbox" :checked="user.is_admin" @change="toggleAdminStatus(user.id)">
                                </td>
                                <td>
                                    <telegram-notification-toggle
                                        :user-id="user.id"
                                        :is-already-connected="user.telegram_user_id !== null"
                                    />
                                </td>
                                <td class="text-center">
                                    <Link :href="route('users.edit', user.id)">
                                        <i class="fa fa-pencil-alt"></i>
                                    </Link>
                                </td>
                            </tr>

                            </tbody>
                        </table>
                    </div>
                </div>
                <!-- /.card-body -->

                <div class="card-footer clearfix" v-if="users.links.length > 3">
                    <pagination :links="users.links"/>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import {Head, Link} from "@inertiajs/inertia-vue3";
import Pagination from "../../Shared/Pagination.vue";
import TelegramNotificationToggle from "../../Shared/TelegramNotificationToggle.vue";

export default {
    components: {TelegramNotificationToggle, Pagination, Head, Link},
    props: ['users'],
    methods: {
        toggleActivity(id) {
            this.$inertia.post(route('users.toggle_activity', id));
        },
        toggleAdminStatus(id) {
            this.$inertia.post(route('users.toggle-admin-status', id));
        }
    }
}
</script>
