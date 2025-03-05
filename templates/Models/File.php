<?php

namespace App\Models;

use App\Helpers\FileHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Hamatoma\Laraknife\ViewHelpers;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class File extends Model
{
    use HasFactory;
    protected $table = 'files';
    protected $fillable = [
        'title',
        'description',
        'filename',
        'filegroup_scope',
        'visibility_scope',
        'user_id',
        'size',
        'module_id',
        'reference_id'
    ];
    /**
     * Stores a record into the table files and return the id.
     * @param Request $request
     * @param string $title
     * @param int $filegroup e.g. 1105 for "image file"
     * @param int $visibility e.g. 1091 for "public"
     * @param string $description 
     * @param string $filename the filename (without preceding id)
     * @param int $moduleId
     * @param int $referenceId the id in the table referenced by moduleId
     * @param string $fieldFile the name of the form field
     */
    public static function storeFile(
        Request $request,
        string $title,
        int $filegroup,
        int $visibility = 1091 /* public */ ,
        ?string $description = null,
        ?string $filename = null,
        ?int $moduleId,
        ?int $referenceId,
        string $fieldFile = 'file'
    ): ?int {
        $id = null;
        $file = $request->file($fieldFile);
        if ($file != null) {
            $name = empty($filename) ? $file->getClientOriginalName() : $filename;
            $ext = FileHelper::extensionOf($name);
            if (empty($ext)) {
                $name .= FileHelper::extensionOf($file->getClientOriginalName());
            }
            $filename = session('userName') . '_' . strval(time()) . '!' . $name;
            $file2 = new File([
                'title' => $title,
                'description' => $description,
                'filename' => $filename,
                'filegroup_scope' => $filegroup,
                'visibility_scope' => $visibility,
                'user_id' => auth()->id(),
                'size' => $file->getSize() / 1E6,
                'module_id' => $moduleId,
                'reference_id' => $referenceId,
            ]);
            $filePath = FileHelper::storeFile($request, 'file', $filename);
            $file2->save();
            $id = $file2->id;
            $filename2 = strval($id) . '_' . $name;
            FileHelper::renameUploadedFile($filename, $filename2, $file2->created_at);
            $file2->update(['filename' => $filename2]);
        }
        return $id;
    }
    /**
     * Returns the filename with the relative path (from document root) of a file.
     * @param int $fileId the primary key
     * @return null|string the filename
     */
    public static function relativeFileLink(int $fileId): ?string
    {
        $rc = null;
        if (($file = File::find($fileId)) != null) {
            $rc = FileHelper::buildFileLink($file->filename, $file->created_at);
        }
        return $rc;
    }
}
