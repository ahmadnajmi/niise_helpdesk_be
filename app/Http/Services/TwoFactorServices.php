<?php

namespace App\Http\Services;

use App\Models\User;
use BaconQrCode\Renderer\Image\SvgImageBackEnd;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Writer;
use PragmaRX\Google2FA\Google2FA;
use Illuminate\Support\Facades\Auth;

class TwoFactorServices
{
    protected Google2FA $google2fa;

    public function __construct(){
        $this->google2fa = new Google2FA();
    }

    public function generateSecretKey(): string {
        return $this->google2fa->generateSecretKey(16);
    }

    public function generateQrCodeUrl(string $secret): string{
        $appName = config('app.name');

        return $this->google2fa->getQRCodeUrl(
            $appName,
            Auth::user()->ic_no ?? Auth::user()->email,
            $secret
        );
    }

    public function generateQrCodeSvg(string $secret): string {
        $qrCodeUrl = $this->generateQrCodeUrl($secret);
        
        $renderer = new ImageRenderer(
            new RendererStyle(200),
            new SvgImageBackEnd()
        );
        
        $writer = new Writer($renderer);
        $svg = $writer->writeString($qrCodeUrl);
        
        return 'data:image/svg+xml;base64,' . base64_encode($svg);
    }

    public function verifyCode(string $code): bool{
        $verify_code =  $this->google2fa->verifyKey(decrypt(Auth::user()->two_fa_secret), $code);

        if($verify_code){
            $this->enableTwoFactor();
            return true;
        }

        return false;
    }

    public function updateTwoFactor(string $secret): bool{
        $user = Auth::user();

        $user->two_fa_secret = encrypt($secret);
        $user->two_fa_enabled = null;
       
        return $user->save();
    }

    public function enableTwoFactor(): bool{
        $user = Auth::user();

        $user->two_fa_enabled = now();
       
        return $user->save();
    }
    
    public function getDecryptedSecret(User $user): ?string{
        if (!$user->two_fa_secret) {
            return null;
        }
        
        try {
            return decrypt($user->two_fa_secret);
        } catch (\Exception $e) {
            return null;
        }
    }

    public function processTwoFactor(){

        $secret_key = $this->generateSecretKey();

        $this->updateTwoFactor($secret_key);
        $qr_image = $this->generateQrCodeSvg($secret_key);

        return ['qr_image' => $qr_image,'secret_key' => $secret_key];
    }
}