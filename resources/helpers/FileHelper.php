<?php
namespace App\Helpers;
use Illuminate\Http\Request;

class FileHelper{
    /**
     * Builds the download link of an uploaded file.
     * @param string $filename the filename without path
     * @param string $date the date/time of the upload
     * @return string the relative link, e.g. "/upload/2024/02/113_london.jpg'
     */
    public static function buildFileLink(string $filename, string $date): string{
        $rc = '/upload/' . FileHelper::buildFileStoragePath($date) . '/' . $filename;
        return $rc;
    }
   /**
     * Builds the relative path for the upload file storage.
     * @param string|\DateTime $date the creation date of the file: defines the path name
     */
    public static function buildFileStoragePath($date): string{
        $date ??= new \DateTime();
        if ($date instanceof \DateTime){
            $rc = $date->format('Y') . '/' . $date->format('m');
        } else {
            $parts = explode('-', $date, 3);
            $rc = $parts[0] . '/' . $parts[1];
        }
        return $rc;
    }
    /**
     * Deletes the uploaded file.
     * @param string $filename the name to rename (without path)
     * @param string|\DateTime $date the creation date of the file: defines the path name
     */
    public static function deleteUploadedFile(string $filename, $date){
        $name = storage_path() . '/app/public/' . FileHelper::buildFileStoragePath($date) . '/' . $filename;
        \unlink($name);
    }
    /**
     * Returns the file extension. This is the part behind the last '.'.
     * @param string $filename the filename to inspect
     * @return string '': no '.' found. Otherwise: the extension, e.g. '.txt'
     */
    public static function extensionOf(string $filename): string{
        $rc = ($ixDot = strrpos($filename, '.')) === false ? '' : substr($filename, $ixDot);
        return $rc;
    }
    /**
     * Renames the uploaded file.
     * That is needed because the filename contains the primary key. This is known after storing the record.
     * @param string $oldName the name to rename (without path)
     * @param string $newName the target name
     * @param string|\DateTime $date the creation date of the file: defines the path name
     */
    public static function renameUploadedFile(string $oldName, string $newName, $date){
        $storage = storage_path() . '/app/public/' . FileHelper::buildFileStoragePath($date) . '/';
        $old = $storage  . $oldName;
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
    public static function replaceUploadedFile(Request $request, string $fieldname, string $filename, $date){
        $relativePath = FileHelper::buildFileStoragePath($date);
        $request->file($fieldname)->storeAs($relativePath, $filename, 'public');
    }

    public static function storeFile(Request $request, string $fieldname, string $filename): string{
        $relativePath = FileHelper::buildFileStoragePath(null);
        $filePath = $request->file($fieldname)->storeAs($relativePath, $filename, 'public');
        return $filePath;
    }
}