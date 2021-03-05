<?php

use app\core\form\InputField;
use app\core\form\TextareaField;
use app\core\View;
use app\core\form\Form;
use app\models\ContactForm;

/**
 * @var $this View
 * @var $model ContactForm
 */

$this->setTitle('Contact');
?>

<?php $form = Form::begin('', 'POST'); ?>
    <?php echo(new InputField($model, 'subject')); ?>
    <?php echo((new InputField($model, 'email'))->emailField()); ?>
    <?php echo(new TextareaField($model, 'body')); ?>
    <div>
        <button type="submit" name="submit" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
            Submit
        </button>
    </div>
<?php Form::end(); ?>
