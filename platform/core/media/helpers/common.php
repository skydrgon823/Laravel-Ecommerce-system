<?php

use Illuminate\Contracts\Routing\UrlGenerator;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\UploadedFile;

if (!function_exists('is_image')) {
    /**
     * Is the mime type an image
     *
     * @param string $mimeType
     * @return bool
     * @deprecated since 5.7
     */
    function is_image(string $mimeType): bool
    {
        return RvMedia::isImage($mimeType);
    }
}

if (!function_exists('get_image_url')) {
    /**
     * @param string $url
     * @param string|null $size
     * @param bool $relativePath
     * @param null $default
     * @return string
     * @deprecated since 5.7
     */
    function get_image_url(string $url, ?string $size = null, bool $relativePath = false, $default = null): string
    {
        return RvMedia::getImageUrl($url, $size, $relativePath, $default);
    }
}

if (!function_exists('get_object_image')) {
    /**
     * @param string $image
     * @param null $size
     * @param bool $relativePath
     * @return UrlGenerator|string
     * @deprecated since 5.7
     */
    function get_object_image(string $image, $size = null, bool $relativePath = false)
    {
        return RvMedia::getImageUrl($image, $size, $relativePath, RvMedia::getDefaultImage());
    }
}

if (!function_exists('rv_media_handle_upload')) {
    /**
     * @param UploadedFile|null $fileUpload
     * @param int $folderId
     * @param string $path
     * @return array|JsonResponse
     * @deprecated since 5.7
     */
    function rv_media_handle_upload(?UploadedFile $fileUpload, int $folderId = 0, string $path = '')
    {
        return RvMedia::handleUpload($fileUpload, $folderId, $path);
    }
}
