<?php

namespace Tests;

use PHPUnit\Framework\Attributes\Test;
use PixelError\Friendships\Status as PixelErrorStatus;

class LegacyNamespaceAliasesTest extends TestCase
{
    #[Test]
    public function legacy_hootlex_namespace_aliases_are_registered(): void
    {
        $this->assertTrue(class_exists('Hootlex\\Friendships\\FriendshipsServiceProvider'));
        $this->assertTrue(class_exists('Hootlex\\Friendships\\Status'));
        $this->assertTrue(class_exists('Hootlex\\Friendships\\Models\\Friendship'));
        $this->assertTrue(class_exists('Hootlex\\Friendships\\Models\\FriendshipGroup'));
        $this->assertTrue(class_exists('Hootlex\\Friendships\\Models\\FriendFriendshipGroups'));
        $this->assertTrue(trait_exists('Hootlex\\Friendships\\Traits\\Friendable'));
    }

    #[Test]
    public function legacy_status_alias_uses_the_same_status_values(): void
    {
        $this->assertSame(PixelErrorStatus::PENDING, \Hootlex\Friendships\Status::PENDING);
        $this->assertSame(PixelErrorStatus::ACCEPTED, \Hootlex\Friendships\Status::ACCEPTED);
        $this->assertSame(PixelErrorStatus::DENIED, \Hootlex\Friendships\Status::DENIED);
        $this->assertSame(PixelErrorStatus::BLOCKED, \Hootlex\Friendships\Status::BLOCKED);
    }
}
