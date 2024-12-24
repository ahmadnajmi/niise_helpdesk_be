<?php
namespace App\Http\Traits;

use Illuminate\Testing\Exceptions\InvalidArgumentException;


trait ResponseTrait {

    protected function success(string $message, $data = [], int $status = 200) {

        $response = [
            'status' => true,
            'message' => $message,
        ];

        if (!empty($data)) {
            $response['data'] = $data;
        }

        return response()->json($response, $status);

    }

    protected function failure(string $message, $errors = [], int $status = 500) {

        $response = [
            'status' => false,
            'message' => $message
        ];

        if (!empty($errors)) {
            // Validate and normalize errors to match the desired structure
            $formattedErrors = array_map(function ($error) {
                if (is_array($error) && isset($error['name']) && isset($error['message'])) {
                    // Valid structure, return as is
                    return $error;
                }

                // If the structure is incorrect, throw an exception
                throw new InvalidArgumentException('Each error must have "name" and "message" keys.');
            }, $errors);

            $response['errors'] = $formattedErrors;
        }

        return response()->json($response, $status);
    }


    protected function forbidden(string $message = null, int $status = 200) {

        $response = [
            'status' => false,
            'message' => $message == null ? "Forbidden request." : $message,
        ];

        return response()->json($response, $status);
    }

}
 