<?php

use Illuminate\Support\ViewErrorBag;
use Netto\Services\LanguageService;

if (!function_exists('escape_quotes')) {
    /**
     * @param string $string
     * @return string
     */
    function escape_quotes(string $string): string {
        return str_replace("'", "\'", $string);
    }
}

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

if (!function_exists('format_number')) {
    /**
     * @param int|float $number
     * @param int|null $precision
     * @return string
     */
    function format_number(int|float $number, ?int $precision = null): string
    {
        $formatter = new \NumberFormatter(config('locale'), \NumberFormatter::DECIMAL);
        if (!is_null($precision)) {
            $formatter->setAttribute(\NumberFormatter::FRACTION_DIGITS, abs($precision));
        }

        return (string) $formatter->format($number);
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

if (!function_exists('get_errors_multilang')) {
    /**
     * @param ViewErrorBag $errorBag
     * @param string $attribute
     * @return array
     */
    function get_errors_multilang(ViewErrorBag $errorBag, string $attribute): array
    {
        $return = [];
        foreach (LanguageService::getList() as $lang => $value) {
            $return[$lang] = ($errors = $errorBag->get("{$attribute}|{$lang}")) ? $errors : [];
        }

        return $return;
    }
}

if (!function_exists('get_labels')) {
    /**
     * @param string $class
     * @param bool $empty
     * @param string $columnKey
     * @param string $columnValue
     * @return array
     */
    function get_labels(string $class, bool $empty = false, string $columnKey = 'id', string $columnValue = 'name', ): array
    {
        $return = [];
        if ($empty) {
            $return[''] = '';
        }

        /** @var \Illuminate\Database\Eloquent\Model $class */
        foreach ($class::query()->select($columnValue, $columnKey)->orderBy($columnValue)->get() as $model) {
            $return[$model->{$columnKey}] = $model->{$columnValue};
        }

        return $return;
    }
}

if (!function_exists('get_labels_boolean')) {
    /**
     * @return array
     */
    function get_labels_boolean(): array
    {
        static $return;
        if (is_null($return)) {
            $return = [1 => __('cms::main.general_yes'), 0 => __('cms::main.general_no')];
        }

        return $return;
    }
}

if (!function_exists('get_next_sort')) {
    /**
     * @param \Illuminate\Database\Eloquent\Model $model
     * @param array $filter
     * @return int
     */
    function get_next_sort(\Illuminate\Database\Eloquent\Model $model, array $filter = []): int
    {
        $return = 0;

        $builder = $model->newQuery()->select('sort')->orderBy('sort', 'desc')->limit(1);
        set_builder_filter($builder, $filter);

        if ($builder->count()) {
            $item = $builder->get()->get(0);
            $return = $item->sort;
        }

        $return += 10;
        return $return;
    }
}

if (!function_exists('get_rules_multilang')) {
    /**
     * @param array $rules
     * @return array
     */
    function get_rules_multilang(array $rules): array
    {
        $return = [];
        foreach (LanguageService::getList() as $lang => $value) {
            foreach ($rules as $attribute => $array) {
                $return["{$attribute}|{$lang}"] = $array;
            }
        }

        return $return;
    }
}

if (!function_exists('get_rules_upload')) {
    function get_rules_upload(array $rules): array
    {
        $return = [];
        foreach ($rules as $attribute => $value) {
            $old = $attribute;
            $new = $attribute.'_new';

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

if (!function_exists('get_storage_path')) {
    /**
     * @param string $storage
     * @param string $suffix
     * @return string
     */
    function get_storage_path(string $storage, string $suffix = ''): string
    {
        return str_replace(base_path(), '', config("filesystems.disks.{$storage}.root")).$suffix;
    }
}

if (!function_exists('get_text_direction')) {
    /**
     * @param string|null $language
     * @return string
     */
    function get_text_direction(?string $language): string
    {
        return in_array($language, ['ar', 'he', 'fa']) ? 'rtl' : 'ltr';
    }
}

if (!function_exists('image_resize')) {
    /**
     * @param string $path
     * @param string $storage
     * @param int|null $width
     * @param int|null $height
     * @return string
     */
    function image_resize(string $path, string $storage, ?int $width = null, ?int $height = null): string
    {
        if (is_null($width)) {
            $width = config('cms.album_max_width', 150);
        }

        if (is_null($height)) {
            $height = config('cms.album_max_height', 150);
        }

        $basePath = base_path().DIRECTORY_SEPARATOR;
        $manager = new \Intervention\Image\ImageManager(\Intervention\Image\Drivers\Gd\Driver::class);

        $image = $manager->read($basePath.$path);

        if ($imageSize = getimagesize($basePath.$path)) {
            if (($imageSize[0] <= $width) && ($imageSize[1] <= $height)) {
                return $path;
            }
        }

        $tmp = tempnam('/tmp', 'resize');
        $image->cover($width, $height)->save($tmp, 95);
        $file = new \Illuminate\Http\UploadedFile($tmp, basename($tmp));

        $resized = $file->store('auto', $storage);
        $disk = \Illuminate\Support\Facades\Storage::disk($storage);

        return str_replace($basePath, '', $disk->path('').$resized);
    }
}

if (!function_exists('load_cdn_resources')) {
    /**
     * @param string|array $id
     * @param bool $preconnect
     * @return void
     */
    function load_cdn_resources(string|array $id, bool $preconnect = false): void
    {
        if (is_string($id)) {
            $id = [$id];
        }

        $config = config('cms.cdn.files', []);
        $files = [];
        foreach (array_unique($id) as $item) {
            $files = array_merge($files, $config[$item]);
        }

        if (empty($files)) {
            return;
        }

        $baseUrl = config('cms.cdn.url', '');
        $tags = [];
        if ($preconnect && $baseUrl) {
            $tags[] = "<link rel=\"preconnect\" href=\"{$baseUrl}\">";
        }

        $language = app()->getLocale();
        foreach ($files as $file) {
            if (str_contains($file, ':langId')) {
                $file = str_replace(':langId', $language, $file);
            }

            if (str_starts_with($file, 'js/')) {
                $tags[] = "<script src=\"{$baseUrl}/{$file}\"></script>";
            } elseif (str_starts_with($file, 'css/')) {
                $tags[] = "<link href=\"{$baseUrl}/{$file}\" rel=\"stylesheet\" type=\"text/css\">";
            }
        }

        echo implode(PHP_EOL, $tags);
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

if (!function_exists('set_admin_cookie')) {
    /**
     * @param string $name
     * @param string $value
     * @return void
     */
    function set_admin_cookie(string $name, string $value): void
    {
        \Illuminate\Support\Facades\Cookie::queue(\Illuminate\Support\Facades\Cookie::forever($name, $value, '/'.config('cms.location', 'admin')));
    }
}

if (!function_exists('set_builder_filter')) {
    /**
     * @param \Illuminate\Database\Eloquent\Builder $builder
     * @param array $filter
     * @return void
     */
    function set_builder_filter(\Illuminate\Database\Eloquent\Builder &$builder, array $filter): void
    {
        if (empty($filter)) {
            return;
        }

        foreach ($filter as $key => $value) {
            if (str_contains($key, '.')) {
                $tmp = explode('.', $key);
                $builder->whereHas($tmp[0], function($builder) use ($tmp, $value) {
                    unset($tmp[0]);
                    set_builder_filter($builder, [
                        implode('.', $tmp) => $value,
                    ]);
                });
            } else {
                if (is_null($value['value'])) {
                    $builder->whereNull($key);
                } else {
                    if (is_array($value['value'])) {
                        $builder->whereIn($key, $value['value']);
                    } else {
                        $builder->where($key, $value['operator'], $value['value']);
                    }
                }
            }
        }
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
