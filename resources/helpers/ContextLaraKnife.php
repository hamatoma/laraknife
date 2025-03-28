<?php
namespace App\Helpers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;

/**
 * Helpers for views
 * 
 * This class contains only static methods.
 */
class ContextLaraKnife
{
    public ?array $fields;
    public Request $request;
    public ?Model $model;
    public ?Model $model2;
    public ?Model $model3;
    public int $currentNo;
    public ?array $callbacks;
    public array $snippets;
    public ?Role $role;

    public ?array $combobox;
    public static array $dayOfWeek = ['', 'Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
    public function __construct(Request $request, ?array $fields, ?Model $model = null)
    {
        $this->fields = $fields;
        $this->request = $request;
        $this->model = $model;
        $this->currentNo = 0;
        $this->callbacks = null;
        $this->callbackMethod = '';
        $this->snippets = [];
        $this->model2 = $this->model3 = null;
        $this->role = null;
        $this->combobox = null;
    }
    /**
     * Convert a database date-time string to a date string.
     * 
     * @param string $dbDateTime    The date-time string from the database.
     * @param bool $positionDayOfWeek    The position of the day of week relative to the date: "<" before, ">" after, null none.
     * @return string the standard date string with/without the day of week.
     */
    public function asDateString(string $dbDateTime, ?string $positionDayOfWeek = null): string
    {
        if ($positionDayOfWeek != null) {
            $date = date_create_from_format('Y-m-d H:i:s', $dbDateTime);
            $dayEnglish = ContextLaraKnife::$dayOfWeek[$date->format('w')];
            $day = __($dayEnglish);
            $rc = $date->format('d.m.Y');
            $rc = $positionDayOfWeek === '<' ? "$day $rc" : "$rc $day";
        } else {
            $parts = explode(' ', $dbDateTime);
            $partsDate = explode('-', $parts[0]);
            $rc = "$partsDate[2].$partsDate[1].$partsDate[0]";
        }
        return $rc;
    }
    /**
     * Convert a database date-time string to a standard date and time string.
     * 
     * @param string $dbDateTime    The date-time string from the database.
     * @param bool $withSeconds     true: include the seconds in the time.
     * @param bool $positionDayOfWeek    The position of the day of week relative to the date: "<" before, ">" after, null none.
     * @return string the standard date string with/without the day of week.
     */
    public function asDateTimeString(string $dbDateTime, bool $withSeconds = false, $positionDayOfWeek = null): string
    {
        if ($positionDayOfWeek != null) {
            $date = date_create_from_format('Y-m-d H:i:s', $dbDateTime);
            $dayEnglish = ContextLaraKnife::$dayOfWeek[$date->format('w')];
            $day = __($dayEnglish);
            $rc = $date->format($withSeconds ? 'd.m.Y H:i:s' : 'd.m.Y H:i');
            $rc = $positionDayOfWeek === '<' ? "$day $rc" : "$rc $day";
        } else {
            $parts = explode(' ', $dbDateTime);
            $partsDate = explode('-', $parts[0]);
            $time = $withSeconds ? $parts[1] : substr($parts[1], 0, 5);
            $rc = "$partsDate[2].$partsDate[1].$partsDate[0] $time";
        }
        return $rc;
    }
    /**
     * Convert a day number to a day of week string.
     * 
     * @param int $dayNo    The day number (1..7).
     * @return string the day of week string (translated).
     */
    public static function asDayOfWeek(int $dayNo): string
    {
        $dayEnglish = ContextLaraKnife::$dayOfWeek[$dayNo];
        $rc = __($dayEnglish);
        return $rc;
    }
    public function asDuration(int $duration): string
    {
        $rc = sprintf("%02d:%02d", intval($duration / 60), $duration % 60);
        return $rc;
    }

    public function buildFileLink(string $node, string $relPath, string $label = null): string
    {
        $label ??= $node;
        $rc = "<a href=\"/$relPath/$node\">$label</a>";
        return $rc;
    }
    public function callback(string $name, $data)
    {
        $rc = null;
        if ($this->callbacks != null && array_key_exists($name, $this->callbacks)) {
            $item = $this->callbacks[$name];
            $object = $item[0];
            $method = $item[1];
            $rc = $object->$method($data);
        }
        return $rc;
    }
    public function combobox(): ?array
    {
        return $this->combobox;
    }
    protected function findRole()
    {
        if ($this->role == null) {
            $user = Auth::user();
            $this->role = Role::find($user->role_id);
        }
    }
    public function hasRole(int $priority): bool
    {
        $this->findRole();
        $rc = $this->role->priority <= $priority;
        return $rc;
    }
    public function isAdmin(): bool
    {
        $this->findRole();
        $rc = $this->role->priority <= 19;
        return $rc;
    }
    public function isAdminOrOwner(int $id = null): bool
    {
        $rc = $this->isAdmin();
        if (!$rc && $id != null) {
            $rc = $id === auth()->user()->id;
        }
        return $rc;
    }
    public function readonlyUnlessOwner(int $id = null): string
    {
        if ($id == null && $this->model instanceof User) {
            $id = $this->model->id;
        }
        $isOwner = $this->isAdmin() || ($id != null && $id === auth()->user()->id);
        $rc = $isOwner ? '' : 'readonly';
        return $rc;

    }
    public function text(string $text): string
    {
        return $text;
    }
    public function taskHelper(string $task, $parameter = null)
    {
        return TaskHelper::contextHelper($task, $parameter);
    }
    public function valueOf(string $name, bool $preferFields = false)
    {
        if (!$preferFields && $this->model != null && $this->model->$name !== null) {
            $rc = old($name, $this->model[$name]);
        } else {
            if ($this->fields == null) {
                $this->fields = $this->request->all();
            }
            $rc = array_key_exists($name, $this->fields) ? old($name, $this->fields[$name]) : '';
        }
        return $rc;
    }
    public function currentNo(): int
    {
        return ++$this->currentNo;
    }
    public function getSnippet(string $key): string
    {
        $rc = array_key_exists($key, $this->snippets) ? $this->snippets[$key] : '';
        return $rc;
    }
    public function setCallback(string $name, $object, string $method)
    {
        $this->callbacks ??= [];
        $this->callbacks[$name] = [$object, $method];
    }
    public function setCombobox(string $name, string $label, array &$options, string $attribute = '', string $class = '')
    {
        $this->combobox = ['name' => $name, 'label' => $label, 'opt' => $options, 'attr' => $attribute, 'class' => $class];
    }
    public function setSnippet(string $key, string $value)
    {
        $this->snippets[$key] = $value;
    }
    public function urlStorage(): string
    {
        $rc = '/upload';
        return $rc;
    }
}
