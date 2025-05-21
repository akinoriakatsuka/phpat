<?php declare(strict_types=1);

namespace PHPat\Tests\Architecture;

use PHPat\Parser\BuiltInClasses;
use PHPat\Selector\Selector;
use PHPUnit\Framework\TestCase;

class IsBuiltinSelectorTest extends TestCase
{
    public function test_builtin_class_is_matched(): void
    {
        $reflection = new \ReflectionClass(\Exception::class);
        $this->assertTrue($this->isBuiltinReflection($reflection));
    }

    public function test_user_defined_class_is_not_matched(): void
    {
        require_once __DIR__ . '/../fixtures/FixtureClass.php';
        $reflection = new \ReflectionClass(\Tests\PHPat\fixtures\FixtureClass::class);
        $this->assertFalse($this->isBuiltinReflection($reflection));
    }

    private function isBuiltinReflection(\ReflectionClass $reflection): bool
    {
        $name = ltrim($reflection->getName(), '\\');
        if (!$reflection->isInternal()) {
            return false;
        }
        $shortName = $reflection->getShortName();
        if (in_array($shortName, BuiltInClasses::PHP_8_BUILT_IN_CLASSES, true)) {
            return true;
        }
        foreach (BuiltInClasses::PHP_8_BUILT_IN_CLASSES as $builtin) {
            if ($name === $builtin) {
                return true;
            }
        }
        return false;
    }
}
