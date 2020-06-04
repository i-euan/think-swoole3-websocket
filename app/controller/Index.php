<?php
namespace app\controller;

use app\BaseController;
use Swoole\Server;
use think\cache\driver\Redis;

class Index extends BaseController
{
	//异步发送验证码
    public function sendCode()
    {
		$phone_num = input('phone_num');
		go(function() use ($phone_num){
			//TODO 发送验证码
			//sleep(10);
			//把验证码存入缓存
			cache('code_'.$phone_num, rand(1,9), 30);
		});
    	return json(['status'=>'ok']);
    }

	//推送直播信息
	public function push(Server $server)
	{
		$type = input('type');
		$team_id = input('team_id');
		$content = input('content');

		$redis = new Redis;
		$members = $redis->zrange('membner', 0, -1);	//所有用户的fd
		//把信息推给所有人
		foreach($members as $member){
			$server->push($member, json_encode(compact('type', 'team_id', 'content')));
		}
	}
}
