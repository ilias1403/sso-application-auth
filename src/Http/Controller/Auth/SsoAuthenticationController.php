<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use InvalidArgumentException;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class SsoAuthenticationController extends Controller
{
    public function redirect(Request $request)
    {
        // Generate state and verifier
        $state = Str::random(40);
        $codeVerifier = Str::random(128);
        
        // Store in cache instead of session
        Cache::put('sso_state_' . $state, [
            'state' => $state,
            'code_verifier' => $codeVerifier
        ], now()->addMinutes(10));

        $codeChallenge = strtr(rtrim(
            base64_encode(hash('sha256', $codeVerifier, true))
            , '='), '+/', '-_');

        $query = http_build_query([
            'client_id' => config('services.sso.client_id'),
            'client_secret' => config('services.sso.client_secret'),
            'redirect_uri' => url('auth/callback'),
            'response_type' => 'code',
            'scope' => '*',
            'state' => $state,
            'code_challenge' => $codeChallenge,
            'code_challenge_method' => 'S256',
            // 'prompt' => '', // "none", "consent", or "login"
        ]);
        
        return redirect(config('services.sso.host').'/oauth/authorize?'.$query);

    }

    public function authenticate(Request $request)
    {
        if ($request->get('error')) {
            return redirect('login');
        }

        $cacheKey = 'sso_state_' . $request->state;
        $ssoData = Cache::get($cacheKey);
        
        if (!$ssoData) {
            throw new InvalidArgumentException('Invalid state');
        }
        
        // Use $ssoData['state'] and $ssoData['code_verifier']
        $codeVerifier = $ssoData['code_verifier'];
        // Remember to delete the cache after use
        Cache::forget($cacheKey);

        $response = Http::asForm()->post(config('services.sso.host').'/oauth/token', [
            'grant_type' => 'authorization_code',
            'client_id' => config('services.sso.client_id'),
            'client_secret' => config('services.sso.client_secret'),
            'redirect_uri' => route('sso.authenticate'),
            'code_verifier' => $codeVerifier,
            'code' => $request->get('code'),
        ]);

        $ssoUser = Http::withToken($response->json()['access_token'])
            ->get(config('services.sso.host').'/api/user')
            ->throw()
            ->json();

        if ($ssoUser) {
            unset($ssoUser['email']);
            $user = User::firstOrCreate(
                ['staff_id' => $ssoUser['employee_id']],
                ['password' => '', 'name' => '', ...$ssoUser]
            );
        }

        Auth::login($user);

        return redirect('/dashboard');
    }
}
