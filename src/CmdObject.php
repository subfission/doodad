<?php namespace Subfission\Doodad;


/***********************************
 * Author: Zach Jetson
 * Licence: MIT
 ***********************************/

/**
 * Class CmdObject
 * @package Subfission\Doodad
 */
class CmdObject {

    protected $return_value;
    protected $output = [];
    protected $cmd;


    /**
     * Accepts a string for command
     * @param $cmd
     */
    public function __construct($cmd)
    {
        $this->cmd = $cmd;
    }

    /**
     * Get the return value of the command
     * in whatever format was provided.
     * Usually this will be integer.
     *
     * @return mixed
     */
    public function getReturnVal()
    {
        return $this->return_value;
    }


    /**
     * Get the return status code as an integer.
     * @return int
     */
    public function getStatusCode()
    {
        return (int) $this->getReturnVal();
    }

    /**
     * Get the output of the command as an array.
     * This method will return an empty array
     * if nothing was available for output.
     * @return array
     */
    public function getOutput()
    {
        return $this->output;
    }

    /**
     * Display the output of the command in a
     * formatted pre tag ready for view.
     * @param string $class
     * @return string
     */
    public function getOutputPretty($class = '')
    {
        $class ? $pre_class = implode('"', [" class=", $class, '']) : $pre_class = '';

        return "<pre{$pre_class}>" . implode('<br />' . PHP_EOL, $this->output) . '</pre>';
    }

    /**
     * Execute a command through shell_exec.
     * @void
     */
    public function shellExec()
    {
        $this->output[] = shell_exec($this->cmd);
    }

    /**
     * Execute a command through exec.
     * @void
     */
    public function exec()
    {
        exec($this->cmd, $this->output, $this->return_value);
    }

    /**
     * Execute a command through system.
     * @void
     */
    public function system()
    {
        $this->output[] = system($this->cmd, $this->return_value);
    }

}