<?php

namespace Botble\Base\Supports;

use Carbon\Carbon;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class MembershipAuthorization
{
    /**
     * @var Client
     */
    protected $client;

    /**
     * @var Request
     */
    protected $request;

    /**
     * @var string
     */
    protected $url;

    /**
     * MembershipAuthorization constructor.
     * @param Client $client
     * @param Request $request
     */
    public function __construct(Client $client, Request $request)
    {
        $this->client = $client;
        $this->request = $request;
        $this->url = rtrim(url('/'), '/');
    }

    /**
     * @return boolean
     * @throws GuzzleException
     */
    public function authorize(): bool
    {
        try {
            if (!filter_var($this->url, FILTER_VALIDATE_URL)) {
                return false;
            }

            if ($this->isInvalidDomain()) {
                return false;
            }

            $authorizeDate = setting('membership_authorization_at');

            if (!$authorizeDate) {
                return $this->processAuthorize();
            }

            $authorizeDate = Carbon::createFromFormat('Y-m-d H:i:s', $authorizeDate);
            if (Carbon::now()->diffInDays($authorizeDate) > 7) {
                return $this->processAuthorize();
            }

            return true;
        } catch (Exception $exception) {
            return false;
        }
    }

    /**
     * @return bool
     */
    protected function isInvalidDomain(): bool
    {
        if (filter_var($this->url, FILTER_VALIDATE_IP)) {
            return true;
        }

        $blacklistDomains = [
            'localhost',
            '.local',
            '.test',
            '127.0.0.1',
            '192.',
            'mail.',
            '8000',
        ];

        foreach ($blacklistDomains as $blacklistDomain) {
            if (Str::contains($this->url, $blacklistDomain)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @return bool
     * @throws GuzzleException
     */
    protected function processAuthorize(): bool
    {
        $this->client->post('https://botble.com/membership/authorize', [
            'form_params' => [
                'website' => $this->url,
            ],
        ]);

        setting()
            ->set('membership_authorization_at', Carbon::now()->toDateTimeString())
            ->save();

        return true;
    }
}
