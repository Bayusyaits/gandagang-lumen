<?php

namespace Modules\UserModule\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Authenticatable;
use Laravel\Lumen\Auth\Authorizable;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;

class AuthClientEntity extends Model implements AuthenticatableContract
{
    use Authenticatable, Authorizable, Notifiable;

    //
    protected static $elq = __CLASS__;
    protected $table      = 'authClient';
    protected $primaryKey = 'AuthClientId';
    protected $dates      = ['authClientDeletedDate'];
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    
    protected $fillable = [
        'authClientSignature',
        'authClientKey',
        'authClientListFirewallId',
        'authClientAccess',
        'authClientPassword',
        'authClientExpiredDate',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'authClientPassword',
    ];
    
    public $timestamps = false;
    const  CREATED_AT  = 'authClientCreatedDate';
    const  UPDATED_AT  = 'authClientUpdatedDate';

    protected $fieldRules = [
        'authClientSignature'      => ['',''],
        'authClientKey'            => ['',''],
        'authClientListFirewallId' => ['',''],
        'authClientAccess'         => ['',''],
        'authClientPassword'       => ['',''],
        'authClientExpiredDate'    => ['',''],
    ];
    
    public function __construct()
    {
        parent:: __construct();
    }
    
    public function scopeGetAuthClient($query, $body, $condition = '')
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
        $query->whereNull('authClientDeletedDate');
        if ($cond && !empty($cond)) {
            $query->whereNotNull($cond);
        }
        return $query;
    }
}
