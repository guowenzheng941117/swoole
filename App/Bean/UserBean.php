<?

namespace App\Bean;

use EasySwoole\Spl\SplBean;

class UserBean extends SplBean
{
    protected $avatar;
    protected $nickname;
    protected $type;
    protected $username;
    protected $password;
    protected $login_time;
    protected $create_time;
    protected $groupid;
    protected $ban;
}
