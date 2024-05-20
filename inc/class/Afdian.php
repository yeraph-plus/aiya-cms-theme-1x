<?php
/**
 * 爱发电
 * Version 1.0
 * 
 * By Yeraph.
 * https://www.yeraph.com/
 * 
 * 官方文档
 * https://afdian.net/dashboard/dev
 * 
 */

//返回结果处理
if ( ! class_exists( 'Afdian_Response' ) ) {
    class Afdian_Response {
        
        //初始化
        public function __construct($status, $headers, $data){
            $this->status = $status;
            $this->headers = $headers;
            $this->data = $data;
        }
    }
}

//查询组件
if ( ! class_exists( 'Afdian_Query' ) ) {
    class Afdian_Query{
        private $api_url = 'https://afdian.net/api/open/%s';

        private $userid, $token;

        //初始化
        public function __construct($userid, $token){
            $this->userid = $userid;
            $this->token  = $token;
        }

        //Curl函数
        public function curl($url, $postData = [], $cookie = '', $headers = false){
            $curl = curl_init();
            $rHeaders = [];
            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 10.0; Windows NT 6.1; Trident/6.0)');
            curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
            curl_setopt($curl, CURLOPT_AUTOREFERER, 1);
            curl_setopt($curl, CURLOPT_REFERER, $url);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($curl, CURLOPT_TIMEOUT, 60);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($curl, CURLOPT_HEADERFUNCTION,
                function ($curl, $header) use (&$rHeaders) {
                    $len = strlen($header);
                    $header = explode(':', $header, 2);
                    if (count($header) < 2) {
                        return $len;
                    }
                    $rHeaders[strtolower(trim($header[0]))][] = trim($header[1]);
                    return $len;
                }
            );
            if (!empty($postData)) {
                curl_setopt($curl, CURLOPT_POST, 1);
                if (is_array($postData)) {
                    curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($postData));
                } else {
                    curl_setopt($curl, CURLOPT_POSTFIELDS, $postData);
                }
            }
            if ($cookie) {
                curl_setopt($curl, CURLOPT_COOKIE, $cookie);
            }
            if ($headers) {
                curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
            }
            $data = curl_exec($curl);
            $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
            if (curl_errno($curl)) {
                $httpCode = curl_error($curl);
            }
            curl_close($curl);
            return new Afdian_Response($httpCode, $rHeaders, $data);
        }

        //计算API请求需要的签名
        public function signature($params, $time){
            return md5("{$this->token}params{$params}ts{$time}user_id{$this->userid}");
        }

        //Api查询
        public function query($api_name, $params){
            if(!isset($api_name) || empty($api_name)){
                return new Afdian_Response('Error: Empty api name endpoint', [], '');
            }
            $params    = json_encode($params);
            $queryData = json_encode([
                'user_id' => $this->userid,
                'params'  => $params,
                'ts'      => time(),
                'sign'    => $this->signature($params, time())
            ]);
            return $this->curl(sprintf($this->api_url, $api_name), $queryData, false, ['Content-Type: application/json']);
        }

        //Api查询 返回Ping状态
        public function ping(){
            $result = $this->query('ping', ['ping' => 'hello world']);
            if($result->status == 200) {
                $json = json_decode($result->data, true);
                if($json && is_array($json)) {
                    return (isset($json['ec']) && $json['ec'] == 200);
                }
                return new Afdian_Response('Error: Ping api failed', [], '');
            }
            return false;
        }

        //Api查询 查询订单号
        public function order($order = ''){
            $result = $this->query('query-order', ['out_trade_no' => $order]);
            if($result->status == 200) {
                $json = json_decode($result->data, true);
                if($json && is_array($json)) {
                    return (isset($json['ec']) && $json['ec'] == 200) ? $json : $json['em'];
                }
                return new Afdian_Response('Error: Cannot parse json string', [], '');
            }
            return $result->status;
        }

        //Api查询 返回订单列表
        public function get_orders($page = 1){
            $result = $this->query('query-order', ['page' => $page]);
            if($result->status == 200) {
                $json = json_decode($result->data, true);
                if($json && is_array($json)) {
                    return (isset($json['ec']) && $json['ec'] == 200) ? $json : $json['em'];
                }
                return new Afdian_Response('Error: Cannot parse json string', [], '');
            }
            return $result->status;
        }

        //Api查询 返回赞助者列表
        public function get_sponsors($page = 1){
            $result = $this->query('query-sponsor', ['page' => $page]);
            if($result->status == 200) {
                $json = json_decode($result->data, true);
                if($json && is_array($json)) {
                    return (isset($json['ec']) && $json['ec'] == 200) ? $json : $json['em'];
                }
                return new Afdian_Response('Error: Cannot parse json string', [], '');
            }
            return $result->status;
        }

        //Tips：以下方法不会返回 total_page 信息

        //循环 获取所有订单
        public function get_all_orders(){
            $orders = ["data" => ["list" => []]];
            $result = $this->get_orders(1);
            if(isset($result['data']['list'], $result['data']['total_page'])) {
                foreach($result['data']['list'] as $order) {
                    $orders['data']['list'][] = $order;
                }
                for($i = 2;$i <= $result['data']['total_page'];$i++) {
                    $result = $this->get_orders($i);
                    if(isset($result['data']['list'])) {
                        foreach($result['data']['list'] as $order) {
                            $orders['data']['list'][] = $order;
                        }
                    }
                }
            }
            return $orders;
        }

        //循环 获取所有赞助者名单
        public function get_all_sponsors(){
            $sponsors = ["data" => ["list" => []]];
            $result   = $this->get_sponsors(1);
            if(isset($result['data']['list'], $result['data']['total_page'])) {
                foreach($result['data']['list'] as $order) {
                    $sponsors['data']['list'][] = $order;
                }
                for($i = 2;$i <= $result['data']['total_page'];$i++) {
                    $result = $this->get_sponsors($i);
                    if(isset($result['data']['list'])) {
                        foreach($result['data']['list'] as $order) {
                            $sponsors['data']['list'][] = $order;
                        }
                    }
                }
            }
            return $sponsors;
        }
    }
}

//工具类
if ( ! class_exists( 'Afdian_Tool' ) ) {
    class Afdian_Tool{

        //搜索订单
        public function serach_order($result, $order_id){
            if(isset($result['data']['list'])) {
                foreach($result['data']['list'] as $order) {
                    if($order['out_trade_no'] == $order_id) {
                        return $order;
                    }
                }
            }
            return false;
        }

        //搜索用户ID
        public function serach_user($result, $user_id){
            if(isset($result['data']['list'])) {
                foreach($result['data']['list'] as $sponsor) {
                    if(isset($sponsor['user'], $sponsor['user']['user_id']) && $sponsor['user']['user_id'] == $user_id) {
                        return $sponsor;
                    }
                }
            }
            return false;
        }

        //搜索用户名
        public function serach_user_name($result, $user_name){
            if(isset($result['data']['list'])) {
                foreach($result['data']['list'] as $sponsor) {
                    if(isset($sponsor['user'], $sponsor['user']['name']) && $sponsor['user']['name'] == $user_name) {
                        return $sponsor;
                    }
                }
            }
            return false;
        }

        //查询指定方案的订单列表
        public function list_plan_order($result, $plan_id){
            $orders = [];
            if(isset($result['data']['list'])) {
                foreach($result['data']['list'] as $order) {
                    if($order['plan_id'] == $plan_id) {
                        $orders[] = $order;
                    }
                }
            }
            return $orders;
        }

        //查询指定用户的订单列表
        public function list_user_order($result, $user_id){
            $orders = [];
            if(isset($result['data']['list'])) {
                foreach($result['data']['list'] as $order) {
                    if($order['user_id'] == $user_id) {
                        $orders[] = $order;
                    }
                }
            }
            return $orders;
        }

    }
}