<?php

namespace App\Services;

use Exception;
use RuntimeException;
use sms_net_bd\SMS;

class SmsService
{
    public function sendOtp(string $countryCode, string $phone, string $otp): array
    {
        $to = $this->normalizePhone($countryCode, $phone);
        $message = "Your verification code is {$otp}. It will expire in 5 minutes.";

        try {
            $sms = new SMS();
            $response = $sms->sendSMS($message, $to);

            if (is_array($response)) {
                return $response;
            }

            return ['success' => true, 'response' => $response];
        } catch (Exception $e) {
            throw new RuntimeException($e->getMessage() ?: 'SMS sending failed.');
        }
    }

    public function getBalance(): array
    {
        try {
            $sms = new SMS();
            $response = $sms->getBalance();

            if (is_array($response)) {
                return $response;
            }

            return ['success' => true, 'response' => $response];
        } catch (Exception $e) {
            throw new RuntimeException($e->getMessage() ?: 'Unable to fetch SMS balance.');
        }
    }

    public function normalizePhone(string $countryCode, string $phone): string
    {
        $phone = preg_replace('/\D+/', '', $phone);
        $countryCode = preg_replace('/\D+/', '', $countryCode);

        if (str_starts_with($phone, '880')) {
            return $phone;
        }

        if (str_starts_with($phone, '0')) {
            return $phone;
        }

        if (strlen($phone) === 10 && str_starts_with($phone, '1')) {
            return '0' . $phone;
        }

        if ($countryCode === '880' && ! str_starts_with($phone, '0')) {
            return '0' . ltrim($phone, '0');
        }

        return $phone;
    }
}
