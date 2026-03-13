<?php

if (!function_exists('pluralize')) {
    /**
     * Склонение слова в зависимости от числа
     * 
     * @param int $number Число
     * @param string $one Форма для 1 (игрок)
     * @param string $two Форма для 2-4 (игрока)
     * @param string $many Форма для 5-20 (игроков)
     * @return string
     */
    function pluralize(int $number, string $one, string $two, string $many): string
    {
        $lastDigit = $number % 10;
        $lastTwoDigits = $number % 100;
        
        if ($lastTwoDigits >= 11 && $lastTwoDigits <= 19) {
            return $many;
        }
        
        if ($lastDigit === 1) {
            return $one;
        }
        
        if ($lastDigit >= 2 && $lastDigit <= 4) {
            return $two;
        }
        
        return $many;
    }
}

if (!function_exists('pluralize_players')) {
    /**
     * Склонение слова "игрок"
     * 
     * @param int $count Количество
     * @return string
     */
    function pluralize_players(int $count): string
    {
        return pluralize($count, 'игрок', 'игрока', 'игроков');
    }
}
