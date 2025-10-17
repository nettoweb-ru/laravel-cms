<?php

namespace Netto\Http\Controllers;

use App\Http\Controllers\Controller as BaseController;
use Illuminate\Http\Response;

abstract class AbstractController extends BaseController
{
    protected array $crumbs = [];
    private array $title = [];

    /**
     * @param string $title
     * @param string $link
     * @return void
     */
    protected function addCrumb(string $title, string $link = ''): void
    {
        $this->crumbs[] = [
            'title' => $title,
            'link' => $link,
        ];
    }

    /**
     * @param string $title
     * @return void
     */
    protected function addTitle(string $title): void
    {
        $this->title[] = $title;
    }

    /**
     * @return array
     */
    abstract protected function getChain(): array;

    /**
     * @return string
     */
    protected function getTitle(): string
    {
        $title = $this->title;
        $title[] = $this->getTitleLead();

        return implode($this->getTitleSeparator(), $title);
    }

    /**
     * @return string
     */
    abstract protected function getTitleLead(): string;

    /**
     * @return string
     */
    protected function getTitleSeparator(): string
    {
        return config('cms.title_separator', ' | ');
    }

    /**
     * @return array
     */
    protected function getSharedHeadData(): array
    {
        return [
            'head' => [
                'language' => app()->getLocale(),
                'text_dir' => config('text_dir'),
            ],
            'chain' => $this->getChain(),
        ];
    }

    /**
     * @param string $id
     * @param array $data
     * @return Response
     */
    abstract protected function view(string $id, array $data): Response;
}
