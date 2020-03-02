<?php

namespace Tests;

use Net_SSH2;

abstract class Task
{
    protected $ssh;
    protected $student_response;
    protected $files;

    /**
     * Task constructor.
     */
    public function __construct($student_response, $files)
    {
        $this->student_response = $student_response;
        $this->files = $files;
        $this->ssh = new Net_SSH2('165.22.21.183');
        if (!$this->ssh->login('grader', '123456@')) {
            exit('Login SSH Failed');
        }
        //echo $this->ssh->exec("cd docker-compose-lamp");
    }

    public abstract function upload();

    public abstract function test();
}