<?php
/**
 * Author: Zach Jetson
 */

namespace Subfission\Doodad;


class CmdObject {

    /**
     * @var
     */
    protected $return_value;
    protected $output = [];
    protected $cmd;

    public function __construct($cmd)
    {
        $this->cmd = $cmd;
    }

    public function getReturnVal()
    {
        return $this->return_value;
    }

    public function getStatusCode()
    {
        return (int) $this->getReturnVal();
    }

    /**
     * Get the output
     * @return array
     */
    public function getOutput()
    {
        return $this->output;
    }

    /**
     * Display the output of the command in a formatted pre tag
     * @param string $class
     * @return string
     */
    public function getOutputPretty($class = '')
    {
        $class = ($class == '' ?: ' class="' . $class . '"');

        return "<pre {$class}>" .
        implode('<br />', $this->output) .
        '</pre>';
    }

    /**
     * Execute a command through shell_exec
     * @void
     */
    public function shellExec()
    {
        $this->output[] = shell_exec($this->cmd);
    }

    /**
     * Execute a command through exec
     * @void
     */
    public function exec()
    {
        exec($this->cmd, $this->output, $this->return_value);
    }

    /**
     * Execute a command through system
     * @void
     */
    public function system()
    {
        $this->output[] = system($this->cmd, $this->return_value);
    }

}