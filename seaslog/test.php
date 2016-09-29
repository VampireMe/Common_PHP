<?php
/**
 * @version 0.0.0.1
 */

SeasLog::setBasePath('./log');
SeasLog::setLogger('test');
SeasLog::log(SEASLOG_INFO, 'this is the seaslog test2 !');
