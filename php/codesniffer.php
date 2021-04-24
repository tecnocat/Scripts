<pre><?php

$files = scandir('.');

foreach ($files as $file) {

  if (is_file($file)) {

    $ruleset    = '/home/tecnocat/workspace/drupalcs/Drupal/ruleset.xml';
    $extensions = 'php,module,inc,install,test,profile,theme';
    $command    = "phpcs --standard=$ruleset --extensions=$extensions";
    $filepath   = getcwd() . '/' . $file;
    ob_start();
    system($command . ' ' . $filepath);
    $output = explode(PHP_EOL, ob_get_contents());
    ob_end_clean();

    foreach ($output as $index => $line) {

      if (trim($line)) {

        if (preg_match('/^FILE/', $line)) {
          echo $line . "\n";
          echo next($output) . "\n";
        }

        if (is_numeric($readline = trim(array_shift(explode('|', $line))))) {
          $content = explode(PHP_EOL, file_get_contents($filepath));
          $code    = (trim($content[$readline - 1])) ? highlight_string($content[$readline - 1], TRUE) : '';
          echo $line . $code . "\n";
        }
      }
    }
  }
}
?></pre>