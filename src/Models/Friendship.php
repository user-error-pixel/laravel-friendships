<?php

namespace PixelError\Friendships\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * Represents a friendship relationship between two Eloquent models.
 */
class Friendship extends Model
{
    /**
     * The attributes that are not mass assignable.
     *
     * @var list<string>
     */
    protected $guarded = ['id', 'created_at', 'updated_at'];

    /**
     * Create a new friendship model instance.
     *
     * @param array<string, mixed> $attributes
     */
    public function __construct(array $attributes = [])
    {
        $this->table = config('friendships.tables.fr_pivot');

        parent::__construct($attributes);
    }

    /**
     * Get the model that sent the friendship request.
     */
    public function sender(): MorphTo
    {
        return $this->morphTo('sender');
    }

    /**
     * Get the model that received the friendship request.
     */
    public function recipient(): MorphTo
    {
        return $this->morphTo('recipient');
    }

    /**
     * Get the group records attached to this friendship.
     */
    public function groups(): HasMany
    {
        return $this->hasMany(FriendshipGroup::class, 'friendship_id');
    }

    /**
     * Fill the recipient morph columns for the given model.
     */
    public function fillRecipient(Model $recipient): static
    {
        return $this->fill([
            'recipient_id'   => $recipient->getKey(),
            'recipient_type' => $recipient->getMorphClass(),
        ]);
    }

    /**
     * Scope the query to friendships received by the given model.
     */
    public function scopeWhereRecipient(Builder $query, Model $model): Builder
    {
        return $query->where('recipient_id', $model->getKey())
            ->where('recipient_type', $model->getMorphClass());
    }

    /**
     * Scope the query to friendships sent by the given model.
     */
    public function scopeWhereSender(Builder $query, Model $model): Builder
    {
        return $query->where('sender_id', $model->getKey())
            ->where('sender_type', $model->getMorphClass());
    }

    /**
     * Scope the query to a friendship group owned by the given model.
     */
    public function scopeWhereGroup(Builder $query, Model $model, string $groupSlug = ''): Builder
    {
        $groupsPivotTable = config('friendships.tables.fr_groups_pivot');
        $friendsPivotTable = config('friendships.tables.fr_pivot');
        $groupsAvailable = config('friendships.groups', []);

        if ($groupSlug !== '' && isset($groupsAvailable[$groupSlug])) {
            $groupId = $groupsAvailable[$groupSlug];

            $query->join($groupsPivotTable, function ($join) use ($groupsPivotTable, $friendsPivotTable, $groupId, $model): void {
                $join->on($groupsPivotTable.'.friendship_id', '=', $friendsPivotTable.'.id')
                    ->where($groupsPivotTable.'.group_id', '=', $groupId)
                    ->where(function ($query) use ($groupsPivotTable, $model): void {
                        $query->where(function ($query) use ($groupsPivotTable, $model): void {
                            $query->where($groupsPivotTable.'.friend_id', '!=', $model->getKey())
                                ->where($groupsPivotTable.'.friend_type', '=', $model->getMorphClass());
                        })->orWhere($groupsPivotTable.'.friend_type', '!=', $model->getMorphClass());
                    });
            });
        }

        return $query;
    }

    /**
     * Scope the query to the relationship between two models in either direction.
     */
    public function scopeBetweenModels(Builder $query, Model $sender, Model $recipient): Builder
    {
        return $query->where(function (Builder $queryIn) use ($sender, $recipient): void {
            $queryIn->where(function (Builder $q) use ($sender, $recipient): void {
                $q->whereSender($sender)->whereRecipient($recipient);
            })->orWhere(function (Builder $q) use ($sender, $recipient): void {
                $q->whereSender($recipient)->whereRecipient($sender);
            });
        });
    }
}
