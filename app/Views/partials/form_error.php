<?php /** Galat umum form (mis. data ganda dari database). Variabel: $errors */ ?>
<?php if (isset($errors['_form'])): ?>
  <div class="alert alert-danger"><?= e($errors['_form']) ?></div>
<?php endif; ?>
