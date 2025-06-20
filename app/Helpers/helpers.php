<?php

use Carbon\Carbon;

function lot_time_left($timer)
{
    if (!$timer) {
        return 'Auction ended';
    }

    $now = \Carbon\Carbon::now();
    $end = \Carbon\Carbon::parse($timer);

    if ($end->isPast()) {
        return 'Auction ended';
    }

    $pluralize = function($count, $forms) {
        $count = abs($count);

        if ($count == 1) {
            return $forms[0];
        }
        return $forms[1];
    };

    $diff = $end->diff($now);

    $days = $diff->d;
    $hours = $diff->h;
    $minutes = $diff->i;

    $parts = [];

    if ($days > 0) {
        $parts[] = $days . ' ' . $pluralize($days, ['day', 'days']);
    }

    if ($hours > 0) {
        $parts[] = $hours . ' ' . $pluralize($hours, ['hour', 'hours']);
    }

    if ($minutes > 0) {
        $parts[] = $minutes . ' ' . $pluralize($minutes, ['minute', 'minutes']);
    }

    if (empty($parts)) {
        return 'less than a minute';
    }

    return implode(' ', $parts);
}

function include_template($template_name, $data, $template_path = 'templates/') {
    $template_name = $template_path . $template_name;
    $result = '';

    if (!file_exists($template_name)) {
        return $result;
    }

    ob_start();
    extract($data);
    require $template_name;

    $result = ob_get_clean();

    return $result;
}

function formatPrice($price) {
    $price = ceil($price);
    if ($price >= 1000) {
        $price = number_format($price, 0, '.', ' ');
    }
    return '$' . $price;
}

if (!function_exists('getDynamicPageTitle')) {
    function getDynamicPageTitle($slug)
    {
        $page = \App\Models\Page::where('slug', $slug)->first();
        return $page ? $page->title : null;
    }
}
