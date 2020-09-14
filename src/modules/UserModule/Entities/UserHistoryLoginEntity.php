<?php

namespace Modules\UserModule\Entities;

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
}
