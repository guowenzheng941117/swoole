<?

namespace App\Validate;

use EasySwoole\Validate\Validate;

class UserValidate
{

    private $valitor;

    public function __construct()
    {
        $this->valitor = new Validate();
    }

    public function toReturn($bool)
    {
        if ($bool === false) {
            return ['status' => false, 'msg' => "{$this->valitor->getError()->getField()}@{$this->valitor->getError()->getFieldAlias()}:{$this->valitor->getError()->getErrorRuleMsg()}"];
        }
        return ['status' => true, 'msg' => "ok"];
    }

    public function userList($data)
    {
        $this->valitor->addColumn('pageSize', '每页大小')->required('不能为空')->integer('必须为整数')->min(0);
        $this->valitor->addColumn('page', '页数')->required('不能为空')->integer('必须为整数')->min(0);
        return $this->toReturn($this->valitor->validate($data));
    }
}
