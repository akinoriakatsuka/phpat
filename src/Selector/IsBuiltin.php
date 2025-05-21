<?php declare(strict_types=1);

namespace PHPat\Selector;

use PHPat\Parser\BuiltInClasses;
use PHPStan\Reflection\ClassReflection;

class IsBuiltin implements SelectorInterface
{
    public function getName(): string
    {
        return '-built-in classes-';
    }

    public function matches(ClassReflection $classReflection): bool
    {
        $name = ltrim($classReflection->getName(), '\\');
        if (!$classReflection->isInternal()) {
            return false;
        }
        $shortName = $classReflection->getShortName();
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
