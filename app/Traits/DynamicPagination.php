<?php

namespace App\Traits;

use Illuminate\Contracts\Database\Eloquent\Builder;

trait DynamicPagination
{
    public function get_paginate()
    {
        if (isset(request()->paginate)) {
            $paginate = explode(',', request()->paginate);
            $limit = $paginate[0];
            $type = 'paginate';
            if (isset($paginate[1])) {
                $type = $paginate[1];
            }
        } else {
            $limit = 15;
            $type = 'paginate';
        }

        $current_page = intval(request()->page ?? 1);

        if (! is_numeric($current_page)) {
            $current_page = 1;
        }

        if (! is_numeric($limit)) {
            $limit = 15;
        }

        $add = ($current_page - 1) * $limit;
        $from = 1 + $add;
        $to = $limit + $add;

        return [
            'limit' => intval($limit),
            'type' => $type,
            'page' => $current_page,
            'from' => $from,
            'to' => $to,
        ];
    }

    public function pagination(Builder $query, $setting = [])
    {
        $temp = $this->get_paginate();
        $limit = $setting['limit'] ?? $temp['limit'];
        $type = $setting['type'] ?? $temp['type'];

        $url = request()->getRequestUri();

        $filtered_uri = preg_replace('~(\?|&)page=[^&]*~', '', $url);
        $filtered_uri = preg_replace('~(\?|&)cursor=[^&]*~', '', $filtered_uri);

        switch ($type) {
            case 'simple':
                return $query->simplePaginate($limit)->withPath(url($filtered_uri));
                break;
            case 'cursor':
                return $query->cursorPaginate($limit)->withPath(url($filtered_uri));
                break;
            default:
                if (is_a($query, 'Jenssegers\Mongodb\Eloquent\Builder')) {
                    return $query->paginate($limit)->withPath(url($filtered_uri));
                } else {
                    // remove all columns replace by id
                    return $query->fastPaginate($limit)->withPath(url($filtered_uri));
                }
                break;
        }
    }

    public function custom_pagination(int $total, array $paginate, $data = [], $additional = null)
    {
        $last_page = ceil($total / $paginate['limit']);
        $from = null;
        $to = null;
        if ($total > 0 && count($data) > 0) {
            $from = $paginate['from'] ?? null;
            $to = $paginate['to'] ?? null;
        }

        if ($to > $total) {
            $to = $total;
        }

        $output = [
            'success' => true,
            'message' => __('message.granted'),
            'data' => $data,
            'pagination' => [
                'current_page' => $paginate['page'],
                'from' => $from,
                'last_page' => intval($last_page),
                'per_page' => $paginate['limit'],
                'to' => $to,
                'total' => $total,
            ],
        ];

        if ($additional) {
            $output['additional'] = $additional;
        }

        return $output;
    }

    public function split_pagination($data)
    {
        $collection = $data->response()->getData(true);
        $res['data'] = $collection['data'];
        if (isset($collection['meta'])) {
            if (isset($collection['meta']['links'])) {
                unset($collection['meta']['links']);
            }
            if (isset($collection['meta']['path'])) {
                unset($collection['meta']['path']);
            }
            $res['pagination'] = $collection['meta'];
        }

        if (isset($collection['pagination']) && ! empty($collection['pagination'])) {
            if (isset($collection['pagination'])) {
                $res['pagination'] = array_merge($res['pagination'], $collection['pagination']);
                unset($collection['pagination']);
            }
        }

        return $res;
    }

    /**
     * Handle dynamic static method calls into the class.
     *
     * @param  string  $method
     * @param  array  $parameters
     * @return mixed
     */
    public static function __callStatic($method, $parameters)
    {
        return (new static)->$method(...$parameters);
    }
}
