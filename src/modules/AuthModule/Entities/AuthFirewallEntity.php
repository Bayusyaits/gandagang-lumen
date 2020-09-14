<?php

namespace Modules\AuthModule\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Authenticatable;
use Laravel\Lumen\Auth\Authorizable;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;

class AuthFirewallEntity extends Model implements AuthenticatableContract
{
    use Authenticatable, Authorizable, Notifiable;

    //
    protected static $elq = __CLASS__;
    protected $table = 'authFirewall';
    protected $primaryKey = 'authFirewallId';
    protected $dates = ['authFirewallDeletedDate'];
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    
    protected $fillable = [
        'authFirewallIpAddress',
        'authFirewallDomain',
        'authFirewallStatus',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [];
    
    public $timestamps = false;
    const CREATED_AT = 'authFirewallCreatedDate';
    const UPDATED_AT = 'authFirewallUpdatedDate';

    protected $fieldRules = [
        'authFirewallIpAddress' => ['',''],
        'authFirewallDomain'    => ['required|unique:authFirewall,authFirewallDomain',''],
        'authFirewallStatus'    => ['',''],
    ];
    
    public function __construct()
    {
        parent::__construct();
    }
    
    public function scopeAddAuthFirewall($query, $body)
    {
        $now = \Carbon\Carbon::now();
               setlocale(LC_TIME, 'IND');
        $query->insert([
            'authFirewallIpAddress'     =>  $body['firewallIpAddress'],
            'authFirewallDomain'        =>  $body['firewallDomain'],
            'authFirewallStatus'        =>  '1',
            'authFirewallCreatedDate'   =>  $now
        ]);
    }

    public function scopeGetAuthFirewall($query, $body, $condition = '')
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
        if (isset($body[1])) {
            foreach ($body as $key => $value) {
                # code...
                $query->where($value['param'], $value['value']);
            }
        } else {
            $query->where($body['param'], $body['value']);
        }
        $query->whereNull('authFirewallDeletedDate');
        if ($cond && !empty($cond)) {
            $query->whereNotNull($cond);
        }
        return $query;
    }
}
