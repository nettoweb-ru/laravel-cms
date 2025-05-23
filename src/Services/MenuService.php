<?php

namespace Netto\Services;

use Netto\Models\{Menu, MenuItem};

abstract class MenuService
{
    private static array $structure = [
        1 => null,
        0 => null,
    ];

    /**
     * Return the list of menu items, that can be used as parents for given menu ID.
     *
     * @param int|null $id
     * @return array
     */
    public static function getDropdownOptions(?int $id): array
    {
        $skipId = [];
        if (!is_null($id)) {
            foreach (self::getStructure() as $menu) {
                if (is_null($menu['menu_item_id'])) {
                    $skipId = array_merge($skipId, self::getChildrenId($id, $menu));
                }
            }
        }

        $builder = MenuItem::query()->orderBy('sort')->with('menu');
        if ($skipId) {
            $builder->whereNotIn('menu_id', $skipId);
        }

        $list = [];
        foreach ($builder->get() as $item) {
            /** @var MenuItem $item */
            if (!array_key_exists($item->getAttribute('menu_id'), $list)) {
                $list[$item->getAttribute('menu_id')] = [
                    'name' => $item->menu->getAttribute('name'),
                    'options' => [],
                ];
            }

            $list[$item->getAttribute('menu_id')]['options'][$item->getAttribute('id')] = $item->getAttribute('name');
        }

        $return = ['' => ''];
        foreach ($list as $value) {
            $return[$value['name']] = $value['options'];
        }

        ksort($return);
        return $return;
    }

    /**
     * Return public menu by given slug.
     *
     * @param string $code
     * @param string|null $language
     * @return array|null
     */
    public static function getPublic(string $code, ?string $language = null): ?array
    {
        if (is_null($language)) {
            $language = app()->getLocale();
        }

        foreach (self::getStructure(true) as $menu) {
            if (($menu['slug'] == $code) && ($menu['language'] == $language)) {
                return $menu;
            }
        }

        return null;
    }

    /**
     * @param int $id
     * @param array $menu
     * @return array
     */
    private static function getChildrenId(int $id, array $menu): array
    {
        $return = [];
        if ($menu['id'] == $id) {
            $return[] = $menu['id'];
            foreach ($menu['kids'] as $kid) {
                if (!is_null($kid['dropdown'])) {
                    $return = array_merge($return, self::getChildrenId($kid['dropdown']['id'], $kid['dropdown']));
                }
            }
        }

        foreach ($menu['kids'] as $kid) {
            if (!is_null($kid['dropdown'])) {
                $return = array_merge($return, self::getChildrenId($id, $kid['dropdown']));
            }
        }

        return $return;
    }

    /**
     * @param bool $public
     * @return array
     */
    private static function getStructure(bool $public = false): array
    {
        $key = (int) $public;

        if (is_null(self::$structure[$key])) {
            $builder = MenuItem::query()->orderBy('sort');
            if ($public) {
                $builder->where('is_active', '1')->with('permissions');
            }

            $items = [];
            foreach ($builder->get() as $item) {
                /** @var MenuItem $item */
                if ($public && !$item->isAccessible()) {
                    continue;
                }

                $array = $item->toArray();
                $array['dropdown'] = null;

                if ($public) {
                    $array['is_current'] = request()->routeIs(...array_filter(array_merge([$array['link']], (array) $array['highlight'])));
                    $array['target'] = $array['is_blank'] ? '_blank' : '_self';
                    if ($array['link']) {
                        $array['link'] = route($array['link']);
                    }

                    unset($array['roles']);
                }

                $items[$item->getAttribute('id')] = $array;
            }

            $menus = [];
            foreach (Menu::all() as $menu) {
                /** @var Menu $menu */
                $array = $menu->toArray();
                $array['language'] = find_language_code($array['lang_id']);
                $array['kids'] = [];

                $menus[$array['id']] = $array;

                if ($array['menu_item_id'] && isset($items[$array['menu_item_id']])) {
                    $items[$array['menu_item_id']]['dropdown'] = $array['id'];
                }
            }

            foreach ($menus as $menu) {
                foreach ($items as $id => $item) {
                    if ($item['menu_id'] == $menu['id']) {
                        $menus[$menu['id']]['kids'][$id] = $item;
                    }
                }
            }

            foreach ($menus as $menu) {
                foreach ($menu['kids'] as $item) {
                    if (!is_null($item['dropdown'])) {
                        $menus[$menu['id']]['kids'][$item['id']]['dropdown'] = self::pull($item['dropdown'], $menus);
                    }
                }
            }

            if ($public) {
                $return = [];
                foreach ($menus as $id => $menu) {
                    if (is_null($menu['menu_item_id'])) {
                        $return[$id] = $menu;
                    }
                }
                $menus = $return;
            }

            self::$structure[$key] = $menus;
        }

        return self::$structure[$key];
    }

    /**
     * @param int $menuId
     * @param array $menu
     * @return array
     */
    private static function pull(int $menuId, array $menu): array
    {
        $return = $menu[$menuId];

        foreach ($return['kids'] as $itemId => $item) {
            if (!empty($item['dropdown'])) {
                $return['kids'][$itemId]['dropdown'] = self::pull($item['dropdown'], $menu);
            }
        }

        return $return;
    }
}
