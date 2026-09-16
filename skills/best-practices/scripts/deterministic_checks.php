<?php
/**
 * deterministic_checks.php
 *
 * Run this BEFORE asking the skill to review a file. Feed both this
 * script's output and the target file to the LLM — it only has to use
 * judgment on the checklist items that actually require judgment (custom
 * rule-object logic, docblock quality, naming, whether a query constraint
 * belongs in the FormRequest vs the controller, etc).
 *
 * Usage:
 *     php deterministic_checks.php path/to/TaskController.php
 */

declare(strict_types=1);

final class Finding
{
    public function __construct(
        public readonly bool $passed,
        public readonly string $label,
        public readonly string $fix = '',
    ) {}
}

final class Report
{
    /** @var Finding[] */
    private array $findings = [];

    public function add(bool $passed, string $label, string $fix = ''): void
    {
        $this->findings[] = new Finding($passed, $label, $fix);
    }

    public function render(): string
    {
        $lines = ['## Deterministic pre-check (no LLM judgment involved)', ''];
        $passedCount = 0;
        foreach ($this->findings as $f) {
            $mark = $f->passed ? '✅' : '❌';
            $lines[] = "{$mark} {$f->label}";
            if (!$f->passed && $f->fix !== '') {
                $lines[] = "   Fix: {$f->fix}";
            }
            if ($f->passed) {
                $passedCount++;
            }
        }
        $lines[] = '';
        $lines[] = '---';
        $lines[] = sprintf('Result: %d passed / %d checked', $passedCount, count($this->findings));
        return implode("\n", $lines);
    }

    public function exitCode(): int
    {
        foreach ($this->findings as $f) {
            if (!$f->passed) {
                return 1;
            }
        }
        return 0;
    }
}

/**
 * Extract top-level `'field' => 'rule|rule2|rule3'` entries from the first
 * rules()-shaped array found in the source (a `rules()` method body, or an
 * inline `$request->validate([...])` call). Heuristic, not a real parser.
 */
function extractValidationRules(string $source): array
{
    $rules = [];

    // rules() method: public function rules(): array { return [ ... ]; }
    if (preg_match('/function\s+rules\s*\([^)]*\)[^{]*\{(.*)/s', $source, $m)) {
        $body = extractBalancedBlock($m[1]);
        $rules = array_merge($rules, extractArrayPairs($body));
    }

    // inline $request->validate([ ... ])
    if (preg_match_all('/->validate\s*\(\s*\[(.*?)\]\s*\)/s', $source, $matches)) {
        foreach ($matches[1] as $block) {
            $rules = array_merge($rules, extractArrayPairs($block));
        }
    }

    return $rules;
}

/** Given text starting just after an opening `{`, return the matching balanced block. */
function extractBalancedBlock(string $text): string
{
    $depth = 1;
    $len = strlen($text);
    for ($i = 0; $i < $len; $i++) {
        if ($text[$i] === '{') {
            $depth++;
        } elseif ($text[$i] === '}') {
            $depth--;
            if ($depth === 0) {
                return substr($text, 0, $i);
            }
        }
    }
    return $text;
}

/**
 * Pull `'key' => 'rule|rule2'` OR `'key' => ['rule', 'rule2']` pairs out of
 * an array body, normalising the array form to a pipe-delimited string so
 * both look the same to the caller.
 */
function extractArrayPairs(string $body): array
{
    $pairs = [];

    // 'key' => 'rule|rule2'
    if (preg_match_all("/['\"]([a-zA-Z0-9_.]+)['\"]\s*=>\s*['\"]([^'\"]*)['\"]/", $body, $m, PREG_SET_ORDER)) {
        foreach ($m as $match) {
            $pairs[$match[1]] = $match[2];
        }
    }

    // 'key' => ['rule', 'rule2', ...]
    if (preg_match_all("/['\"]([a-zA-Z0-9_.]+)['\"]\s*=>\s*\[([^\]]*)\]/", $body, $m, PREG_SET_ORDER)) {
        foreach ($m as $match) {
            $items = [];
            if (preg_match_all("/['\"]([^'\"]*)['\"]/", $match[2], $im)) {
                $items = $im[1];
            }
            if (!empty($items)) {
                $pairs[$match[1]] = implode('|', $items);
            }
        }
    }

    return $pairs;
}

function checkValidationRuleConstraints(string $source, Report $report): void
{
    $rules = extractValidationRules($source);
    if (empty($rules)) {
        return;
    }

    $constraintTokens = ['min:', 'max:', 'between:', 'digits:', 'exists:', 'size:', 'in:'];

    foreach ($rules as $field => $ruleString) {
        $isNumericLike = preg_match('/\b(integer|numeric)\b/', $ruleString) === 1;
        if (!$isNumericLike) {
            continue;
        }
        $hasConstraint = false;
        foreach ($constraintTokens as $token) {
            if (str_contains($ruleString, $token)) {
                $hasConstraint = true;
                break;
            }
        }
        if ($hasConstraint) {
            $report->add(true, "`{$field}` rule declares a bound alongside its type");
        } else {
            $report->add(
                false,
                "`{$field}` is `{$ruleString}` with no bound",
                "Add a bound to `{$field}`'s rule, e.g. `integer|min:1` or `integer|exists:table,column`.",
            );
        }
    }
}

function checkHardcodedStringDefaults(string $source, Report $report): void
{
    // Typed/untyped property defaults: protected $name = 'Something Real';
    if (preg_match_all(
        '/(?:public|protected|private)\s+(?:\??[A-Za-z0-9_\\\\]+\s+)?\$([A-Za-z_][A-Za-z0-9_]*)\s*=\s*[\'"]([^\'"]+)[\'"]\s*;/',
        $source,
        $m,
        PREG_SET_ORDER,
    )) {
        foreach ($m as $match) {
            [$full, $prop, $literal] = $match;
            if (looksLikeRealData($literal)) {
                $report->add(
                    false,
                    "`\${$prop}` defaults to a hardcoded literal: " . var_export($literal, true),
                    "Move " . var_export($literal, true) . " to config()/.env or a named constant; reference it instead of hardcoding it on the property.",
                );
            } else {
                $report->add(true, "`\${$prop}` default looks like a placeholder, not hardcoded data");
            }
        }
    }
}

function looksLikeRealData(string $literal): bool
{
    // Skip things that look like validation rule strings (pipe-delimited).
    if (str_contains($literal, '|')) {
        return false;
    }
    return (str_contains($literal, ' ') || str_contains($literal, '-')) && strlen($literal) > 3;
}

function checkBroadCatch(string $source, Report $report): void
{
    if (preg_match_all('/catch\s*\(\s*\\\\?(Exception|Throwable)\s+\$[A-Za-z_][A-Za-z0-9_]*\s*\)\s*\{(.*?)\n\s*\}/s', $source, $m, PREG_SET_ORDER)) {
        foreach ($m as $match) {
            $body = $match[2];
            $handled = preg_match('/\bthrow\b|Log::|report\(/', $body) === 1;
            if ($handled) {
                $report->add(true, "`catch ({$match[1]} \$e)` re-throws or logs instead of swallowing it");
            } else {
                $report->add(
                    false,
                    "`catch ({$match[1]} \$e)` swallows the error with no rethrow/log",
                    "Catch a specific exception type, or `throw`/`Log::error(...)`/`report(\$e)` inside the broad catch instead of silently continuing.",
                );
            }
        }
    } else {
        $report->add(true, 'No overly broad `catch (\\Exception)` / `catch (\\Throwable)` clauses');
    }
}

function checkDebugCalls(string $source, Report $report): void
{
    if (preg_match('/\b(dd|dump|var_dump|print_r)\s*\(/', $source, $m)) {
        $report->add(false, 'No debug output left in the file', "Replace `{$m[1]}(...)` with a `Log::` call, or remove it entirely.");
    } else {
        $report->add(true, 'No `dd()`/`dump()`/`var_dump()`/`print_r()` left in the file');
    }
}

/** Split the file into `public function name(...) { ... }` blocks with their names. */
function extractMethods(string $source): array
{
    $methods = [];
    if (preg_match_all('/(?:public|protected)\s+function\s+([A-Za-z_][A-Za-z0-9_]*)\s*\([^)]*\)[^{]*\{/', $source, $m, PREG_OFFSET_CAPTURE)) {
        foreach ($m[0] as $i => $match) {
            [$decl, $offset] = $match;
            $name = $m[1][$i][0];
            $bodyStart = $offset + strlen($decl);
            $body = extractBalancedBlock(substr($source, $bodyStart));
            $methods[] = ['name' => $name, 'body' => $body];
        }
    }
    return $methods;
}

function checkControllerActions(string $source, Report $report): void
{
    if (!preg_match('/class\s+\w+\s+extends\s+Controller/', $source)) {
        return;
    }

    $resourceMethods = ['index', 'show', 'store', 'update'];
    foreach (extractMethods($source) as $method) {
        $name = $method['name'];
        $body = $method['body'];

        if (in_array($name, $resourceMethods, true)) {
            $usesResource = preg_match('/[A-Za-z_]+Resource::(make|collection)\s*\(/', $body) === 1;
            if ($usesResource) {
                $report->add(true, "`{$name}()` shapes its response through an API Resource");
            } else {
                $report->add(
                    false,
                    "`{$name}()` does not return an API Resource",
                    "Wrap the returned model/collection in `SomeResource::make(...)` or `SomeResource::collection(...)` instead of returning it raw.",
                );
            }
        }

        if ($name === 'store') {
            $has201 = preg_match('/\b201\b|->created\s*\(/', $body) === 1;
            if ($has201) {
                $report->add(true, '`store()` returns `201` on success');
            } else {
                $report->add(
                    false,
                    '`store()` does not return `201` on success',
                    "Add `201` to the response, e.g. `response()->json(TaskResource::make(\$created), 201)`.",
                );
            }
        }
    }
}

function main(array $argv): int
{
    if (count($argv) !== 2) {
        fwrite(STDOUT, "Usage: php deterministic_checks.php path/to/file.php\n");
        return 2;
    }

    $path = $argv[1];
    $source = file_get_contents($path);
    if ($source === false) {
        fwrite(STDERR, "Could not read {$path}\n");
        return 2;
    }

    $report = new Report();

    checkValidationRuleConstraints($source, $report);
    checkHardcodedStringDefaults($source, $report);
    checkControllerActions($source, $report);
    checkBroadCatch($source, $report);
    checkDebugCalls($source, $report);

    echo $report->render() . "\n";
    return $report->exitCode();
}

exit(main($argv));
