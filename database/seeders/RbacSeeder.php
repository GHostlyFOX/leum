<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Seeder для RBAC-системы «Детская лига».
 *
 * 1. Справочник ref_user_roles (роли внутри команды)
 * 2. Таблица permissions (гранулярные разрешения)
 * 3. Таблица role_permissions (матрица «глобальная роль → разрешения»)
 */
class RbacSeeder extends Seeder
{
    public function run(): void
    {
        // ── 1. Командные роли (ref_user_roles) ───────────────────────────────

        $teamRoles = ['coach', 'player', 'team_manager', 'parent'];

        foreach ($teamRoles as $role) {
            DB::table('ref_user_roles')->updateOrInsert(
                ['name' => $role],
                ['name' => $role]
            );
        }

        // ── 2. Разрешения ────────────────────────────────────────────────────

        $permissions = [
            // Клубы
            ['slug' => 'clubs.view',     'name' => 'Просмотр клубов',                  'group' => 'clubs'],
            ['slug' => 'clubs.create',   'name' => 'Создание клубов',                  'group' => 'clubs'],
            ['slug' => 'clubs.update',   'name' => 'Редактирование клубов',            'group' => 'clubs'],
            ['slug' => 'clubs.delete',   'name' => 'Удаление клубов',                  'group' => 'clubs'],

            // Команды
            ['slug' => 'teams.view',     'name' => 'Просмотр команд',                  'group' => 'teams'],
            ['slug' => 'teams.create',   'name' => 'Создание команд',                  'group' => 'teams'],
            ['slug' => 'teams.update',   'name' => 'Редактирование команд',            'group' => 'teams'],
            ['slug' => 'teams.delete',   'name' => 'Удаление команд',                  'group' => 'teams'],
            ['slug' => 'teams.members',  'name' => 'Управление составом команды',      'group' => 'teams'],

            // Тренировки
            ['slug' => 'trainings.view',       'name' => 'Просмотр тренировок',        'group' => 'trainings'],
            ['slug' => 'trainings.create',     'name' => 'Создание тренировок',        'group' => 'trainings'],
            ['slug' => 'trainings.update',     'name' => 'Редактирование тренировок',  'group' => 'trainings'],
            ['slug' => 'trainings.cancel',     'name' => 'Отмена тренировок',          'group' => 'trainings'],
            ['slug' => 'trainings.attendance', 'name' => 'Отметка посещаемости',       'group' => 'trainings'],

            // Матчи
            ['slug' => 'matches.view',     'name' => 'Просмотр матчей',                'group' => 'matches'],
            ['slug' => 'matches.create',   'name' => 'Создание матчей',                'group' => 'matches'],
            ['slug' => 'matches.update',   'name' => 'Редактирование матчей',          'group' => 'matches'],
            ['slug' => 'matches.manage',   'name' => 'Управление матчем (старт/стоп)', 'group' => 'matches'],

            // Турниры
            ['slug' => 'tournaments.view',     'name' => 'Просмотр турниров',          'group' => 'tournaments'],
            ['slug' => 'tournaments.create',   'name' => 'Создание турниров',          'group' => 'tournaments'],
            ['slug' => 'tournaments.update',   'name' => 'Редактирование турниров',    'group' => 'tournaments'],
            ['slug' => 'tournaments.register', 'name' => 'Регистрация команд на турнир', 'group' => 'tournaments'],

            // Пользователи
            ['slug' => 'users.view',       'name' => 'Просмотр пользователей',         'group' => 'users'],
            ['slug' => 'users.update',     'name' => 'Редактирование пользователей',   'group' => 'users'],
            ['slug' => 'users.manage',     'name' => 'Управление пользователями',      'group' => 'users'],

            // Файлы
            ['slug' => 'files.upload',   'name' => 'Загрузка файлов',                  'group' => 'files'],
            ['slug' => 'files.delete',   'name' => 'Удаление файлов',                  'group' => 'files'],
        ];

        foreach ($permissions as $perm) {
            DB::table('permissions')->updateOrInsert(
                ['slug' => $perm['slug']],
                $perm
            );
        }

        // ── 3. Матрица «глобальная роль → разрешения» ────────────────────────

        // super_admin получает всё автоматически через User::isSuperAdmin()
        // Здесь задаём только admin, coach, parent, player

        $matrix = [
            'admin' => [
                'clubs.*', 'teams.*', 'trainings.*', 'matches.*',
                'tournaments.*', 'users.*', 'files.*',
            ],
            'coach' => [
                'clubs.view',
                'teams.view', 'teams.members',
                'trainings.view', 'trainings.create', 'trainings.update', 'trainings.cancel', 'trainings.attendance',
                'matches.view', 'matches.create', 'matches.update', 'matches.manage',
                'tournaments.view', 'tournaments.register',
                'users.view',
                'files.upload',
            ],
            'parent' => [
                'clubs.view',
                'teams.view',
                'trainings.view',
                'matches.view',
                'tournaments.view',
                'users.view',
            ],
            'player' => [
                'clubs.view',
                'teams.view',
                'trainings.view',
                'matches.view',
                'tournaments.view',
            ],
        ];

        // Получаем id всех разрешений
        $allPerms = DB::table('permissions')->pluck('id', 'slug');

        // Очищаем таблицу привязок и заполняем заново
        DB::table('role_permissions')->truncate();

        foreach ($matrix as $role => $slugPatterns) {
            $permIds = collect();

            foreach ($slugPatterns as $pattern) {
                if (str_ends_with($pattern, '.*')) {
                    // Подстановка: clubs.* → все slug, начинающиеся с "clubs."
                    $prefix = str_replace('.*', '.', $pattern);
                    $matched = $allPerms->filter(fn ($id, $slug) => str_starts_with($slug, $prefix));
                    $permIds = $permIds->merge($matched->values());
                } else {
                    if ($allPerms->has($pattern)) {
                        $permIds->push($allPerms[$pattern]);
                    }
                }
            }

            foreach ($permIds->unique() as $permId) {
                DB::table('role_permissions')->insert([
                    'role'          => $role,
                    'permission_id' => $permId,
                ]);
            }
        }
    }
}
