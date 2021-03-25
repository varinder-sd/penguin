<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserProfile extends Model {
	  use HasFactory;
    protected $table = 'user_profiles';
    /**
     * Blacklist
     *
     * Allow for mass Update
     *
     * @var string
     */
	protected $fillable = [
        'user_id'
    ];
    protected $guarded = array();

	public function User(){
		return $this->belongsTo('App\Models\User');
	}
}