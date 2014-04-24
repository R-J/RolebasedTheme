<?php defined('APPLICATION') or exit();?>

<h1><?php echo $this->Data('Title');?></h1>
<div class="Info"><?php echo T('Assign themes to the existing roles');?></div>

<?php
echo $this->Form->Open();
echo $this->Form->Errors();
?>
<ul>
<?php
foreach($this->Data('Roles') as $Role) {
?>
<li>
<?php   
   echo $this->Form->Label($Role, 'Plugins.RolebasedTheme.'.$Role);
   echo $this->Form->DropDown('Plugins.RolebasedTheme.'.$Role, $this->Data('Themes'));
?>
</li>
<?php   
}
?>
</ul>
<?php
echo $this->Form->Button('Save');
echo $this->Form->Close();
