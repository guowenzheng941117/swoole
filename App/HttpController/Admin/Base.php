<?

namespace App\HttpController\Admin;

use EasySwoole\Http\AbstractInterface\Controller;
use App\Utility\MyLog;
use EasySwoole\Jwt\Jwt;
use Exception;

/**
 * 基础控制器
 * Class Base
 * @package App\HttpController
 */
class Base extends Controller
{

    public $id;

    public function index()
    {
        $this->actionNotFound('index');
    }

    protected function actionNotFound(?string $action)
    {
        $this->response()->withStatus(404);
        $file = EASYSWOOLE_ROOT . '/vendor/easyswoole/easyswoole/src/Resource/Http/404.html';
        if (!is_file($file)) {
            $file = EASYSWOOLE_ROOT . '/src/Resource/Http/404.html';
        }
        $this->response()->write(file_get_contents($file));
    }

    public function onRequest(?string $action): ?bool
    {
        try {
            $header = $this->request()->getHeaders();
            if (isset($header['oid'][0]) && isset($header['ot'][0])) {
                $ownerid = $header['oid'][0];
                $token = $header['ot'][0];
            } else {
                $this->writeJson(null, '需要重新登录', 1003);
                return false;
            }
            $this->id = openssl_decrypt($ownerid, 'DES-ECB', 'ownerid', 0);
            $jwt = Jwt::getInstance();

            $result = $jwt->decode($token);
            if ($result->getStatus() === 1 && $result->getAud() == $this->id) {
                return true;
            } else {
                if ($result->getStatus() === -1) {
                    throw new Exception("token error");
                }
                if ($result->getStatus() === -2) {
                    throw new Exception("token expire");
                }
                throw new Exception("token not init");
                return false;
            }
        } catch (\Exception $e) {
            // TODO: 处理异常
            MyLog::getInstance()->log($e->getMessage());
            $this->writeJson(null, '需要重新登录', 1003);
            return false;
        }
    }

    protected function writeJson($data = null, $info = null, $status = null)
    {
        if (!$this->response()->isEndResponse()) {
            $data = array(
                "data" => $data,
                "info" => $info,
                "status" => $status
            );
            $this->response()->write(json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
            $this->response()->withHeader('Content-type', 'application/json;charset=utf-8');
            $this->response()->withStatus(200);
            return true;
        } else {
            return false;
        }
    }


    function onException(\Throwable $throwable): void
    {
        MyLog::getInstance()->log($throwable->getMessage(), MyLog::LOG_LEVEL_INFO, 'Exception');
        $this->writeJson(null, $throwable->getMessage(), $throwable->getCode());
    }
}
