<?php declare(strict_types=1);

namespace Tests\PHPat\unit\rules\CanOnlyDepend;

use PHPat\Configuration;
use PHPat\Rule\Assertion\Relation\CanOnlyDepend\CanOnlyDepend;
use PHPat\Rule\Assertion\Relation\CanOnlyDepend\DocMethodTagRule;
use PHPat\Selector\Classname;
use PHPat\Statement\Builder\StatementBuilderFactory;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use PHPStan\Type\FileTypeMapper;
use Tests\PHPat\fixtures\FixtureClass;
use Tests\PHPat\fixtures\Simple\SimpleClass;
use Tests\PHPat\fixtures\Simple\SimpleClassFive;
use Tests\PHPat\fixtures\Simple\SimpleClassFour;
use Tests\PHPat\fixtures\Simple\SimpleClassSix;
use Tests\PHPat\fixtures\Simple\SimpleClassThree;
use Tests\PHPat\fixtures\Simple\SimpleClassTwo;
use Tests\PHPat\fixtures\Simple\SimpleException;
use Tests\PHPat\fixtures\Simple\SimpleInterface;
use Tests\PHPat\fixtures\Special\ClassImplementing;
use Tests\PHPat\fixtures\Special\InterfaceWithTemplate;
use Tests\PHPat\unit\FakeTestParser;

/**
 * @extends RuleTestCase<DocMethodTagRule>
 * @internal
 * @coversNothing
 */
class DocMethodTagTest extends RuleTestCase
{
    public const RULE_NAME = 'testFixtureClassCanOnlyDependSimpleAndSpecial';

    public function testRule(): void
    {
        $this->analyse(['tests/fixtures/FixtureClass.php'], [
            [sprintf('%s should not depend on %s', FixtureClass::class, SimpleClassFive::class), 29],
        ]);
    }

    protected function getRule(): Rule
    {
        $testParser = FakeTestParser::create(
            self::RULE_NAME,
            CanOnlyDepend::class,
            [new Classname(FixtureClass::class, false)],
            [
                new Classname(SimpleClass::class, false),
                new Classname(SimpleClassTwo::class, false),
                new Classname(SimpleClassThree::class, false),
                new Classname(SimpleClassFour::class, false),
                new Classname(SimpleClassSix::class, false),
                new Classname(InterfaceWithTemplate::class, false),
                new Classname(ClassImplementing::class, false),
                new Classname(SimpleException::class, false),
                new Classname(SimpleInterface::class, false),
            ]
        );

        return new DocMethodTagRule(
            new StatementBuilderFactory($testParser),
            new Configuration(false, true, false),
            $this->createReflectionProvider(),
            self::getContainer()->getByType(FileTypeMapper::class)
        );
    }
}
