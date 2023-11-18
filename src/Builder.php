<?php
namespace Hamatoma\Laraknife;
if (is_dir(__DIR__ . '/../vendor')){
    require __DIR__ . '/../vendor/autoload.php';
} else {
    require __DIR__ . '/../autoload.php';
}

use Hamatoma\Laraknife\Helper;
use Hamatoma\Laraknife\OsHelper;

$VERSION = '2023.11.18';
class Builder
{
    protected array $lines = [];
    protected int $ixLines = -1;
    protected ?string $currentLine = null;
    protected string $tablename = '';
    protected ?string $module = null;
    protected string $tableCapital = '';
    protected string $moduleCapital = '';
    protected array $fields = [];
    protected ?string $comment = null;
    public bool $force = false;
    public function createModule(string $templates, string $views, string $controllers, string $models)
    {
        $module = $this->module;
        $moduleCapital = Helper::toCapital($module);
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
            $again = preg_match('!^\s*//!', $this->currentLine);
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
            if ($this->module == null){
                $this->setModule(preg_replace('/s$/', '', $this->tablename));
            }
            $this->tableCapital = Helper::toCapital($this->tablename);
            $this->moduleCapital = Helper::toCapital($this->module);
            echo "Table: $this->tablename\n";
            while (($line = $this->nextLine(true)) != null) {
                if (preg_match("/table->(\\w+)\\([\"'](\\w+)(.*)/", $line, $match)) {
                    $fieldname = $match[2];
                    $this->fields[$fieldname] = new FieldInfo(
                        $fieldname,
                        $match[1],
                        $match[3],
                        $this->comment != null && strpos($this->comment, 'spropert') !== false
                    );
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
    public function setModule(?string $module){
        if ($module != null && $this->module == null){
            $this->module = strtolower($module);
            $this->moduleCapital = Helper::toCapital($this->module);
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
    public function writeTemplateToFile(string $source, string $target)
    {
        $parent = dirname($target);
        if (file_exists($target) && ! $this->force){
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
                if ($line === ''){
                    array_push($output, '');
                    continue;
                } elseif ($line == null) {
                    break;
                }
                if (!str_starts_with($line, '##FIELDS##')) {
                    array_push($output, $this->replaceVariables($line));
                } else {
                    $block = [];
                    $start = 1 + $this->ixLines;
                    while (($line = $this->nextLine()) != null) {
                        if (!str_starts_with($line, '##END.FIELDS##')) {
                            array_push($block, $this->replaceVariables($line));
                        } else {
                            foreach ($this->fields as $name => $field) {
                                foreach ($block as $line2) {
                                    array_push($output, $field->replaceVariables($line2));
                                }
                            }
                            break;
                        }
                    }
                    if ($line == null){
                        $this->error("missing ##END.FIELDS##. Start in $source-$start");
                        break;
                    }
                }
            } while (true);
            $contents = implode("\n", $output);
            file_put_contents($target, $contents);
            echo "= written $target\n";
        }
    }
}

class FieldInfo
{
    public string $name;
    public string $nameCapital;
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
        $this->nameCapital = Helper::toCapital($fieldname);
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
            case 'longtext':
                $this->multiline = true;
                $this->type = 'text';
                break;
            case 'decimal':
            case 'float':
            case 'double':
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
        return $line;
    }
}

function parseArguments(array $argv, string $definition, array &$arguments, array &$options): bool
{
    $rc = true;
    $parameters = [];
    foreach(explode(' ', $definition) as $item){
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
    $argv = [
        'dummy',
        'create:module',
        '/home/ws/php/laraknife/scripts/data/migration_test.php',
        '--force',
        '--module=noun',
        '--templates=/home/ws/php/laraknife/templates/builder',
        '--views=/tmp/unittest',
        '--controllers=/tmp/unittest',
        '--models=/tmp/unittest'
    ];
    if (count($argv) < 3) {
        usage("missing argument");
    } else {
        $args = [];
        $options = [
            'module' => null,
            'templates' => 'templates/builder',
            'views' => 'resources/views',
            'controllers' => 'app/Http/Controllers',
            'models' => 'app/Models'
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
                    case 'create:module':
                        if (count($args) < 2) {
                            usage("missing FILE_MIGRATION");
                        } else {
                            $builder->setModule($options['module']);
                            $builder->readDefinition($args[1], );
                            $views = $options['views'];
                            $controllers = $options['controllers'];
                            $models = $options['models'];
                            $builder->createModule($options['templates'], $views, $controllers, $models);
                        }
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