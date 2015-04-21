<?php namespace Subfission\Doodad;


/**
 * Author: Zach Jetson
 */

class Doodad {

    private $system_hook;
    private $cmd;
    private $ignore_errors;

    public function __construct()
    {
        $this->checkSystemFunctionality();
    }

    /**
     * Perform an 'exec' system call
     * @param $cmd
     */
    public function execute($cmd)
    {
        $this->cmd['cmd'] = $cmd;

        return $this;
    }

    public function ignoreErrors()
    {
        $this->ignore_errors = true;
    }

    public function quietly($cmd)
    {
        return $this->call($cmd, true);
    }

    protected function call($cmd, $silent = false)
    {
        if (!$this->system_hook)
        {
            return false;
        }

        if ($this->ignore_errors && (strpos($cmd, '/dev/null') === false))
        {
            $cmd = $cmd . ' 2> /dev/null';
        }

        $cmd_obj = new CmdObject($cmd);

        // filter through system calls and select appropriate call
        $cmd_obj->{$this->system_hook};

        return $cmd_obj;
    }

    /**
     * Determine what functions the system has enabled
     */
    protected function checkSystemFunctionality()
    {
        switch (true)
        {
            case function_exists('exec'):
                $this->system_hook = 'exec';
                break;

            case function_exists('shell_exec'):
                $this->system_hook = 'shellExec';
                break;

            case function_exists('system'):
                $this->system_hook = 'system';
                break;

            default :
                break;
        }
    }
}