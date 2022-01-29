<?php

namespace App\Helpers;

use Exception;

class APIResponse
{
    /**
     * Defines a structure for API response following the 'JSEND' pattern.
     * Learn about it: https://github.com/omniti-labs/jsend
     */
    public static function formatJSONPayload(string $status, mixed $data): array
    {
        if (APIResponse::statusCheck($status) === false) {
            throw new Exception("'{$status}' is not accepted as a status for the API response.");
        }

        return [
            'status' => $status,
            'data' => $data
        ];
    }

    public static function statusCheck(string $status): bool
    {
        return in_array($status, ['success', 'fail', 'error']);
    }
}
