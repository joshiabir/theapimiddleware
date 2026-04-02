<?php

namespace App\Services;
use Illuminate\Support\Facades\DB;

class dlp_service
{
    public function dlp(string $body, string $domain, string $url)
    {
        $replacements = [];
        $count_m = 0;
        $policies = DB::table('apis')
            ->join('clusters', 'apis.host', '=', 'clusters.host')
            ->join('cluster_apis', 'cluster_apis.api_id', '=', 'apis.api_id')
            ->join('cluster_policies', 'clusters.policy_id', '=', 'cluster_policies.id')
            ->join('dlp_policies', function ($join) {
                $join->on('dlp_policies.cluster_policy_id', '=', 'cluster_policies.id')
                    ->on('dlp_policies.domain', '=', 'clusters.host');
            })
            ->leftJoin('cluster_policy_lists', function ($join) {
                $join->on('cluster_policy_lists.policy_id', '=', 'cluster_policies.id')
                    ->where('cluster_policy_lists.name', '=', 'pii_dlp');
            })
            ->where('apis.url', parse_url($url, PHP_URL_PATH) ?? $url)
            ->where('apis.host', $domain)
            ->whereColumn('cluster_apis.cluster_id', 'clusters.id')
            ->select([
                'dlp_policies.id as dlp_id',
                'dlp_policies.type',
                'dlp_policies.value',
                'dlp_policies.cluster_policy_id',
                'dlp_policies.domain',
                'cluster_policy_lists.value as pii_dlp_value'
            ], )
            ->get();



        if (count($policies) > 0) {

            $action       = $policies[0]->pii_dlp_value;
            $redacted_body = $body; // chains redactions across all policies

            foreach ($policies as $policy) {
                $matches = 0;
                $count   = 0;
                $rep     = [];

                if ($policy->type == 'Keyword') {
                    $redacted_body = preg_replace('/' . preg_quote($policy->value, '/') . '/', '[redacted]', $redacted_body, -1, $count);
                    if ($count > 0) {
                        $rep = [
                            'type'   => 'keyword',
                            'count'  => $count,
                            'values' => $policy->value,
                        ];
                        array_push($replacements, $rep);
                    }
                }
                if ($policy->type == 'Pattern') {
                    $pattern = '~' . $policy->value . '~';

                    if (@preg_match($pattern, null) === false) {
                        error_log("Invalid regex pattern skipped: " . $policy->value);
                    } else {
                        preg_match_all($pattern, $redacted_body, $matches);
                        $redacted_body = preg_replace($pattern, '[redacted]', $redacted_body, -1, $count);

                        if ($count > 0) {
                            $rep = [
                                'type'   => 'pattern',
                                'count'  => $count,
                                'values' => $matches,
                            ];
                            array_push($replacements, $rep);
                        }
                    }
                }
                $count_m += $count;
            }

            // Block only when there are actual matches
            if ($action == 'block' && $count_m > 0) {
                return 'Blocked';
            }

            return (object) [
                'body'         => $action === 'redact' ? $redacted_body : $body,
                'replacements' => $replacements,
                'count'        => $count_m,
            ];
        } else {
            $res = (object) [
                'body' => $body,
                'replacements' => '0',
                'count' => '0',
            ];
            return $res;
        }


    }
}