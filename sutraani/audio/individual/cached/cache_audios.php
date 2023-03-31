<?php
/* This script reads all the audio files of each paad and combines them in a single file 
 * (i.e. 1 file per paad), which can then be returned by the connections/getAudioData.php call.
 * 
 * This function was run on 18th Oct 2021. It was run on the local machine, and then the generated files were copied 
 * to the production server.
 * 
 * Unless the audio files are updated, this function does not need to be run again. 
 * But if it is ever needed, you can just invoke this script, without making any changes to it. 
 * Please note that this script will overwrite ALL the 32 files in this directory.
 * Therefore, if you want to generate only a specific file, you can either tweak this code
 * OR just make a backup of the current data and use it to replace the newly generated data. 
 * 
 * To invoke: Switch to the current directory and just say "php ./cache_audio.php"
 * (The "./" part is important, the script won't work without it)
 */

function main() {
  // For Shivasutras.
  buildCacheFileForShivasutra();
  
  // For ashtadhyayi adhaay and paad
  for ($a = 1; $a <= 8; ++$a) {
    for ($p = 1; $p <= 4; ++$p) {
      buildCacheFileForSpecificPaad($a, $p);
    }
  }
}

function buildCacheFileForShivasutra() {
  $arr = Array();
  for ($n = 0; $n <= 14; ++$n) {
    $data = readAudioFromFile("0", "0", $n);
    $arr[$n] =  $data;
  }
  
  $output = json_encode($arr);
  writeDataToFile("0", "0", $output);
}

function buildCacheFileForSpecificPaad($a, $p) {
  $arr = Array();
  
  for ($n = 0; $n <= 240; ++$n) {
    $data = readAudioFromFile($a, $p, $n);
    if ($n < 10) {
      $n = '00' . $n;
    } else if ($n < 100) {
      $n = '0' . $n;
    }
      
    $key = $a.$p.$n;
    $arr[$key] =  $data;
  }
  
  $output = json_encode($arr);
  writeDataToFile($a, $p, $output);
} 

function readAudioFromFile($a, $p, $n) {
  $fileName = "../${a}_${p}/${a}-${p}-${n}.mp3";
  $data = "";
  if (file_exists($fileName)) {
    $data =  'data:audio/mp3;base64,' . base64_encode(file_get_contents($fileName));
  }
  return $data;
}

function writeDataToFile($a, $p, $data) {
  $outputFileName = "./${a}_${p}.txt";
  file_put_contents($outputFileName, $data);
}

// Actual invocation below.
main();

?>
