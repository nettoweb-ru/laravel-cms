<?php

namespace Netto\Http\Controllers\Admin\Abstract;

use Illuminate\Http\Response;
use Netto\Http\Controllers\AbstractController as BaseController;

abstract class Controller extends BaseController
{
    /**
     * @return array
     */
    protected function getChain(): array
    {
        return (count($this->crumbs) > 1) ? array_reverse($this->crumbs) : [];
    }

    /**
     * @param string $chunk
     * @return string
     */
    protected function getRouteAdmin(string $chunk): string
    {
        return 'admin.'.$chunk;
    }

    /**
     * @return string
     */
    protected function getTitleLead(): string
    {
        return __('main.general_management');
    }

    /**
     * @param string $id
     * @param array $data
     * @return Response
     */
    protected function view(string $id, array $data = []): Response
    {
        $data = array_merge_recursive($data, $this->getSharedHeadData(), [
            'head' => [
                'title' => $this->getTitle(),
            ],
            'url' => [
                'home' => $this->getRouteAdmin('home'),
                'logout' => $this->getRouteAdmin('logout'),
                'profile' => $this->getRouteAdmin('profile.edit'),
            ],
        ]);

        return response()->view($id, $data);
    }
}
