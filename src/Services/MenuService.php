<?php

namespace Netto\Services;

use Illuminate\Support\Facades\Auth;
use Netto\Models\Menu;
use Netto\Models\MenuItem;

abstract class MenuService
{
    /**
     * @param string $code
     * @param string $language
     * @return array|null
     */
    public static function getByCode(string $code, string $language): ?array
    {
        static $structure;

        if (is_null($structure)) {
            $structure = self::getStructure();
        }

        foreach ($structure as $item) {
            if (($item['slug'] == $code) && ($item['language'] == $language)) {
                return $item;
            }
        }

        return null;
    }

    /**
     * @param int|null $id
     * @return array
     */
    public static function getDropdownOptions(?int $id): array
    {
        $skipId = [];
        if (!is_null($id)) {
            foreach (self::getStructure() as $menu) {
                if (empty($menu->menu_item_id)) {
                    $skipId = array_merge($skipId, self::getChildrenId($id, $menu));
                }
            }
        }

        $list = [];
        foreach (MenuItem::with('menu')->whereNotIn('menu_id', $skipId)->orderBy('sort')->get() as $item) {
            /** @var MenuItem $item */
            if (!array_key_exists($item->menu_id, $list)) {
                $list[$item->menu_id] = [
                    'name' => $item->menu->name,
                    'options' => [],
                ];
            }

            $list[$item->menu_id]['options'][$item->id] = $item->name;
        }

        $return = ['' => ''];
        foreach ($list as $value) {
            $return[$value['name']] = $value['options'];
        }

        ksort($return);
        return $return;
    }

    /**
     * @param string $code
     * @param string|null $lang
     * @return array
     */
    public static function getPublic(string $code, ?string $lang = null): array
    {
        if (is_null($lang)) {
            $lang = app()->getLocale();
        }

        if ($menu = self::getByCode($code, $lang)) {
            return self::pullKidsPublic($menu['kids'], ($user = Auth::user()) ? $user->roles->pluck('id')->all() : []);
        }

        return [];
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
     * @return array
     */
    private static function getStructure(): array
    {
        $items = [];
        foreach (MenuItem::orderBy('sort')->with('roles')->get() as $item) {
            /** @var MenuItem $item */
            $items[$item->id] = [
                'id' => $item->id,
                'menu_id' => $item->menu_id,
                'name' => $item->name,
                'slug' => $item->slug,
                'link' => $item->link,
                'is_active' => $item->is_active,
                'is_blank' => $item->is_blank,
                'dropdown' => null,
                'roles_id' => $item->roles->pluck('id')->all(),
                'highlight' => $item->highlight,
            ];
        }

        $return = [];
        foreach (Menu::with('language')->get() as $menu) {
            /** @var Menu $menu */
            $return[$menu->id] = [
                'id' => $menu->id,
                'menu_item_id' => $menu->menu_item_id,
                'name' => $menu->name,
                'slug' => $menu->slug,
                'language' => $menu->language->slug,
                'kids' => [],
            ];

            if ($menu->menu_item_id) {
                $items[$menu->menu_item_id]['dropdown'] = $menu->id;
            }
        }

        foreach ($return as $menu) {
            foreach ($items as $itemId => $item) {
                if ($item['menu_id'] == $menu['id']) {
                    $return[$menu['id']]['kids'][$itemId] = $item;
                }
            }
        }

        foreach ($return as $menu) {
            foreach ($menu['kids'] as $item) {
                if (!is_null($item['dropdown'])) {
                    $return[$menu['id']]['kids'][$item['id']]['dropdown'] = self::pullKids($item['dropdown'], $return);
                }
            }
        }

        return $return;
    }

    /**
     * @param int $menuId
     * @param array $menu
     * @return array
     */
    private static function pullKids(int $menuId, array $menu): array
    {
        $return = $menu[$menuId];

        foreach ($return['kids'] as $itemId => $item) {
            if (!empty($item['dropdown'])) {
                $return['kids'][$itemId]['dropdown'] = self::pullKids($item['dropdown'], $menu);
            }
        }

        return $return;
    }

    /**
     * @param array $kids
     * @param array $rolesId
     * @return array
     */
    private static function pullKidsPublic(array $kids, array $rolesId): array
    {
        $return = [];
        foreach ($kids as $kid) {
            if (!$kid['is_active']) {
                continue;
            }

            if ($kid['roles_id'] && !array_intersect($kid['roles_id'], $rolesId)) {
                continue;
            }

            if ($kid['dropdown']) {
                $kid['dropdown']['kids'] = self::pullKidsPublic($kid['dropdown']['kids'], $rolesId);
            }

            $kid['target'] = $kid['is_blank'] ? '_blank' : '_self';
            $kid['is_current'] = request()->routeIs($kid['link']) || ($kid['highlight'] && request()->routeIs(...$kid['highlight']));
            $kid['link'] = $kid['link'] ? route($kid['link'], [], false) : '';

            $return[] = $kid;
        }

        return $return;
    }
}
