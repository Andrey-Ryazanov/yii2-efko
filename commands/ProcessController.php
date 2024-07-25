<?php
/**
 * @link https://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license https://www.yiiframework.com/license/
 */

namespace app\commands;

use yii\console\Controller;
use yii\console\ExitCode;

/**
 * This command echoes the first argument that you have entered.
 *
 * This command is provided as an example for you to learn how to create console commands.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class ProcessController extends Controller
{
    public function actionQueueResults()
    {
        $basePath =  dirname(__DIR__)  . '/runtime/queue.job';
        $counter = 0;
        while (true) {
            $counter++;
            $result = 'Текущая итерация: ' . $counter . PHP_EOL;
            echo $result;
            if (file_exists($basePath)) {
                $result = file_get_contents($basePath);
                echo $result;
                unlink($basePath);
            }
            sleep(2);
        }
    }
}