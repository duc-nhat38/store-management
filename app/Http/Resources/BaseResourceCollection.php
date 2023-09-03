<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class BaseResourceCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return parent::toArray($request);
    }

    /**
     * Customize the pagination information for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  array $paginated
     * @param  array $default
     * @return array
     */
    public function paginationInformation($request, $paginated, $default)
    {
        unset(
            $default['first_page_url'],
            $default['last_page'],
            $default['last_page_url'],
            $default['links'],
            $default['next_page_url'],
            $default['meta']['path'],
            $default['prev_page_url'],
            $default['meta']['links'],
        );

        return $default;
    }

    /**
     * @param mixed $resource
     * @param \Illuminate\Http\Request $request
     * @param boolean $assoc
     * @param integer $depth
     * @return mixed
     */
    public static function getResponse($resource, ?Request $request = null, $assoc = true, $depth = 512)
    {
        return (new static($resource))->toResponse($request ?? app('request'))->getData($assoc, $depth);
    }
}
