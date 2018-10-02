<?php

declare(strict_types=1);

/*
 * This file is part of the Sonata Project package.
 *
 * (c) Thomas Rabaix <thomas.rabaix@sonata-project.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sonata\Exporter\Tests\Source;

use Doctrine\ORM\Configuration;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Query;
use PHPUnit\Framework\TestCase;
use Sonata\Exporter\Tests\Source\Fixtures\DoctrineORMQuerySourceIterator;
use Sonata\Exporter\Tests\Source\Fixtures\ObjectWithToString;

/**
 * @author Joseph Maarek <josephmaarek@gmail.com>
 */
final class DoctrineORMQuerySourceIteratorTest extends TestCase
{
    /**
     * @var Query
     */
    private $query;

    protected function setUp(): void
    {
        $entityManager = $this->createMock(EntityManager::class);
        $entityManager->method('getConfiguration')->willReturn(new Configuration());
        $this->query = new Query($entityManager);
    }

    /**
     * @dataProvider getValueProvider
     */
    public function testGetValue($value, $expected, $dateFormat = 'r'): void
    {
        $iterator = new DoctrineORMQuerySourceIterator($this->query, [], $dateFormat);
        $this->assertSame($expected, $iterator->getValue($value));
    }

    public function getValueProvider()
    {
        $datetime = new \DateTime();
        $dateTimeImmutable = new \DateTimeImmutable();

        $data = [
            [[1, 2, 3], null],
            [new \ArrayIterator([1, 2, 3]), null],
            [$datetime, $datetime->format('r')],
            [$datetime, $datetime->format('Y-m-d H:i:s'), 'Y-m-d H:i:s'],
            [123, 123],
            ['123', '123'],
            [new ObjectWithToString('object with to string'), 'object with to string'],
            [$dateTimeImmutable, $dateTimeImmutable->format('r')],
            [$dateTimeImmutable, $dateTimeImmutable->format('Y-m-d H:i:s'), 'Y-m-d H:i:s'],
        ];

        return $data;
    }
}
