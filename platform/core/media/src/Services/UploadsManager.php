<?php

namespace Botble\Media\Services;

use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\File;
use Illuminate\Contracts\Translation\Translator;
use Illuminate\Http\UploadedFile;
use Mimey\MimeTypes;
use RvMedia;
use Storage;

class UploadsManager
{
    /**
     * @var MimeTypes
     */
    protected $mimeType;

    /**
     * UploadsManager constructor.
     * @param MimeTypes $mimeType
     */
    public function __construct(MimeTypes $mimeType)
    {
        $this->mimeType = $mimeType;
    }

    /**
     * Return an array of file details for a file
     *
     * @param string $path
     * @return array
     */
    public function fileDetails(string $path): array
    {
        return [
            'filename'  => File::basename($path),
            'url'       => $path,
            'mime_type' => $this->fileMimeType($path),
            'size'      => $this->fileSize($path),
            'modified'  => $this->fileModified($path),
        ];
    }

    /**
     * Return the mime type
     *
     * @param string $path
     * @return mixed|null|string
     */
    public function fileMimeType(string $path): ?string
    {
        return $this->mimeType->getMimeType(File::extension(RvMedia::getRealPath($path)));
    }

    /**
     * Return the file size
     *
     * @param string $path
     * @return int
     */
    public function fileSize(string $path): int
    {
        return Storage::size($path);
    }

    /**
     * Return the last modified time
     *
     * @param string $path
     * @return string
     */
    public function fileModified(string $path): string
    {
        return Carbon::createFromTimestamp(Storage::lastModified($path));
    }

    /**
     * @param string $folder
     * @return array|bool|Translator|string|null
     */
    public function createDirectory(string $folder)
    {
        $folder = $this->cleanFolder($folder);

        if (Storage::exists($folder)) {
            return trans('core/media::media.folder_exists', compact('folder'));
        }

        return Storage::makeDirectory($folder);
    }

    /**
     * Sanitize the folder name
     *
     * @param string $folder
     * @return string
     */
    protected function cleanFolder(string $folder): string
    {
        return DIRECTORY_SEPARATOR . trim(str_replace('..', '', $folder), DIRECTORY_SEPARATOR);
    }

    /**
     * @param string $folder
     * @return array|bool|Translator|string|null
     */
    public function deleteDirectory(string $folder)
    {
        $folder = $this->cleanFolder($folder);

        $filesFolders = array_merge(Storage::directories($folder), Storage::files($folder));

        if (!empty($filesFolders)) {
            return trans('core/media::media.directory_must_empty');
        }

        return Storage::deleteDirectory($folder);
    }

    /**
     * @param string $path
     * @return bool
     */
    public function deleteFile(string $path): bool
    {
        $path = $this->cleanFolder($path);

        return Storage::delete($path);
    }

    /**
     * @param string $path
     * @param string $content
     * @param UploadedFile|null $file
     * @param array $visibility
     * @return bool
     */
    public function saveFile(
        string       $path,
        string       $content,
        UploadedFile $file = null,
        array        $visibility = ['visibility' => 'public']
    ): bool {
        if (!RvMedia::isChunkUploadEnabled() || !$file) {
            return Storage::put($this->cleanFolder($path), $content, $visibility);
        }

        $currentChunksPath = RvMedia::getConfig('chunk.storage.chunks') . '/' . $file->getFilename();
        $disk = Storage::disk(RvMedia::getConfig('chunk.storage.disk'));

        try {
            $stream = $disk->getDriver()->readStream($currentChunksPath);

            if ($result = Storage::writeStream($path, $stream, $visibility)) {
                $disk->delete($currentChunksPath);
            }
        } catch (Exception $exception) {
            return Storage::put($this->cleanFolder($path), $content, $visibility);
        }

        return $result;
    }
}
