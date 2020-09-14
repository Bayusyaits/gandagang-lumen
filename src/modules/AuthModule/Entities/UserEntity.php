<?php

namespace Modules\AuthModule\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Authenticatable;
use Laravel\Lumen\Auth\Authorizable;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;

class UserEntity extends Model implements AuthenticatableContract
{
    use Authenticatable, Authorizable, Notifiable;

    //
    protected static $elq = __CLASS__;
    protected $table      = 'user';
    protected $primaryKey = 'userId';
    protected $dates      = ['userDeletedDate'];
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    
    protected $fillable = [
        'userName',
        'userEmail',
        'userSalutation',
        'userPassword',
        'userClientId',
        'userMobilePrefix',
        'userPhoneNumber',
        'userClientSecret',
        'userRegisterdBy',
        'userFirstName',
        'userLastName',
        'userPlatform',
        'userIpAddress',
        'userToken',
        'userOTPStatus',
        'userStatus',
        'userVerifyPhoneNumberCode',
        'userVerifyPhoneNumberDate',
        'userVerifyEmailDate',
        'userVerifyEmailCode',
        'userType',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'userPassword',
    ];
    
    public $timestamps = false;
    const  CREATED_AT  = 'userCreatedDate';
    const  UPDATED_AT  = 'userUpdatedDate';

    protected $fieldRules = [
        'userId'                    => ['',''],
        'userPlatform'              => ['',''],
        'userIpAddress'             => ['',''],
        'userAgreePrivacy'          => ['',''],
        'userAgreeSubscribe'        => ['',''],
        'userClientId'              => ['',''],
        'userClientSecret'          => ['',''],
        'userMobilePrefix'          => ['',''],
        'userPhoneNumber'           => ['',''],
        'userToken'                 => ['',''],
        'userVerifyPhoneNumberCode' => ['',''],
        'userVerifyEmailCode'       => ['',''],
        'userVerifyPhoneNumberDate' => ['',''],
        'userVerifyEmailDate'       => ['',''],
        'userVerifyDataDate'        => ['',''],
        'userOTPStatus'             => ['',''],
        'userStatus'                => ['',''],
        'userType'                  => ['',''],
        'userSalutation'            => ['required',''],
        'userName'                  => ['required|string',''],
        'userEmail'                 => ['required|email',''],
        'userPassword'              => ['required',''],
        'userFirstName'             => ['required',''],
        'userLastName'              => ['','']
    ];
    
    public function __construct()
    {
        parent:: __construct();
    }

    public function scopeAddUser($query, $body)
    {
        $now = \Carbon\Carbon::now();
               setlocale(LC_TIME, 'IND');
        $query->insert([
            //fc = field contact
            'userName'                  => $body['userName'],
            'userEmail'                 => $body['userEmail'],
            'userPlatform'              => $body['userPlatform'],
            'userIpAddress'             => $body['userIpAddress'],
            'userPassword'              => $body['userPassword'],
            'userFirstName'             => $body['userFirstName'],
            'userLastName'              => $body['userLastName'],
            'userToken'                 => generateToken(64),
            'userVerifyPhoneNumberCode' => generateRandomCode(6),
            'userVerifyEmailCode'       => generateRandomCode(6),
            'userAgreePrivacy'          => $body['agreePrivacy'],
            'userAgreeSubscribe'        => $body['agreeSubscribe'],
            'userPhoneNumber'           => $body['phoneNumber'],
            'userMobilePrefix'          => $body['mobilePrefix'],
            'userStatus'                => '1',
            'userType'                  => '1',
            'userSalutation'            => $body['userSalutation'],
            'userClientId'              => isset($body['userClientId']) ? $body['userClientId'] : null,
            'userClientSecret'          => isset($body['userClientSecret']) ? $body['userClientSecret'] : null,
            'userCreatedDate'           => $now
        ]);
    }

    /**
     * The attributes that are mass assignable.
     * condition value userVerifyPhoneNumber,userVerifyEmailDate
     * @param $body['param'] is name field like userEmail
     * @param $body['value] value from response or payload
     * @var array
     */
    public function scopeGetUser($query, $body, $condition = '')
    {
        $explode = explode(',', $condition);
        if (isset($explode[1])) {
            foreach ($explode as $key => $value) {
                # code...
                $cond[$key] = $value;
            }
        } else {
            $cond = $condition;
        }
        if (isset($body[0])) {
            foreach ($body as $key => $value) {
                # code...
                $query->where($value['param'], $value['value']);
            }
        } else {
            $query->where($body['param'], $body['value']);
        }

        $query->whereNull('userDeletedDate');
        if ($cond && !empty($cond)) {
            $query->whereNotNull($cond);
        }
        return $query;
    }

    public function scopeGetUserVerifyCode($query, $body, $condition = '')
    {
        $explode = explode(',', $condition);
        if (isset($explode[1])) {
            foreach ($explode as $key => $value) {
                # code...
                $query->whereNull($value);
            }
        } else {
            $query->whereNull($condition);
        }
        if (isset($body[0])) {
            foreach ($body as $key => $value) {
                # code...
                $query->where($value['param'], $value['value']);
            }
        } else {
            $query->where($body['param'], $body['value']);
        }
        return $query;
    }
}
