<?php

require_once "vendor/autoload.php";
while (true) {
    $object = json_decode(getTask());
    var_dump($object);
    if ($object->return_code === 0) {
        $content = json_decode($object->content);
        $header = json_decode($content->xqueue_header);
        $body = json_decode($content->xqueue_body);
        $files = json_decode($content->xqueue_files);
        //$file = "project.zip";
        //var_dump($files->$file);
        $payload = json_decode($body->grader_payload);
        var_dump($payload);
        $class_name = "Tests\\" . $payload->task_name;
        /* @var $class \Tests\Task */
        $class = new $class_name($body->student_response, $files);
        $class->upload();
        $point = $class->test();
        putresult($header->submission_id, $header->submission_key, $point[0], $point[1]);
    } else echo "Нет новых задач \n";
}

function login(){
    $ch = curl_init("https://stepik.org/api/xqueue/login/");
    curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query(["username" => 'php_mvc1@external.grader', 'password' => 'aJtp2Dtzi3ySQnqi']));
    curl_setopt($ch, CURLOPT_USERPWD, "php_mvc1@external.grader:aJtp2Dtzi3ySQnqi");
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    curl_setopt($ch, CURLOPT_COOKIEJAR, 'cookies.txt');
    curl_setopt($ch, CURLOPT_COOKIEFILE, 'cookies.txt');
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    $return = (curl_exec($ch));
    curl_close($ch);
    return $return;
}


function getTask(){
    $ch = curl_init("https://stepik.org/api/xqueue/get_submission/?queue_name=php_mvc1");
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
    curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
    curl_setopt($ch, CURLOPT_COOKIEJAR, 'cookies.txt');
    curl_setopt($ch, CURLOPT_COOKIEFILE, 'cookies.txt');
    curl_setopt($ch, CURLOPT_USERPWD, "php_mvc1@external.grader:aJtp2Dtzi3ySQnqi");
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    $return = (curl_exec($ch));
    if (curl_errno($ch)) {
        $error_msg = curl_error($ch);
        echo $error_msg;
    }
    curl_close($ch);
    return $return;
}

function putresult($sub_id, $sub_key, $score, $msg){
    $ch = curl_init("https://stepik.org/api/xqueue/put_result/");
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded'));
    curl_setopt($ch, CURLOPT_HEADER, 1);
    curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query(["xqueue_body" => json_encode(array("score" => $score, "msg" => $msg)), 'xqueue_header' => json_encode(array("submission_id" => $sub_id, "submission_key" => $sub_key))]));
    curl_setopt($ch, CURLOPT_USERPWD, "php_mvc1@external.grader:aJtp2Dtzi3ySQnqi");
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_COOKIEJAR, 'cookies.txt');
    curl_setopt($ch, CURLOPT_COOKIEFILE, 'cookies.txt');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    $return = (curl_exec($ch));
    curl_close($ch);
    return $return;
}
