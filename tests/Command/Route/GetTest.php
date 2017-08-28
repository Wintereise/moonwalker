<?php namespace Phprest\Command\Route;

use Phprest\Application as PhprestApp;
use Phprest\Config;
use Symfony\Component\Console\Application as ConsoleApp;
use Symfony\Component\Console\Tester\CommandTester;
use Symfony\Component\HttpFoundation\Response;

class GetTest extends \PHPUnit_Framework_TestCase
{
    public function testDisplayedData()
    {
        $phprestApp = new PhprestApp(new Config('phprest-test', '2.3', true));
        $phprestApp->get('/2.3/get-the-answer-of-everything', 'Phprest\Stub\Controller::getTheAnswerOfEverything');
        $phprestApp->get('/2.3/get-welcome-message', function() {
            return new Response('Welcome!');
        });

        $cliApp = new ConsoleApp();
        $cliApp->add(new Get($phprestApp));

        $command = $cliApp->find('routes:get');
        $commandTester = new CommandTester($command);
        $commandTester->execute(array('command' => $command->getName()));

        $displayedData = $commandTester->getDisplay();

        $this->assertContains(
            '| GET    | /2.3/get-the-answer-of-everything | Phprest\Stub\Controller::getTheAnswerOfEverything |',
            $displayedData
        );
        $this->assertContains(
            '| GET    | /2.3/get-welcome-message          | Closure                                           |',
            $displayedData
        );
    }
}
