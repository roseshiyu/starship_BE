<?php

namespace App\Providers;

use Illuminate\Database\Query\Builder;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Response::macro('data', function ($data, ?string $message = null, int $status_code = 200, $success = true) {
            $res = [];
            $res['success'] = $success;
            $res['message'] = $message ?? __('message.granted');
            if (is_object($data) && method_exists($data, 'response')) {
                if (is_a($data->response()->original, 'Illuminate\Support\Collection')) {
                    $collection = $data->response()->getData(true);
                    $res['data'] = $collection['data'];
                    if (isset($collection['meta'])) {
                        if (isset($collection['meta']['links'])) {
                            unset($collection['meta']['links']);
                        }
                        $res['pagination'] = $collection['meta'];
                    }
                    if (isset($collection['links'])) {
                        $res['pagination']['links'] = $collection['links'];
                    }

                    if (isset($collection['summary'])) {
                        $res['summary'] = $collection['summary'];
                        unset($data->additional['summary']);
                    }

                    if (isset($data->additional) && ! empty($data->additional)) {
                        if (isset($data->additional['pagination'])) {
                            $res['pagination'] = array_merge($res['pagination'], $data->additional['pagination']);
                            unset($data->additional['pagination']);
                        }

                        if (count($data->additional)) {
                            $res['additional'] = $data->additional;
                        }
                    }
                } else {
                    $res['data'] = $data;

                    if (isset($data->additional) && isset($data->additional['summary'])) {
                        $res['summary'] = $data->additional['summary'];
                    }
                }
            } elseif (is_a($data, 'Illuminate\Pagination\LengthAwarePaginator')) {
                $collection = $data->toArray();
                $res['data'] = $collection['data'];
                unset($collection['data']);
                unset($collection['links']);
                $links['first'] = $collection['first_page_url'];
                $links['last'] = $collection['last_page_url'];
                $links['next'] = $collection['next_page_url'];
                $links['prev'] = $collection['prev_page_url'];
                unset($collection['first_page_url']);
                unset($collection['last_page_url']);
                unset($collection['next_page_url']);
                unset($collection['prev_page_url']);
                $res['pagination'] = $collection;
                $res['pagination']['links'] = $links;
            } elseif (is_a($data, 'Illuminate\Pagination\Paginator')) {
                $collection = $data->toArray();
                $res['data'] = $collection['data'];
                unset($collection['data']);
                unset($collection['links']);
                $links['first'] = $collection['first_page_url'];
                $links['last'] = null;
                $links['next'] = $collection['next_page_url'];
                $links['prev'] = $collection['prev_page_url'];
                unset($collection['first_page_url']);
                unset($collection['next_page_url']);
                unset($collection['prev_page_url']);
                $res['pagination'] = $collection;
                $res['pagination']['links'] = $links;
            } elseif (is_a($data, 'Illuminate\Pagination\CursorPaginator')) {
                $collection = $data->toArray();
                $res['data'] = $collection['data'];
                unset($collection['data']);
                unset($collection['links']);
                $links['first'] = null;
                $links['last'] = null;
                $links['next'] = $collection['next_page_url'];
                $links['prev'] = $collection['prev_page_url'];
                unset($collection['next_page_url']);
                unset($collection['prev_page_url']);
                $res['pagination'] = $collection;
                $res['pagination']['links'] = $links;
            } else {
                $res['data'] = $data;
            }

            return response()->json($res, $status_code);
        });

        Response::macro('message', function (string $message = 'Granted', $status_code = 200, $success = true) {
            return response()->json([
                'success' => $success,
                'message' => $message,
            ], $status_code);
        });

        Response::macro('error', function ($errors, string $message, int $status_code, $success = false) {
            return response()->json([
                'success' => $success,
                'message' => $message,
                'errors' => $errors,
            ], $status_code);
        });

        Builder::macro('like', function ($columns, $params = []) {
            $this->where(function (Builder $query) use ($columns, $params) {
                if (empty($params)) {
                    $params = request()->all();
                }

                foreach (Arr::wrap($columns) as $column) {
                    $query->when(isset($params[$column]), function ($query) use ($params, $column) {
                        // $query = $query->whereLike($column, $params[$column]);
                        $query = $query->where($column, 'like', '%'.$params[$column].'%');
                    });
                }

                return $query;
            });
        });

        Builder::macro('exact', function ($columns, $params = []) {
            $this->where(function (Builder $query) use ($columns, $params) {
                if (empty($params)) {
                    $params = request()->all();
                }

                foreach (Arr::wrap($columns) as $column) {
                    if (str_contains($column, '.')) {
                        $temp = explode('.', $column);
                        $table_col = $column;
                        $params_col = $temp[1];
                    } else {
                        $table_col = $column;
                        $params_col = $column;
                    }

                    $query->when(isset($params[$params_col]), function ($query) use ($params, $table_col, $params_col) {
                        $query = $query->where($table_col, $params[$params_col]);
                    });
                }

                return $query;
            });
        });

        Builder::macro('between', function ($columns, $params = []) {
            $this->where(function (Builder $query) use ($columns, $params) {
                if (empty($params)) {
                    $params = request()->all();
                }

                foreach (Arr::wrap($columns) as $column) {
                    $query->when(isset($params[$column]), function ($query) use ($params, $column) {
                        if (is_string($params[$column])) {
                            $params[$column] = json_decode($params[$column], true);
                        }

                        if (isset($params[$column]['from']) && isset($params[$column]['to'])) {
                            $query = $query->whereBetween($column, [$params[$column]['from'], $params[$column]['to']]);
                        }
                    });
                }

                return $query;
            });
        });

        Builder::macro('inCsv', function ($columns, $params = []) {
            $this->where(function (Builder $query) use ($columns, $params) {
                if (empty($params)) {
                    $params = request()->all();
                }
                foreach (Arr::wrap($columns) as $column) {
                    $query->when(isset($params[$column]), function ($query) use ($column, $params) {
                        $value = explode(',', strval($params[$column]));
                        $query->whereIn($column, $value);
                    });
                }

                return $query;
            });
        });

        Builder::macro('sort', function ($columns) {
            if (isset(request()->sort)) {
                $sort = explode(',', request()->sort);
                $sort_column = $sort[0];
                $sort_order = $sort[1];

                foreach (Arr::wrap($columns) as $column) {
                    if ($sort_column == $column) {
                        return $this->orderBy($sort_column, $sort_order);
                    }
                }
            }
        });

        Builder::macro('toRawSql', function () {
            return vsprintf(
                str_replace(
                    ['?'],
                    ['\'%s\''],
                    $this->toSql()
                ),
                $this->getBindings()
            );
        });
    }
}
