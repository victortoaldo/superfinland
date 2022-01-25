<?php

declare(strict_types = 1);

namespace Tests\unit;

use PHPUnit\Framework\TestCase;
use SocialPost\Dto\SocialPostTo;
use Statistics\Dto\StatisticsTo;
use Statistics\Calculator\NoopCalculator;
use \DateTime;

class AveragePerUserPerMonthTest extends TestCase
{

    /**
     * @param $object
     * @param string $method
     * @param array $parameters
     * @return mixed
     * @throws \Exception
     */
    private function callMethod($object, string $method , array $parameters = [])
    {
        try {
            $className = get_class($object);
            $reflection = new \ReflectionClass($className);
        } catch (\ReflectionException $e) {
           throw new \Exception($e->getMessage());
        }

        $method = $reflection->getMethod($method);
        $method->setAccessible(true);

        return $method->invokeArgs($object, $parameters);
    }

    /**
     * @dataProvider pageProvider
     */
    public function testAveragePerUser($posts, $statistics)
    {
        $average_per_month = new NoopCalculator();

        foreach($posts as $post) {
            $this->callMethod($average_per_month, 'doAccumulate', array($post));
        }
                
        $this->assertTrue($this->callMethod($average_per_month, 'doCalculate') == $statistics);
    }

    /**
     * @return array
     */
    public function pageProvider()
    {
        $post1 = new SocialPostTo();

        $post1->setId('1');
        $post1->setAuthorName('Test');
        $post1->setAuthorId('1');
        $post1->setText('Test');
        $post1->setType('Foo');
        $post1->setDate(new DateTime('2022-01-25T10:10:10+00:00'));

        $post2 = new SocialPostTo();

        $post2->setId('2');
        $post2->setAuthorName('Test');
        $post2->setAuthorId('1');
        $post2->setText('Test');
        $post2->setType('Foo');
        $post2->setDate(new DateTime('2022-01-25T10:15:10+00:00'));

        $posts1 = array($post1, $post2);

        $statistics1 = new StatisticsTo();

        $statistics1->setValue(2.0);

        $post3 = new SocialPostTo();

        $post3->setId('1');
        $post3->setAuthorName('Test');
        $post3->setAuthorId('1');
        $post3->setText('Test');
        $post3->setType('Foo');
        $post3->setDate(new DateTime('2022-01-25T10:10:10+00:00'));

        $posts2 = array($post3);

        $statistics2 = new StatisticsTo();

        $statistics2->setValue(1.0);

        return [
            [$posts1, $statistics1],
            [$posts2, $statistics2]
        ];
    }
}