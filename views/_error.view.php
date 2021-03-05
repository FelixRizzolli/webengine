<?php
use app\core\View;

/**
 * @var $this View
 * @var $exception Exception
 */

$this->setTitle($exception->getCode());
?>

<p>
    <?php echo($exception->getMessage()); ?>
</p>
