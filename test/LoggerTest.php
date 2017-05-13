<?php
namespace yii\mustache;

use PHPUnit\Framework\{TestCase};
use yii\base\{InvalidParamException};

/**
 * Tests the features of the `yii\mustache\Logger` class.
 */
class LoggerTest extends TestCase {

  /**
   * @test Logger::log
   */
  public function testLog() {
    it('should throw an exception if the log level is invalid', function() {
      expect(function() { (new Logger)->log('dummy', 'Hello World!'); })->to->throw(InvalidParamException::class);
    });
  }
}
