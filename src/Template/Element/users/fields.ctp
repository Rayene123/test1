<?php 
    foreach ($fields as $name) {
        $prettyName = str_replace('_', ' ', $name);
        if (isset($optionalFields) && in_array($name, $optionalFields))
            $prettyName .= ' (optional)';
        $type = isset($specialTypes[$name]) ? $specialTypes[$name] : null;
        echo $this->element('users/field', compact('name', 'prettyName', 'type'));
    }
?>