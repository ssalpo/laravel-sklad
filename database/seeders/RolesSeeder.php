<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roleAdmin = Role::create(['name' => 'admin', 'readable_name' => 'Администратор системы']);
        $roleResident = Role::create(['name' => 'resident', 'readable_name' => 'Ординатор']);
        $roleDoctor = Role::create(['name' => 'doctor', 'readable_name' => 'Доктор']);

        $permissionReadRoles = Permission::create(['name' => 'read_roles', 'readable_name' => 'Просматривать список ролей']);
        $permissionEditRoles = Permission::create(['name' => 'edit_roles', 'readable_name' => 'Редактировать ролей']);
        $permissionCreateRoles = Permission::create(['name' => 'create_roles', 'readable_name' => 'Создавать ролей']);
        $permissionDeleteRoles = Permission::create(['name' => 'delete_roles', 'readable_name' => 'Удалять ролей']);

        $permissionsManagerRoles = [
            $permissionReadRoles,
            $permissionEditRoles,
            $permissionCreateRoles,
            $permissionDeleteRoles,
        ];

        $permissionReadUsers = Permission::create(['name' => 'read_users', 'readable_name' => 'Просматривать список пользователей']);
        $permissionEditUsers = Permission::create(['name' => 'edit_users', 'readable_name' => 'Редактировать пользователей']);
        $permissionCreateUsers = Permission::create(['name' => 'create_users', 'readable_name' => 'Создавать пользователей']);
        $permissionDeleteUsers = Permission::create(['name' => 'delete_users', 'readable_name' => 'Удалять пользователей']);
        $permissionToggleActivityUsers = Permission::create(['name' => 'toggle_activity_users', 'readable_name' => 'Изменять активность пользователей']);

        $permissionsManagerUsers = [
            $permissionReadUsers,
            $permissionEditUsers,
            $permissionCreateUsers,
            $permissionDeleteUsers,
            $permissionToggleActivityUsers
        ];

        $permissionReadPatients = Permission::create(['name' => 'read_all_patients', 'readable_name' => 'Просматривать всех пациентов']);
        $permissionSelectDoctorPatients = Permission::create(['name' => 'select_doctor_patients', 'readable_name' => 'Выбрать врача в при создании/редактировании']);
        $permissionEditPatients = Permission::create(['name' => 'edit_patients', 'readable_name' => 'Редактировать пациентов']);
        $permissionCreatePatients = Permission::create(['name' => 'create_patients', 'readable_name' => 'Создавать пациентов']);
        $permissionDeletePatients = Permission::create(['name' => 'delete_patients', 'readable_name' => 'Удалять пациентов']);

        $permissionsManagerPatients = [
            $permissionEditPatients,
            $permissionCreatePatients,
            $permissionDeletePatients
        ];

        $permissionReadDoctors = Permission::create(['name' => 'read_doctors', 'readable_name' => 'Просматривать список врачей']);
        $permissionEditDoctors = Permission::create(['name' => 'edit_doctors', 'readable_name' => 'Редактировать врачей']);
        $permissionCreateDoctors = Permission::create(['name' => 'create_doctors', 'readable_name' => 'Создавать врачей']);
        $permissionDeleteDoctors = Permission::create(['name' => 'delete_doctors', 'readable_name' => 'Удалять врачей']);

        $permissionsManagerDoctors = [
            $permissionReadDoctors,
            $permissionEditDoctors,
            $permissionCreateDoctors,
            $permissionDeleteDoctors
        ];

        $permissionAddReport = Permission::create(['name' => 'add_report', 'readable_name' => 'Добавлять диагноз']);

//        $roleAdmin->syncPermissions([
//            ...$permissionsManagerRoles,
//            ...$permissionsManagerUsers,
//            ...$permissionsManagerPatients,
//            $permissionReadPatients,
//            $permissionSelectDoctorPatients,
//            ...$permissionsManagerDoctors,
//            $permissionAddReport
//        ]);

        $roleDoctor->syncPermissions([
            ...$permissionsManagerPatients
        ]);
    }
}
