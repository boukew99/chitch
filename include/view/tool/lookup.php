<?php
# Chitch © its Maintainers 2025, Licensed under the EUPL

require('../../chitch.php');

?>
<?= Chitch\head(); ?>
<link rel="stylesheet" href="code.css" />
<title>Namespace Lookup Table</title>
<style>
    .deprecated {
        color: #a00;
        text-decoration: line-through;
    }

    /* TODO: make it always even number of columns */
    dl {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(min(100%, 17em), 2fr));
        gap: clamp(0.5rem, 2vw, 1rem);
        align-items: center;
    }

    dt,
    dd {
        margin: 0;
        padding: clamp(0.25rem, 1vw, 0.5rem);
    }
</style>
<header>
    <h1>Lookup Table of PHP binary</h1>
    <p>See all the functions and constants available in the various extensions in PHP and also see the included namespaces. This is done by <a href="https://www.php.net/manual/en/book.reflection.php">the PHP Reflection extensions</a>. It loops through the extension and loaded namespace. For each the functions and constants are listed. For the functions the input and output is set in a table. The constants are matched to their values.
    <details>
        <summary>Table of Contents</summary>
        <nav id="toc"></nav>
    </details>
</header>
<main>

    <?php
    /**
     * Generate a table for functions and a list for constants for a given entity (extension or namespace).
     */
    function generate_entity_overview(string $entity, array $functions, array $constants, string $hrefBase): void
    {
        $sectionId = strtolower(str_replace(' ', '-', $entity)); // Generate a unique ID for the section
    ?>
        <section id="<?= htmlspecialchars($sectionId) ?>">
            <h2><?= htmlspecialchars($entity) ?></h2>
            <table>
                <caption>Functions in <?= htmlspecialchars($entity) ?></caption>
                <tr>
                    <th>Name</th>
                    <th>Input</th>
                    <th>Output</th>
                </tr>
                <?php foreach ($functions as $funcName => $refFunc): ?>
                    <?php
                    $params = array_map(
                        fn($p) => '$' . $p->getName() . ($p->hasType() ? ": " . $p->getType() : ""),
                        $refFunc->getParameters()
                    );
                    $returnType = $refFunc->hasReturnType() ? $refFunc->getReturnType() : 'mixed';
                    $isDeprecated = $refFunc->isDeprecated() ? 'class="deprecated"' : '';
                    ?>
                    <tr <?= $isDeprecated ?>>
                        <td>
                            <a href="<?= htmlspecialchars($hrefBase . strtolower(str_replace('_', '-', $funcName))) ?>" target="_blank">
                                <?= htmlspecialchars($funcName) ?>
                            </a>
                        </td>
                        <td><?= htmlspecialchars(implode(", ", $params)) ?></td>
                        <td><?= htmlspecialchars($returnType) ?></td>
                    </tr>
                <?php endforeach; ?>
            </table>
            <?php if (!empty($constants)): ?>
                <p><strong>Constants in <?= htmlspecialchars($entity) ?></strong>
                <dl>
                    <?php foreach ($constants as $constName => $value): ?>
                        <?php
                        $valueType = gettype($value);
                        $isDeprecated = '';
                        try {
                            if (class_exists($entity)) {
                                $reflectionConst = new ReflectionClassConstant($entity, $constName);
                                $isDeprecated = $reflectionConst->isDeprecated() ? 'class="deprecated"' : '';
                            }
                        } catch (ReflectionException $e) {
                            $isDeprecated = '';
                        }
                        ?>
                        <dt <?= $isDeprecated ?>><?= htmlspecialchars($constName) ?></dt>
                        <dd><?= htmlspecialchars(var_export($value, true)) ?> <em>(<?= htmlspecialchars($valueType) ?>)</em>
                            <?php if ($isDeprecated): ?>
                                <span style="color: #a00;">[Deprecated]</span>
                            <?php endif; ?>
                        </dd>
                    <?php endforeach; ?>
                </dl>
            <?php endif; ?>
        </section>
    <?php
    }

    // Generate overview for namespaces
    $includedNamespaces = array_unique(array_filter(
        array_map(fn($fn) => explode('\\', $fn)[0], get_defined_functions()['user']),
        fn($namespace) => !empty($namespace)
    ));

    foreach ($includedNamespaces as $namespace) {
        $namespaceFunctions = array_filter(
            get_defined_functions()['user'],
            fn($fn) => str_starts_with($fn, "$namespace\\")
        );
        $namespaceFunctions = array_combine(
            array_map(fn($fn) => str_replace("$namespace\\", '', $fn), $namespaceFunctions),
            array_map(fn($fn) => new ReflectionFunction($fn), $namespaceFunctions)
        );
        generate_entity_overview(
            $namespace,
            $namespaceFunctions,
            [], // Namespaces typically don't have constants
            '/tool/reference.php#'
        );
    }

    // Generate overview for extensions
    $extensions = get_loaded_extensions();

    foreach ($extensions as $ext) {
        $refExtension = new ReflectionExtension($ext);
        $extensionFunctions = $refExtension->getFunctions();
        $extensionConstants = $refExtension->getConstants();
        generate_entity_overview(
            $ext,
            $extensionFunctions,
            $extensionConstants,
            'https://www.php.net/manual/en/function.'
        );
    }
    ?>

</main>

<footer>
    <?= Chitch\foot() ?>
</footer>
