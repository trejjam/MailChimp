<?php

declare(strict_types=1);

use PhpCsFixer\Fixer\Basic\BracesFixer;
use PhpCsFixer\Fixer\FunctionNotation\ReturnTypeDeclarationFixer;
use PhpCsFixer\Fixer\Operator\BinaryOperatorSpacesFixer;
use PhpCsFixer\Fixer\PhpTag\BlankLineAfterOpeningTagFixer;
use Symplify\EasyCodingStandard\Config\ECSConfig;
use Symplify\EasyCodingStandard\ValueObject\Option;

$rootDir = __DIR__ . '/../';

return static function (ECSConfig $ecsConfig) use ($rootDir) : void {
    $ecsConfig->import($rootDir . 'vendor/symplify/easy-coding-standard/config/set/common/array.php');

    $ecsConfig->import($rootDir . 'vendor/symplify/easy-coding-standard/config/set/common/control-structures.php');

    $ecsConfig->import($rootDir . 'vendor/symplify/easy-coding-standard/config/set/common/docblock.php');

    $ecsConfig->import($rootDir . 'vendor/symplify/easy-coding-standard/config/set/common/namespaces.php');

    $ecsConfig->import($rootDir . 'vendor/symplify/easy-coding-standard/config/set/common/strict.php');

    $ecsConfig->import($rootDir . 'vendor/symplify/easy-coding-standard/config/set/clean-code.php');

    $ecsConfig->import($rootDir . 'vendor/symplify/easy-coding-standard/config/set/psr12.php');

    $ecsConfig->indentation(Option::INDENTATION_SPACES);
    $ecsConfig->skip([
        BlankLineAfterOpeningTagFixer::class => null,
        BracesFixer::class => null,
        BinaryOperatorSpacesFixer::class => null,
    ]);

    $ecsConfig->ruleWithConfiguration(ReturnTypeDeclarationFixer::class, [
        'space_before' => 'one',
    ]);
};
