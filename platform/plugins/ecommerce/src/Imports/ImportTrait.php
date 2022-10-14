<?php

namespace Botble\Ecommerce\Imports;

use Carbon\Carbon;
use Exception;
use Illuminate\Support\Collection;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use DateTime;

trait ImportTrait
{
    /**
     * @var int
     */
    protected $totalImported = 0;

    /**
     * @var array
     */
    protected $successes = [];

    /**
     * @return int
     */
    public function getTotalImported()
    {
        return $this->totalImported;
    }

    /**
     * @return ImportTrait
     */
    public function setTotalImported()
    {
        ++$this->totalImported;

        return $this;
    }

    /**
     * @param mixed $item
     */
    public function onSuccess($item)
    {
        $this->successes[] = $item;
    }

    /**
     * @return Collection
     */
    public function successes(): Collection
    {
        return collect($this->successes);
    }

    /**
     * Transform a date value into a Carbon object.
     *
     * @return string
     */
    public function transformDate($value, $format = '')
    {
        $format = $format ?: config('core.base.general.date_format.date_time');

        try {
            return Carbon::instance(Date::excelToDateTimeObject($value))->format($format);
        } catch (Exception $exception) {
            return Carbon::createFromFormat($format, $value);
        }
    }

    /**
     * Transform a date value into a Carbon object.
     *
     * @return string
     */
    public function getDate($value, $format = 'Y-m-d H:i:s', $default = null)
    {
        try {
            $date = DateTime::createFromFormat('!' . $format, $value);
            return $date ? $date->format(config('core.base.general.date_format.date_time')) : $value;
        } catch (Exception $exception) {
            return $default;
        }
    }
}
