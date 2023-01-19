<?php

namespace App\Http\Controllers\Captcha;

use App\Services\Captcha\Captcha;
use Exception;
use Illuminate\Routing\Controller;

/**
 * Class CaptchaController
 */
class CaptchaController extends Controller
{
    /**
     * get CAPTCHA
     *
     * @param Captcha $captcha
     * @param string $config
     * @return array|mixed
     * @throws Exception
     */
    public function getCaptcha(Captcha $captcha, string $config = 'default')
    {
        if (ob_get_contents()) {
            ob_clean();
        }
        
        if(!in_array($config, ['default', 'math', 'flat', 'mini', 'inverse'])){
            abort(404);
        }

        return $captcha->create($config);
    }

    public function reload(){
        return response()->json(['captcha'=> captcha_img('flat')]);
    }
}
