<?php

namespace App\Http\Controllers\Cs;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GroqApiController extends Controller
{
    /**
     * Test the connection to the API
     *
     * @return \Illuminate\Http\Response
     */
    public function testConnection()
    {
        // Log the test connection attempt
        Log::info('Groq API test connection attempt');
        
        // Get the API key from environment variables
        $apiKey = env('GROQ_API_KEY');
        
        // Check if API key is configured
        if (!$apiKey) {
            Log::error('Groq API key not configured');
            return response()->json([
                'status' => 'error',
                'message' => 'API key not configured'
            ], 500);
        }
        
        try {
            // Test the connection to Groq API with SSL verification disabled
            $response = Http::withoutVerifying()->withHeaders([
                'Authorization' => 'Bearer ' . $apiKey,
                'Content-Type' => 'application/json'
            ])->get('https://api.groq.com/openai/v1/models');
            
            if ($response->successful()) {
                Log::info('Groq API connection test successful');
                return response()->json([
                    'status' => 'success',
                    'message' => 'API connection test successful',
                    'api_key_configured' => true
                ]);
            } else {
                Log::error('Groq API connection test failed', ['status' => $response->status()]);
                return response()->json([
                    'status' => 'error',
                    'message' => 'API connection test failed: ' . $response->status(),
                    'api_key_configured' => true
                ], 500);
            }
        } catch (\Exception $e) {
            Log::error('Exception during API connection test', ['message' => $e->getMessage()]);
            return response()->json([
                'status' => 'error',
                'message' => 'API connection test failed: ' . $e->getMessage(),
                'api_key_configured' => true
            ], 500);
        }
    }
    
    /**
     * Handle the API request to Groq
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function chat(Request $request)
    {
        // Log the incoming request
        Log::info('Groq API chat request received', [
            'model' => $request->input('model'),
            'message_length' => strlen($request->input('message', '')),
            'has_chat_history' => $request->has('chat_history')
        ]);
        
        try {
            // Validate the request
            $request->validate([
                'message' => 'required|string',
                'chat_history' => 'nullable|array',
                // Model tidak lagi diperlukan karena sudah ditetapkan secara permanen
            ]);

            // Get the API key from environment variables
            $apiKey = env('GROQ_API_KEY');
            
            if (!$apiKey || $apiKey === 'your_groq_api_key_here') {
                Log::error('Groq API key not configured or using default value');
                return response()->json([
                    'error' => 'API key not configured properly'
                ], 500);
            }
            
            Log::info('Groq API key validated successfully');
        } catch (\Exception $e) {
            Log::error('Validation error in Groq API request', ['error' => $e->getMessage()]);
            return response()->json([
                'error' => 'Validation error: ' . $e->getMessage()
            ], 400);
        }

        try {
            // Prepare the messages array for the API
            $messages = [];
            
            // Add system message
            $messages[] = [
                'role' => 'system',
                'content' => 'Anda adalah asisten teknis yang membantu pengguna dengan pertanyaan seputar layanan service elektronik. Berikan jawaban yang informatif, akurat, dan ramah.'
            ];
            
            // Add chat history if provided
            if ($request->has('chat_history') && is_array($request->chat_history)) {
                Log::info('Processing chat history', ['count' => count($request->chat_history)]);
                foreach ($request->chat_history as $message) {
                    if (isset($message['role']) && isset($message['content'])) {
                        $messages[] = [
                            'role' => $message['role'],
                            'content' => $message['content']
                        ];
                    }
                }
            }
            
            // Add the current user message
            $messages[] = [
                'role' => 'user',
                'content' => $request->message
            ];

            // Use fixed model regardless of what is sent from frontend
            $model = 'llama3-70b-8192'; // Model tetap: Llama3 70B
            Log::info('Using fixed Groq model', ['model' => $model]);
            
            // Prepare the request payload
            $payload = [
                'model' => $model,
                'messages' => $messages,
                'temperature' => 0.7,
                'max_tokens' => 1000,
            ];
            
            Log::info('Sending request to Groq API', [
                'url' => 'https://api.groq.com/openai/v1/chat/completions',
                'model' => $model,
                'message_count' => count($messages)
            ]);
            
            // Make the API request to Groq with SSL verification disabled
            $response = Http::withoutVerifying()->withHeaders([
                'Authorization' => 'Bearer ' . $apiKey,
                'Content-Type' => 'application/json'
            ])->post('https://api.groq.com/openai/v1/chat/completions', $payload);

            // Check if the request was successful
            if ($response->successful()) {
                $responseData = $response->json();
                Log::info('Received successful response from Groq API');
                
                // Extract the assistant's message
                if (isset($responseData['choices'][0]['message']['content'])) {
                    $content = $responseData['choices'][0]['message']['content'];
                    Log::info('Extracted message content', ['length' => strlen($content)]);
                    
                    return response()->json([
                        'message' => $content,
                        'model' => 'llama3-70b-8192' // Model tetap: Llama3 70B
                    ]);
                } else {
                    Log::error('Unexpected API response format', ['response' => $responseData]);
                    return response()->json([
                        'error' => 'Unexpected API response format',
                        'response_data' => $responseData
                    ], 500);
                }
            } else {
                $statusCode = $response->status();
                $responseBody = $response->body();
                Log::error('API request failed', [
                    'status' => $statusCode,
                    'body' => $responseBody
                ]);
                
                return response()->json([
                    'error' => 'API request failed with status ' . $statusCode,
                    'details' => $responseBody
                ], $statusCode);
            }
        } catch (\Exception $e) {
            Log::error('Exception during API request', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'error' => 'Exception occurred: ' . $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ], 500);
        }
    }
}