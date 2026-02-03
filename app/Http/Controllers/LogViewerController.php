<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Traits\ResponseTrait;
use Illuminate\Support\Facades\URL;

class LogViewerController extends Controller
{
    use ResponseTrait;

    public function index(Request $request){

        URL::forceRootUrl(config('app.admin_url'));

        $url = URL::temporarySignedRoute(
                        'web.log-viewer.url',
                        now()->addMinutes(5),
                    );

        return $this->success('Success',$url);
    }

    public function logViewerUrl(Request $request){

        if (!$request->hasValidSignature()) {
            abort(401, 'Invalid or expired link');
        }

        session(['log_viewer_authorized' => true]);
        session(['log_viewer_expires' => now()->addMinutes(2)]);

        return redirect('/log-viewer');
    }
}
