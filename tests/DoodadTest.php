<?php namespace Subfission\Doodad\Test;


/***********************************
 * Author: Zach Jetson
 * Licence: MIT
 ***********************************/

use Subfission\Doodad\CmdObject;
use Subfission\Doodad\Doodad;

/**
 * Class DoodadTest
 * @package Subfission\Doodad\Test
 */
class DoodadTest extends \PHPUnit_Framework_TestCase {

    /**
     * Test that ls does in fact show directory contents
     */
    public function testDirectoryListing()
    {
        $cmd = new Doodad();
        $ret = $cmd->execute('ls');
        $this->assertEquals(0, $ret->getStatusCode());
        $this->assertEquals("0", $ret->getReturnVal());
        $this->assertContains("LICENSE", $ret->getOutput());
    }

    public function testPrettyOutput()
    {
        $cmd = new Doodad();
        $ret = $cmd->execute('ls');
        $this->assertRegExp('/\<pre>/', $ret->getOutputPretty());
        $this->assertRegExp('/\<pre class="pre-scrollable/', $ret->getOutputPretty('pre-scrollable'));
    }

    public function testIgnoreFunction()
    {
        $cmd = new Doodad();
        $ret = $cmd->execute('ls /nonexistant/');
        $this->assertEquals(2, $ret->getReturnVal());
        $this->assertContains('ls: cannot access /nonexistant/: No such file or directory', $ret->getOutput());
        $ret = $cmd->ignoreErrors()->execute('ls /nonexistant/');
        $this->assertArrayNotHasKey(0, $ret->getOutput());
    }

    /**
     * Test to see if the system hooks for exec calls are available (shell_exec, exec, system)
     */
    public function testCheckSystemHooks()
    {
        $cmd = new Doodad();
        $this->assertNotEmpty($cmd);
        $this->assertAttributeContains('exec', 'system_hook', $cmd);
        $cmd->execute('ls');
        $this->assertAttributeNotEmpty('cmd', $cmd);
        $this->assertEquals('ls', $cmd->lastCommand());

        $cmd = (new \ReflectionMethod('Subfission\Doodad\Doodad', 'checkSystemFunctionality'));
        $cmd->setAccessible(true);
        $exec = $cmd->invoke(new Doodad);
        $this->assertEquals('exec', $exec);
    }

    public function testShellExecCalls()
    {
        $exec = new CmdObject('ls 2>&1');
        $exec->shellExec();
        $this->assertAttributeEmpty('return_value', $exec);
        $this->assertArrayHasKey(0, $exec->getOutput());
    }

    public function testSystemExecCalls()
    {
        $exec = new CmdObject('ls 2>&1');
        $exec->system();
        $this->assertEquals(0, $exec->getReturnVal());
        $this->assertArrayHasKey(0, $exec->getOutput());
    }
}
