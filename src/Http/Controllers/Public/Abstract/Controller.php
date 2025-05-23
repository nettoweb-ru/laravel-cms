<?php

namespace Netto\Http\Controllers\Public\Abstract;

use Carbon\Carbon;
use Illuminate\Http\Response;

use Netto\Http\Controllers\AbstractController as BaseController;
use Netto\Models\Abstract\Model;
use Netto\Models\{Album, Image, Publication};

abstract class Controller extends BaseController
{
    private const HOME = 'home';

    /**
     * @return array
     */
    protected function getChain(): array
    {
        $crumbs = $this->crumbs;
        $crumbs[] = [
            'title' => $this->getTitleLead(),
            'link' => get_language_route(self::HOME),
        ];

        return (count($crumbs) > 1) ? array_reverse($crumbs) : [];
    }

    /**
     * @param Model $model
     * @return array
     */
    protected function getModelData(Model $model): array
    {
        $lastModified = null;

        /** @var Carbon $modified */
        if ($modified = $model->getAttribute('updated_at')) {
            $modified->setTimezone('GMT');
            $lastModified = $modified->format('D, d M Y H:i:s e');
        }


        return [
            'head' => [
                'title' => '',
                'meta_title' => $model->meta_title,
                'meta_keywords' => $model->meta_keywords,
                'meta_description' => $model->meta_description,
                'og_title' => $model->og_title,
                'og_description' => $model->og_description,
                'canonical' => rtrim(config('app.url').'/'.request()->path(), '/'),
            ],
            'content' => [
                'header' => $model->name,
                'body' => $model->content,
                'images' => $this->getModelImages($model->album),
            ],
            'modified' => $lastModified,
        ];
    }

    /**
     * @param Album|null $album
     * @return array
     */
    protected function getModelImages(?Album $album): array
    {
        $return = [];
        if (is_null($album)) {
            return $return;
        }

        foreach ($album->images->all() as $image) {
            /** @var Image $image */
            $return[] = [
                'caption' => $image->caption,
                'thumb' => get_public_uploaded_path($image->getAttribute('thumb')),
                'filename' => get_public_uploaded_path($image->getAttribute('filename')),
            ];
        }

        return $return;
    }

    /**
     * Return public variables for publication.
     *
     * @param string|null $slug
     * If no slug is provided, it will be extracted from the first request segment.
     *
     * @return array
     */
    protected function getPublicationData(?string $slug = null): array
    {
        $languages = get_language_list();

        if (is_null($slug)) {
            $input = trim(request()->getPathInfo(), '/');
            if (empty($input) || array_key_exists($input, $languages)) {
                $slug = self::HOME;
            } else {
                $slug = $input;
                foreach ($languages as $languageSlug => $language) {
                    if (str_starts_with($slug, "$languageSlug/")) {
                        $slug = substr($slug, 3);
                    }
                }
            }
        }

        $publication = Publication::query()->with(['album.images', 'language'])->where('slug', $slug)->where('lang_id', get_current_language_id())->first();

        /** @var Publication $publication */
        if (is_null($publication)) {
            abort(404);
        }

        if ($slug != self::HOME) {
            $this->addTitle($publication->name);
            $this->addCrumb($publication->name, get_language_route($slug));
        }

        return $this->getModelData($publication);
    }

    /**
     * @return string
     */
    protected function getTitleLead(): string
    {
        return config('app.name');
    }

    /**
     * @param array $data
     * @return void
     */
    protected function prepareData(array &$data): void
    {

    }

    /**
     * Show view for standard publication.
     *
     * @param string $viewId
     * @return Response
     */
    protected function publication(string $viewId): Response
    {
        return $this->view($viewId, $this->getPublicationData());
    }

    /**
     * @param string $id
     * @param array $data
     * @return Response
     */
    protected function view(string $id, array $data): Response
    {
        $data = array_merge_recursive($data, $this->getSharedHeadData(), [
            'head' => [
                'locale' => config('locale'),
            ],
        ]);

        $data['head']['title'] = $data['head']['meta_title'] ?: $this->getTitle();

        if (empty($data['head']['og_title']) && $data['head']['meta_title']) {
            $data['head']['og_title'] = $data['head']['meta_title'];
        }

        if (empty($data['head']['og_description']) && $data['head']['meta_description']) {
            $data['head']['og_description'] = $data['head']['meta_description'];
        }

        $this->prepareData($data);

        if (empty($data['modified'])) {
            return response()->view($id, $data);
        }

        return response()->view($id, $data)->header('Last-Modified', $data['modified']);
    }
}
