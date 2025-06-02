<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ChatbotController extends Controller
{
    public function preguntar(Request $request)
    {
        $mensaje = $request->input('message');
        $response = Http::post('http://127.0.0.1:8000/chat', [
            'message' => $mensaje
        ]);
        return $response->json();
    }
}