<?php

namespace Czechbox\LaravelPlans\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Czechbox\LaravelPlans\Contracts\PlanSubscriptionUsageInterface;

class PlanSubscriptionUsage extends Model implements PlanSubscriptionUsageInterface
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'subscription_id',
        'code',
        'valid_until',
        'used',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'valid_until' => 'datetime',
    ];

    /**
     * Get feature.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function feature()
    {
        return $this->belongsTo(config('laravelplans.models.plan_feature'));
    }

    /**
     * Get subscription.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function subscription()
    {
        return $this->belongsTo(config('laravelplans.models.plan_subscription'));
    }

    /**
     * Scope by feature code.
     *
     * @param \Illuminate\Database\Eloquent\Builder
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByFeatureCode($query, $feature_code)
    {
        return $query->whereCode($feature_code);
    }

    /**
     * Check whether usage has been expired or not.
     *
     * @return bool
     */
    public function isExpired()
    {
        if (is_null($this->valid_until)) {
            return false;
        }

        return Carbon::now()->gte($this->valid_until);
    }
}
