<?php

class UserrecommandationPostprocessModuleFrontController extends ModuleFrontController
{
    public function postProcess()
    {
        PrestaShopLogger::addLog('Je passe dans la fonction postProcess', 1, null, 'CustomTest');
        // Vérifiez si le formulaire a été soumis
        if (Tools::isSubmit('submit_preferences')) {
            $id_customer = (int)$this->context->customer->id;
            $type_consommation = Tools::getValue('type_consommation');
            $souhaitez_cereales_originales = Tools::getValue('souhaitez_cereales_originales');
            $gout_prefere = Tools::getValue('gout_prefere');
            $forme_favorite = Tools::getValue('forme_favorite');
            $consommation_pour_qui = Tools::getValue('consommation_pour_qui');

            //PrestaShopLogger::addLog('Avant de passer dans la fonction saveCustomerPreferences', 1, null, 'CustomTest');

            // Appeler la méthode saveCustomerPreferences() de votre module pour sauvegarder les données
            $this->module->saveCustomerPreferences(
                $id_customer,
                $type_consommation,
                $souhaitez_cereales_originales,
                $gout_prefere,
                $forme_favorite,
                $consommation_pour_qui
            );

            // Redirection ou message de succès
            $this->redirectWithNotifications('index.php?controller=my-account');
        }
    }
}
