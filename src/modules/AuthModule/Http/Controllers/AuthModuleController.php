<?php

namespace Modules\AuthModule\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

use Validator;
use Firebase\JWT\JWT;
use Firebase\JWT\ExpiredException;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use App\Annotations\AuthEntityAnnotation;

class AuthModuleController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
    }

    public function postRegistrationFirewall(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email'      => 'required|email',
            'domain'     => 'required',
        ]);

        if ($validator->fails()) {
            //return error message
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
                'value' => $request->input('domain')
            ],
            [
                'param' => 'authFirewallStatus',
                'value' => '1'
            ]
        ])->first();
        if ($firewall) {
            return response()->json([
                'status'  => 'error',
                'code'    => Response::HTTP_CREATED,
                'message' => 'data is exist',
                'data'    => null
            ]);
        }
        $ip     = getClientIp();
        entity('AuthModule', 'AuthFirewallEntity')::addAuthFirewall(
            [
                'firewallIpAddress' => $ip,
                'firewallDomain'    => $request->input('domain')
            ]
        );

        //return successful response
        return response()->json([
            'status'  => 'success',
            'code'    => Response::HTTP_OK,
            'message' => 'data has added',
            'data'    => true
        ]);
    }
    
    public function postRegistrationClient(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'firewallId' => 'required',
            'domain'     => 'required',
        ]);

        if ($validator->fails()) {
            //return error message
            return response()->json([
                'status'  => 'error',
                'code'    => Response::HTTP_FORBIDDEN,
                'message' => 'validation is fail',
                'data'    => null
            ]);
        }
        $firewall = entity('AuthModule', 'AuthFirewallEntity')::getAuthFirewall([
            [
                'param' => 'authFirewallId',
                'value' => $request->input('firewallId')
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
        $ip     = getClientIp();
        $client = entity('AuthModule', 'AuthClientEntity')
        ::getAuthClient([
            'param' => 'authClientListFirewallId',
            'value' => $firewall['authFirewallId']
        ])->first();

        if ($client) {
            return response()->json([
                'status'  => 'error',
                'code'    => Response::HTTP_CREATED,
                'message' => 'data has been created',
                'data'    => null
            ]);
        }
        
        if ($firewall['authFirewallIpAddress'] === $ip) {
            entity('AuthModule', 'AuthClientEntity'):: addAuthClient($request->all());
        } else {
            return response()->json([
                'status'  => 'error',
                'code'    => Response::HTTP_NOT_FOUND,
                'message' => 'data not found',
                'data'    => nul
            ]);
        }
        //return successful response
        return response()->json([
            'status'  => 'success',
            'code'    => Response::HTTP_OK,
            'message' => 'data has added',
            'data'    => true
        ]);
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    
    public function postRegistration(Request $request)
    {
        $validator = Validator::make($request->all(), [
           'username'     => 'required|string|unique:user',
           'email'        => 'required|email|string|unique:user,userEmail',
           'firstName'    => 'required',
           'lastName'     => 'required',
           'salutation'   => 'required',
           'password'     => 'required',
           'agreePrivacy' => 'required',
           'mobilePrefix' => 'required',
           'phoneNumber'  => 'required',
        ]);
        if ($validator->fails()) {
            //return error message
            return response()->json(['message' => 'User Registration Failed!'], 409);
        }
                          $body             = [];
                    $body['userName']       = $request->input('username');
                    $body['userEmail']      = $request->input('email');
                    $body['userFirstName']  = $request->input('firstName');
                    $body['userLastName']   = $request->input('lastName');
                    $body['userSalutation'] = $request->input('salutation');
                    $body['userIpAddress']  = getClientIp();
                    $body['userPlatform']   = $request->server('HTTP_USER_AGENT');
                    $body['userAgent']      = $request->header('User-Agent');
                    $body['agreePrivacy']   = $request->input('agreePrivacy');
                    $body['agreeSubscribe'] = $request->input('agreeSubscribe');
                    $body['mobilePrefix']   = $request->input('mobilePrefix');
                    $body['phoneNumber']    = $request->input('phoneNumber');
                          $plainPassword    = $request->input('password');
                    $body['userPassword']   = app('hash')->make($plainPassword);
                          $user             = entity('AuthModule', 'UserEntity')::addUser($body);
        if ($user) {
            //return successful response
            return $this->postLogin($request);
        } else {
            return response()->json([
                'status'  => 'error',
                'code'    => Response::HTTP_INTERNAL_SERVER_ERROR,
                'message' => 'internal server error',
                'data'    => null
            ]);
        }
    }

    public function postCheck(Request $request, $login = false)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required|string',
            'reCaptcha' => 'string',
        ]);

        if ($validator->fails()) {
            //return error message
            return response()->json([
                'status'  => 'error',
                'code'    => Response::HTTP_FORBIDDEN,
                'message' => 'validation is fail',
                'data'    => null
            ]);
        }

        $body['param'] = 'userName';
        $body['value'] = $request->input('username');
        // check if e-mail address is well-formed
        if (filter_var($body['value'], FILTER_VALIDATE_EMAIL)) {
            $body['param'] = 'userEmail';
        }

        $user = entity('AuthModule', 'UserEntity')
            ::getUser(
                [
                [
                    'param' => $body['param'],
                    'value' => $body['value']
                ],
                [
                    'param' => 'userType',
                    'value' => '1'
                ]
                ]
            )->first();

        if ($user) {
            if ($login) {
                return $user;
            }
            return response()->json([
                'status'  => 'success',
                'code'    => Response::HTTP_OK,
                'message' => 'user is exist',
                'data'    => true
            ]);
        }
        if ($login) {
            return false;
        }
        return response()->json([
            'status'  => 'error',
            'code'    => Response::HTTP_NOT_FOUND,
            'message' => 'user not found',
            'data'    => $body
        ]);
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    
    public function postLogin(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username'    => 'required|string',
            'password'    => 'required|string',
            'rememberMe'  => '',
        ]);

        if ($validator->fails()) {
            //return error message
            return response()->json([
                'status'  => 'error',
                'code'    => Response::HTTP_FORBIDDEN,
                'message' => 'validation is fail',
                'data'    => null
            ]);
        }
        
        // Verify the password and generate the token
        $password = $request->input('password');
        $rememberMe = $request->input('rememberMe');
        $user = $this->postCheck($request, $login = true);

        if (!$user) {
            return response()->json([
                'status'  => 'error',
                'code'    => Response::HTTP_NOT_FOUND,
                'message' => 'user not found',
                'data'    => null
            ]);
        }

        if (Hash::check($password, $user->userPassword)) {
            $user->update(['userToken' => generateToken(64)]);
            return $this->postHistoryLogin($user, [
                'rememberMe' => isset($rememberMe) ? $rememberMe : null,
                'userAgent'  => $request->server('HTTP_USER_AGENT')
            ]);
        }
        
        // Bad Request response
        return response()->json([
            'status'  => 'error',
            'code'    => Response::HTTP_FORBIDDEN,
            'message' => 'username or password is not valid',
            'data'    => null
        ]);
    }
    /**
     * Post Verification Phone Number & Email Code
     * Default is code
     * @return \Illuminate\Http\JsonResponse
     */

    public function postVerification(Request $request, $uri = '')
    {
        switch ($uri) {
            case 'phone-number':
                $validator = Validator::make($request->all(), [
                'mobilePrefix' => 'required',
                'phoneNumber'  => 'required',
                'code'         => 'required',
                ]);
                if ($validator->fails()) {
                    //return error message
                    return response()->json([
                    'status'  => 'error',
                    'code'    => Response::HTTP_FORBIDDEN,
                    'message' => 'validation is fail',
                    'data'    => null
                    ]);
                }
                $body = $request->only(['mobilePrefix', 'phoneNumber', 'code']);
                return $this->postVerificationPhoneNumber($body);
            break;
            case 'email':
                $validator = Validator::make($request->all(), [
                    'email'        => 'required|email',
                    'code'         => 'required',
                ]);
                if ($validator->fails()) {
                    //return error message
                    return response()->json([
                        'status'  => 'error',
                        'code'    => Response::HTTP_FORBIDDEN,
                        'message' => 'validation is fail',
                        'data'    => null
                    ]);
                }
                $body = $request->only(['email', 'code']);
                return $this->postVerificationEmail($body);
            default:
                return response()->json([
                'status'  => 'error',
                'code'    => Response::HTTP_NOT_FOUND,
                'message' => 'not found',
                'data'    => null
                ]);
        }
    }

    public function postVerificationPhoneNumber($body)
    {
        $user = entity('AuthModule', 'UserEntity')
                ::getUserVerifyCode(
                    [
                    [
                        'param' => 'userPhoneNumber',
                        'value' => $body['phoneNumber']
                    ],
                    [
                        'param' => 'userVerifyPhoneNumberCode',
                        'value' => $body['code']
                    ]
                    ],
                    'userDeletedDate,userVerifyPhoneNumberDate'
                )->first();
        if ($user) {
            if (!empty($user->userVerifyEmailDate)
            && $user->userVerifyData === '0') {
                $updateCode = $user->update([
                    'userStatus'   => '2',
                ]);
            }
            $now = \Carbon\Carbon::now();
               setlocale(LC_TIME, 'IND');
            $updateCode = $user->update([
                'userVerifyPhoneNumberCode' => generateRandomCode(6),
                'userVerifyPhoneNumberDate' => $now
            ]);
            if (!$updateCode) {
                return response()->json([
                    'status'  => 'error',
                    'code'    => Response::HTTP_INTERNAL_SERVER_ERROR,
                    'message' => 'server error',
                    'data'    => null
                ]);
            }
        } else {
            return response()->json([
                'status'  => 'error',
                'code'    => Response::HTTP_NOT_FOUND,
                'message' => 'user not found',
                'data'    => null
            ]);
        }
        return response()->json([
            'status'  => 'success',
            'code'    => Response::HTTP_OK,
            'message' => 'verified phone number',
            'data'    => true
        ]);
    }

    public function postVerificationEmail($body)
    {
        $user = entity('AuthModule', 'UserEntity')
            ::getUserVerifyCode(
                [
                [
                    'param' => 'userEmail',
                    'value' => $body['email']
                ],
                [
                    'param' => 'userVerifyEmailCode',
                    'value' => $body['code']
                ]
                ],
                'userDeletedDate,userVerifyEmailDate'
            )->first();
        if ($user) {
            $now = \Carbon\Carbon::now();
               setlocale(LC_TIME, 'IND');
            if (!empty($user->userVerifyPhoneNumberDate)
            && $user->userVerifyData === '0') {
                $updateCode = $user->update([
                    'userStatus'   => '2',
                ]);
            }
            $updateCode = $user->update([
                'userVerifyEmailCode' => generateRandomCode(6),
                'userVerifyEmailDate' => $now
            ]);
            if (!$updateCode) {
                return response()->json([
                    'status'  => 'error',
                    'code'    => Response::HTTP_INTERNAL_SERVER_ERROR,
                    'message' => 'server error',
                    'data'    => null
                ]);
            }
        } else {
            return response()->json([
                'status'  => 'error',
                'code'    => Response::HTTP_NOT_FOUND,
                'message' => 'user not found',
                'data'    => null
            ]);
        }
        return response()->json([
            'status'  => 'success',
            'code'    => Response::HTTP_OK,
            'message' => 'verified email',
            'data'    => true
        ]);
    }

    /**
     * Get Verification email
     * @var $expired 1 week after created date token
     * @AuthEntityAnnotation(authTimeStamp=$now)
     */

    public function getVerificationEmail(Request $request, $token = '')
    {
        if (!$token || empty($token)) {
            return response()->json([
                'status'  => 'error',
                'code'    => Response::HTTP_NOT_FOUND,
                'message' => 'user not found',
                'data'    => null
            ]);
        }
        $time = timeId();
        $encrypt = Crypt::encrypt("bayusyaits@gmail.com||V8ZCYS||$time");
        try {
            $decrypt = Crypt::decrypt($token);
        } catch (DecryptException $e) {
            return response()->json([
                'status'  => 'error',
                'code'    => Response::HTTP_BAD_REQUEST,
                'message' => 'invalid token',
                'data'    => null
            ]);
        }
        $explode = explode('||', $decrypt);
        $body    = [];
        
        if (isset($explode[0])) {
            $body['email']    = $explode[0];
        } else {
            return response()->json([
                'status'  => 'error',
                'code'    => Response::HTTP_BAD_REQUEST,
                'message' => 'email address is invalid',
                'data'    => null
            ]);
        }

        if (isset($explode[1])) {
            $body['code']    = $explode[1];
        } else {
            return response()->json([
                'status'  => 'error',
                'code'    => Response::HTTP_BAD_REQUEST,
                'message' => 'code is invalid',
                'data'    => null
            ]);
        }

        if (isset($explode[2]) && !empty($explode[2])) {
            $body['expired']    = $explode[2]+ 10080*60;
        } else {
            return response()->json([
                'status'  => 'error',
                'code'    => Response::HTTP_BAD_REQUEST,
                'message' => 'format date is invalid',
                'data'    => null
            ]);
        }

        if ($body['expired'] < $time) {
            return response()->json([
                'status'  => 'error',
                'code'    => Response::HTTP_BAD_REQUEST,
                'message' => 'token is expired',
                'data'    => null
            ]);
        }
        
        return $this->postVerificationEmail($body);
    }


    public function refreshVerificationPhoneNumber($body)
    {
        $user = entity('AuthModule', 'UserEntity')
                ::getUserVerifyCode(
                    [
                    [
                        'param' => 'userPhoneNumber',
                        'value' => $body['phoneNumber']
                    ],
                    [
                        'param' => 'userMobilePrefix',
                        'value' => $body['mobilePrefix']
                    ]
                    ],
                    'userDeletedDate'
                );
        if ($user->first()) {
            $updateCode = $user->update([
                'userVerifyPhoneNumberCode' => generateRandomCode(6)
            ]);
            if (!$updateCode) {
                return response()->json([
                    'status'  => 'error',
                    'code'    => Response::HTTP_INTERNAL_SERVER_ERROR,
                    'message' => 'server error',
                    'data'    => null
                ]);
            }
        } else {
            return response()->json([
                'status'  => 'error',
                'code'    => Response::HTTP_NOT_FOUND,
                'message' => 'user not found',
                'data'    => null
            ]);
        }
        return response()->json([
            'status'  => 'success',
            'code'    => Response::HTTP_OK,
            'message' => 'refresh code is success',
            'data'    => true
        ]);
    }

    public function refreshVerificationEmail($body)
    {
        $user = entity('AuthModule', 'UserEntity')
            ::getUserVerifyCode(
                [
                'param' => 'userEmail',
                'value' => $body['email']
                ],
                'userDeletedDate'
            );
        if ($user->first()) {
            $updateCode = $user->update([
                'userVerifyEmailCode' => generateRandomCode(6)
            ]);
            if (!$updateCode) {
                return response()->json([
                    'status'  => 'error',
                    'code'    => Response::HTTP_INTERNAL_SERVER_ERROR,
                    'message' => 'server error',
                    'data'    => null
                ]);
            }
        } else {
            return response()->json([
                'status'  => 'error',
                'code'    => Response::HTTP_NOT_FOUND,
                'message' => 'user not found',
                'data'    => null
            ]);
        }
        return response()->json([
            'status'  => 'success',
            'code'    => Response::HTTP_OK,
            'message' => 'refresh code is success',
            'data'    => true
        ]);
    }

    public function postRefreshVerificationCode(Request $request, $uri)
    {
        switch ($uri) {
            case 'phone-number':
                $validator = Validator::make($request->all(), [
                'mobilePrefix' => 'required',
                'phoneNumber'  => 'required'
                ]);
                if ($validator->fails()) {
                    //return error message
                    return response()->json([
                    'status'  => 'error',
                    'code'    => Response::HTTP_FORBIDDEN,
                    'message' => 'validation is fail',
                    'data'    => null
                    ]);
                }
                $body = $request->only(['mobilePrefix', 'phoneNumber']);
                return $this->refreshVerificationPhoneNumber($body);
            break;
            case 'email':
                $validator = Validator::make($request->all(), [
                    'email'        => 'required|email'
                ]);
                if ($validator->fails()) {
                    //return error message
                    return response()->json([
                        'status'  => 'error',
                        'code'    => Response::HTTP_FORBIDDEN,
                        'message' => 'validation is fail',
                        'data'    => null
                    ]);
                }
                $body = $request->only(['email']);
                return $this->refreshVerificationEmail($body);
            default:
                return response()->json([
                'status'  => 'error',
                'code'    => Response::HTTP_NOT_FOUND,
                'message' => 'not found',
                'data'    => null
                ]);
        }
    }
    
    protected function postHistoryLogin($body, $request)
    {
        $time      = timeId();
        $expired   = $time + 1440*60;
        $otpCode   = isset($body->userOTPStatus) && $body->userOTPStatus === '1'
        ? generateRandomCode(6) : null;
        $user = entity('AuthModule', 'UserHistoryLoginEntity')
        ::getUserHistoryLogin($body)->first();
        $updateScope = entity('AuthModule', 'UserHistoryLoginEntity')
        ::updateUserHistoryLoginScope($body);
        $insertUserHistory = entity('AuthModule', 'UserHistoryLoginEntity')
        ::addUserHistoryLogin([
            'userId'          => $body->userId,
            'userToken'       => $body->userToken,
            'scope'           => '0',
            'otpCode'         => $otpCode,
            'ipAddress'       => getClientIp(),
            'platform'        => $request['userAgent'],
            'userExpiredDate' => dateToTime($expired),
        ]);
        
        return response()->json([
            'status'  => 'success',
            'code'    => Response::HTTP_OK,
            'message' => 'logged in',
            'data'    => [
                'loginData'         => [
                    'loggedIn'     => true,
                    'accessToken'  => $this->respondWithToken($body, $time, $expired),
                    '_expires'     => $expired,
                ],
                'userData'         => [
                    'data'         => aliasResponseUserData($body, '', 'first'),
                    '_expires'     => $expired,
                ],
            ]
        ]);
    }

    public function postOTP(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'code'      => 'required',
            'userToken' => 'required'
        ]);
        if ($validator->fails()) {
            //return error message
            return response()->json([
                'status'  => 'error',
                'code'    => Response::HTTP_FORBIDDEN,
                'message' => 'validation is fail',
                'data'    => null
            ]);
        }
        try {
            $decrypt = Crypt::decrypt($request->input('userToken'));
        } catch (DecryptException $e) {
            //
            return response()->json([
                'status'  => 'error',
                'code'    => Response::HTTP_BAD_REQUEST,
                'message' => 'invalid token',
                'data'    => null
            ]);
        }
        $user = entity('AuthModule', 'UserHistoryLoginEntity')
        ::getUpdateUserHistoryLoginScope([
            [
                'param' => 'userHistoryOTPCode',
                'value' => $request->input('code')
            ],
            [
                'param' => 'userHistoryLoginUserToken',
                'value' => $decrypt
            ]
        ])->first();

        if ($user) {
            return response()->json([
                'status'  => 'success',
                'code'    => Response::HTTP_OK,
                'message' => 'otp is success',
                'data'    => true
            ]);
        }

        return response()->json([
            'status'  => 'error',
            'code'    => Response::HTTP_FORBIDDEN,
            'message' => 'otp is fail',
            'data'    => null
        ]);
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function postResetPassword()
    {
        return response()->json(auth()->user());
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function postLogout()
    {
        auth()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    protected function respondWithToken($user, $time = '', $expired = '')
    {
        $payload = [
           'iss' => 'https://gandagang.com',                             // Issuer of the token
           'sub' => cryptoJsAesEncrypt(env('APP_KEY'), $user->userId),   // Subject of the token
           'iat' => $time,                                               // Time when JWT was issued.
           'exp' => $expired                                             // Expiration time
        ];
        
        // As you can see we are passing `JWT_SECRET` as the second parameter that will
        // be used to decode the token in the future.
        return JWT:: encode($payload, env('JWT_SECRET'));
    }
}
