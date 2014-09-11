<?php
    load_model('user.func');

    class TWTAPIUserWrapper
    {
        var $lock;
        function __construct($params)
        {
            global $db;
            $this->lock = new Locker($db);
        }

        var $last;
        private function result($ret='')
        {
            if(is_string($ret))
            {
                $this->last=array('message' => $ret);
                $this->last=json_decode(json_encode($this->last));
                return false;
            }

            if(is_array($ret))
            {
                $ret=json_decode(json_encode($ret));
            } 
            $this->last=$ret;
            return $ret;
        }

        function query($method,$params)
        {
            $this->result();
            $method=str_replace(".", "_", $method);
            if(!method_exists($this, $method))
            {
                return $this->result(array('message'=>'方法未定义'));
            }
            return call_user_method($method, $this, $params);
        }

        function twt_islogin($p)
        {
            $username=base_protect($p['username']);
            $auth_key=base_protect($p['auth_key']);
            $u = user_getBySQL("WHERE `type`='native' AND `username`='$username' LIMIT 1");
            if(count($u)!=1)
                return $this->result('用户未找到，这也有可能是由于数据库失败引起的');
            $u = $u[0];
            if($auth_key!=$this->calc_auth_key($u))
                return $this->result('AuthKey未匹配');
            if($this->lock->islock($u['type'].'@'.$u['uid'],'account.session'))
                return $this->result('用户未登录');
            return $this->result(array(
                'twtname'   =>  $u['username'],
                'auth_key'   =>  $u['auth_key'],
                'realname'   =>  $u['realname'],
                'uid'   =>  $u['uid'],
            ));
            
        }

        private function calc_auth_key($u,$ip=false)
        {
            if(!$ip)
                $ip = getIp();
            return substr(md5($u['username'].$u['password'].$ip),10);
        }

        function twt_login($p)
        {
            $username=base_protect($p['username']);
            $password=base_protect($p['password']);
            $ishashed=$p['ishashed'];
            $password=user_encryptpasswd($password, $ishashed);
            $u = user_getBySQL("WHERE `type`='native' AND `username`='$username' LIMIT 1");
            if(count($u)!=1)
                return $this->result('用户未找到，这也有可能是由于数据库失败引起的');
            $u = $u[0];
            if($u['password']!=$password)
                return $this->result('密码错误');
            if($u['isforbidden']!='0')
                return $this->result('用户被禁用');
            $u['auth_key']=$this->calc_auth_key($u);
            $this->lock->lock($u['type'].'@'.$u['uid'],'account.session',8,'HOUR');
            return $this->result(array(
                'twtname'   =>  $u['username'],
                'auth_key'   =>  $u['auth_key'],
                'realname'   =>  $u['realname'],
                'uid'   =>  $u['uid'],
            ));
        }

        function twt_logout($p)
        {
            $username=base_protect($p['username']);
            $auth_key=base_protect($p['auth_key']);
            $u = user_getBySQL("WHERE `type`='native' AND `username`='$username' LIMIT 1");
            if(count($u)!=1)
                return $this->result('用户未找到，这也有可能是由于数据库失败引起的');
            $u = $u[0];
            if($auth_key!=$this->calc_auth_key($u))
                return $this->result('AuthKey未匹配');
            $this->lock->unlock($u['type'].'@'.$u['uid'],'account.session');
        }
    }