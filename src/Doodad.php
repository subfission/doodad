<?php namespace Subfission\Doodad;

/**
 * Author: Zach Jetson
 * Licence: MIT
 */

use \UnexpectedValueException;

/**
 * Class Doodad
 * @package Subfission\Doodad
 */
class Doodad {

    /**
     * @var $system_hook
     */
    protected $system_hook;

    /**
     * @var $cmd
     */
    protected $cmd = '';

    /**
     * @var $ignore_errors
     */
    private $ignore_errors;


    /**
     * Creates new instance of Doodad executor.
     * @usage :
     *
     * $cmd = new Doodad();
     * $output = $cmd->execute($cmd_string);
     * $output->getOutput();
     */
    public function __construct()
    {
        $this->system_hook = $this->checkSystemFunctionality();
    }

    /**
     * Send all errors to /dev/null. This method
     * allows for chaining of method calls.
     * @return Doodad
     */
    public function ignoreErrors()
    {
        $this->ignore_errors = true;

        return $this;
    }

    /**
     * Execute a call to the system given a
     * command.  If errors are enabled,
     * you will see the error as the
     * first result in output.
     *
     * @param $cmd String
     * @return bool|CmdObject
     */
    public function execute($cmd)
    {
        if (!$this->system_hook)
        {
            throw new UnexpectedValueException(
                'Unable to access available system calls methods. ' .
                'Check your php.ini file for restrictions on system calls.');
        }
        $this->cmd = $cmd;

        if ($this->ignore_errors && (strpos($cmd, '/dev/null') === false))
        {
            $cmd = $cmd . ' 2> /dev/null';
        } else
        {
            $cmd = $cmd . ' 2>&1';
        }

        $cmd_obj = new CmdObject($cmd);

        // filter through system calls and select appropriate call
        $cmd_obj->{$this->system_hook}();

        return $cmd_obj;
    }

    /**
     * Determine what functions the system has enabled.
     * @return string
     */
    protected function checkSystemFunctionality()
    {
        switch (true)
        {
            case function_exists('exec'):
                return 'exec';

            case function_exists('shell_exec'):
                return 'shellExec';

            case function_exists('system'):
                return 'system';

            default:
                return '';
        }
    }

    /**
     * Get the last command executed.
     * @return string
     */
    public function lastCommand()
    {
        return $this->cmd;
    }
}