<?php

namespace Botble\Api\Http\Controllers;

use Assets;
use Botble\Base\Http\Responses\BaseHttpResponse;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class ApiController extends Controller
{
    /**
     * @return Factory|View
     */
    public function settings()
    {
        page_title()->setTitle(trans('packages/api::api.settings'));

        Assets::addScriptsDirectly('vendor/core/core/setting/js/setting.js');
        Assets::addStylesDirectly('vendor/core/core/setting/css/setting.css');

        return view('packages/api::settings');
    }

    /**
     * @param Request $request
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     */
    public function storeSettings(Request $request, BaseHttpResponse $response)
    {
        $this->saveSettings($request->except([
            '_token',
        ]));

        return $response
            ->setPreviousUrl(route('api.settings'))
            ->setMessage(trans('core/base::notices.update_success_message'));
    }

    /**
     * @param array $data
     */
    protected function saveSettings(array $data)
    {
        foreach ($data as $settingKey => $settingValue) {
            if (is_array($settingValue)) {
                $settingValue = json_encode(array_filter($settingValue));
            }

            setting()->set($settingKey, (string)$settingValue);
        }

        setting()->save();
    }
}
