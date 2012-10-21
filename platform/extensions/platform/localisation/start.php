<?php
/**
 * Part of the Platform application.
 *
 * NOTICE OF LICENSE
 *
 * Licensed under the 3-clause BSD License.
 *
 * This source file is subject to the 3-clause BSD License that is
 * bundled with this package in the LICENSE file.  It is also available at
 * the following URL: http://www.opensource.org/licenses/BSD-3-Clause
 *
 * @package    Platform
 * @version    1.1.0
 * @author     Cartalyst LLC
 * @license    BSD License (3-clause)
 * @copyright  (c) 2011 - 2012, Cartalyst LLC
 * @link       http://cartalyst.com
 */


/*
 * --------------------------------------------------------------------------
 * Register some namespaces.
 * --------------------------------------------------------------------------
 */
Autoloader::namespaces(array(
    'Platform\\Localisation\\Widgets' => __DIR__ . DS . 'widgets',
    'Platform\\Localisation\\Model'   => __DIR__ . DS . 'models'
));


/*
 * --------------------------------------------------------------------------
 * Include our helpers file.
 * --------------------------------------------------------------------------
 */
require_once __DIR__ . DS . 'helpers.php';


/*
 * --------------------------------------------------------------------------
 * Check if the extension is enabled !
 * --------------------------------------------------------------------------
 */
if (Platform::extensions_manager()->is_enabled('localisation'))
{
    // Get all the localisation settings.
    //
    $settings = array();
    foreach (DB::table('settings')->where('extension', '=', 'localisation')->get() as $setting)
    {
        $settings[ $setting->name ] = $setting->value;
    }

    // Set the currency.
    //
    Config::set('application.currency', strtolower($settings['currency']));

    // Set the language.
    //
    Config::set('application.language', strtolower($settings['language']));

    // Set the timezone.
    //
    Config::set('application.timezone', $settings['timezone']);

    // Set the Openexchangerates.org API Key.
    //
    if (($api_key = $settings['currency_api_key']) != '')
    {
        Config::set('application.currency_api_key', $api_key);
    }

    // Update the currencies exchange rates.
    //
    if ($api_key && ($auto_update = $settings['currency_auto_update']) > 0)
    {
        // Store the value.
        //
        Config::set('application.currency_auto_update', $auto_update);

        // Proceed with the update.
        //
        Platform\Localisation\Currency::update_currencies();
    }
}