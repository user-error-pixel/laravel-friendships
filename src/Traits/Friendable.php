<?php

namespace PixelError\Friendships\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Event;
use PixelError\Friendships\Models\Friendship;
use PixelError\Friendships\Models\FriendshipGroup;
use PixelError\Friendships\Status;

/**
 * Adds friendship behavior to an Eloquent model.
 */
trait Friendable
{
    /**
     * Send a friend request to another model.
     */
    public function befriend(Model $recipient): Friendship|false
    {
        if (!$this->canBefriend($recipient)) {
            return false;
        }

        $friendship = (new Friendship())->fillRecipient($recipient)->fill([
            'status' => Status::PENDING,
        ]);

        $this->friends()->save($friendship);

        Event::dispatch('friendships.sent', [$this, $recipient]);

        return $friendship;
    }

    /**
     * Remove an existing friendship or pending request.
     */
    public function unfriend(Model $recipient): int
    {
        $deleted = $this->findFriendship($recipient)->delete();

        Event::dispatch('friendships.cancelled', [$this, $recipient]);

        return (int) $deleted;
    }

    /**
     * Determine whether this model has a pending request from another model.
     */
    public function hasFriendRequestFrom(Model $recipient): bool
    {
        return $this->findFriendship($recipient)
            ->whereSender($recipient)
            ->whereStatus(Status::PENDING)
            ->exists();
    }

    /**
     * Determine whether this model sent a pending request to another model.
     */
    public function hasSentFriendRequestTo(Model $recipient): bool
    {
        return Friendship::whereRecipient($recipient)
            ->whereSender($this)
            ->whereStatus(Status::PENDING)
            ->exists();
    }

    /**
     * Determine whether this model is friends with another model.
     */
    public function isFriendWith(Model $recipient): bool
    {
        return $this->findFriendship($recipient)
            ->where('status', Status::ACCEPTED)
            ->exists();
    }

    /**
     * Accept a pending friend request from another model.
     */
    public function acceptFriendRequest(Model $recipient): int
    {
        $updated = $this->findFriendship($recipient)
            ->whereRecipient($this)
            ->update([
                'status' => Status::ACCEPTED,
            ]);

        Event::dispatch('friendships.accepted', [$this, $recipient]);

        return (int) $updated;
    }

    /**
     * Deny a pending friend request from another model.
     */
    public function denyFriendRequest(Model $recipient): int
    {
        $updated = $this->findFriendship($recipient)
            ->whereRecipient($this)
            ->update([
                'status' => Status::DENIED,
            ]);

        Event::dispatch('friendships.denied', [$this, $recipient]);

        return (int) $updated;
    }

    /**
     * Add a friend to one of this model's configured friend groups.
     */
    public function groupFriend(Model $friend, string $groupSlug): bool
    {
        $friendship = $this->findFriendship($friend)
            ->whereStatus(Status::ACCEPTED)
            ->first();

        $groupsAvailable = config('friendships.groups', []);

        if (!isset($groupsAvailable[$groupSlug]) || !$friendship instanceof Friendship) {
            return false;
        }

        $group = $friendship->groups()->firstOrCreate([
            'friendship_id' => $friendship->id,
            'group_id'      => $groupsAvailable[$groupSlug],
            'friend_id'     => $friend->getKey(),
            'friend_type'   => $friend->getMorphClass(),
        ]);

        return $group->wasRecentlyCreated;
    }

    /**
     * Remove a friend from one group, or from all groups when no group is supplied.
     */
    public function ungroupFriend(Model $friend, string $groupSlug = ''): int|false
    {
        $friendship = $this->findFriendship($friend)->first();
        $groupsAvailable = config('friendships.groups', []);

        if (!$friendship instanceof Friendship) {
            return false;
        }

        $where = [
            'friendship_id' => $friendship->id,
            'friend_id'     => $friend->getKey(),
            'friend_type'   => $friend->getMorphClass(),
        ];

        if ($groupSlug !== '' && isset($groupsAvailable[$groupSlug])) {
            $where['group_id'] = $groupsAvailable[$groupSlug];
        }

        return (int) $friendship->groups()->where($where)->delete();
    }

    /**
     * Block another model.
     */
    public function blockFriend(Model $recipient): Friendship
    {
        if (!$this->isBlockedBy($recipient)) {
            $this->findFriendship($recipient)->delete();
        }

        $friendship = (new Friendship())->fillRecipient($recipient)->fill([
            'status' => Status::BLOCKED,
        ]);

        $this->friends()->save($friendship);

        Event::dispatch('friendships.blocked', [$this, $recipient]);

        return $friendship;
    }

    /**
     * Unblock another model.
     */
    public function unblockFriend(Model $recipient): int
    {
        $deleted = $this->findFriendship($recipient)
            ->whereSender($this)
            ->delete();

        Event::dispatch('friendships.unblocked', [$this, $recipient]);

        return (int) $deleted;
    }

    /**
     * Get the friendship between this model and another model.
     */
    public function getFriendship(Model $recipient): ?Friendship
    {
        $friendship = $this->findFriendship($recipient)->first();

        return $friendship instanceof Friendship ? $friendship : null;
    }

    /**
     * Get all friendship records connected to this model.
     */
    public function getAllFriendships(string $groupSlug = ''): EloquentCollection
    {
        return $this->findFriendships(null, $groupSlug)->get();
    }

    /**
     * Get pending friendship records connected to this model.
     */
    public function getPendingFriendships(string $groupSlug = ''): EloquentCollection
    {
        return $this->findFriendships(Status::PENDING, $groupSlug)->get();
    }

    /**
     * Get accepted friendship records connected to this model.
     */
    public function getAcceptedFriendships(string $groupSlug = ''): EloquentCollection
    {
        return $this->findFriendships(Status::ACCEPTED, $groupSlug)->get();
    }

    /**
     * Get denied friendship records connected to this model.
     */
    public function getDeniedFriendships(): EloquentCollection
    {
        return $this->findFriendships(Status::DENIED)->get();
    }

    /**
     * Get blocked friendship records connected to this model.
     */
    public function getBlockedFriendships(): EloquentCollection
    {
        return $this->findFriendships(Status::BLOCKED)->get();
    }

    /**
     * Determine whether this model has blocked another model.
     */
    public function hasBlocked(Model $recipient): bool
    {
        return $this->friends()
            ->whereRecipient($recipient)
            ->whereStatus(Status::BLOCKED)
            ->exists();
    }

    /**
     * Determine whether another model has blocked this model.
     */
    public function isBlockedBy(Model $recipient): bool
    {
        return method_exists($recipient, 'hasBlocked') && $recipient->hasBlocked($this);
    }

    /**
     * Get pending friend requests received by this model.
     */
    public function getFriendRequests(): EloquentCollection
    {
        return Friendship::whereRecipient($this)
            ->whereStatus(Status::PENDING)
            ->get();
    }

    /**
     * Get this model's friends as models, not friendship rows.
     */
    public function getFriends(int $perPage = 0, string $groupSlug = ''): EloquentCollection|LengthAwarePaginator
    {
        return $this->getOrPaginate($this->getFriendsQueryBuilder($groupSlug), $perPage);
    }

    /**
     * Get friends shared by this model and another model.
     */
    public function getMutualFriends(Model $other, int $perPage = 0): EloquentCollection|LengthAwarePaginator
    {
        return $this->getOrPaginate($this->getMutualFriendsQueryBuilder($other), $perPage);
    }

    /**
     * Count friends shared by this model and another model.
     */
    public function getMutualFriendsCount(Model $other): int
    {
        return $this->getMutualFriendsQueryBuilder($other)->count();
    }

    /**
     * Get friends of this model's friends.
     */
    public function getFriendsOfFriends(int $perPage = 0, string $groupSlug = ''): EloquentCollection|LengthAwarePaginator
    {
        return $this->getOrPaginate($this->friendsOfFriendsQueryBuilder($groupSlug), $perPage);
    }

    /**
     * Count accepted friends connected to this model.
     */
    public function getFriendsCount(string $groupSlug = ''): int
    {
        return $this->findFriendships(Status::ACCEPTED, $groupSlug)->count();
    }

    /**
     * Determine whether this model can send a friend request to another model.
     */
    public function canBefriend(Model $recipient): bool
    {
        if ($this->hasBlocked($recipient)) {
            $this->unblockFriend($recipient);

            return true;
        }

        $friendship = $this->getFriendship($recipient);

        if ($friendship instanceof Friendship && $friendship->status !== Status::DENIED) {
            return false;
        }

        return true;
    }

    /**
     * Build a query for the friendship between this model and another model.
     */
    private function findFriendship(Model $recipient): Builder
    {
        return Friendship::betweenModels($this, $recipient);
    }

    /**
     * Build a query for friendships connected to this model.
     */
    private function findFriendships(?int $status = null, string $groupSlug = ''): Builder
    {
        $query = Friendship::where(function (Builder $query): void {
            $query->where(function (Builder $q): void {
                $q->whereSender($this);
            })->orWhere(function (Builder $q): void {
                $q->whereRecipient($this);
            });
        })->whereGroup($this, $groupSlug);

        if ($status !== null) {
            $query->where('status', $status);
        }

        return $query;
    }

    /**
     * Build a query for this model's accepted friend models.
     */
    private function getFriendsQueryBuilder(string $groupSlug = ''): Builder
    {
        $friendships = $this->findFriendships(Status::ACCEPTED, $groupSlug)
            ->get(['sender_id', 'recipient_id']);

        $recipients = $friendships->pluck('recipient_id')->all();
        $senders = $friendships->pluck('sender_id')->all();

        return $this->newQuery()
            ->whereKeyNot($this->getKey())
            ->whereIn($this->getKeyName(), array_unique(array_merge($recipients, $senders)));
    }

    /**
     * Build a query for mutual friends shared by this model and another model.
     */
    private function getMutualFriendsQueryBuilder(Model $other): Builder
    {
        $thisFriendships = $this->findFriendships(Status::ACCEPTED)->get(['sender_id', 'recipient_id']);
        $otherFriendships = $other->findFriendships(Status::ACCEPTED)->get(['sender_id', 'recipient_id']);

        $thisFriendIds = array_merge(
            $thisFriendships->pluck('recipient_id')->all(),
            $thisFriendships->pluck('sender_id')->all()
        );

        $otherFriendIds = array_merge(
            $otherFriendships->pluck('recipient_id')->all(),
            $otherFriendships->pluck('sender_id')->all()
        );

        $mutualFriendIds = array_unique(array_intersect($thisFriendIds, $otherFriendIds));

        return $this->newQuery()
            ->whereKeyNot([$this->getKey(), $other->getKey()])
            ->whereIn($this->getKeyName(), $mutualFriendIds);
    }

    /**
     * Build a query for friends of friends.
     */
    private function friendsOfFriendsQueryBuilder(string $groupSlug = ''): Builder
    {
        $friendships = $this->findFriendships(Status::ACCEPTED)->get(['sender_id', 'recipient_id']);
        $friendIds = array_unique(array_merge(
            $friendships->pluck('recipient_id')->all(),
            $friendships->pluck('sender_id')->all()
        ));

        $friendsOfFriends = Friendship::where('status', Status::ACCEPTED)
            ->where(function (Builder $query) use ($friendIds): void {
                $query->where(function (Builder $q) use ($friendIds): void {
                    $q->whereIn('sender_id', $friendIds);
                })->orWhere(function (Builder $q) use ($friendIds): void {
                    $q->whereIn('recipient_id', $friendIds);
                });
            })
            ->whereGroup($this, $groupSlug)
            ->get(['sender_id', 'recipient_id']);

        $friendOfFriendIds = array_unique(array_merge(
            $friendsOfFriends->pluck('sender_id')->all(),
            $friendsOfFriends->pluck('recipient_id')->all()
        ));

        return $this->newQuery()
            ->whereIn($this->getKeyName(), $friendOfFriendIds)
            ->whereNotIn($this->getKeyName(), array_merge($friendIds, [$this->getKey()]));
    }

    /**
     * Friendship records sent by this model.
     */
    public function friends(): MorphMany
    {
        return $this->morphMany(Friendship::class, 'sender');
    }

    /**
     * Friendship group records owned by this model.
     */
    public function groups(): MorphMany
    {
        return $this->morphMany(FriendshipGroup::class, 'friend');
    }

    /**
     * Return either a collection or paginator for a query builder.
     */
    protected function getOrPaginate(Builder $builder, int $perPage): EloquentCollection|LengthAwarePaginator
    {
        if ($perPage === 0) {
            return $builder->get();
        }

        return $builder->paginate($perPage);
    }
}
