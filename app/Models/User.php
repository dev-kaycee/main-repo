<?php

namespace App\Models;

use App\Traits\FilterByTeam;
use Hash;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * Class User
 *
 * @package App
 * @property string $name
 * @property string $email
 * @property string $password
 * @property string $role
 * @property string $remember_token
 * @property string $team
 */
class User extends Authenticatable
{
	use Notifiable;
	use FilterByTeam;

	protected $fillable = ['name', 'email', 'password', 'remember_token', 'role_id', 'team_id'];
	protected $hidden = ['password', 'remember_token'];

	/**
	 * Hash password
	 * @param $input
	 */
	public function setPasswordAttribute($input)
	{
		if ($input)
			$this->attributes['password'] = app('hash')->needsRehash($input) ? Hash::make($input) : $input;
	}

	/**
	 * Set to null if empty
	 * @param $input
	 */
	public function setRoleIdAttribute($input)
	{
		$this->attributes['role_id'] = $input ? $input : null;
	}

	public function role()
	{
		return $this->belongsTo(Role::class, 'role_id');
	}

	public function teams()
	{
		return $this->belongsToMany(Team::class);
	}

	public function sendPasswordResetNotification($token)
	{
		$this->notify(new ResetPassword($token));
	}

	public function hasPermission(string $permission): bool
	{
		return $this->permissions->contains('name', $permission);
	}

// Check if a user has a specific permission

	public function assignPermission(string $permission)
	{
		$permissionModel = Permission::firstOrCreate(['name' => $permission]);
		$this->permissions()->syncWithoutDetaching($permissionModel);
	}

// Assign a permission to the user

	public function permissions()
	{
		return $this->belongsToMany(Permission::class);
	}

    public function isAdmin()
    {
        return $this->role_id === 1;
    }

    public function isTeamAdmin()
    {
        return $this->role_id === 3;
    }
}