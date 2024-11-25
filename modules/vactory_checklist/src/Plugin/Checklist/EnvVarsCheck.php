<?php

namespace Drupal\vactory_checklist\Plugin\Checklist;

use Drupal\Core\Site\Settings;

/**
 * Vérifie la présence des variables d'environnement nécessaires.
 *
 * @ChecklistPlugin(
 *   id = "environment_variables_check",
 *   label = @Translation("Environnement Variables Check"),
 *   description = @Translation("Vérifie la présence des variables d'environnement nécessaires"),
 *   category = "env"
 * )
 */
class EnvVarsCheck extends ChecklistBase {

  /**
   * Liste des variables d'environnement requises.
   */
  protected const REQUIRED_VARIABLES = [
    'BASE_FRONTEND_URL',
    'FRONTEND_CACHE_KEY',
    'BASE_MEDIA_URL',
  ];

  /**
   * {@inheritdoc}
   */
  public function runCheck() {

    $missing_vars = [];

    foreach (self::REQUIRED_VARIABLES as $var) {
      $value = getenv($var);
      $settings = Settings::get($var, FALSE);

      if (empty($value) && empty($settings)) {
        $missing_vars[] = $var;
      }
    }

    $status = empty($missing_vars);
    $message = $status
      ? $this->t("les variables d'environnement nécessaires sont présents")
      : $this->t("@count variables d'environnement manquant.", ['@count' => count($missing_vars)]);

    $details = [];
    foreach ($missing_vars as $var) {
      $details[] = [
        'var' => $var,
      ];
    }

    return [
      'status' => $status,
      'message' => $message,
      'details' => $details,
    ];
  }

}
