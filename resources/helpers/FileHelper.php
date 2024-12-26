<?php
namespace App\Helpers;

use Illuminate\Http\Request;

class FileHelper
{
    /**
     * Builds a filename as two instances: an URL and a real filename.
     * 
     * The node starts with the username: that allows filtering.
     *  
     * @param string $name the basic part of the filename without extension
     * @param string $extension the file type, e.g. '.txt'
     * @return string the constructed filename e.g. '/srv/www/storage/export/jonny_pages_102383.txt'
     */
    public static function buildExportName(string $name, string $extension, bool $unique = true): string
    {
        $user = auth()->user()->name;
        $rc = $_SERVER['DOCUMENT_ROOT'] . "/export/$user.$name";
        if ($unique) {
            $rc .= '_' . strval(time() % 86400);
        }
        $rc .= $extension;
        return $rc;
    }

    /**
     * Builds the download link of an uploaded file.
     * @param string $filename the filename without path
     * @param string $date the date/time of the upload
     * @return string the relative link, e.g. "/upload/2024/02/113_london.jpg"
     */
    public static function buildFileLink(string $filename, string $date): string
    {
        $rc = '/upload/' . FileHelper::buildFileStoragePath($date) . '/' . $filename;
        return $rc;
    }
    /**
     * Builds the relative path for the upload file storage.
     * @param string|\DateTime $date the creation date of the file: defines the path name
     */
    public static function buildFileStoragePath($date): string
    {
        $date ??= new \DateTime();
        if ($date instanceof \DateTime) {
            $rc = $date->format('Y') . '/' . $date->format('m');
        } else {
            $parts = explode('-', $date, 3);
            $rc = $parts[0] . '/' . $parts[1];
        }
        return $rc;
    }
    public static function decodeUrl(string $text): string
    {
        $count = strlen($text);
        $rc = preg_replace_callback(
            '/%[0-9a-fA-F][0-9a-fA-F]/',
            function ($matches) {
                $cc = hexdec(substr($matches[0], 1));
                return chr($cc);
            },
            $text
        );
        return $rc;
    }
    public static function encodeUrl(string $text): string
    {
        $count = strlen($text);
        $rc = '';
        for ($ix = 0; $ix < $count; $ix++) {
            $cc = $text[$ix];
            if ($cc >= '@' && $cc <= 'Z' || $cc >= 'a' && $cc <= 'z' || strpos("0123456789_+-.", $cc) !== false) {
                $rc .= $cc;
            } else {
                $rc .= sprintf("%%%02X", ord($cc));
            }
        }
        return $rc;
    }
    /**
     * Deletes the uploaded file.
     * @param string $filename the name to rename (without path)
     * @param string|\DateTime $date the creation date of the file: defines the path name
     */
    public static function deleteUploadedFile(string $filename, $date)
    {
        $name = storage_path() . '/app/public/' . FileHelper::buildFileStoragePath($date) . '/' . $filename;
        \unlink($name);
    }
    /**
     * Returns the file extension. This is the part behind the last '.'.
     * @param string $filename the filename to inspect
     * @return string '': no '.' found. Otherwise: the extension, e.g. '.txt'
     */
    public static function extensionOf(string $filename): string
    {
        $rc = ($ixDot = strrpos($filename, '.')) === false ? '' : substr($filename, $ixDot);
        return $rc;
    }
    /**
     * Returns a list of FileInfo instances of a given $directory.
     * @param string $directory that directory will be scanned
     * @param null|string $pattern a regular expression for filtering. Only matching files will be returned. Example: "/^.*.txt$"
     * @return array the list of filtered files from $directory 
     */
    public static function fileInfoList(string $directory, string $pattern = null): array
    {
        $rc = [];
        $nodes = scandir($directory);
        foreach ($nodes as $node) {
            if ($node !== '.' && $node !== '..' && ($pattern == null || preg_match($pattern, $node))) {
                $full = "$directory/$node";
                $size = filesize($full);
                $timestamp = filemtime($full);
                $date = new \DateTime("@$timestamp");
                array_push($rc, new FileInfo($node, $date, $size / 1E6));
            }
        }
        return $rc;
    }
    /**
     * Renames the uploaded file.
     * That is needed because the filename contains the primary key. This is known after storing the record.
     * @param string $oldName the name to rename (without path)
     * @param string $newName the target name
     * @param string|\DateTime $date the creation date of the file: defines the path name
     */
    public static function renameUploadedFile(string $oldName, string $newName, $date)
    {
        $storage = storage_path() . '/app/public/' . FileHelper::buildFileStoragePath($date) . '/';
        $old = $storage . $oldName;
        $new = $storage . $newName;
        \rename($old, $new);
    }
    /**
     * Replaces the uploaded file.
     * @param Request $request
     * @param string $fieldname the name of the field with type "file"
     * @param string $filename the name of the file to replace
     * @param string|\DateTime $date the creation date of the file: defines the path name
     */
    public static function replaceUploadedFile(Request $request, string $fieldname, string $filename, $date)
    {
        $relativePath = FileHelper::buildFileStoragePath($date);
        $request->file($fieldname)->storeAs($relativePath, $filename, 'public');
    }
    /**
     * Stores the uploaded file given in $fieldname in the file storage path using $filename. 
     *
     * @param \Illuminate\Http\Request $request
     * @param string $fieldname
     * @param string $filename
     * @return string the absolute path of the stored file
     */
    public static function storeFile(Request $request, string $fieldname, string $filename): string
    {
        $relativePath = FileHelper::buildFileStoragePath(null);
        $filePath = $request->file($fieldname)->storeAs($relativePath, $filename, 'public');
        return $filePath;
    }
    /**
     * Converts any text to a filename: remove/convert wrong characters.
     * @param string $text the text to inspect
     * @param int $maxLength the maximum lenght of the result
     * @return string a valid filename
     */
    public static function textToFilename(string $text, int $maxLength = 32)
    {
        $rc = preg_replace(['/\s+/', '/[\W=+-]+/', '/__+/'], ['_', '', '_'], $text);
        if (strlen($rc) > $maxLength) {
            $rc = substr($rc, 0, $maxLength);
        }
        if (empty($rc)) {
            $rc = '_';
        }
        return $rc;
    }
}
class FileInfo
{
    public $node;
    public $date;
    public $sizeMByte;
    public $id;
    public function __construct(string $node, \DateTime $date, float $sizeMByte, int $id = 0)
    {
        $this->node = $node;
        $this->date = $date;
        $this->sizeMByte = $sizeMByte;
        $this->id = $id;
    }
    public function urlEncoded()
    {
        $rc = FileHelper::encodeUrl($this->node);
        return $rc;
    }
}