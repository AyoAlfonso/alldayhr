<?php namespace App;

use Zizaco\Entrust\EntrustPermission;

class Permission extends EntrustPermission
{
    protected $guarded = ['id'];
}