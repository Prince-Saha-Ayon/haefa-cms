<?php
  $filePath = base_path('images/version.txt');

  $fileContents = File::get($filePath);
  $lines = explode("\n", $fileContents);
  $lastLine = end($lines);

?>
                

<footer class="dt-footer">
    Copyright API © 2023 | Nirog Plus Version: {{ $lastLine }}
  </footer>
