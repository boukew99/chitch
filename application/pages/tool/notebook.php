<?php

declare(strict_types=1);

/**
 * https://www.php.net/manual/en/function.eval.php
 */

require_once('../../library/bootstrap.php');



// Function to render inline code blocks
function renderCodeBlock($code)
{
    global $array, $variable;
    // Build output string
    $result = '';

    // Pretty-print the raw code
    // tokenize($code)
    $result .= '<pre><code>' . Chitch\tokenize("<?php $code") . '</code></pre>';

    #eval($code);
    // Capture and evaluate the output
    ob_start();
    eval($code);
    $output = ob_get_clean();

    // Add the output
    $result .= $output ? '<pre class="output">' . print_r($output, true) . '</pre>' : '';

    return $result;
};
echo Chitch\head();
?>
<style>
    .output::before {
        content: '> '
    }

    .T_OPEN_TAG {
        display: none;
    }
</style>
<link rel="stylesheet" href="styles/code.css" />
<header>
    <h1>PHP Notebook</h1>
    <p>How does PHP work with outputs after each step! This is live code (on the server) and can be edited in the source code.
</header>
<main>
    <section id="hello-world">
        <h2>Hello World</h2>
        <blockquote>PHP has the shortest hello world <cite><a href="https://www.youtube.com/watch?v=nmD1Q4FsXCc&ab_channel=WeAreDevelopers">Rasmus Lerdorf 2019</a></cite></blockquote>
        <p>PHP started its life as a template language. Thus it is geared towards outputting HTML. This means it has a very simple hello world, which is just a file with 'Hello world' in it!
        <pre><code>Hello World</code></pre>
        <pre class="output">Hello World</pre>
        <p>All the functionality of PHP is denoted between its tags <code>&lt;?php</code> and <code>?&gt;</code>. The PHP interpreter will execute all code between these tags.
        <p><a href="https://www.php.net/manual/phpfi2.php#history">You can read more of PHP's history</a> and how it started.

    </section>
    <section id="constructs">
        <h2>Language Constructs</h2>
        <p><a href="Expressions are the most important building blocks of PHP.">The most important building block in PHP is the expression</a>. Here we lean completely on this building block. An expression is anything that returns a value. Which means all expressions are by nature observable. This is the key to making software visible and combined with the implicit output or automatic flushing in PHP, makes for a system with rich feedback.

        <p><a href="https://www.php.net/manual/en/language.expressions.php#:~:text=The%20most%20basic%20forms%20of%20expressions%20are%20constants%20and%20variables">The most basic forms of expressions are constants and variables</a>.
            <?= renderCodeBlock(<<<PHP
                        \$variable = 'constant';
                        PHP) ?>
        <p>Here <code>'constant'</code> is a constant (string). It is assigned with the equal character (<code>=</code>) to <code>$variable</code>, which is a variable, denoted with <code>$</code>. This is assignment is also an expression by itself, so it returns a value, which can be printed with <code>print_r</code>, which prints human-readable information about a variable.
            <?= renderCodeBlock(<<<PHP
                        print_r(\$variable = 'constant');
                        PHP) ?>
        <p>We can also assign the value of the assingment again to another variable, which will hold the same value again. It assigns the value of the assignment to the next assignment which value is outputted.
            <?= renderCodeBlock(<<<PHP
                            print_r(\$variable2 = (\$variable = 'constant'));
                            PHP) ?>
        <p>Now the value <code>'constant'</code>, which is a string, is stored in <code>$variable</code>. This means we can recall it later.
            <?= renderCodeBlock(<<<PHP
                        print_r(\$variable);
                        PHP); ?>
        <p><a href="https://www.php.net/manual/en/language.expressions.php#:~:text=PHP%20supports%20four%20scalar%20value%20types">PHP supports four scalar value types</a> for constants, which are <a href="https://www.php.net/manual/en/language.types.integer.php">int</a>, <a href="https://www.php.net/manual/en/language.types.float.php">float</a>, <a href="https://www.php.net/manual/en/language.types.string.php">string</a> and <a href="https://www.php.net/manual/en/language.types.boolean.php">bool</a>. These form the basic elements to create other types by composition. An <a href="https://www.php.net/manual/en/language.types.array.php">array</a> is one such built-in composite type. Following we store the basic types in an array starting with the simplest:
            <?= renderCodeBlock(<<<PHP
                            print_r(
                                \$array = [
                                'bool' => true,
                                'int' => 1,
                                'float' => 1.0,
                                'string' => 'string',
                                ]
                            );
                            PHP) ?>

        <p><a href="https://www.php.net/manual/en/language.expressions.php#:~:text=PHP%20is%20an%20expression%2Doriented%20language">PHP is an expression-oriented language, almost everything is an expression</a>. Even incremention is an expression, which is done with <code>++</code>. You can control what the expression returns by <a href="https://www.php.net/manual/en/language.operators.increment.php">pre-incrementing or post-incrementing</a>. Here we do post-incrementation before pre-incrementation.
            <?= renderCodeBlock(<<<PHP
    \$number = 1;
    print_r(\$number++);
    print_r(++\$number);
    PHP) ?>

        <p><a href="https://www.php.net/manual/en/language.operators.comparison.php">Comparisons</a> use th <code>=</code> operator too but are not assingments. They test values against each and return a boolean value (either true or false) instead of the assigned value. You can make it an strict comparison by adding a second <code>=</code>, which helps with differing types.
            <?= renderCodeBlock(
                <<<PHP
    \$a = 0;
    \$b = 0;
    \$c = false;
    print_r([
        \$a == \$b,
        \$a == \$c,
        \$a === \$c
    ]);
    PHP
            ) ?>

            <?= renderCodeBlock(
                <<<PHP
    \$up = strtolower(\$upper = strtoupper(\$trimmed = trim('  HeLLo WorLD
      ')));
    print_r([\$up, \$upper, \$trimmed]);
    PHP
            ) ?>

            <?= renderCodeBlock(
                <<<PHP
        \$a = 5;
        print_r([
            \$a += 5,
            \$a -= 5,
            \$a *= 5,
            \$a /= 5
            ]);
        PHP
            ) ?>
            <? renderCodeBlock(
                <<<PHP
        print_r(true == true ? false == false : false == true );
        PHP
            ) ?>

            <?= renderCodeBlock(<<<PHP
    \$c = (\$a = 5) + (\$b = 10);
    print_r([\$a,\$b,\$c]);
    PHP) ?>
        <p>Then we could change the values in $array to uppercase for example.</p>
        <?= renderCodeBlock(
            <<<PHP
            \$array = array_map(
                fn(\$value) => strtoupper(\$value),
                \$array
            );
            print_r(\$array) ;
            PHP
        ) ?>

        <?= renderCodeBlock(
            <<<PHP
            \$array = ['apple', 'pear'];
            print_r(['cheese', ...\$array, 'drop']);
            PHP
        )
        ?>
    </section>
    <section id="style">
        <h2>Coding Style</h2>
        <p>Text has the quality to only say things once. This has been stated in code as Don't Repeat Yourself. One should recognize that substitution on its own is not enough to not Repeat Yourself. You will just end up repeating the substitution. Here we focus on declaration. It is about the declaration expressing exactly your intent without getting lost.
    </section>


    <blockquote>PHP is Chill Like That 🏝️</blockquote>
    <section id="sanitization">
        <h2>Filters</h2>

        <ul>
            <?php
            foreach (filter_list() as $id => $filter) {
                echo '<li>' . $filter;
            }
            ?>
        </ul>
    </section>
</main>
<footer>
    <?= Chitch\foot() ?>
</footer>
