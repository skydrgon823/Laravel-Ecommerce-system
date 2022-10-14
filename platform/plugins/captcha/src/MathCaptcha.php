<?php

namespace Botble\Captcha;

use Exception;
use Illuminate\Session\SessionManager;

class MathCaptcha
{
    /**
     * @var SessionManager
     */
    protected $session;

    /**
     * @param SessionManager|null $session
     */
    public function __construct(SessionManager $session = null)
    {
        $this->session = $session;
    }

    /**
     * Returns the math question as string. The second operand is always a larger
     * number then the first one. So it's on first position because we don't want
     * any negative results.
     *
     * @return string
     */
    public function label(): string
    {
        $label = $this->getMathLabelOnly();

        return __('Please solve the following math function: :label = ?', compact('label'));
    }

    /**
     * @return string
     */
    public function getMathLabelOnly(): string
    {
        return sprintf('%d %s %d', $this->getMathSecondOperator(), $this->getMathOperand(), $this->getMathFirstOperator());
    }

    /**
     * Returns the math input field
     * @param array $attributes Additional HTML attributes
     * @return string the input field
     */
    public function input(array $attributes = []): string
    {
        $default = [];
        $default['type'] = 'text';
        $default['id'] = 'math-captcha';
        $default['name'] = 'math-captcha';
        $default['required'] = 'required';
        $default['value'] = old('math-captcha');

        $attributes = array_merge($default, $attributes);

        return '<input ' . $this->buildAttributes($attributes) . '>';
    }

    /**
     * Laravel input validation
     * @param string $value
     * @return boolean
     * @throws Exception
     */
    public function verify(string $value): bool
    {
        return $value == $this->getMathResult();
    }

    /**
     * Reset the math operators to regenerate a new question.
     *
     * @return void
     */
    public function reset()
    {
        $this->session->forget('math-captcha.first');
        $this->session->forget('math-captcha.second');
        $this->session->forget('math-captcha.operand');
    }

    /**
     * Operand to be used ('*','-','+')
     *
     * @return string
     */
    protected function getMathOperand(): string
    {
        if (!$this->session->get('math-captcha.operand')) {
            $this->session->put(
                'math-captcha.operand',
                config('plugins.captcha.general.math-captcha.operands.' . array_rand(config('plugins.captcha.general.math-captcha.operands')))
            );
        }

        return $this->session->get('math-captcha.operand');
    }

    /**
     * The first math operand.
     *
     * @return int
     */
    protected function getMathFirstOperator(): int
    {
        if (!$this->session->get('math-captcha.first')) {
            $this->session->put(
                'math-captcha.first',
                rand(config('plugins.captcha.general.math-captcha.rand-min'), config('plugins.captcha.general.math-captcha.rand-max'))
            );
        }

        return $this->session->get('math-captcha.first');
    }

    /**
     * The second math operand
     * @return int
     */
    protected function getMathSecondOperator(): int
    {
        if (!$this->session->get('math-captcha.second')) {
            $this->session->put(
                'math-captcha.second',
                $this->getMathFirstOperator() + rand(config('plugins.captcha.general.math-captcha.rand-min'), config('plugins.captcha.general.math-captcha.rand-max'))
            );
        }

        return $this->session->get('math-captcha.second');
    }

    /**
     * The math result to be validated.
     * @return int
     * @throws Exception
     */
    protected function getMathResult()
    {
        switch ($this->getMathOperand()) {
            case '+':
                return $this->getMathFirstOperator() + $this->getMathSecondOperator();
            case '*':
                return $this->getMathFirstOperator() * $this->getMathSecondOperator();
            case '-':
                return abs($this->getMathFirstOperator() - $this->getMathSecondOperator());
            default:
                throw new Exception('Math captcha uses an unknown operand.');
        }
    }

    /**
     * Build HTML attributes.
     *
     * @param array $attributes
     *
     * @return string
     */
    protected function buildAttributes(array $attributes): string
    {
        $html = [];
        foreach ($attributes as $key => $value) {
            $html[] = $key . '="' . $value . '"';
        }

        return count($html) ? ' ' . implode(' ', $html) : '';
    }
}
