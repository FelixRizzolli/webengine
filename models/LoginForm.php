<?php

namespace app\models;

use app\core\Application;
use app\core\Model;

/**
 * Class LoginForm
 *
 * @author Felix Rizzolli <felix.rizzolli@outlook.de>
 * @package app\models
 */
class LoginForm extends Model {
    public string $email = '';
    public string $password = '';

    public function rules(): array {
        return [
            'email' => [self::RULE_REQUIRED, self::RULE_EMAIL],
            'password' => [self::RULE_REQUIRED]
        ];
    }
    public function getLabels(): array {
        return [
            'email' => 'E-Mail',
            'password' => 'Password'
        ];
    }

    public function login(): bool {
        $user = User::fetchOne(['email' => $this->email]);
        if (!$user) {
            $this->addError('email', 'User does not exists with this E-Mail');
            return false;
        }
        if (!password_verify($this->password, $user->password)) {
            $this->addError('password', 'Password is incorrect');
            return false;
        }

        return Application::$app->login($user);
    }

}