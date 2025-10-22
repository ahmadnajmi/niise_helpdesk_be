<?php

namespace App\Http\Services;

use Laravel\Socialite\Two\AbstractProvider;
use Laravel\Socialite\Two\ProviderInterface;
use Laravel\Socialite\Two\User;
use Illuminate\Support\Facades\Http;

class NetIQSocialiteProvider extends AbstractProvider implements ProviderInterface
{
    protected $scopes = ['openid', 'profile', 'email'];
    protected $scopeSeparator = ' ';    

    protected function getAuthUrl($state)
    {
        return $this->buildAuthUrlFromBase( config('app.netiq.auth_url'), $state);
    }

    public function getRedirectUrl(){
        $state = uniqid(); 
        return $this->getAuthUrl($state);
    }

    protected function getTokenUrl()
    {
        return config('app.netiq.token_url');
    }

    protected function getUserByToken($token)
    {
        $response = $this->getHttpClient()->get(config('app.netiq.userinfo_url'), [
            'headers' => [
                'Authorization' => 'Bearer ' . $token,
            ],
        ]);

        return json_decode($response->getBody()->getContents(), true);
    }

    protected function mapUserToObject(array $user)
    {
        return (new User())->setRaw($user)->map([
            'id'    => $user['sub'] ?? null,
            'name'  => $user['name'] ?? $user['preferred_username'] ?? null,
            'email' => $user['email'] ?? null,
        ]);
    }

    protected function getCodeFields($state = null) {
       $data = [
            'client_id'     => $this->clientId,
            'redirect_uri'  => $this->redirectUrl,
            'response_type' => 'code',
            'scope'         => $this->formatScopes($this->scopes, $this->scopeSeparator),
            'state'         => $state,
        ];

        return $data;
    }

    public function getUserFromCode($code){
        // $token = $this->getAccessTokenResponse($code);

        $response = Http::asForm()
                        ->withOptions(['verify' => false])
                        ->post(config('app.netiq.token_url'), [
                            'grant_type'    => 'authorization_code',
                            'code'          => $code,
                            'redirect_uri'  => config('app.netiq.redirect_url'),
                            'client_id'     => config('app.netiq.client_id'),
                            'client_secret' => config('app.netiq.client_secret'),
                        ]);

        $token = $response->json();


        $user  = $this->getUserByToken($token['access_token']);

        return [$user, $token];
    }
}
