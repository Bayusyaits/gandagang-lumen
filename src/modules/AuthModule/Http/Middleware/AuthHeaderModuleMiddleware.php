<?php

namespace Modules\AuthModule\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

use Exception;

use Firebase\JWT\JWT;
use Firebase\JWT\ExpiredException;

class AuthHeaderModuleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * sigtature & appkey get from app in frontend
     * timestamp format YYYY-MM-DDTHH: mm: ss
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        $key       = $request->header('X-Auth-Key');
        $token     = $request->header('X-Auth-Token');
        $signature = $request->header('X-Auth-Signature');
        $timestamp = $request->header('X-Auth-TimeStamp');
        
        if (!$token || !$signature || !$key || !$timestamp) {
            // Unauthorized response if token not there
            return response()->json([
                'status'  => 'error',
                'code'    => Response::HTTP_METHOD_NOT_ALLOWED,
                'message' => 'token not provided..',
                'data'    => null
            ]);
        }
        /**
         * Handle an incoming client
         * @param Signature $signature
         * @param Key $key
         */
        $client = entity('AuthModule', 'AuthClientEntity')
        ::getAuthClient(
            [
                [
                    'param' => 'authClientSignature',
                    'value' => $signature
                ],
                [
                    'param' => 'authClientKey',
                    'value' => $key
                ]
            ]
        );
        if (!$client->first()) {
            return response()->json([
                'status'  => 'error',
                'code'    => Response::HTTP_FORBIDDEN,
                'message' => 'forbidden access client',
                'data'    => null
            ]);
        }

        try {
            $credentials = JWT::decode($token, env('JWT_SECRET'), ['HS256']);
        } catch (ExpiredException $e) {
            return response()->json([
                'status'  => 'error',
                'code'    => Response::HTTP_UNAUTHORIZED,
                'message' => 'provided token is expired.',
                'data'    => null
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status'  => 'error',
                'code'    => Response::HTTP_UNAUTHORIZED,
                'message' => 'an error while decoding token.',
                'data'    => null
            ]);
        }
        $user = entity('AuthModule', 'UserEntity')::getUser(
            [
                'param' => 'userId',
                'value' => cryptoJsAesDecrypt(env('APP_KEY'), $credentials->sub)
            ],
        );
        
        if (!$user->first()) {
            return response()->json([
                'status'  => 'error',
                'code'    => Response::HTTP_NOT_FOUND,
                'message' => 'user not found',
                'data'    => null
            ]);
        }

        $request->auth = $user;
        return $next($request);
    }
}
