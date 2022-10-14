<?php

namespace Botble\Media\Storage\BunnyCDN\Exceptions;

use Exception;

/**
 * An exception thrown by BunnyCDNStorage caused by authentication failure
 */
class BunnyCDNStorageAuthenticationException extends BunnyCDNStorageException
{
    /**
     * @param string $storageZoneName
     * @param string $accessKey
     * @param string $code
     * @param Exception|null $previous
     */
    public function __construct($storageZoneName, $accessKey, $code = 0, Exception $previous = null)
    {
        parent::__construct('Authentication failed for storage zone ' .  $storageZoneName . ' with access key ' . $accessKey . '.', $code, $previous);
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return __CLASS__ . ': '. $this->message . "\n";
    }
}
