<?php if (!defined('APPLICATION')) exit();

$PluginInfo['RolebasedTheme'] = array(
   'Name' => 'Rolebased Theme',
   'Description' => 'Allows assigning a different theme to one role. Might not work as expected for users with more than one role.',
   'Version' => '0.1',
   'MobileFriendly' => TRUE,
   'SettingsUrl' => '/dashboard/settings/rolebasedtheme',
   'SettingsPermission' => 'Garden.Settings.Manage',
   'Author' => 'Robin',
   'License' => 'MIT'
);

class RolebasedThemePlugin extends Gdn_Plugin {

   public function Setup() {
      $RoleModel = new RoleModel();
      foreach($RoleModel->GetArray() as $Role) {
         $ConfigKey = 'Plugins.RolebasedTheme.'.$Role;
         if (C($ConfigKey) == '') {
            SaveToConfig($ConfigKey, Theme());
         }
      }      
   }
   
   public function Base_Render_Before($Sender) {
      $UserID = Gdn::Session()->User->UserID;
      $RoleModel = new RoleModel();
      $UserRoles = $RoleModel->GetByUserID($UserID)->ResultArray();
      $Theme = C('Plugins.RolebasedTheme.'.$UserRoles[0]['Name'], Theme());
      $Sender->Theme = $Theme;
   }

   public function SettingsController_RolebasedTheme_Create($Sender) {
      $Sender->Permission('Garden.Settings.Manage');
      $Sender->AddSideMenu('dashboard/settings/plugins');

      $Sender->SetData('Title', T('Rolebased Theme Assignments'));
      $ThemeManager = new Gdn_ThemeManager;
      $Themes = array();
      foreach ($ThemeManager->AvailableThemes() as $Theme) {
         $Themes[$Theme['Index']] = $Theme['Name'];
      }
      $Sender->SetData('Themes', $Themes);
      $RoleModel = new RoleModel();
      $Sender->SetData('Roles', $RoleModel->GetArray());

      $Validation = new Gdn_Validation();
      $ConfigurationModel = new Gdn_ConfigurationModel($Validation);
      foreach($RoleModel->GetArray() as $Role) {
         $ConfigKeys[] = 'Plugins.RolebasedTheme.'.$Role;
      }
      $ConfigurationModel->SetField($ConfigKeys);

      $Form = $Sender->Form;
      $Sender->Form->SetModel($ConfigurationModel);

      if ($Sender->Form->AuthenticatedPostBack() === FALSE) {
         $Sender->Form->SetData($ConfigurationModel->Data);
      } else {
         $Saved = $Sender->Form->Save();
         if($Saved) {
            $Sender->InformMessage(T('Your changes have been saved.'));
         }
      }

      $Sender->Render('settings', '', 'plugins/RolebasedTheme');   
   }
   
}
