<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Заполнение групп и типов событий матча
 * 
 * Группы событий разбиты по видам спорта и категориям
 */
return new class extends Migration
{
    public function up(): void
    {
        // ═════════════════════════════════════════════════════════════════════
        // 1. ГРУППЫ СОБЫТИЙ (общие и специфичные для видов спорта)
        // ═════════════════════════════════════════════════════════════════════
        $groups = [
            // Общие группы (sport_type_id = NULL)
            ['id' => 1, 'name' => 'Голы', 'code' => 'goals', 'icon' => 'soccer-ball', 'color' => '#28a745', 'sort_order' => 10, 'sport_type_id' => null],
            ['id' => 2, 'name' => 'Карточки', 'code' => 'cards', 'icon' => 'card', 'color' => '#dc3545', 'sort_order' => 20, 'sport_type_id' => null],
            ['id' => 3, 'name' => 'Замены', 'code' => 'substitutions', 'icon' => 'refresh', 'color' => '#007bff', 'sort_order' => 30, 'sport_type_id' => null],
            ['id' => 4, 'name' => 'Таймы', 'code' => 'periods', 'icon' => 'clock', 'color' => '#6c757d', 'sort_order' => 40, 'sport_type_id' => null],
            ['id' => 5, 'name' => 'Судейские решения', 'code' => 'referee', 'icon' => 'whistle', 'color' => '#fd7e14', 'sort_order' => 50, 'sport_type_id' => null],
            ['id' => 6, 'name' => 'Травмы', 'code' => 'injuries', 'icon' => 'medical', 'color' => '#e83e8c', 'sort_order' => 60, 'sport_type_id' => null],
            ['id' => 7, 'name' => 'Статистика', 'code' => 'stats', 'icon' => 'chart', 'color' => '#17a2b8', 'sort_order' => 70, 'sport_type_id' => null],
            ['id' => 8, 'name' => 'Системные', 'code' => 'system', 'icon' => 'cog', 'color' => '#6c757d', 'sort_order' => 100, 'sport_type_id' => null],
            
            // Специфичные группы для футбола
            ['id' => 9, 'name' => 'VAR', 'code' => 'var', 'icon' => 'video', 'color' => '#6610f2', 'sort_order' => 45, 'sport_type_id' => 1],
            ['id' => 10, 'name' => 'Удары', 'code' => 'shots', 'icon' => 'target', 'color' => '#20c997', 'sort_order' => 71, 'sport_type_id' => 1],
            
            // Специфичные группы для хоккея
            ['id' => 11, 'name' => 'Буллиты', 'code' => 'penalty_shots', 'icon' => 'target', 'color' => '#ffc107', 'sort_order' => 15, 'sport_type_id' => 2],
            ['id' => 12, 'name' => 'Удаления', 'code' => 'penalties', 'icon' => 'user-x', 'color' => '#dc3545', 'sort_order' => 21, 'sport_type_id' => 2],
            
            // Специфичные группы для баскетбола
            ['id' => 13, 'name' => 'Броски', 'code' => 'shots', 'icon' => 'basketball', 'color' => '#fd7e14', 'sort_order' => 11, 'sport_type_id' => 3],
            ['id' => 14, 'name' => 'Фолы', 'code' => 'fouls', 'icon' => 'hand-stop', 'color' => '#dc3545', 'sort_order' => 22, 'sport_type_id' => 3],
            ['id' => 15, 'name' => 'Тайм-ауты', 'code' => 'timeouts', 'icon' => 'pause', 'color' => '#6c757d', 'sort_order' => 35, 'sport_type_id' => 3],
            
            // Специфичные группы для волейбола
            ['id' => 16, 'name' => 'Очки', 'code' => 'points', 'icon' => 'volleyball', 'color' => '#28a745', 'sort_order' => 12, 'sport_type_id' => 4],
            ['id' => 17, 'name' => 'Сеты', 'code' => 'sets', 'icon' => 'layers', 'color' => '#17a2b8', 'sort_order' => 41, 'sport_type_id' => 4],
        ];

        foreach ($groups as $group) {
            DB::table('ref_match_event_groups')->insert($group);
        }

        // ═════════════════════════════════════════════════════════════════════
        // 2. ТИПЫ СОБЫТИЙ
        // ═════════════════════════════════════════════════════════════════════
        $eventTypes = [
            // ГОЛЫ (group_id = 1)
            ['id' => 1, 'name' => 'Гол', 'code' => 'goal', 'group_id' => 1, 'sport_type_id' => null, 'sort_order' => 10, 'is_statistical' => true, 'affects_score' => true, 'icon' => 'soccer-ball', 'color' => '#28a745'],
            ['id' => 2, 'name' => 'Гол с пенальти', 'code' => 'goal_penalty', 'group_id' => 1, 'sport_type_id' => 1, 'sort_order' => 11, 'is_statistical' => true, 'affects_score' => true, 'icon' => 'soccer-ball', 'color' => '#28a745'],
            ['id' => 3, 'name' => 'Гол со штрафного', 'code' => 'goal_free_kick', 'group_id' => 1, 'sport_type_id' => 1, 'sort_order' => 12, 'is_statistical' => true, 'affects_score' => true, 'icon' => 'soccer-ball', 'color' => '#28a745'],
            ['id' => 4, 'name' => 'Автогол', 'code' => 'own_goal', 'group_id' => 1, 'sport_type_id' => 1, 'sort_order' => 13, 'is_statistical' => true, 'affects_score' => true, 'icon' => 'soccer-ball', 'color' => '#dc3545'],
            ['id' => 5, 'name' => 'Гол в большинстве', 'code' => 'goal_powerplay', 'group_id' => 1, 'sport_type_id' => 2, 'sort_order' => 14, 'is_statistical' => true, 'affects_score' => true, 'icon' => 'hockey-puck', 'color' => '#28a745'],
            ['id' => 6, 'name' => 'Гол в меньшинстве', 'code' => 'goal_shorthanded', 'group_id' => 1, 'sport_type_id' => 2, 'sort_order' => 15, 'is_statistical' => true, 'affects_score' => true, 'icon' => 'hockey-puck', 'color' => '#28a745'],
            ['id' => 7, 'name' => 'Буллит забит', 'code' => 'penalty_shot_goal', 'group_id' => 11, 'sport_type_id' => 2, 'sort_order' => 10, 'is_statistical' => true, 'affects_score' => true, 'icon' => 'target', 'color' => '#28a745'],
            ['id' => 8, 'name' => 'Буллит не забит', 'code' => 'penalty_shot_miss', 'group_id' => 11, 'sport_type_id' => 2, 'sort_order' => 11, 'is_statistical' => true, 'affects_score' => false, 'icon' => 'target', 'color' => '#dc3545'],
            ['id' => 9, 'name' => 'Двухочковый', 'code' => '2_point', 'group_id' => 13, 'sport_type_id' => 3, 'sort_order' => 10, 'is_statistical' => true, 'affects_score' => true, 'icon' => 'basketball', 'color' => '#fd7e14'],
            ['id' => 10, 'name' => 'Трехочковый', 'code' => '3_point', 'group_id' => 13, 'sport_type_id' => 3, 'sort_order' => 11, 'is_statistical' => true, 'affects_score' => true, 'icon' => 'basketball', 'color' => '#28a745'],
            ['id' => 11, 'name' => 'Штрафной бросок', 'code' => 'free_throw', 'group_id' => 13, 'sport_type_id' => 3, 'sort_order' => 12, 'is_statistical' => true, 'affects_score' => true, 'icon' => 'basketball', 'color' => '#007bff'],
            ['id' => 12, 'name' => 'Очко в атаке', 'code' => 'point_attack', 'group_id' => 16, 'sport_type_id' => 4, 'sort_order' => 10, 'is_statistical' => true, 'affects_score' => true, 'icon' => 'volleyball', 'color' => '#28a745'],
            ['id' => 13, 'name' => 'Очко в блоке', 'code' => 'point_block', 'group_id' => 16, 'sport_type_id' => 4, 'sort_order' => 11, 'is_statistical' => true, 'affects_score' => true, 'icon' => 'volleyball', 'color' => '#28a745'],
            ['id' => 14, 'name' => 'Эйс', 'code' => 'ace', 'group_id' => 16, 'sport_type_id' => 4, 'sort_order' => 12, 'is_statistical' => true, 'affects_score' => true, 'icon' => 'volleyball', 'color' => '#ffc107'],

            // КАРТОЧКИ / ФОЛЫ (group_id = 2, 12, 14)
            ['id' => 20, 'name' => 'Желтая карточка', 'code' => 'yellow_card', 'group_id' => 2, 'sport_type_id' => 1, 'sort_order' => 10, 'is_statistical' => true, 'affects_score' => false, 'icon' => 'card-yellow', 'color' => '#ffc107'],
            ['id' => 21, 'name' => 'Вторая желтая', 'code' => 'second_yellow', 'group_id' => 2, 'sport_type_id' => 1, 'sort_order' => 11, 'is_statistical' => true, 'affects_score' => false, 'icon' => 'card-yellow-red', 'color' => '#ff9800'],
            ['id' => 22, 'name' => 'Красная карточка', 'code' => 'red_card', 'group_id' => 2, 'sport_type_id' => 1, 'sort_order' => 12, 'is_statistical' => true, 'affects_score' => false, 'icon' => 'card-red', 'color' => '#dc3545'],
            ['id' => 23, 'name' => 'Удаление 2 мин', 'code' => 'penalty_2min', 'group_id' => 12, 'sport_type_id' => 2, 'sort_order' => 10, 'is_statistical' => true, 'affects_score' => false, 'icon' => 'user-x', 'color' => '#ffc107'],
            ['id' => 24, 'name' => 'Удаление 5 мин', 'code' => 'penalty_5min', 'group_id' => 12, 'sport_type_id' => 2, 'sort_order' => 11, 'is_statistical' => true, 'affects_score' => false, 'icon' => 'user-x', 'color' => '#ff9800'],
            ['id' => 25, 'name' => 'Удаление до конца', 'code' => 'penalty_game', 'group_id' => 12, 'sport_type_id' => 2, 'sort_order' => 12, 'is_statistical' => true, 'affects_score' => false, 'icon' => 'user-x', 'color' => '#dc3545'],
            ['id' => 26, 'name' => 'Фол', 'code' => 'foul', 'group_id' => 14, 'sport_type_id' => 3, 'sort_order' => 10, 'is_statistical' => true, 'affects_score' => false, 'icon' => 'hand-stop', 'color' => '#ffc107'],
            ['id' => 27, 'name' => 'Технический фол', 'code' => 'technical_foul', 'group_id' => 14, 'sport_type_id' => 3, 'sort_order' => 11, 'is_statistical' => true, 'affects_score' => false, 'icon' => 'hand-stop', 'color' => '#dc3545'],
            ['id' => 28, 'name' => 'Неспортивное поведение', 'code' => 'unsportsmanlike', 'group_id' => 14, 'sport_type_id' => 3, 'sort_order' => 12, 'is_statistical' => true, 'affects_score' => false, 'icon' => 'hand-stop', 'color' => '#e83e8c'],

            // ЗАМЕНЫ (group_id = 3)
            ['id' => 30, 'name' => 'Замена', 'code' => 'substitution', 'group_id' => 3, 'sport_type_id' => null, 'sort_order' => 10, 'is_statistical' => false, 'affects_score' => false, 'icon' => 'refresh', 'color' => '#007bff'],
            ['id' => 31, 'name' => 'Замена (травма)', 'code' => 'sub_injury', 'group_id' => 3, 'sport_type_id' => null, 'sort_order' => 11, 'is_statistical' => false, 'affects_score' => false, 'icon' => 'refresh-medical', 'color' => '#e83e8c'],

            // ТАЙМЫ / ПЕРИОДЫ (group_id = 4, 17)
            ['id' => 40, 'name' => 'Начало матча', 'code' => 'match_start', 'group_id' => 4, 'sport_type_id' => null, 'sort_order' => 1, 'is_statistical' => false, 'affects_score' => false, 'icon' => 'play', 'color' => '#28a745'],
            ['id' => 41, 'name' => 'Конец первого тайма', 'code' => 'half_time', 'group_id' => 4, 'sport_type_id' => null, 'sort_order' => 2, 'is_statistical' => false, 'affects_score' => false, 'icon' => 'pause', 'color' => '#6c757d'],
            ['id' => 42, 'name' => 'Начало второго тайма', 'code' => 'second_half_start', 'group_id' => 4, 'sport_type_id' => null, 'sort_order' => 3, 'is_statistical' => false, 'affects_score' => false, 'icon' => 'play', 'color' => '#28a745'],
            ['id' => 43, 'name' => 'Конец матча', 'code' => 'match_end', 'group_id' => 4, 'sport_type_id' => null, 'sort_order' => 4, 'is_statistical' => false, 'affects_score' => false, 'icon' => 'stop', 'color' => '#dc3545'],
            ['id' => 44, 'name' => 'Начало овертайма', 'code' => 'overtime_start', 'group_id' => 4, 'sport_type_id' => null, 'sort_order' => 5, 'is_statistical' => false, 'affects_score' => false, 'icon' => 'play-circle', 'color' => '#fd7e14'],
            ['id' => 45, 'name' => 'Конец овертайма', 'code' => 'overtime_end', 'group_id' => 4, 'sport_type_id' => null, 'sort_order' => 6, 'is_statistical' => false, 'affects_score' => false, 'icon' => 'stop-circle', 'color' => '#6c757d'],
            ['id' => 46, 'name' => 'Начало пенальти', 'code' => 'penalties_start', 'group_id' => 4, 'sport_type_id' => null, 'sort_order' => 7, 'is_statistical' => false, 'affects_score' => false, 'icon' => 'target', 'color' => '#ffc107'],
            ['id' => 47, 'name' => 'Конец пенальти', 'code' => 'penalties_end', 'group_id' => 4, 'sport_type_id' => null, 'sort_order' => 8, 'is_statistical' => false, 'affects_score' => false, 'icon' => 'check', 'color' => '#28a745'],
            ['id' => 48, 'name' => 'Выигран сет', 'code' => 'set_won', 'group_id' => 17, 'sport_type_id' => 4, 'sort_order' => 10, 'is_statistical' => true, 'affects_score' => true, 'icon' => 'layers', 'color' => '#28a745'],

            // СУДЕЙСКИЕ РЕШЕНИЯ / VAR (group_id = 5, 9)
            ['id' => 50, 'name' => 'Пенальти назначен', 'code' => 'penalty_awarded', 'group_id' => 5, 'sport_type_id' => 1, 'sort_order' => 10, 'is_statistical' => false, 'affects_score' => false, 'icon' => 'flag', 'color' => '#fd7e14'],
            ['id' => 51, 'name' => 'Пенальти не реализован', 'code' => 'penalty_missed', 'group_id' => 5, 'sport_type_id' => 1, 'sort_order' => 11, 'is_statistical' => true, 'affects_score' => false, 'icon' => 'times', 'color' => '#dc3545'],
            ['id' => 52, 'name' => 'VAR проверка', 'code' => 'var_check', 'group_id' => 9, 'sport_type_id' => 1, 'sort_order' => 10, 'is_statistical' => false, 'affects_score' => false, 'icon' => 'video', 'color' => '#6610f2'],
            ['id' => 53, 'name' => 'VAR подтверждено', 'code' => 'var_confirmed', 'group_id' => 9, 'sport_type_id' => 1, 'sort_order' => 11, 'is_statistical' => false, 'affects_score' => false, 'icon' => 'video', 'color' => '#28a745'],
            ['id' => 54, 'name' => 'VAR отменено', 'code' => 'var_cancelled', 'group_id' => 9, 'sport_type_id' => 1, 'sort_order' => 12, 'is_statistical' => false, 'affects_score' => false, 'icon' => 'video', 'color' => '#dc3545'],

            // ТРАВМЫ (group_id = 6)
            ['id' => 60, 'name' => 'Травма', 'code' => 'injury', 'group_id' => 6, 'sport_type_id' => null, 'sort_order' => 10, 'is_statistical' => false, 'affects_score' => false, 'icon' => 'medical', 'color' => '#e83e8c'],
            ['id' => 61, 'name' => 'Медицинский перерыв', 'code' => 'medical_timeout', 'group_id' => 6, 'sport_type_id' => null, 'sort_order' => 11, 'is_statistical' => false, 'affects_score' => false, 'icon' => 'medical', 'color' => '#e83e8c'],

            // СТАТИСТИКА / УДАРЫ (group_id = 7, 10)
            ['id' => 70, 'name' => 'Удар в створ', 'code' => 'shot_on_target', 'group_id' => 10, 'sport_type_id' => 1, 'sort_order' => 10, 'is_statistical' => true, 'affects_score' => false, 'icon' => 'target', 'color' => '#17a2b8'],
            ['id' => 71, 'name' => 'Удар мимо', 'code' => 'shot_off_target', 'group_id' => 10, 'sport_type_id' => 1, 'sort_order' => 11, 'is_statistical' => true, 'affects_score' => false, 'icon' => 'target', 'color' => '#6c757d'],
            ['id' => 72, 'name' => 'Штанга/перекладина', 'code' => 'shot_post', 'group_id' => 10, 'sport_type_id' => 1, 'sort_order' => 12, 'is_statistical' => true, 'affects_score' => false, 'icon' => 'target', 'color' => '#ffc107'],
            ['id' => 73, 'name' => 'Сейв вратаря', 'code' => 'goalkeeper_save', 'group_id' => 10, 'sport_type_id' => 1, 'sort_order' => 13, 'is_statistical' => true, 'affects_score' => false, 'icon' => 'shield', 'color' => '#28a745'],
            ['id' => 74, 'name' => 'Угловой', 'code' => 'corner', 'group_id' => 7, 'sport_type_id' => 1, 'sort_order' => 10, 'is_statistical' => true, 'affects_score' => false, 'icon' => 'flag-corner', 'color' => '#007bff'],
            ['id' => 75, 'name' => 'Офсайд', 'code' => 'offside', 'group_id' => 7, 'sport_type_id' => 1, 'sort_order' => 11, 'is_statistical' => true, 'affects_score' => false, 'icon' => 'flag-offside', 'color' => '#dc3545'],

            // ТАЙМ-АУТЫ (group_id = 15)
            ['id' => 80, 'name' => 'Тайм-аут', 'code' => 'timeout', 'group_id' => 15, 'sport_type_id' => 3, 'sort_order' => 10, 'is_statistical' => false, 'affects_score' => false, 'icon' => 'pause', 'color' => '#6c757d'],

            // СИСТЕМНЫЕ (group_id = 8)
            ['id' => 90, 'name' => 'Добавленное время', 'code' => 'added_time', 'group_id' => 8, 'sport_type_id' => null, 'sort_order' => 10, 'is_statistical' => false, 'affects_score' => false, 'icon' => 'clock', 'color' => '#6c757d'],
            ['id' => 91, 'name' => 'Технический перерыв', 'code' => 'technical_pause', 'group_id' => 8, 'sport_type_id' => null, 'sort_order' => 11, 'is_statistical' => false, 'affects_score' => false, 'icon' => 'cog', 'color' => '#6c757d'],
            ['id' => 92, 'name' => 'Матч отменен', 'code' => 'match_cancelled', 'group_id' => 8, 'sport_type_id' => null, 'sort_order' => 20, 'is_statistical' => false, 'affects_score' => false, 'icon' => 'ban', 'color' => '#dc3545'],
            ['id' => 93, 'name' => 'Матч перенесен', 'code' => 'match_postponed', 'group_id' => 8, 'sport_type_id' => null, 'sort_order' => 21, 'is_statistical' => false, 'affects_score' => false, 'icon' => 'calendar-times', 'color' => '#ffc107'],
            ['id' => 94, 'name' => 'Техническое поражение', 'code' => 'technical_defeat', 'group_id' => 8, 'sport_type_id' => null, 'sort_order' => 22, 'is_statistical' => false, 'affects_score' => true, 'icon' => 'ban', 'color' => '#dc3545'],
        ];

        foreach ($eventTypes as $type) {
            DB::table('ref_match_event_types')->insert($type);
        }
    }

    public function down(): void
    {
        DB::table('ref_match_event_types')->delete();
        DB::table('ref_match_event_groups')->delete();
    }
};
