<?php

class Utils  {
    
    public static function Login() {
        
        global $config;
        
        $entityBody = json_decode(file_get_contents('php://input'), true);
        
        $username = isset($entityBody["username"]) ? $entityBody["username"] : null;
        $password = isset($entityBody["password"]) ? $entityBody["password"] : null;
        $ok = false;
        
        foreach($config['auth'] as $user) {
            
            if($user['username'] == $username && $user['password'] == $password) {
                $ok = true;
                break;
            }
        }
        
        if (!$ok) {
            
            header('HTTP/1.1 401 Unauthorized');
            header('Content-type: application/json');
            echo json_encode(["message" => "Identifiants invalides"]);
            exit;
            
        } else {
            
            header('Content-type: application/json');
            echo json_encode(["user" => ["admin" => true,"username" => $username]]);
            exit;
        }
    }

    public static function AuthenticateReq() {
        
        global $config;
        $username = null;
        $password = null;
        $ok = false;
        
        
        
        if (isset($_SERVER['PHP_AUTH_USER'])) {
            $username = $_SERVER['PHP_AUTH_USER'];
            $password = $_SERVER['PHP_AUTH_PW'];
            
            // most other servers
        } elseif (isset($_SERVER['HTTP_AUTHORIZATION'])) {
            
            if (strpos(strtolower($_SERVER['HTTP_AUTHORIZATION']),'basic')===0)
                list($username,$password) = explode(':',base64_decode(substr($_SERVER['HTTP_AUTHORIZATION'], 6)));
            
        } elseif (isset($_SERVER['REDIRECT_HTTP_AUTHORIZATION'])) {
            
            if (strpos(strtolower($_SERVER['REDIRECT_HTTP_AUTHORIZATION']),'basic')===0)
                list($username,$password) = explode(':',base64_decode(substr($_SERVER['REDIRECT_HTTP_AUTHORIZATION'], 6)));
            
        }
        elseif (isset($headers['Authorization'])) {
        
            if (strpos(strtolower($headers['Authorization']),'basic')===0)
                list($username,$password) = explode(':',base64_decode(substr($headers['Authorization'], 6)));
        
        }
        

        foreach($config['auth'] as $user) {
            
            if($user['username'] == $username && $user['password'] == $password) {
                $ok = true;
                break;
        }
        
    }

    if (!$ok) {
        
        header('HTTP/1.1 401 Unauthorized');
        header('Content-type: application/json');
        echo json_encode(["message" => "Identifiants invalides"]);
        exit;
        
    }

    }

    public static function getScriptFromConfig($script_name) {
        global $config;
        
        $key = array_search($script_name, array_column($config['scripts'], "chemin"));
        if($key !== FALSE) {
            return $config['scripts'][$key];
        } else {
            header('Content-type: application/json');
            echo json_encode(["result" => ["Script : " . $script_name . " invalide"] , 'status' => 2]);
            exit;
        }
        
    }

    private static function EscapeCharacters($t) {
        $res = '';
        for($i=0;$i<strlen($t);$i++) {
            if($t[$i] == '"') {
                $res .= '""';
            } else {
                $res .= '\\'. $t[$i];
            }       
        }
        return '"'.$res.'"';
    }

    public static function ExecuteScript() {
        
        global $config;
        
        $entityBody = json_decode(file_get_contents('php://input'), true);
        
        $ascript = $entityBody["scriptname"];
        $env = $entityBody["env"];
        
        $script_data = self::getScriptFromConfig($ascript);

        $script = $script_data["chemin"];
        $params = "";

        if($script_data["type"] == "sql") {

            $params = " -h " . $config['db'][$env]['host'] . " -d " . $config['db'][$env]['database'] . " -U " . $config['db'][$env]['username'];
            

            foreach($script_data["parametres"] as $param) {

                if(isset($entityBody[$param['key']]) && !empty($entityBody[$param['key']])) {
                    
                    $params .= " -v " .$param['key'] . "=" . escapeshellarg($entityBody[$param['key']]);

                } else {

                    header('Content-type: application/json');
                    echo json_encode(["result" => ["Parametre : " . $param['key'] . " vide"] , 'status' => 2]);
                    exit;
                }
            }
            

            $params .= " -f " . __DIR__ . '/scripts/' . $script . '.sql';

            $cmd = '/usr/bin/psql ' . $params;

        } else if($script_data["type"] == "bash") {


            $envssh = $config['ssh'][$env];

            foreach($script_data["parametres"] as $param) {

                if(isset($entityBody[$param['key']]) && !empty($entityBody[$param['key']])) {

                    if($envssh == null) {
                        $params .= " --" .$param['key'] . " " . escapeshellarg($entityBody[$param['key']]);
                    } else {
                        $params .= ' --' .$param['key'] . ' ' . self::EscapeCharacters($entityBody[$param['key']]);
                    }

                } else {

                    header('Content-type: application/json');
                    echo json_encode(["result" => ["Parametre : " . $param['key'] . " vide"] , 'status' => 2]);
                    exit;
                }
            }

            if($envssh != null) {

                $host = $envssh['host'];
                $username = $envssh['username'];

                $cmd = '/usr/bin/ssh ' . $username . '@' . $host . ' /bin/bash -s - < '.__DIR__ . '/scripts/'.$script.'.sh '.$params;


            } else {

                $cmd = '/bin/bash ' .__DIR__ . '/scripts/'.$script.'.sh '.$params;
            } 

        }

        $result = [];
        $status = null;
        
        exec($cmd, $result, $status);
        
        header('Content-type: application/json');
        echo json_encode(["result" => $result , 'status' => $status]);
        exit;
    }

}