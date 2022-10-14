<?php

namespace Botble\Base\Exceptions;

use App\Exceptions\Handler as ExceptionHandler;
use BaseHelper;
use Botble\Base\Http\Responses\BaseHttpResponse;
use EmailHandler;
use Exception;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Exceptions\PostTooLargeException;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Session\TokenMismatchException;
use Log;
use RvMedia;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Theme;
use Throwable;
use Illuminate\Support\Facades\URL;

class Handler extends ExceptionHandler
{
    /**
     * {@inheritDoc}
     */
    public function render($request, Throwable $exception)
    {
        if ($exception instanceof PostTooLargeException) {
            return RvMedia::responseError(trans('core/media::media.upload_failed', [
                'size' => BaseHelper::humanFilesize(RvMedia::getServerConfigMaxUploadFileSize()),
            ]));
        }

        if ($exception instanceof ModelNotFoundException || $exception instanceof MethodNotAllowedHttpException) {
            $exception = new NotFoundHttpException($exception->getMessage(), $exception);
        }

        if ($exception instanceof TokenMismatchException) {
            return (new BaseHttpResponse())
                ->setError()
                ->setCode($exception->getCode())
                ->setMessage('CSRF token mismatch. Please try again!');
        }

        if ($this->isHttpException($exception)) {
            $code = $exception->getStatusCode();

            if ($request->expectsJson()) {
                if (function_exists('admin_bar')) {
                    admin_bar()->setIsDisplay(false);
                }

                $response = new BaseHttpResponse();

                switch ($code) {
                    case 401:
                        return $response
                            ->setError()
                            ->setMessage(trans('core/acl::permissions.access_denied_message'))
                            ->setCode($code)
                            ->toResponse($request);

                    case 403:
                        return $response
                            ->setError()
                            ->setMessage(trans('core/acl::permissions.action_unauthorized'))
                            ->setCode($code)
                            ->toResponse($request);

                    case 404:
                        return $response
                            ->setError()
                            ->setMessage(trans('core/base::errors.not_found'))
                            ->setCode(404)
                            ->toResponse($request);
                }
            }

            if (!app()->isDownForMaintenance()) {
                do_action(BASE_ACTION_SITE_ERROR, $code);
            }
        }

        if ($exception instanceof NotFoundHttpException && setting('redirect_404_to_homepage', 0) == 1) {
            return redirect(route('public.index'));
        }

        return parent::render($request, $exception);
    }

    /**
     * {@inheritDoc}
     */
    protected function renderHttpException(HttpExceptionInterface $exception)
    {
        if ($exception instanceof NotFoundHttpException) {
            /**
             * @var EncryptCookies $encryptCookies
             */
            $encryptCookies = app(EncryptCookies::class);

            /**
             * @var StartSession $startSession
             */
            $startSession = app(StartSession::class);

            $request = app('request');

            $encryptCookies->handle($request, function () use ($startSession, $request) {
                return $startSession->handle($request, function () {
                    return response('');
                });
            });
        }

        return parent::renderHttpException($exception);
    }

    /**
     * {@inheritDoc}
     */
    public function report(Throwable $exception)
    {
        if ($this->shouldReport($exception) && !$this->isExceptionFromBot()) {
            if (!app()->isLocal() && !app()->runningInConsole()) {
                if (setting('enable_send_error_reporting_via_email', false) &&
                    setting('email_driver', config('mail.default')) &&
                    $exception instanceof Exception
                ) {
                    EmailHandler::sendErrorException($exception);
                }

                if (config('core.base.general.error_reporting.via_slack', false)) {
                    Log::channel('slack')
                        ->critical(URL::full() . "\n" . $exception->getFile() . ':' . $exception->getLine() . "\n" . $exception->getMessage());
                }
            }
        }

        parent::report($exception);
    }

    /**
     * Determine if the exception is from the bot.
     *
     * @return boolean
     */
    protected function isExceptionFromBot(): bool
    {
        $ignoredBots = config('core.base.general.error_reporting.ignored_bots', []);
        $agent = strtolower(request()->server('HTTP_USER_AGENT'));

        if (empty($agent)) {
            return false;
        }

        foreach ($ignoredBots as $bot) {
            if ((strpos($agent, $bot) !== false)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Get the view used to render HTTP exceptions.
     * @param HttpExceptionInterface $exception
     * @return string
     */
    protected function getHttpExceptionView(HttpExceptionInterface $exception)
    {
        $code = $exception->getStatusCode();

        if (request()->is(BaseHelper::getAdminPrefix() . '/*') || request()->is(BaseHelper::getAdminPrefix())) {
            return 'core/base::errors.' . $code;
        }

        if (class_exists('Theme')) {
            try {
                return 'theme.' . Theme::getThemeName() . '::views.' . $code;
            } catch (Throwable $throwable) {
                return parent::getHttpExceptionView($exception);
            }
        }

        return parent::getHttpExceptionView($exception);
    }

    /**
     * {@inheritDoc}
     */
    protected function unauthenticated($request, AuthenticationException $exception)
    {
        if ($request->expectsJson()) {
            return (new BaseHttpResponse())
                ->setError()
                ->setMessage($exception->getMessage())
                ->setCode(401)
                ->toResponse($request);
        }

        return redirect()->guest(route('access.login'));
    }
}
