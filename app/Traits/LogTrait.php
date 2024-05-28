<?php

namespace App\Traits;

use App\Models\CurlLog;
use App\Models\PersonalAccessToken;
use Exception;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

trait LogTrait
{
    public function curlWithLogger(string $endpoint, string $method, array $request, array $header = [], bool $asForm = false, int $timeout = 60, $skip_logging = false)
    {
        try {
            $personalAccessTokenRes = PersonalAccessToken::isAuth(str(request()->bearerToken())->before('|'));
            $user_type = $user_id = null;
            if ($personalAccessTokenRes) {
                $user_type = $personalAccessTokenRes->tokenable_type;
                $user_id = $personalAccessTokenRes->tokenable->id;
            }

            $new_log = CurlLog::create([
                'user_type' => $user_type,
                'user_id' => $user_id,
                'method' => $method,
                'endpoint' => $endpoint,
                'request_body' => json_encode($request),
            ]);

            if ($asForm) {
                $response = Http::withHeaders($header)->timeout($timeout)->asForm()->$method($endpoint, $request);
            } else {
                $response = Http::withHeaders($header)->timeout($timeout)->$method($endpoint, $request);
            }

            if (! $skip_logging) {
                $new_log->response_body = $response->failed() ? 'Unable to reach API server.' : $response->body();
                $new_log->status_code = $response->status();
                $new_log->save();
            }

            return $response->json();
        } catch (Exception $e) {
            if (! $skip_logging) {
                $new_log->response_body = $e->getMessage() ?? 'timed out';
                $new_log->status_code = $e->getCode();
                $new_log->save();
            }

            return [
                'success' => false,
                'id' => $new_log->id,
                'notification_sent' => true,
                'data' => json_encode([
                    'status' => false,
                    'code' => $e->getCode(),
                    'message' => $e->getMessage() ?? 'timed out',
                ]),
            ];
        }
    }

    private function schedulerWithLogger($channel, $msg, $level = 'info')
    {
        if (is_object($msg) || is_array($msg)) {
            json_encode($msg);
        }

        switch ($level) {
            case 'warn':
                Log::channel($channel)->warning(print_r($msg, 1));
                break;

            default:
                Log::channel($channel)->info(print_r($msg, 1));
                break;
        }
    }

    public function calcExecTime($ary_exec = [], $get_total = false)
    {
        $cnt = count($ary_exec) ?? 0;
        $now = microtime(true);
        if ($cnt == 0) {
            $ary_exec[] = (string) $now;
        } else {
            $prev = 0;
            if ($get_total) {
                return bcsub($now, $ary_exec[0], 6);
            } else {
                foreach ($ary_exec as $v) {
                    $prev = bcadd($prev, $v, 6);
                }
            }
            $now = bcsub($now, $prev, 6);
            $ary_exec[] = $now;
        }

        return $ary_exec;
    }
}
