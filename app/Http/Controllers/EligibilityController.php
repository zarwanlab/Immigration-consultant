<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class EligibilityController extends Controller
{
    public function getSuggestion(Request $request)
    {
        $data = $request->validate([
            'formData' => 'required|array',
        ]);

        $formData = $data['formData'];
        $locale = app()->getLocale();

        $prompt = $this->generatePrompt($formData, $locale);

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => config('services.ai.base_url', env('AI_BASE_URL')),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => json_encode([
                "model" => config('services.ai.model', env('AI_MODEL')),
                "messages" => [
                    [
                        "role" => "system",
                        "content" => "You are an expert Senior Immigration Consultant with 20+ years of experience. Your task is to provide a highly professional, realistic, and legally-informed immigration assessment. 

Tone: Professional, empathetic, and objective.
Output Language: " . ($locale === 'fa' ? 'Persian (Farsi)' : ($locale === 'ar' ? 'Arabic' : 'English')) . ".

Structure your response strictly as a JSON object with these keys:
- 'countries': Array of 3-5 countries most suitable for this specific profile.
- 'paths': Array of specific immigration programs/visas (e.g., 'Express Entry (FSWP)', 'Opportunity Card (Chancenkarte)', 'D7 Visa', etc.).
- 'reason': A detailed, paragraph-style explanation (4-6 sentences) analyzing the strengths of the profile and why these specific countries/paths were chosen.
- 'gaps': Array of specific, actionable advice to improve eligibility (e.g., 'Target IELTS 8.0', 'Gain 2 more years of experience in STEM', 'Increase liquid assets by $20k').
- 'alternative': A single sentence suggesting a 'Plan B' if the primary paths are too competitive."
                    ],
                    [
                        "role" => "user",
                        "content" => $prompt
                    ]
                ],
                "temperature" => 0.6,
                "max_tokens" => 6500,
                "stream" => false
            ]),
            CURLOPT_HTTPHEADER => array(
                'Authorization: Bearer ' . config('services.ai.token', env('AI_TOKEN')),
                'Content-Type: application/json'
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);

        if ($err) {
            return response()->json(['error' => 'AI Service Unavailable'], 503);
        }

        $result = json_decode($response, true);
        $aiContent = $result['choices'][0]['message']['content'] ?? null;

        if (!$aiContent) {
            return response()->json(['error' => 'Invalid AI Response'], 500);
        }

        // Clean JSON if AI returns it with markdown blocks
        $aiContent = preg_replace('/^```json\s?|\s?```$/m', '', $aiContent);
        $structuredData = json_decode(trim($aiContent), true);

        if (!$structuredData) {
            return response()->json([
                'countries' => ['Canada', 'Germany', 'Portugal'],
                'paths' => ['Skilled Worker', 'Study Permit'],
                'reason' => "We've analyzed your profile. " . $aiContent,
                'gaps' => ['Please consult with our human experts for a more detailed breakdown.'],
                'alternative' => 'Consider exploring digital nomad visas in Southern Europe.'
            ]);
        }

        return response()->json($structuredData);
    }

    public function getHistory()
    {
        if (Storage::disk('local')->exists('eligibility_history.json')) {
            $history = json_decode(Storage::disk('local')->get('eligibility_history.json'), true);
            return response()->json(array_reverse(array_slice($history, -5))); // Get last 5
        }
        return response()->json([]);
    }

    private function generatePrompt($data, $locale)
    {
        return "Candidate Profile Breakdown:
- Current Age: {$data['age']}
- Highest Education: {$data['education']}
- Professional Experience: {$data['work']}
- Language Proficiency: {$data['language']}
- Available Capital (USD): {$data['capital']}
- Primary Motivation: {$data['goal']}

Analyze the feasibility of relocation for this candidate based on 2024-2025 global immigration trends. Be specific about programs.";
    }

    public function saveResult(Request $request)
    {
        $data = $request->validate([
            'formData' => 'required|array',
            'results' => 'required|array',
        ]);

        $data['timestamp'] = now()->toDateTimeString();
        $data['ip'] = $request->ip();

        $history = [];
        if (Storage::disk('local')->exists('eligibility_history.json')) {
            $history = json_decode(Storage::disk('local')->get('eligibility_history.json'), true);
        }

        $history[] = $data;

        Storage::disk('local')->put('eligibility_history.json', json_encode($history, JSON_PRETTY_PRINT));

        return response()->json(['message' => 'Result saved successfully']);
    }
}