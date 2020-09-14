<?php

namespace Modules\AuthModule\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Authenticatable;
use Laravel\Lumen\Auth\Authorizable;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;

class ListFirewallEntity extends Model implements AuthenticatableContract
{
    use Authenticatable, Authorizable, Notifiable;

    //
    protected static $elq = __CLASS__;
    protected $table = 'listFirewall';
    protected $primaryKey = 'listFirewallId';
    protected $dates = ['listFirewallDeletedDate'];
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    
    protected $fillable = [
        'listFirewallIpAddress',
        'listFirewallDomain',
        'listFirewallStatus',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'listFirewallIpAddress', 'listFirewallDomain'
    ];
    
    public $timestamps = false;
    const CREATED_AT = 'listFirewallCreatedDate';
    const UPDATED_AT = 'listFirewallUpdatedDate';

    protected $fieldRules = [
        'listFirewallIpAddress' => ['',''],
        'listFirewallDomain'    => ['unique:listFirewall,listFirewallDomain',''],
        'listFirewallStatus'    => ['',''],
    ];
    
    public function __construct()
    {
        parent::__construct();
    }
    
    public function scopeGetListFirewallFirst($query, $body)
    {
        return $query->where([
            'listFirewallId'            =>  $body['firewallId'],
            'listFirewallStatus'        =>  '1',
            'listFirewallDeletedDate'   =>  null
        ])->first();
    }
}
