<?php

namespace Modules\AuthModule\Entities;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;

class UserHistoryLoginEntity extends Model
{
    use  Notifiable;

    //
    protected static $elq = __CLASS__;
    protected $table      = 'userHistoryLogin';
    protected $primaryKey = 'userHistoryLoginId';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    
    protected $fillable = [
        'userHistoryLoginUserId',
        'userHistoryLoginScope',
        'userHistoryLoginUserToken',
        'userHistoryLoginIpAddress',
        'userHistoryLoginPlatform',
        'userHistoryLoginClientId',
        'userHistoryOTPCode',
        'userHistoryLoginExpiredDate'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = ['userHistoryLoginUserId'];
    
    public $timestamps = false;
    const  CREATED_AT  = 'userHistoryLoginCreatedDate';

    protected $fieldRules = [
        'userHistoryLoginUserId'      => ['required',''],
        'userHistoryLoginScope'       => ['',''],
        'userHistoryLoginUserToken'   => ['required',''],
        'userHistoryLoginIpAddress'   => ['',''],
        'userHistoryLoginPlatform'    => ['',''],
        'userHistoryLoginClientId'    => ['',''],
        'userHistoryOTPCode'          => ['',''],
        'userHistoryLoginExpiredDate' => ['required','']
    ];
    
    public function __construct()
    {
        parent:: __construct();
    }

    public function scopeAddUserHistoryLogin($query, $body)
    {
        $now = \Carbon\Carbon::now();
               setlocale(LC_TIME, 'IND');
        $query->insert([
            //fc = field contact
            'userHistoryLoginUserId'      => $body['userId'],
            'userHistoryLoginClientId'    => isset($body['clientId']) ? $body['clientId'] : null,
            'userHistoryLoginUserToken'   => isset($body['userToken']) ? $body['userToken'] : null,
            'userHistoryLoginScope'       => $body['scope'],
            'userHistoryOTPCode'          => $body['otpCode'],
            'userHistoryLoginIpAddress'   => $body['ipAddress'],
            'userHistoryLoginPlatform'    => $body['platform'],
            'userHistoryLoginExpiredDate' => $body['userExpiredDate'],
            'userHistoryLoginCreatedDate' => $now
        ]);
    }

    public function scopeGetUserHistoryLogin($query, $body)
    {
        $now = \Carbon\Carbon::now();
        setlocale(LC_TIME, 'IND');
        $query->where([
            'userHistoryLoginUserId' => $body['userId'],
            'userHistoryLoginScope'  => '1'
        ]);
        if ($query->first()) {
            $query->where('userHistoryLoginExpiredDate', '>', $now);
        }
        return $query;
    }

    public function scopeGetUpdateUserHistoryLoginScope($query, $body)
    {
        $now = \Carbon\Carbon::now();
        setlocale(LC_TIME, 'IND');
        if (isset($body[1])) {
            foreach ($body as $key => $value) {
                # code...
                $query->where($value['param'], $value['value']);
            }
        } else {
            $query->where($body['param'], $body['value']);
        }
        $query->where('userHistoryLoginExpiredDate', '>', $now);
        if ($query->first()) {
            $query->update(['userHistoryLoginScope' => '1']);
        }
        return $query;
    }

    public function scopeUpdateUserHistoryLoginScope($query, $body)
    {
        $now = \Carbon\Carbon::now();
        setlocale(LC_TIME, 'IND');
        $query->where([
            'userHistoryLoginUserId' => $body['userId'],
            'userHistoryLoginScope'  => '1'
        ]);
        $query->where('userHistoryLoginExpiredDate', '>', $now);
        if ($query->first()) {
            $query->update(['userHistoryLoginScope' => '0']);
        }
    }
}
