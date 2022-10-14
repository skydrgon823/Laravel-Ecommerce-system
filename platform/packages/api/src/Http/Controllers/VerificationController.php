<?php

namespace Botble\Api\Http\Controllers;

use ApiHelper;
use App\Http\Controllers\Controller;
use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\Api\Http\Requests\ResendEmailVerificationRequest;
use Hash;
use Illuminate\Support\Str;

class VerificationController extends Controller
{
    /**
     * Resend email verification
     *
     * Resend the email verification notification.
     *
     * @bodyParam email string required The email of the user.
     *
     * @group Authentication
     *
     * @param ResendEmailVerificationRequest $request
     * @param BaseHttpResponse $response
     *
     * @return BaseHttpResponse
     */
    public function resend(ResendEmailVerificationRequest $request, BaseHttpResponse $response)
    {
        $user = ApiHelper::newModel()->where(['email' => $request->input('email')])->first();

        if (!$user) {
            return $response
                ->setError()
                ->setMessage(__('User not found!'))
                ->setCode(404);
        }

        if ($user->hasVerifiedEmail()) {
            return $response
                ->setError()
                ->setMessage(__('This user has verified email'));
        }

        $token = Hash::make(Str::random(32));

        $user->email_verify_token = $token;
        $user->save();

        $user->sendEmailVerificationNotification();

        return $response
            ->setMessage(__('Resend email verification successfully!'));
    }
}
