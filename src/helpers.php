<?php

if (!function_exists('format_date')) {
    /**
     * @param string|int|null $value
     * @param int $timeType
     * @param int $dateType
     * @return string
     */
    function format_date(string|int|null $value, int $timeType = \IntlDateFormatter::MEDIUM, int $dateType = \IntlDateFormatter::MEDIUM): string {
        if (is_null($value)) {
            return '';
        }

        if (!is_int($value)) {
            $value = strtotime($value);
        }

        return (new \IntlDateFormatter(config('locale'), $dateType, $timeType))->format($value);
    }
}

if (!function_exists('format_number')) {
    /**
     * @param int|float $number
     * @param int|null $precision
     * @return string
     */
    function format_number(int|float $number, ?int $precision = null): string
    {
        $formatter = new \NumberFormatter(config()->get('locale'), \NumberFormatter::DECIMAL);
        if (!is_null($precision)) {
            $formatter->setAttribute(\NumberFormatter::FRACTION_DIGITS, abs($precision));
        }

        return (string) $formatter->format($number);
    }
}

if (!function_exists('format_file_size')) {
    /**
     * @param int $size
     * @return string
     */
    function format_file_size(int $size): string {
        $prefix = '';
        if ($size >= 1 << 40) {
            $size = ($size / (1 << 40));
            $prefix = 'T';
        } elseif ($size >= 1 << 30) {
            $size = ($size / (1 << 30));
            $prefix = 'G';
        } elseif ($size >= 1 << 20) {
            $size = ($size / (1 << 20));
            $prefix = 'M';
        } elseif ($size >= 1 << 10) {
            $size = ($size / (1 << 10));
            $prefix = 'K';
        }

        if (!is_int($size)) {
            $size = round($size, 2);
        }

        return format_number($size)." {$prefix}b";
    }
}

if (!function_exists('get_auto_upload_path')) {
    /**
     * @param string $filename
     * @return string
     */
    function get_auto_upload_path(string $filename = ''): string
    {
        return '/storage/auto'.($filename ? '/'.basename($filename) : '');
    }
}

if (!function_exists('get_storage_path')) {
    /**
     * @param string $storage
     * @param string $suffix
     * @return string
     */
    function get_storage_path(string $storage, string $suffix = ''): string
    {
        return str_replace(base_path(), '', config('filesystems.disks.'.$storage.'.root')).$suffix;
    }
}

if (!function_exists('get_text_direction')) {
    /**
     * @param string|null $lang
     * @return string
     */
    function get_text_direction(?string $lang): string
    {
        return in_array($lang, ['ar', 'he', 'fa']) ? 'rtl' : 'ltr';
    }
}

if (!function_exists('parse_xml')) {
    /**
     * @param string $string
     * @return mixed
     */
    function parse_xml(string $string): mixed
    {
        return json_decode(json_encode(simplexml_load_string($string, null, LIBXML_NOCDATA)), true);
    }
}

if (!function_exists('set_language')) {
    /**
     * @param string $language
     * @param string $locale
     * @return void
     */
    function set_language(string $language, string $locale): void
    {
        setlocale(LC_ALL, $locale.'.utf8');

        app()->setLocale($language);

        config()->set('locale', $locale);
        config()->set('text_dir', get_text_direction($language));
    }
}

if (!function_exists('transliterate')) {
    /**
     * @param string $string
     * @return string
     */
    function transliterate(string $string): string
    {
        $return = '';
        if (empty($string)) {
            return $return;
        }

        $string = mb_strtolower(trim($string));

        static $allowed;
        if (is_null($allowed)) {
            $allowed = array_merge([45, 95], range(48, 57), range(97, 122)); // ASCII dashes underscores
        }

        $transliterate = str_replace(' ', '-', (\Transliterator::create('Any-Latin; Latin-ASCII'))->transliterate($string));
        for ($a = 0; $a < strlen($transliterate); $a++) {
            $return .= in_array(ord($transliterate[$a]), $allowed) ? $transliterate[$a] : '-';
        }

        while (str_contains($return, '--')) {
            $return = str_replace('--', '-', $return);
        }

        return trim($return, '-');
    }
}
