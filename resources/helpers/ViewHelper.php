<?php
namespace App\Helpers;

/**
 * Helpers for views
 * 
 * This class contains only static methods.
 */
class ViewHelper
{
    /**
     * Adapts the data of a $_POST array for a checkbox.
     * Note: If a checkbox is not checked the field is missing in $_POST.
     * And the field value is changed into 1 (true) or 0 (false): BOOL in MySql dbs are tiny int.
     * @param array $fields the array with the $_POST / $_GET variables
     * @param string $name the fieldname of the checkbox
     */
    public static function adaptCheckbox(array &$fields, string $name)
    {
        if (!array_key_exists($name, $fields)) {
            $fields[$name] = 0;
        } else {
            $fields[$name] = 1;
        }
    }
    /**
     * Adds a SQL condition "compared to FIELD" for filtering records.
     * @param array $conditions IN/OUT: the new condition is put to that list
     * @param array $parameters IN/OUT: the named sql parameters (":value")
     * @param string $column the column name
     * @param string $filterField name of the filter field (HTML input field). If null $column is taken
     * @param string $operator the comparison operator: "=", ">", ">=", "<", "<=", "!="
     * @param string $ignoreValue if the filter value has that value no condition is created
     */
    public static function addConditionComparism(
        array &$conditions,
        array &$parameters,
        string $column,
        ?string $filterField = null,
        string $operator = "=",
        $ignoreValue = '-'
    ) {
        $filterField ??= $column;
        $value = array_key_exists($filterField, $_POST) ? $_POST[$filterField] : '';
        if ($value !== '' && $value !== $ignoreValue) {
            if (strpos($column, '.') === false) {
                array_push($conditions, "`$column`=:$filterField");
            } else {
                $parts = explode('.', $column);
                array_push($conditions, "$parts[0].`$parts[1]`=:$filterField");
            }
            $parameters[":$filterField"] = $value;
        }
    }
    /**
     * Adds a SQL condition "a field compared to a const" for filtering records.
     * @param array $conditions IN/OUT: the new condition is put to that list
     * @param array $parameters IN/OUT: the named sql parameters (":value")
     * @param string $column the column name
     * @param mixed $value the value to compare
     * @param string $operator the comparison operator: "=", ">", ">=", "<", "<=", "!="
     */
    public static function addConditionConstComparison(
        array &$conditions,
        array &$parameters,
        string $column,
        $value,
        string $operator = "=",
    ) {
        if (strpos($column, '.') === false) {
            $column = "`$column`";
        }
        if ($value === null) {
            if ($operator === '=') {
                array_push($conditions, "$column IS NULL");
            } else {
                array_push($conditions, "$column IS NOT NULL");
            }
        } else {
            $condition = gettype($value) === 'string' ? "$column$operator'$value'" : "$column$operator$value";
            array_push($conditions, $condition);
        }
    }
    /**
     * Adds a SQL condition that finds a value in a list.
     * Example: the list (value of the column): ",77,99,123," the value:  "99"
     * @param array $conditions IN/OUT: the new condition is put to that list
     * @param array $parameters IN/OUT: the named sql parameters (":value")
     * @param string $column the column with a $separator separated list of values
     * @param mixed $value the value to find
     * @param string $separator the separator between two entries in the $column
     */
    public static function addConditionFindInList(
        array &$conditions,
        array &$parameters,
        string $column,
        $value,
        string $separator = ",",
    ) {
        if ($value != null) {
            if (strpos($column, '.') === false) {
                $column = "`$column`";
            }
            $condition = "$column like '%$separator$value$separator%'";
            array_push($conditions, $condition);
        }
    }
    /**
     * Adds a SQL condition "from until to" for filtering records.
     * @param array $conditions IN/OUT: the new condition is put to that list
     * @param array $parameters IN/OUT: the named sql parameters (":value")
     * @param string $fromField the field name of the dattime lower bound.
     * @param string $toField the field name of the dattime upper bound.
     * @param string $column the name of the column to compare 
     */
    public static function addConditionDateTimeRange(array &$conditions, array &$parameters, string $fromField, string $toField, string $column)
    {
        $from = array_key_exists($fromField, $_POST) ? $_POST[$fromField] : '';
        $to = array_key_exists($toField, $_POST) ? $_POST[$toField] : '';
        if ($to !== '' && strpos($to, ':') === false) {
            $to .= ' 23:59:59';
        }
        $isValidFrom = $from !== '';
        $isValidTo = $to !== '';
        if ($isValidFrom or $isValidTo) {
            if ($isValidFrom && !$isValidTo) {
                array_push($conditions, "`$column`>=?");
                array_push($parameters, $from);
            } elseif (!$isValidFrom && $isValidTo) {
                array_push($conditions, "`$column`<=?");
                array_push($parameters, $to);
            } else {
                array_push($conditions, "(`$column`>=? AND `$column`<=?)");
                array_push($parameters, $from);
                array_push($parameters, $to);
            }
        }
    }
    /**
     * Adds a SQL condition "like a FIELD" for filtering records.
     * @param array $conditions IN/OUT: the new condition is put to that list
     * @param array $parameters IN/OUT: the named sql parameters (":value")
     * @param string $column the column name. May be a comma separated list of columns.
     *   In this case each of the columns will be compared and combined with the OR operator
     * @param string $filterField name of the filter field (HTML input field). If null $column is taken
     */
    public static function addConditionPattern(array &$conditions, array &$parameters, string $column, ?string $filterField = null)
    {
        $filterField ??= $column;
        $value = array_key_exists($filterField, $_POST) ? $_POST[$filterField] : '';
        if (!empty($value) && $value !== "*") {
            $value = str_replace('*', '%', $value) . '%';
            $value = str_replace('%%', '%', $value);
            $value = str_replace('?', '_', $value);
            if ($value !== '%') {
                $fields = explode(',', $column);
                $conditions2 = [];
                $no = 0;
                foreach ($fields as $field) {
                    $no++;
                    if (strpos($field, '.') === false) {
                        array_push($conditions2, "`$field` like :$field$no");
                        $parameters[":$field$no"] = $value;
                    } else {
                        $names = explode('.', $field);
                        $table = $names[0];
                        $field = $names[1];
                        array_push($conditions2, "$table.`$field` like :$field$no");
                        $parameters[":$field$no"] = $value;
                    }
                }
                array_push($conditions, '(' . implode(" OR ", $conditions2) . ')');
            }
        }
    }
    /**
     * Appends a field to a field list if that field is not in the list.
     * @param array $fields IN/OUT: the field list
     * @param string $name the field name
     * @param string $value the field value
     */
    public static function addFieldIfMissing(array &$fields, string $name, ?string $value)
    {
        if (!array_key_exists($name, $fields)) {
            $fields[$name] = $value;
        }
    }

    /**
     * Builds the HTML text of the entries ("options") of a combobox ("selection").
     * 
     * @param array $texts the list with then texts of the entries
     * @param NULL|array $value null: $texts is used. Otherwise the list with the values of the entries
     * @param string $selected '' or the value of the selected entry (from the request)
     * @param NULL|string $textUndefined null: no additional entry.
     *   Otherwise: an entry is added as first entry with that text and the value ''
     * @return array a list of combobox entries, e.g. [['text' => 'x', 'value' => 'y', 'active' => false], ...]
     */
    public static function buildEntriesOfCombobox(
        array $texts,
        ?array $values,
        string $selected = '',
        ?string $textUndefined = null,
        bool $translate = false
    ): array {
        if ($values == null) {
            $values = $texts;
        }
        if (count($texts) != count($values)) {
            $rc = [['text' => 'different length', 'value' => 1, 'active' => true]];
        } else {
            if ($textUndefined == null) {
                $rc = [];
            } else {
                if ($translate) {
                    $textUndefined = __($textUndefined);
                }
                $rc = [['text' => $textUndefined, 'value' => '', 'active' => $selected === '']];
            }
            for ($ix = 0; $ix < count($texts); $ix++) {
                $text = $texts[$ix];
                if ($translate) {
                    $text = __($text);
                }
                $text = htmlentities($text);
                $value = strval($values[$ix]);
                $sel = $value === $selected ? 'selected ' : '';
                array_push($rc, ['text' => $text, 'value' => $value, 'active' => $selected === $value]);
            }
        }
        return $rc;
    }
    /**
     * Converts fields with special data types into a usable form.
     * 
     * Example: datetime is delivered as "2024-03-02T00:40". The converted form is "2024-03-02 00:40"
     * 
     * @param array $list the list with the field data
     * @param array $dataFields a associative field with fieldname => datatype entries. Datatype: "datetime"
     */
    public static function adaptFieldValues(array &$list, array $dateFields)
    {
        foreach ($dateFields as $name => $type) {
            if (array_key_exists($name, $list)) {
                switch ($type) {
                    case 'datetime':
                        $list[$name] = str_replace('T', ' ', $list[$name]);
                        break;
                    default:
                        break;
                }
            }
        }
    }
    /**
     * Extracts the number of a numbered button.
     * @param array $fields the form fields
     * @param string $name the name of the numbered button
     * @return int|NULL NULL: no button found. Otherwise: the number of the button
     */
    public static function numberOfButton(array $fields, string $name): ?int
    {
        $rc = null;
        $fieldname = '_lknAction';
        if (array_key_exists($fieldname, $fields)) {
            $value = $fields[$fieldname];
            if (str_starts_with($value, $name)) {
                $rc = intval(substr($value, strlen($name) + 1));
            }
        }
        return $rc;
    }
}