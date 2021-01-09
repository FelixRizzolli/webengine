<?php


namespace app\models;

use app\core\Model;

/**
 * Class ContactForm
 *
 * @author Felix Rizzolli <felix.rizzolli@outlook.de>
 * @package app\models
 */
class ContactForm extends Model {
    public string $subject = '';
    public string $email = '';
    public string $body = '';

    public function rules(): array {
        return [
            'subject' => [self::RULE_REQUIRED],
            'email' => [self::RULE_REQUIRED, self::RULE_EMAIL],
            'body' => [self::RULE_REQUIRED]
        ];
    }

    public function getLabels(): array {
        return [
            'subject' => 'Enter your subject',
            'email' => 'Your email',
            'body' => 'Body'
        ];
    }

    public function send(): bool {
        return true;
    }
}