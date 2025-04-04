<?php declare(strict_types=1);

use PhpCsFixer\Config;
use PhpCsFixer\Finder;
use PhpCsFixer\Runner\Parallel\ParallelConfigFactory;
use PhpCsFixerCustomFixers\Fixer;

return (new Config())
    ->setParallelConfig(ParallelConfigFactory::detect())
    ->setRiskyAllowed(true)
    ->registerCustomFixers(new PhpCsFixerCustomFixers\Fixers())
    ->setRules([
        '@Symfony' => true,
        '@Symfony:risky' => true,
        'linebreak_after_opening_tag' => false,
        'blank_line_after_opening_tag' => false,
        'phpdoc_summary' => false,
        'phpdoc_annotation_without_dot' => false,
        'phpdoc_to_comment' => false,
        'declare_strict_types' => true,
        'strict_comparison' => true,
        'strict_param' => true,
        'no_useless_else' => true,
        'void_return' => true,
        'phpdoc_line_span' => true,
        'php_unit_dedicate_assert_internal_type' => true,
        'php_unit_mock' => true,
        'php_unit_test_case_static_method_calls' => ['call_type' => 'static'],
        'php_unit_attributes' => true,
        'php_unit_data_provider_static' => true,
        'php_unit_data_provider_name' => ['prefix' => 'provide', 'suffix' => ''],
        'no_useless_return' => true,
        'yoda_style' => [
            'equal' => false,
            'identical' => false,
            'less_and_greater' => false,
        ],
        'single_line_throw' => false,
        'fopen_flags' => false,
        'self_accessor' => false,
        'phpdoc_align' => ['align' => 'left'],
        'phpdoc_order' => ['order' => ['param', 'throws', 'return']],
        'class_attributes_separation' => ['elements' => ['property' => 'one', 'method' => 'one']],
        'method_argument_space' => ['on_multiline' => 'ensure_fully_multiline'],
        'concat_space' => ['spacing' => 'one'],
        'native_function_invocation' => [
            'scope' => 'namespaced',
            'strict' => false,
            'exclude' => ['ini_get'],
        ],
        'general_phpdoc_annotation_remove' => ['annotations' => ['copyright', 'category']],
        'no_superfluous_phpdoc_tags' => ['allow_unused_params' => true, 'allow_mixed' => true],
        'php_unit_dedicate_assert' => ['target' => 'newest'],
        'single_quote' => ['strings_containing_single_quote_chars' => true],
        'attribute_empty_parentheses' => ['use_parentheses' => false],
        'phpdoc_param_order' => true,
        Fixer\CommentSurroundedBySpacesFixer::name() => true,
        Fixer\ConstructorEmptyBracesFixer::name() => true,
        Fixer\DeclareAfterOpeningTagFixer::name() => true,
        Fixer\MultilinePromotedPropertiesFixer::name() => ['minimum_number_of_parameters' => 2],
        Fixer\NoDoctrineMigrationsGeneratedCommentFixer::name() => true,
        Fixer\NoImportFromGlobalNamespaceFixer::name() => true,
        Fixer\NoTrailingCommaInSinglelineFixer::name() => true,
        Fixer\PhpdocNoSuperfluousParamFixer::name() => true,
        Fixer\PhpdocSingleLineVarFixer::name() => true,
        Fixer\PhpdocTypesCommaSpacesFixer::name() => true,
        Fixer\PhpdocTypesTrimFixer::name() => true,
        Fixer\SingleSpaceAfterStatementFixer::name() => true,
        Fixer\SingleSpaceBeforeStatementFixer::name() => true,

        'header_comment' => ['header' => '(c) shopware AG <info@shopware.com>
For the full copyright and license information, please view the LICENSE
file that was distributed with this source code.', 'separate' => 'bottom', 'location' => 'after_declare_strict', 'comment_type' => 'comment'],
    ])
    ->setUsingCache(true)
    ->setCacheFile(__DIR__ . '/var/cache/.csfixer.cache')
    ->setFinder(
        (new Finder())->in([
            __DIR__ . '/src',
            __DIR__ . '/tests',
        ])
    );