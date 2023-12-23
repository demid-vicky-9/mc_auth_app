<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class FbDeletionController extends Controller
{
    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function handle(Request $request): JsonResponse
    {
        $confirmationCode = 'abc123';

        $statusUrl = 'https://website.com/deletion-status/' . $confirmationCode;

        return response()->json([
            'url'               => $statusUrl,
            'confirmation_code' => $confirmationCode,
        ], 200);
    }
}
