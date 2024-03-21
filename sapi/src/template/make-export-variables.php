CPPFLAGS=""
CFLAGS=""
LDFLAGS=""
LIBS=""
<?php foreach ($this->variables as $name => $value) : ?>
<?= key($value) ?>="<?= current($value) ?>"
<?php endforeach; ?>
result_code=$?
[[ $result_code -ne 0 ]] &&  echo " [ export_variables  FAILURE ]" && exit  $result_code;
<?php foreach ($this->exportVariables as $value) : ?>
export  <?= key($value) ?>="<?= current($value) ?>"
<?php endforeach; ?>
export CPPFLAGS=$(echo $CPPFLAGS | tr ' ' '\n' | sort | uniq | tr '\n' ' ')
export LDFLAGS=$(echo $LDFLAGS | tr ' ' '\n' | sort | uniq | tr '\n' ' ')
export LIBS=$(echo $LIBS | tr ' ' '\n' | sort | uniq | tr '\n' ' ')
result_code=$?
[[ $result_code -ne 0 ]] &&  echo " [ export_variables  FAILURE ]" && exit  $result_code;
