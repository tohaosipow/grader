<?php

namespace Tests;

class HtaccessTask2 extends Task
{


    public function upload()
    {
        var_dump(addslashes($this->student_response));
        echo $this->ssh->exec("cd docker-compose-lamp && rm -rf www && mkdir www && cd www && echo '{$this->student_response}' > .htaccess && echo 'Hello world' > index.html");
        echo $this->ssh->exec("cd docker-compose-lamp && docker-compose restart");
        //&& echo "'.$this->student_response.'" > .htaccess'
        //echo $this->ssh->exec('ls');
    }


    public function test()
    {
        $point = 0;
        $r1 = $this->checkRedirect('http://165.22.21.183:3000/ima2ge.jpg');
        var_dump($r1);
        if ($r1 != null && $r1 == 'https://yandex.ru') $point++;
        return [$point, "Это простое задание. Если неверно - гугл в помощь."];
    }

    public function checkRedirect($url)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, TRUE);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, FALSE);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        $a = curl_exec($ch);
        if (preg_match('#Location: (.*)#', $a, $r)) {
            $l = trim($r[1]);
            return $l;
        }
        return null;
    }
}