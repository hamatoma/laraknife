<?php
namespace App\Helpers;

if (is_dir(__DIR__ . '/../vendor')) {
    require __DIR__ . '/../vendor/autoload.php';
} elseif (file_exists(__DIR__ . '/../../../../autoload.php')) {
    require __DIR__ . '/../../../../autoload.php';
    require_once 'StringHelper.php';
    require_once 'OsHelper.php';
} elseif (is_dir(__DIR__ . '/../../vendor')) {
    require __DIR__ . '/../../vendor/autoload.php';
    require_once 'StringHelper.php';
    require_once 'OsHelper.php';
} else {
    require __DIR__ . '/../autoload.php';
}

use App\Helpers\OsHelper;
use App\Helpers\StringHelper;

$VERSION = '2024.01.27';
class Builder
{
    protected array $lines = [];
    protected int $ixLines = -1;
    protected ?string $currentLine = null;
    protected string $tablename = '';
    protected ?string $module = null;
    protected string $tableCapital = '';
    protected string $moduleCapital = '';
    /// fieldname => fieldObject
    public array $fields = [];
    public ?FieldInfo $secondary = null;
    /// field objects:
    protected ?string $comment = null;
    public bool $force = false;
    public function createModule(string $templates, string $views, string $controllers, string $models)
    {
        $module = $this->module;
        $moduleCapital = StringHelper::toCapital($module);
        foreach (['create', 'edit', 'index', 'show'] as $task) {
            $source = OsHelper::joinPath($templates, "$task.blade.templ");
            $target = OsHelper::joinPaths([$views, $module, "$task.blade.php"]);
            $this->writeTemplateToFile($source, $target);
        }
        $controller = "{$moduleCapital}Controller.php";
        $model = "$moduleCapital.php";
        $source = OsHelper::joinPath($templates, 'controller.templ');
        $target = OsHelper::joinPath($controllers, $controller);
        $this->writeTemplateToFile($source, $target);
        $source = OsHelper::joinPath($templates, 'model.templ');
        $target = OsHelper::joinPath($models, $model);
        $this->writeTemplateToFile($source, $target);
    }
    public function error(string $message)
    {
        echo "+++ $message\n";
    }
    protected function nextLine(bool $skipComment = false): ?string
    {
        $this->comment = null;
        do {
            if ($this->ixLines >= count($this->lines) - 1) {
                $rc = $this->currentLine = null;
            } else {
                $rc = $this->currentLine = $this->lines[++$this->ixLines];
            }
            $again = $this->currentLine != null && preg_match('!^\s*//!', $this->currentLine);
            if ($again) {
                $this->comment = $this->currentLine;
            }
        } while ($again);
        return $rc;
    }

    public function readDefinition(string $filename)
    {
        $data = file_get_contents($filename);
        $this->lines = explode("\n", $data);
        $this->ixLines = 0;
        if (!$this->skipTo('Schema::create', false)) {
            $this->error("missing Schema::create in $filename");
        }
        $match = null;
        if (!preg_match("/create\\([\"']([^\"']+)/", $this->currentLine, $match)) {
            $this->error("cannot extract table in $this->currentLine\n");
        } else {
            $this->tablename = $match[1];
            if ($this->module == null) {
                $this->setModule(preg_replace('/s$/', '', $this->tablename));
            }
            $this->tableCapital = StringHelper::toCapital($this->tablename);
            $this->moduleCapital = StringHelper::toCapital($this->module);
            echo "Table: $this->tablename\n";
            while (($line = $this->nextLine(true)) != null) {
                if (preg_match("/table->(\\w+)\\([\"'](\\w+)(.*)/", $line, $match)) {
                    $fieldname = $match[2];
                    $field = new FieldInfo(
                        $fieldname,
                        $match[1],
                        $match[3],
                        $this->comment != null && strpos($this->comment, 'spropert') !== false
                    );
                    if ($fieldname !== 'id' && $this->secondary == null){
                        $this->secondary = $field;
                    }
                    array_push($this->fields, $field);
                } else if (strpos($this->currentLine, '});') !== false) {
                    break;
                }
            }
        }
        foreach ($this->fields as $name => $field) {
            $field->dump();
        }
    }
    protected function replaceVariables(string $line): string
    {
        $line = str_replace('#table#', $this->tablename, $line);
        $line = str_replace('#module#', $this->module, $line);
        $line = str_replace('#Module#', $this->moduleCapital, $line);
        $line = str_replace('#Table#', $this->tableCapital, $line);
        $line = str_replace('#TableSingular#', $this->moduleCapital, $line);
        return $line;
    }
    public function setModule(?string $module)
    {
        if ($module != null && $this->module == null) {
            $this->module = strtolower($module);
            $this->moduleCapital = StringHelper::toCapital($this->module);
            echo "module: $this->module\n";
        }
    }
    protected function skipTo(string $marker, bool $skipMarker = true): bool
    {
        $rc = false;
        while (!$rc && $this->ixLines + 1 < count($this->lines)) {
            $this->currentLine = $this->lines[++$this->ixLines];
            if (strpos($this->lines[$this->ixLines], $marker) !== false) {
                $rc = true;
                if ($skipMarker) {
                    $this->currentLine = $this->lines[$this->ixLines];
                } else {
                    --$this->ixLines;
                }
            }
            if ($rc) {
                break;
            }
        }
        return $rc;
    }
    public function updateLanguages(string $dirSources, string $fileTarget)
    {
        if (!is_dir($dirSources)) {
            $this->error("not a directory: $dirSources");
        } else {
            $summary = [];
            $parent = dirname($fileTarget);
            if (OsHelper::ensureDirectory($parent)) {
                foreach (scandir($dirSources) as $file) {
                    if (!str_ends_with($file, '.json')) {
                        continue;
                    }
                    $full = OsHelper::joinPath($dirSources, $file);
                    $contents = file_get_contents($full);
                    echo "read: $full\n";
                    $tree = json_decode($contents);
                    foreach ($tree as $key => $value) {
                        if (!array_key_exists($key, $summary)) {
                            $summary[$key] = $value;
                        } else if ($summary[$key] !== $value) {
                            $this->error("$file: $key has different values: \"$value\" != \"$summary[$key]\"");
                        }
                    }
                }
            }
            if (count($summary) == 0) {
                $this->error("no data found");
            } else {
                $keys = array_keys($summary);
                sort($keys);
                $sorted = [];
                foreach ($keys as $key) {
                    $sorted[$key] = $summary[$key];
                }
                $contents = json_encode($sorted, JSON_PRETTY_PRINT);
                file_put_contents($fileTarget, $contents);
                echo "written: $fileTarget\n";
            }
        }
    }
    public function writeTemplateToFile(string $source, string $target)
    {
        $parent = dirname($target);
        if (file_exists($target) && !$this->force) {
            $this->error("$target already exists. Use --force to overwrite");
        } elseif (!file_exists($source)) {
            $this->error("missing $source");
        } elseif (!OsHelper::ensureDirectory($parent)) {
            $this->error("cannot create $parent");
        } else {
            $this->lines = explode("\n", file_get_contents($source));
            $this->ixLines = -1;
            $output = [];
            $lastProcessed = 0;
            do {
                $line = $this->nextLine();
                if ($line === '') {
                    array_push($output, '');
                    continue;
                } elseif ($line == null) {
                    break;
                }
                $loopInfo = CaseInfo::startCase($line, $this);
                if ($loopInfo == null) {
                    array_push($output, $this->replaceVariables($line));
                } else {
                    $sizeOutput = count($output);
                    do {
                        $line = $this->nextLine();
                        if (str_starts_with($line, '##ON ') || $line === '##ELSE##') {
                            $loopInfo->handleBlock($output);
                            $loopInfo->setCondition($line);
                        } elseif (str_starts_with($line, '##END.CASE##')) {
                            $loopInfo->handleBlock($output);
                            break;
                        } else {
                            $loopInfo->addToBlock($this->replaceVariables($line));
                        }
                    } while (true);
                    $comma = count($output) > $sizeOutput ? ',' : '';
                    if ($sizeOutput > 0){
                        $output[$sizeOutput - 1] = str_replace('#Comma#', $comma, $output[$sizeOutput - 1]);
                    }
                }
            } while (true);
            $contents = implode("\n", $output);
            file_put_contents($target, $contents);
            echo "= written $target\n";
        }
    }
}

class CaseInfo
{
    public array $fields;
    public array $currentConditionFields;
    public int $index;
    public $lastField;
    public array $block;
    public Builder $builder;
    public function __construct(array $fields, Builder &$builder)
    {
        $this->index = -1;
        $this->fields = $fields;
        $this->lastField = $fields[count($fields) - 1];
        $this->block = [];
        $this->builder = $builder;
    }
    public function addToBlock(string $line)
    {
        array_push($this->block, $line);
    }
    public function handleBlock(array &$output)
    {
        if (count($this->block) > 0){
            foreach ($this->currentConditionFields as $field) {
                $this->index++;
                foreach ($this->block as $line) {
                    $line = $field->replaceVariables($line);
                    $line = $this->replaceVariables($line, $field);
                    array_push($output, $line);
                }
            }
            $this->block = [];
        }
        $this->currentConditionFields = [];
    }
    public function replaceVariables(string $line, FieldInfo $field): string
    {
        $line = str_replace('#ix#', strval($this->index), $line);
        $line = str_replace('#no#', strval($this->index + 1), $line);
        $line = str_replace('#comma#', $field === $this->lastField ? '' : ',', $line);
        return $line;
    }
    public function setCondition(string $line)
    {
        $match = null;
        if ($line === '##ELSE##') {
            $this->currentConditionFields = $this->fields;
            $this->fields = [];
        } else {
            if (preg_match('/^##ON nameLike\((.*?)\)##$/', $line, $match)) {
                $regEx = $match[1];
                foreach ($this->fields as $field) {
                    if (preg_match("/$regEx/", $field->name)) {
                        array_push($this->currentConditionFields, $field);
                    }
                }
            } elseif (preg_match('/^##ON typeLike\((.*?)\)##$/', $line, $match)) {
                $regEx = $match[1];
                foreach ($this->fields as $field) {
                    if (preg_match("/$regEx/", $field->type)) {
                        array_push($this->currentConditionFields, $field);
                    }
                }
            } elseif (preg_match('/^##ON isSecondary##$/', $line, $match)) {
                array_push($this->currentConditionFields, $this->builder->secondary);
            }
        }
        $count = count($this->currentConditionFields);
        foreach ($this->currentConditionFields as $field) {
            $ix = array_search($field, $this->fields);
            array_splice($this->fields, $ix, 1);
        }
        $this->lastField = $count == 0 ? null :  $this->currentConditionFields[$count - 1];
    }
    public static function startCase(string $line, Builder $builder): ?CaseInfo
    {
        $rc = null;
        if (str_starts_with($line, '##CASE(fields)##')) {
            $rc = new CaseInfo($builder->fields, $builder);
        }
        return $rc;
    }
}
class FieldInfo
{
    public string $name;
    public string $nameCapital;
    public string $baseName;
    public string $baseNameCapital;
    // string text reference number
    public string $type;
    public bool $multiline;
    /// syntax: <table>.<column>
    public ?string $reference;
    public function __construct(string $fieldname, string $type, string $parameters, bool $isSProperty = false)
    {
        $match = null;
        $this->multiline = false;
        $this->reference = null;
        $this->name = $fieldname;
        $this->nameCapital = StringHelper::toCapital($fieldname);
        $endLength = 0;
        if (str_ends_with($fieldname, '_id')) {
            $endLength = 3;
        } elseif (str_ends_with($fieldname, '_scope')) {
            $endLength = 6;
        }
        if ($endLength == 0) {
            $this->baseName = $fieldname;
        } else {
            $this->baseName = substr($fieldname, 0, strlen($fieldname) - $endLength);
        }
        $this->baseNameCapital = StringHelper::toCapital($this->baseName);
        switch ($type) {
            case 'binary':
            case 'date':
            case 'datetime':
            case 'time':
            case 'timestamp':
            case 'string':
                $this->type = $type;
                break;
            case 'text':
                $this->type = $type;
                $this->multiline = true;
                break;
            case 'longText':
                $this->multiline = true;
                $this->type = 'text';
                break;
            case 'decimal':
            case 'float':
            case 'double':
            case 'tinyInteger':
                $this->type = 'number';
                break;
            case 'integer':
                if ($isSProperty) {
                    $this->type = 'ref';
                    $this->reference = 'sproperties.id';
                } else {
                    $this->type = 'number';
                }
                break;
            case 'foreignId':
                $this->type = 'reference';
                // $table->foreignId('verifiedby')->references('id')->on('users')->nullable();
                if ($parameters != null && preg_match("/references.[\"']([^\"']+).*->on.[\"']([^\"']+)/", $parameters, $match)) {
                    $this->reference = $match[2] . '.' . $match[1];
                }
                break;
            default:
                throw new \Exception("unknown column type: $type'\n");
        }
    }
    public function dump()
    {
        $rest = $this->reference ?? null;
        echo $this->name . ": " . $this->type, " $rest\n";
    }
    public function inputType()
    {
        switch ($this->type) {
            case 'reference':
                $rc = 'combobox';
            default:
                $rc = $this->multiline ? 'bigtext' : 'text';
                break;
        }
        return $rc;
    }
    public function replaceVariables(string $line): string
    {
        $line = str_replace('#field#', $this->name, $line);
        $line = str_replace('#type#', $this->inputType(), $line);
        $line = str_replace('#Field#', $this->nameCapital, $line);
        $line = str_replace('#attribute#', $this->multiline ? 'rows="2" ' : '', $line);
        $line = str_replace('#position#', 'alone', $line);
        $line = str_replace('#base#', $this->baseName, $line);
        $line = str_replace('#Base#', $this->baseNameCapital, $line);
        return $line;
    }
}

function parseArguments(array $argv, string $definition, array &$arguments, array &$options): bool
{
    $rc = true;
    $parameters = [];
    foreach (explode(' ', $definition) as $item) {
        $parts = explode(':', $item);
        $parameters[$parts[0]] = $parts[1];
    }
    unset($argv[0]);
    foreach ($argv as $arg) {
        if (!str_starts_with($arg, '--')) {
            array_push($arguments, $arg);
        } else {
            $parts = explode('=', substr($arg, 2));
            $name = $parts[0];
            if (!array_key_exists($name, $parameters)) {
                usage("unknown option: $name");
                $rc = false;
                break;
            } elseif ($parameters[$name] === 'b') {
                $options[$name] = true;
            } elseif (count($parts) < 2) {
                usage("missing value of option $name");
                $rc = false;
                break;
            } else {
                $options[$name] = $parts[1];
            }
        }
    }
    return $rc;
}
function usage(string $message)
{
    echo "Usage:\n";
    echo "php builder.php create:module FILE_MIGRATION \n";
    echo "  [--module=MODULE] [--templates=DIRECTORY]\n";
    echo "  [--views=DIRECTORY] [--controllers=DIRECTORY]\n";
    echo "php builder.php update:languages DIR_SOURCE FILE_TARGET\n";
    echo "php builder.php version\n";
    echo "Examples:\n";
    echo "php builder.php create:module database/migrations/*articles*.php \\\n";
    echo "  --module=article --templates=builder/templates \\\n";
    echo "  --views=resources/views --controllers=app/Http/Controllers\n";
    echo "php builder.php version\n";
    echo "+++ $message\n";
}
function main()
{
    global $argv;
    //echo 'current dir: ', getcwd(), "\n";
    if (count($argv) > 1 && $argv[1] === '--test') {
        $base = realpath(__DIR__ . '/../..');
        $argv = [
            'dummy',
            'create:module',
            "$base/scripts/data/migration_test.php",
            '--force',
            "--templates=$base/templates/builder",
            '--views=/tmp/unittest',
            '--controllers=/tmp/unittest',
            '--models=/tmp/unittest'
        ];
    }
    $count = count($argv);
    if ($count < 1) {
        usage("missing argument: $count");
    } else {
        $args = [];
        $templates = is_dir('templates/builder') ? 'templates/builder' : 'vendor/hamatoma/laraknife/templates/builder';
        $options = [
            'module' => null,
            'templates' => $templates,
            'views' => 'resources/views',
            'controllers' => 'app/Http/Controllers',
            'models' => 'app/Models',
            'force' => false,
        ];
        if (parseArguments($argv, "module:s templates:s views:s controllers:s models:s force:b", $args, $options)) {
            $builder = new Builder();
            $builder->force = $options['force'];
            if (count($args) < 1) {
                usage("missing MODE {create:module | version}");
            } else {
                switch ($args[0]) {
                    case 'version':
                        global $VERSION;
                        echo $VERSION, "\n";
                    case 'update:languages':
                        if (count($args) < 3) {
                            usage("missing arguments (DIR_SOURCES FILE_TARGET)");
                        } else {
                            $builder->updateLanguages($args[1], $args[2]);
                        }
                        break;
                    case 'create:module':
                        if (count($args) < 2) {
                            usage("missing FILE_MIGRATION");
                        } else {
                            $builder->setModule($options['module']);
                            $builder->readDefinition($args[1]);
                            $views = $options['views'];
                            $controllers = $options['controllers'];
                            $models = $options['models'];
                            $builder->createModule($options['templates'], $views, $controllers, $models);
                        }
                        break;
                    case 'test:mini':
                        $builder->readDefinition('tests/data/demo.input.php');
                        $builder->writeTemplateToFile('tests/data/demo.templ', '/tmp/unittest/demo.out');
                        break;
                    case 'test:maxi':
                        $builder->readDefinition('tests/data/demo.input.php');
                        $controllers = $models = $views = '/tmp/unittest';
                        $builder->createModule($options['templates'], $views, $controllers, $models);
                        break;
                    default:
                        $builder->error("unknown MODE: $args[0]");
                        break;
                }
            }
        }
    }
}
main();
