<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Url extends Model
{
    /**
     * @var mixed
     */
    private $old_url;
    /**
     * @var mixed|string|string[]
     */
    private $new_url;
    /**
     * @var mixed
     */
    private $user_ip;
	/**
	 * @var mixed
	 */
	private $user_id;
	
	public function getCreatedAtAttribute($value)
    {
        return Carbon::parse($value)->diffForHumans();
    }
    public function getUpdatedAtAttribute($value)
    {
        return Carbon::parse($value)->diffForHumans();
    }
}
