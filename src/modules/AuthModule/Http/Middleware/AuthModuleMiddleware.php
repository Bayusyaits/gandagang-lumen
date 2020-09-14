<?php

namespace Modules\AuthModule\Http\Middleware;

use Closure;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Fideloper\Proxy\TrustProxies as Middleware;

class AuthModuleMiddleware extends Middleware
{
        /**
     * The trusted proxies for this application.
     *
     * @var array
     */
    protected $proxies = [
        '192.168.1.1',
        '192.168.1.2',
    ];

    /**
     * The headers that should be used to detect proxies.
     *
     * @var string
     */
    protected $headers = Request::HEADER_X_FORWARDED_ALL;

    public function handle($request, Closure $next, $guard = null)
    {
        $ip      = getClientIp();
        $headers = getRequestHeaders();
        if (isset($headers["Origin"])) {
            $domain = removeHttp($headers["Origin"]);
        } else if (isset($headers["Host"])) {
            $domain = $headers["Host"];
        } else {
            $domain = env('APP_DOMAIN', 'localhost');
        }
        
        if (!$ip || !$domain) {
            // Unauthorized response if token not there
            return response()->json([
                'status'  => 'error',
                'code'    => Response::HTTP_FORBIDDEN,
                'message' => 'validation is fail',
                'data'    => null
            ]);
        }
        $firewall = entity('AuthModule', 'AuthFirewallEntity')::getAuthFirewall([
            [
                'param' => 'authFirewallDomain',
                'value' => $domain
            ],
            [
                'param' => 'authFirewallIpAddress',
                'value' => $ip
            ],
            [
                'param' => 'authFirewallStatus',
                'value' => '1'
            ]
        ])->first();

        if (!$firewall) {
            return response()->json([
                'status'  => 'error',
                'code'    => Response::HTTP_NOT_FOUND,
                'message' => 'data not found',
                'data'    => null
            ]);
        }
        $client = entity('AuthModule', 'AuthClientEntity')::getAuthClient([
            'param' => 'authClientListFirewallId',
            'value' => $firewall['authFirewallId']
        ]);

        if (!$client->first()) {
            return response()->json([
                'status'  => 'error',
                'code'    => Response::HTTP_NOT_FOUND,
                'message' => 'data not found',
                'data'    => null
            ]);
        }
        
        return $next($request);
    }
}
