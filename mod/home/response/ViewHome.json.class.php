<?php
/*
 * Simple Framework SOA
 * Module Version 1.0
 * Author: Galih <galih_xp@yahoo.com or galih.xp@gmail.com>
 */

class ViewHome extends JsonResponse {
   static function ProcessRequest() {

      $time_end = getmicrotime();
      $time = $time_end - Config::instance()->getValue("start_time");

      $endOfMemory = memory_get_usage();

      $memory_usage = convert($endOfMemory - Config::instance()->getValue("start_memory"));

      return array("result" => MessageResult::instance()->requestSukses(array("time" => $time, "memory" => $memory_usage)));
   }
}