<?php
declare(strict_types=1);


namespace AnnotationReader\Driver;


use AnnotationReader\Fixtures\Entity1;
use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use PHPUnit\Framework\TestCase;
use SofaScore\Purgatory\AnnotationReader\Driver\DoctrineDriver;
use SofaScore\Purgatory\AnnotationReader\DriverInterface;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @coversDefaultClass \SofaScore\Purgatory\AnnotationReader\Driver\DoctrineDriver
 */
final class DoctrineDriverTest extends TestCase
{
    private DriverInterface $driver;

    protected function setUp(): void
    {
        parent::setUp();

        $this->driver = new DoctrineDriver(new AnnotationReader());
    }

    /**
     * @covers ::getClassAnnotations
     */
    public function testGetClassAnnotations():void
    {
        $annotations = $this->driver->getClassAnnotations(new \ReflectionClass(Entity1::class));

        self::assertInstanceOf(Entity::class, $annotations[0]);
    }

    /**
     * @covers ::getClassAnnotation
     */
    public function testGetClassAnnotation(): void
    {
        $annotation = $this->driver->getClassAnnotation(new \ReflectionClass(Entity1::class), Entity::class);

        self::assertInstanceOf(Entity::class, $annotation);
    }

    /**
     * @covers ::getMethodAnnotations
     */
    public function testGetMethodAnnotations(): void
    {
        $annotations = $this->driver->getMethodAnnotations(new \ReflectionMethod(Entity1::class, 'isEnabled'));

        self::assertCount(1, $annotations);
    }

    /**
     * @covers ::getMethodAnnotation
     */
    public function testGetMethodAnnotation(): void
    {
        $annotation = $this->driver->getMethodAnnotation(new \ReflectionMethod(Entity1::class, 'isEnabled'), Groups::class);

        self::assertInstanceOf(Groups::class, $annotation);
    }

    /**
     * @covers ::getPropertyAnnotations
     */
    public function testGetPropertyAnnotations(): void
    {
        $annotations = $this->driver->getPropertyAnnotations(new \ReflectionProperty(Entity1::class, 'id'));

        self::assertCount(3, $annotations);
    }

    /**
     * @covers ::getPropertyAnnotation
     */
    public function testGetPropertyAnnotation(): void
    {
        $idProperty =new \ReflectionProperty(Entity1::class, 'id');

        $column = $this->driver->getPropertyAnnotation($idProperty, Column::class);
        self::assertInstanceOf(Column::class, $column);

        $generatedValue = $this->driver->getPropertyAnnotation($idProperty, GeneratedValue::class);
        self::assertInstanceOf(GeneratedValue::class, $generatedValue);

        $id = $this->driver->getPropertyAnnotation($idProperty, Id::class);
        self::assertInstanceOf(Id::class, $id);
    }
}