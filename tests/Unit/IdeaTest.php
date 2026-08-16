<?php

namespace Tests\Unit;

use App\Models\Idea;
use PHPUnit\Framework\TestCase;

class IdeaTest extends TestCase
{
    public function test_categories_and_statuses_are_defined(): void
    {
        $this->assertContains('Feature', Idea::CATEGORIES);
        $this->assertContains('Discussion', Idea::CATEGORIES);
        $this->assertSame(['open', 'planned', 'completed'], Idea::STATUSES);
    }

    public function test_a_guest_is_never_counted_as_having_voted(): void
    {
        $this->assertFalse((new Idea)->isVotedBy(null));
    }
}
