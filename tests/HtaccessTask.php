<?php

namespace Tests;

class HtaccessTask extends Task
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
        $r1 = $this->checkRedirect('http://165.22.21.183:3000/ima2ge.html');
        if ($r1 != null) return 0;
        $r1 = $this->checkRedirect('http://165.22.21.183:3000/ima2ge.jpg');
        if ($r1 != null && $r1 = 'http://i.imgur.com/qX4w7.gif') $point++;
        $r1 = $this->checkRedirect('http://165.22.21.183:3000/image.png');
        if ($r1 != null && $r1 = 'http://i.imgur.com/qX4w7.gif') $point++;
        $r1 = $this->checkRedirect('http://165.22.21.183:3000/kk.gif');
        if ($r1 != null && $r1 = 'http://i.imgur.com/qX4w7.gif') $point++;
        $r1 = $this->checkRedirect('http://165.22.21.183:3000/kk.jpeg');
        if ($r1 != null && $r1 = 'http://i.imgur.com/qX4w7.gif') $point++;
        $r1 = $this->checkRedirect('http://165.22.21.183:3000/kk.svg');
        if ($r1 != null && $r1 = 'http://i.imgur.com/qX4w7.gif') $point++;
        return [$point/5, "Если балл частичный, то Вы не все учли. Если неверно - ваш .htaccess не правильно работает."];
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