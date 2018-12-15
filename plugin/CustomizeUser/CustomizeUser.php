<?php

global $global;
require_once $global['systemRootPath'] . 'plugin/Plugin.abstract.php';

class CustomizeUser extends PluginAbstract {

    public function getDescription() {
        $txt = "Fine Tuning User Profile";
        return $txt;
    }

    public function getName() {
        return "CustomizeUser";
    }

    public function getUUID() {
        return "55a4fa56-8a30-48d4-a0fb-8aa6b3fuser3";
    }

    public function getPluginVersion() {
        return "1.0";
    }

    public function getEmptyDataObject() {
        global $advancedCustom;
        $obj = new stdClass();
        $obj->userCanAllowFilesDownload = false;
        $obj->userCanAllowFilesShare = false;


        $obj->usersCanCreateNewCategories = !isset($advancedCustom->usersCanCreateNewCategories) ? false : $advancedCustom->usersCanCreateNewCategories;
        $obj->userCanNotChangeCategory = !isset($advancedCustom->userCanNotChangeCategory) ? false : $advancedCustom->userCanNotChangeCategory;
        $obj->userMustBeLoggedIn = !isset($advancedCustom->userMustBeLoggedIn) ? false : $advancedCustom->userMustBeLoggedIn;
        $obj->onlyVerifiedEmailCanUpload = !isset($advancedCustom->onlyVerifiedEmailCanUpload) ? false : $advancedCustom->onlyVerifiedEmailCanUpload;
        $obj->sendVerificationMailAutomaic = !isset($advancedCustom->sendVerificationMailAutomaic) ? false : $advancedCustom->sendVerificationMailAutomaic;
        $obj->unverifiedEmailsCanNOTLogin = !isset($advancedCustom->unverifiedEmailsCanNOTLogin) ? false : $advancedCustom->unverifiedEmailsCanNOTLogin;
        $obj->newUsersCanStream = !isset($advancedCustom->newUsersCanStream) ? false : $advancedCustom->newUsersCanStream;
        $obj->doNotIndentifyByEmail = !isset($advancedCustom->doNotIndentifyByEmail) ? false : $advancedCustom->doNotIndentifyByEmail;
        $obj->doNotIndentifyByName = !isset($advancedCustom->doNotIndentifyByName) ? false : $advancedCustom->doNotIndentifyByName;
        $obj->doNotIndentifyByUserName = !isset($advancedCustom->doNotIndentifyByUserName) ? false : $advancedCustom->doNotIndentifyByUserName;
        $obj->hideRemoveChannelFromModeYoutube = !isset($advancedCustom->hideRemoveChannelFromModeYoutube) ? false : $advancedCustom->hideRemoveChannelFromModeYoutube;
        $obj->showChannelBannerOnModeYoutube = !isset($advancedCustom->showChannelBannerOnModeYoutube) ? false : $advancedCustom->showChannelBannerOnModeYoutube;
        $obj->encryptPasswordsWithSalt = !isset($advancedCustom->encryptPasswordsWithSalt) ? false : $advancedCustom->encryptPasswordsWithSalt;
        $obj->requestCaptchaAfterLoginsAttempts = !isset($advancedCustom->requestCaptchaAfterLoginsAttempts) ? 0 : $advancedCustom->requestCaptchaAfterLoginsAttempts;
        $obj->disableNativeSignUp = !isset($advancedCustom->disableNativeSignUp) ? false : $advancedCustom->disableNativeSignUp;
        $obj->disableNativeSignIn = !isset($advancedCustom->disableNativeSignIn) ? false : $advancedCustom->disableNativeSignIn;
        $obj->disablePersonalInfo = !isset($advancedCustom->disablePersonalInfo) ? true : $advancedCustom->disablePersonalInfo;

        return $obj;
    }

    static function canDownloadVideosFromUser($users_id) {
        global $config;
        $obj = YouPHPTubePlugin::getObjectDataIfEnabled("CustomizeUser");
        if (empty($obj) || empty($obj->userCanAllowFilesDownload)) {
            return $config->getAllow_download();
        }
        $user = new User($users_id);
        return !empty($user->getExternalOption('userCanAllowFilesDownload'));
    }

    static function setCanDownloadVideosFromUser($users_id, $value = true) {
        $obj = YouPHPTubePlugin::getObjectDataIfEnabled("CustomizeUser");
        if (empty($obj) || empty($obj->userCanAllowFilesDownload)) {
            return false;
        }
        $user = new User($users_id);
        return $user->addExternalOptions('userCanAllowFilesDownload', $value);
    }

    static function canShareVideosFromUser($users_id) {
        global $advancedCustom;
        
        if(!empty($advancedCustom->disableShareAndPlaylist)){
            return false;
        }
        
        $obj = YouPHPTubePlugin::getObjectDataIfEnabled("CustomizeUser");
        if (empty($obj) || empty($obj->userCanAllowFilesShare)) {
            return true;
        }
        $user = new User($users_id);
        return !empty($user->getExternalOption('userCanAllowFilesShare'));
    }

    static function setCanShareVideosFromUser($users_id, $value = true) {
        $obj = YouPHPTubePlugin::getObjectDataIfEnabled("CustomizeUser");
        if (empty($obj) || empty($obj->userCanAllowFilesShare)) {
            return false;
        }
        $user = new User($users_id);
        return $user->addExternalOptions('userCanAllowFilesShare', $value);
    }

    static function getSwitchUserCanAllowFilesDownload($users_id) {
        global $global;
        include $global['systemRootPath'] . 'plugin/CustomizeUser/switchUserCanAllowFilesDownload.php';
    }

    static function getSwitchUserCanAllowFilesShare($users_id) {
        global $global;
        include $global['systemRootPath'] . 'plugin/CustomizeUser/switchUserCanAllowFilesShare.php';
    }

    public function getMyAccount($users_id) {

        $objcu = YouPHPTubePlugin::getObjectDataIfEnabled("CustomizeUser");

        if (!empty($objcu) && !empty($objcu->userCanAllowFilesDownload)) {
            echo '<div class="form-group">
    <label class="col-md-4 control-label">' . __("Allow Download My Videos") . '</label>
    <div class="col-md-8 inputGroupContainer">';
            self::getSwitchUserCanAllowFilesDownload($users_id);
            echo '</div></div>';
        }
        if (!empty($objcu) && !empty($objcu->userCanAllowFilesShare)) {
            echo '<div class="form-group">
    <label class="col-md-4 control-label">' . __("Allow Share My Videos") . '</label>
    <div class="col-md-8 inputGroupContainer">';
            self::getSwitchUserCanAllowFilesShare($users_id);
            echo '</div></div>';
        }
    }

    public function getTags() {
        return array('free', 'customization', 'users');
    }

}
