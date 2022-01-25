<?php

declare(strict_types = 1);

namespace Statistics\Calculator;

use SocialPost\Dto\SocialPostTo;
use Statistics\Dto\StatisticsTo;

class NoopCalculator extends AbstractCalculator
{

    protected const UNITS = 'posts';

    /**
     * @var int
     */
    private $totalAuthorsCount = 0;

    /**
     * @var int
     */
    private $postCount = 0;

    /**
     * @inheritDoc
     */
    protected function doAccumulate(SocialPostTo $postTo): void
    {
        $this->postCount++;
        $key = $postTo->getAuthorName();
                
        $this->totals[$key] = ($this->totals[$key] ?? 0) + 1;
    }

    /**
     * @inheritDoc
     */
    protected function doCalculate(): StatisticsTo
    {
        $this->totalAuthorsCount = count($this->totals);

        $value = $this->postCount > 0
            ? $this->postCount / $this->totalAuthorsCount
            : 0;

        return (new StatisticsTo())->setValue(round($value,2));
    }
}
