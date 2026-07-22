<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class SecurityController extends Controller
{
    public function __construct()
    {
        // Require authentication for the security check dashboard
        $this->middleware('auth:web');
        $this->middleware(function ($request, $next) {
            if (!auth()->user()->hasPermissionTo('audit_security')) {
                abort(403, 'Unauthorized action. You do not have audit_security permission.');
            }
            return $next($request);
        });
    }

    public function check()
    {
        $certPath = base_path('certificates');
        $caCertFile = $certPath . '/ca.crt';
        $siteCertFile = $certPath . '/secure-study.crt';

        $caInfo = null;
        $siteInfo = null;

        // Parse Root CA Certificate if exists
        if (File::exists($caCertFile)) {
            $caContent = File::get($caCertFile);
            $parsedCa = openssl_x509_parse($caContent);
            if ($parsedCa) {
                $caInfo = [
                    'name' => $parsedCa['name'] ?? 'Education Root CA',
                    'subject' => $parsedCa['subject']['CN'] ?? 'Education Root',
                    'issuer' => $parsedCa['issuer']['CN'] ?? 'Education Root',
                    'validFrom' => date('Y-m-d H:i:s', $parsedCa['validFrom_time_t']),
                    'validTo' => date('Y-m-d H:i:s', $parsedCa['validTo_time_t']),
                    'expired' => time() > $parsedCa['validTo_time_t']
                ];
            }
        }

        // Parse Site Certificate if exists
        if (File::exists($siteCertFile)) {
            $siteContent = File::get($siteCertFile);
            $parsedSite = openssl_x509_parse($siteContent);
            if ($parsedSite) {
                $siteInfo = [
                    'name' => $parsedSite['name'] ?? 'www.secure-study.com',
                    'subject' => $parsedSite['subject']['CN'] ?? 'www.secure-study.com',
                    'issuer' => $parsedSite['issuer']['CN'] ?? 'Education Root',
                    'validFrom' => date('Y-m-d H:i:s', $parsedSite['validFrom_time_t']),
                    'validTo' => date('Y-m-d H:i:s', $parsedSite['validTo_time_t']),
                    'expired' => time() > $parsedSite['validTo_time_t']
                ];
            }
        }

        return view('security.check', compact('caInfo', 'siteInfo'));
    }

    // Vulnerability Simulator Action (API-style or AJAX)
    public function testPassword(Request $request)
    {
        $password = $request->password ?? '';
        
        $rules = [
            'length' => strlen($password) >= 8,
            'letters' => (bool) preg_match('/[a-zA-Z]/', $password),
            'mixedCase' => (bool) (preg_match('/[a-z]/', $password) && preg_match('/[A-Z]/', $password)),
            'numbers' => (bool) preg_match('/[0-9]/', $password),
            'symbols' => (bool) preg_match('/[^a-zA-Z0-9]/', $password),
        ];

        $passed = !in_array(false, $rules, true);

        return response()->json([
            'password' => $password,
            'rules' => $rules,
            'passed' => $passed
        ]);
    }

    public function testSqlInjection(Request $request)
    {
        $payload = $request->payload ?? '';
        
        // Detect common SQL injection patterns
        $vulnerablePatterns = [
            '/\'/',
            '/--/',
            '/OR\s+.*=.*/i',
            '/UNION\s+SELECT/i',
            '/#/'
        ];

        $hasSqlPattern = false;
        foreach ($vulnerablePatterns as $pattern) {
            if (preg_match($pattern, $payload)) {
                $hasSqlPattern = true;
                break;
            }
        }

        // Generate the simulated queries
        $vulnerableQuery = "SELECT * FROM users WHERE email = '" . $payload . "' AND password = 'hashed_password'";
        
        // Parameterized secure query representation
        $secureQuery = "SELECT * FROM users WHERE email = ? AND password = ?";
        $bindings = [$payload, 'hashed_password'];

        return response()->json([
            'payload' => $payload,
            'hasSqlPattern' => $hasSqlPattern,
            'vulnerableQuery' => $vulnerableQuery,
            'secureQuery' => $secureQuery,
            'bindings' => $bindings,
            'explanation' => $hasSqlPattern 
                ? "WARNING: Input contains SQL injection tokens! Standard string concatenation executes the payload as command logic. Parameters bind the input strictly as a value, rendering it completely safe." 
                : "Input is treated safely by Laravel Eloquent/PDO parameterized queries."
        ]);
    }

    public function testXss(Request $request)
    {
        $payload = $request->payload ?? '';

        // Laravel double braces: {{ $payload }} escapes html
        $escaped = e($payload);

        // Vulnerable raw echo: {!! $payload !!}
        $rawOutput = $payload;

        $hasHtmlTags = (bool) preg_match('/<[^>]*>/', $payload);

        return response()->json([
            'payload' => $payload,
            'hasHtmlTags' => $hasHtmlTags,
            'escapedOutput' => $escaped,
            'rawOutput' => $rawOutput,
            'explanation' => $hasHtmlTags 
                ? "WARNING: HTML/Script tags detected! Printing with standard blade {{ }} translates these to entities, neutralizing script execution. Using {!! !!} prints raw output and triggers XSS." 
                : "No script tags detected."
        ]);
    }
}
