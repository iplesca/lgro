<?php
if (!function_exists('wn8value')) {
    function wn8value($value)
    {
        $result = 'superunicum';
        if ($value < 2900) {
            $result = 'unicum';
        }
        if ($value < 2500) {
            $result = 'great';
        }
        if ($value < 2000) {
            $result = 'verygood';
        }
        if ($value < 1600) {
            $result = 'good';
        }
        if ($value < 1200) {
            $result = 'aboveaverage';
        }
        if ($value < 900) {
            $result = 'average';
        }
        if ($value < 650) {
            $result = 'belowaverage';
        }
        if ($value < 450) {
            $result = 'bad';
        }
        if ($value < 300) {
            $result = 'verybad';
        }
        return $result;
    }
}
if (!function_exists('ranksOrder')) {
    function ranksOrder()
    {
        return [
            'commander' => 1,
            'executive_officer' => 2,
            'personnel_officer' => 3,
            'quartermaster' => 4,
            'intelligence_officer' => 5,
            'combat_officer' => 6,
            'recruitment_officer' => 7,
            'junior_officer' => 8,
            'private' => 9,
            'recruit' => 10,
            'reservist' => 11,
        ];
    }
}