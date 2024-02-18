<?php
namespace App\Helpers;

class FileHelper{
    /**
     * Builds the download link of an uploaded file.
     * @param string $filename the filename without path
     * @param string $date the date/time of the upload
     * @return string the relative link, e.g. "/upload/2024/02/113_london.jpg'
     */
    public static function buildFileLink(string $filename, string $date): string{
        $rc = 'upload/' . FileHelper::buildFileStoragePath($date) . '/' . $filename;
        return $rc;
    }
   /**
     * Builds the relative path for the upload file storage.
     */
    public static function buildFileStoragePath($date=null): string{
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
     */
    public static function deleteUploadedFile(string $filename){
        $name = storage_path() . '/app/public/' . FileHelper::buildFileStoragePath() . '/' . $filename;
        \unlink($name);
    }
    /**
     * Renames the uploaded file.
     * That is needed because the filename contains the primary key. This is known after storing the record.
     * @param string $oldName the name to rename (without path)
     * @param string $newName the target name
     */
    public static function renameUploadedFile(string $oldName, string $newName){
        $storage = storage_path() . '/app/public/' . FileHelper::buildFileStoragePath() . '/';
        $old = $storage  . $oldName;
        $new = $storage . $newName;
        \rename($old, $new);
    }
}