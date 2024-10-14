<?php

if (!function_exists('statusFormat')) {
    function statusFormat($status)
    {
        if ($status === 'active') {
            return '<div class="capitalize text-xs text-center row-status font-semibold inline-block py-1 px-2 rounded bg-success text-white">'
                . $status .
                '</div>';
        } elseif ($status === 'inactive') {
            return '<div class="capitalize text-xs text-center row-status font-semibold inline-block py-1 px-2 rounded bg-red-600 text-white">'
                . $status .
                '</div>';
        } else {
            return '<div class="capitalize text-xs text-center row-status font-semibold inline-block py-1 px-2 rounded-full bg-gray-600 text-white">'
                . $status .
                '</div>';
        }
    }
}

if (!function_exists('imageFormat')) {
    function imageFormat($src)
    {
        $image_path = $src ? "/storage/{$src}" : "/assets/images/no-image.jpg";
        return '
            <div class="image flex items-center justify-center">
                <img src="' . $image_path . '" alt="Slider Image" class="w-10 h-10 object-cover rounded-lg" />
            </div>';
    }
}

if (!function_exists('generateSlug')) {
    function generateSlug($value)
    {
        return strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $value), '-'));
    }
}
