<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use App\Http\Controllers\Controller;

class EncryptionController extends Controller
{
    public function encryptData(Request $request)
    {
        $validated = $request->validate([
            'data' => 'required|string',
        ]);

         $encryptedData = Crypt::encrypt($validated['data']);

        return response()->json(['encrypted_data' => $encryptedData]);
    }

    public function decryptData(Request $request)
    {
        $validated = $request->validate([
            'encrypted_data' => 'required|string',
        ]);

        $decryptedData = Crypt::decrypt($validated['encrypted_data']);

        return response()->json(['decrypted_data' => $decryptedData]);
    }
}
