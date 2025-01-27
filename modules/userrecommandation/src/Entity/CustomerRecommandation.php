<?php

class CustomerRecommandation extends ObjectModel
{
    public $id_customer;
    public $type_consommation;
    public $souhaitez_cereales_originales;
    public $gout_prefere;
    public $forme_favorite;
    public $consommation_pour_qui;

    public static $definition = [
        'table' => 'user_recommandation',
        'primary' => 'id_customer', // Correspond à la clé primaire de la table SQL
        'fields' => [
            'id_customer' => ['type' => self::TYPE_INT, 'validate' => 'isUnsignedInt', 'required' => true],
            'type_consommation' => ['type' => self::TYPE_STRING, 'validate' => 'isString', 'maxlength' => 255], // Validation simple pour texte et longueur max
            'souhaitez_cereales_originales' => ['type' => self::TYPE_STRING, 'validate' => 'isString', 'maxlength' => 255], // Validation simple pour texte et longueur max
            'gout_prefere' => ['type' => self::TYPE_STRING, 'validate' => 'isString', 'maxlength' => 255], // Validation simple pour texte et longueur max
            'forme_favorite' => ['type' => self::TYPE_STRING, 'validate' => 'isString', 'maxlength' => 255], // Validation simple pour texte et longueur max
            'consommation_pour_qui' => ['type' => self::TYPE_STRING, 'validate' => 'isString', 'maxlength' => 255], // Validation simple pour texte et longueur max
        ],
    ];

    protected $webserviceParameters = [
        'objectNodeName' => 'user_recommandation',
        'objectsNodeName' => 'user_recommandations',
        'fields' => [
            'id_customer' => ['xlink_resource' => 'customers','required' => true],
            'type_consommation' => [],
            'souhaitez_cereales_originales' => [],
            'gout_prefere' => [],
            'forme_favorite' => [],
            'consommation_pour_qui' => [],
        ],
    ];


}
