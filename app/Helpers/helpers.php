<?php

use Carbon\Carbon;

function lot_time_left($timer)
{
    if (!$timer) {
        return 'Торги окончены';
    }

    $now = \Carbon\Carbon::now();
    $end = \Carbon\Carbon::parse($timer);

    if ($end->isPast()) {
        return 'Торги окончены';
    }

    $pluralize = function($count, $forms) {
        // $forms — массив из трёх вариантов: ['минута', 'минуты', 'минут']
        $count = abs($count) % 100;
        $count1 = $count % 10;

        if ($count > 10 && $count < 20) {
            return $forms[2];
        }
        if ($count1 > 1 && $count1 < 5) {
            return $forms[1];
        }
        if ($count1 == 1) {
            return $forms[0];
        }
        return $forms[2];
    };

    $diff = $end->diff($now);

    $days = $diff->d;
    $hours = $diff->h;
    $minutes = $diff->i;

    $parts = [];

    if ($days > 0) {
        $parts[] = $days . ' ' . $pluralize($days, ['день', 'дня', 'дней']);
    }

    if ($hours > 0) {
        $parts[] = $hours . ' ' . $pluralize($hours, ['час', 'часа', 'часов']);
    }

    if ($minutes > 0) {
        $parts[] = $minutes . ' ' . $pluralize($minutes, ['минута', 'минуты', 'минут']);
    }

    if (empty($parts)) {
        return 'меньше минуты';
    }

    return implode(' ', $parts);
}

function include_template($template_name, $data, $template_path = 'templates/') {
    $template_name = $template_path . $template_name;
    $result = '';

    // Проверка существования файла
    if (!file_exists($template_name)) {
        return $result;
    }

    // Использование буферизации вывода для захвата содержимого шаблона
    ob_start();
    extract($data);
    require $template_name;

    // Возвращение итогового содержимого шаблона
    $result = ob_get_clean();

    return $result;
}

function formatPrice($price) {
    $price = ceil($price);
    if ($price >= 1000) {
        $price = number_format($price, 0, '.', ' ');
    }
    return $price . ' ₽';
}

if (!function_exists('getDynamicPageTitle')) {
    function getDynamicPageTitle($slug)
    {
        $page = \App\Models\Page::where('slug', $slug)->first();
        return $page ? $page->title : null;
    }
}


