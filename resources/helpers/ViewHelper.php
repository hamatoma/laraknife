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
     * Adds a SQL condition "compared to FIELD" for filtering records.
     * @param array $conditions IN/OUT: the new condition is put to that list
     * @param array $parameters IN/OUT: the named sql parameters (":value")
     * @param string $column the column name
     * @param string $filterField name of the filter field (HTML input field). If null $column is taken
     * @param string $operator the comparison operator: "=", ">", ">=", "<", "<=", "!="
     * @param string $ignoreValue if the filter value has that value no condition is created
     */
    public static function addConditionComparism(array &$conditions, array &$parameters, string $column,
        ?string $filterField = null, string $operator = "=", $ignoreValue = '-')
    {
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
     * Appends a field to a field list if that field is not in the list.
     * @param array $fields IN/OUT: the field list
     * @param string $name the field name
     * @param string $value the field value
     */
    public static function addFieldIfMissing(array &$fields, string $name, ?string $value){
        if (! array_key_exists($name, $fields)){
            $fields[$name] = $value;
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
     * Builds the HTML text of the entries ("options") of a combobox ("selection").
     * 
     * @param array $texts the list with then texts of the entries
     * @param NULL|array $value null: $texts is used. Otherwise the list with the values of the entries
     * @param string $selected '' or the value of the selected entry (from the request)
     * @param NULL|string $textUndefined null: no additional entry.
     *   Otherwise: an entry is added as first entry with that text and the value ''
     * @return array a list of combobox entries, e.g. [['text' => 'x', 'value' => 'y', 'active' => false], ...]
     */
    public static function buildEntriesOfCombobox(array $texts, ?array $values, string $selected = '',
        ?string $textUndefined = null, bool $translate = false): array
    {
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
     * Extracts the number of a numbered button.
     * @param array $fields the form fields
     * @param string $name the name of the numbered button
     * @return int|NULL NULL: no button found. Otherwise: the number of the button
     */
    public static function numberOfButton(array $fields, string $name): ?int{
        $rc = null;
        $fieldname = '_lknAction';
        if (array_key_exists($fieldname, $fields)){
            $value =  $fields[$fieldname];
            if (str_starts_with($value, $name)){
                $rc = intval(substr($value, strlen($name) + 1));
            }
        }
        return $rc;
    }
}