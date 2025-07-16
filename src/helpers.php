<?php

use Composer\InstalledVersions;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\ViewErrorBag;

use Netto\Services\MultilingualService;
use Netto\Traits\IsMultiLingual;

if (!function_exists('errors_multilingual')) {
    /**
     * Extract errors from error bag for specified multilingual attribute.
     *
     * @param string $attribute
     * @param ViewErrorBag $errorBag
     * @return array
     */
    function errors_multilingual(string $attribute, ViewErrorBag $errorBag): array
    {
        $return = [];

        foreach (get_language_list() as $code => $language) {
            $return[$code] = ($errors = $errorBag->get("{$attribute}|{$code}")) ? $errors : [];

            foreach ($return[$code] as $key => $value) {
                $return[$code][$key] = "[{$language['name']}] {$value}";
            }
        }

        return $return;
    }
}

if (!function_exists('errors_upload')) {
    /**
     * Extract errors from error bag for specified upload attribute.
     *
     * @param string $attribute
     * @param ViewErrorBag $errors
     * @return array
     */
    function errors_upload(string $attribute, ViewErrorBag $errors): array
    {
        return array_merge($errors->get($attribute), $errors->get($attribute.'|new'));
    }
}

if (!function_exists('escape_quotes')) {
    /**
     * @param string $string
     * @return string
     */
    function escape_quotes(string $string): string {
        return str_replace("'", "\'", $string);
    }
}

if (!function_exists('find_language_code')) {
    /**
     * @param int $id
     * @return string|null
     */
    function find_language_code(int $id): ?string
    {
        foreach (get_language_list() as $slug => $language) {
            if ($language['id'] == $id) {
                return $slug;
            }
        }

        return null;
    }
}

if (!function_exists('format_date')) {
    /**
     * Format date using locale settings.
     *
     * @param string|int|null $value
     * @param int $timeType
     * @param int $dateType
     * @return string
     */
    function format_date(string|int|null $value, int $timeType = \IntlDateFormatter::MEDIUM, int $dateType = \IntlDateFormatter::MEDIUM): string {
        if (is_null($value)) {
            return '';
        }

        if (is_string($value)) {
            $value = strtotime($value);
        }

        return (new \IntlDateFormatter(config('locale_full'), $dateType, $timeType))->format($value);
    }
}

if (!function_exists('format_file_size')) {
    /**
     * Format file size using locale settings.
     *
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

if (!function_exists('format_number')) {
    /**
     * Format number using locale settings.
     *
     * @param int|float $number
     * @param int|null $precision
     * @return string
     */
    function format_number(int|float $number, ?int $precision = null): string
    {
        $formatter = new \NumberFormatter(config('locale_full')."@numbers=latn", \NumberFormatter::DECIMAL);
        if (!is_null($precision)) {
            $formatter->setAttribute(\NumberFormatter::FRACTION_DIGITS, abs($precision));
        }

        return (string) $formatter->format($number);
    }
}

if (!function_exists('get_admin_locales')) {
    /**
     * Return the list of supported administrative locales.
     *
     * @return array
     */
    function get_admin_locales(): array
    {
        $return = [
            'en' => 'en_US',
            'fr' => 'fr_FR',
            'es' => 'es_ES',
            'de' => 'de_DE',
            'pt' => 'pt_PT',
            'nl' => 'nl_NL',
            'it' => 'it_IT',
            'ru' => 'ru_RU',
            'tr' => 'tr_TR',
            'zh' => 'zh_CN',
            'ja' => 'ja_JP',
            'ko' => 'ko_KR',
            'hi' => 'hi_IN',
            'ar' => 'ar_AE',
            'fa' => 'fa_IR',
            'he' => 'he_IL',
        ];

        foreach (config('cms.locales', []) as $key => $value) {
            $return[$key] = $value;
        }

        return $return;
    }
}

if (!function_exists('get_admin_role')) {
    /**
     * Return the slug of administrative role from configuration setting.
     *
     * @return string
     */
    function get_admin_role(): string
    {
        return config('cms.admin', 'administrator');
    }
}

if (!function_exists('get_current_language_id')) {
    /**
     * Return ID of current public language. Should be called on public pages only.
     *
     * @return int
     */
    function get_current_language_id(): int
    {
        return MultilingualService::getCurrentId();
    }
}

if (!function_exists('get_default_language_code')) {
    /**
     * Return slug of default public language.
     *
     * @return string
     */
    function get_default_language_code(): string
    {
        return MultilingualService::getDefaultCode();
    }
}

if (!function_exists('get_default_language_id')) {
    /**
     * Return ID of default public language.
     *
     * @return int
     */
    function get_default_language_id(): int
    {
        return MultilingualService::getDefaultId();
    }
}

if (!function_exists('get_labels')) {
    /**
     * Returns associative array [$columnKey => $columnValue] for given model class.
     *
     * @param string $className
     * @param bool $empty
     * @param string $columnKey
     * @param string $columnValue
     * @return array
     */
    function get_labels(string $className, bool $empty = false, string $columnKey = 'id', string $columnValue = 'name'): array
    {
        $return = [];
        if ($empty) {
            $return[''] = '';
        }

        /** @var Model $className */
        $object = new $className();
        $builder = $object::query();

        $isMultilingual = is_multilingual($object) && in_array($columnValue, $object->multiLingual);
        if ($isMultilingual) {
            $builder->with('translated');
            $defaultLanguageCode = get_default_language_code();
        }

        foreach ($builder->get() as $model) {
            if ($isMultilingual) {
                /** @var IsMultiLingual $model */
                $name = $model->getTranslated($columnValue)[$defaultLanguageCode];
            } else {
                $name = $model->{$columnValue};
            }

            $return[$model->{$columnKey}] = $name;
        }

        return $return;
    }
}

if (!function_exists('get_labels_boolean')) {
    /**
     * Return associative array [$value => $name] for boolean values.
     *
     * @return array
     */
    function get_labels_boolean(): array
    {
        static $return;
        if (is_null($return)) {
            $return = [1 => __('main.general_yes'), 0 => __('main.general_no')];
        }

        return $return;
    }
}

if (!function_exists('get_labels_language')) {
    /**
     * Returns associative array [$id => $name] for public language list.
     *
     * @return array
     */
    function get_labels_language(): array
    {
        static $return;
        if (is_null($return)) {
            $return = [];
            foreach (get_language_list() as $code => $language) {
                $return[$language['id']] = $language['name'];
            }
        }

        return $return;
    }
}

if (!function_exists('get_labels_translated')) {
    /**
     * Returns associative array [$columnKey => $columnValue] for given model class with column value used as a translatable message key.
     *
     * @param string $className
     * @param bool $empty
     * @param string $columnKey
     * @param string $columnValue
     * @return array
     */
    function get_labels_translated(string $className, bool $empty = false, string $columnKey = 'id', string $columnValue = 'name'): array
    {
        $return = [];
        foreach (get_labels($className, $empty, $columnKey, $columnValue) as $id => $name) {
            $return[$id] = $name ? __($name) : '';
        }

        return $return;
    }
}

if (!function_exists('get_language_list')) {
    /**
     * Return the list of public languages.
     *
     * @return array
     */
    function get_language_list(): array
    {
        return MultilingualService::getList();
    }
}

if (!function_exists('get_language_route')) {
    /**
     * @param string $route
     * @param array $params
     * @return string
     */
    function get_language_route(string $route, array $params = []): string
    {
        return route(app()->getLocale().'.'.$route, $params);
    }
}

if (!function_exists('get_rules_multilingual')) {
    /**
     * Convert the list of rules to multilingual rules.
     *
     * @param array $rules
     * @return array
     */
    function get_rules_multilingual(array $rules): array
    {
        return MultilingualService::getRules($rules);
    }
}

if (!function_exists('get_rules_upload')) {
    /**
     * Convert the list of rules to file upload rules.
     *
     * @param array $rules
     * @return array
     */
    function get_rules_upload(array $rules): array
    {
        $return = [];
        foreach ($rules as $attribute => $value) {
            $old = $attribute;
            $new = $attribute.'|new';

            $required = false;
            if (in_array('required', $value)) {
                $required = true;
                unset($value[array_search('required', $value)]);
            }

            $return[$new] = array_merge(['sometimes'], $value);
            $return[$old] = $required ? ['required'] : ['nullable'];
            $return[$old][] = 'string';
        }

        return $return;
    }
}

if (!function_exists('get_public_uploaded_path')) {
    /**
     * Return relative public path for automatic upload file.
     *
     * @param string $path
     * @return string
     */
    function get_public_uploaded_path(string $path): string
    {
        return $path
            ? '/storage/auto/'.basename($path)
            : '';
    }
}

if (!function_exists('get_relative_path')) {
    /**
     * Return relative path.
     *
     * @param string $path
     * @return string
     */
    function get_relative_path(string $path): string {
        return str_replace(base_path(), '', $path);
    }
}

if (!function_exists('get_storage_path')) {
    /**
     * Return relative path for configured disk storage.
     *
     * @param string $storage
     * @return string
     */
    function get_storage_path(string $storage): string
    {
        return get_relative_path(config("filesystems.disks.{$storage}.root"));
    }
}

if (!function_exists('get_text_direction')) {
    /**
     * Return text direction for given language code.
     *
     * @param string|null $language
     * @return string
     */
    function get_text_direction(?string $language): string
    {
        return in_array($language, ['ar', 'fa', 'he']) ? 'rtl' : 'ltr';
    }
}

if (!function_exists('get_versions')) {
    /**
     * Return information about used software versions.
     *
     * @return array
     */
    function get_versions(): array
    {
        $packages = [
            'laravel/framework',
            'laravel/sanctum',
            'laravel/tinker',
            'nettoweb/laravel-cms',
            'nettoweb/laravel-cms-currency',
            'nettoweb/laravel-cms-store',
        ];

        $return = ['PHP' => PHP_VERSION];

        foreach ($packages as $package) {
            if (InstalledVersions::isInstalled($package)) {
                $return[$package] = InstalledVersions::getVersion($package);
            }
        }

        return $return;
    }
}

if (!function_exists('is_multilingual')) {
    /**
     * Checks if model supports multiple languages.
     *
     * @param Model $model
     * @return bool
     */
    function is_multilingual(Model $model): bool
    {
        return in_array(IsMultiLingual::class, class_uses_recursive($model));
    }
}

if (!function_exists('old_multilingual')) {
    /**
     * Return old value for multilingual attribute.
     *
     * @param string $attribute
     * @param Model $object
     * @return array
     */
    function old_multilingual(string $attribute, Model $object): array
    {
        /** @var IsMultiLingual $object */
        $old = $object->getTranslated($attribute);

        $return = [];
        foreach (get_language_list() as $lang => $value) {
            $return[$lang] = old("{$attribute}|{$lang}", $old[$lang]);
        }

        return $return;
    }
}

if (!function_exists('parse_xml')) {
    /**
     * Parse XML string into associative array.
     *
     * @param string $string
     * @return mixed
     */
    function parse_xml(string $string): mixed
    {
        return json_decode(json_encode(simplexml_load_string($string, null, LIBXML_NOCDATA)), true);
    }
}

if (!function_exists('set_admin_cookie')) {
    /**
     * Set cookie for administrative pages.
     *
     * @param string $name
     * @param string $value
     * @return void
     */
    function set_admin_cookie(string $name, string $value): void
    {
        Cookie::queue(Cookie::forever($name, $value, '/'.config('cms.location', 'admin')));
    }
}

if (!function_exists('set_language')) {
    /**
     * Set application language and locale.
     *
     * @param string $language
     * @param string $locale
     * @return void
     */
    function set_language(string $language, string $locale): void
    {
        app()->setLocale($language);
        config()->set('text_dir', get_text_direction($language));

        $full = $locale.'.'.config('cms.utf8suffix', 'utf8');

        setlocale(LC_MONETARY, $full);
        setlocale(LC_NUMERIC, $full);
        setlocale(LC_TIME, $full);

        config()->set('locale', $locale);
        config()->set('locale_full', $full);
        config()->set('locale_js', str_replace('_', '-', $locale));
    }
}

if (!function_exists('set_language_default')) {
    /**
     * @return void
     */
    function set_language_default(): void
    {
        $language = get_default_language_code();

        $locales = [];
        foreach (get_language_list() as $key => $value) {
            $locales[$key] = $value['locale'];
        }

        set_language($language, $locales[$language]);
    }
}

if (!function_exists('soft_break_string')) {
    /**
     * Insert soft breaks into string.
     *
     * @param string $string
     * @param int $interval
     * @return string
     */
    function soft_break_string(string $string, int $interval = 10): string {
        $string = html_entity_decode($string);
        $strlen = mb_strlen($string);

        if ($strlen > $interval) {
            $array = [];
            for ($a = 0; $a < $strlen; $a += $interval) {
                $array[] = mb_substr($string, $a, $interval);
            }

            $string = implode('<wbr />', $array);
        }

        return $string;
    }
}

if (!function_exists('spell_number')) {
    /**
     * Spell out number.
     *
     * @param int $number
     * @return string
     */
    function spell_number(int $number): string
    {
        $formatter = new \NumberFormatter(config('locale_full'), \NumberFormatter::SPELLOUT);
        return $formatter->format($number);
    }
}

if (!function_exists('transliterate')) {
    /**
     * Transliterate any string to Latin.
     *
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
