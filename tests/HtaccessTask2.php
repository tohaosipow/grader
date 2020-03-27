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
        curl_setopt($ch, CURLOPT_HEADER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1)');

        $a = curl_exec($ch);
        echo 'Ошибка curl: ' . curl_error($ch);
        var_dump($a);
        if (preg_match('#Location: (.*)#', $a, $r)) {
            $l = trim($r[1]);
            return $l;
        }
        return null;
    }
}