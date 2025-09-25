<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// We get a performance increase by specifying the default
// route since we don't have to scan directories.
$routes->get('/', 'Dashboard::index');

//custom routing for custom pages
//this route will move 'about/any-text' to 'domain.com/about/index/any-text'
$routes->add('about/(:any)', 'About::index/$1');

//add routing for controllers
$excluded_controllers = array("About", "App_Controller", "Security_Controller");
$controller_dropdown = array();
$dir = "./app/Controllers/";
if (is_dir($dir)) {
    if ($dh = opendir($dir)) {
        while (($file = readdir($dh)) !== false) {
            $controller_name = substr($file, 0, -4);
            if ($file && $file != "." && $file != ".." && $file != "index.html" && $file != ".gitkeep" && !in_array($controller_name, $excluded_controllers)) {
                $controller_dropdown[] = $controller_name;
            }
        }
        closedir($dh);
    }
}

// add route for Collect_leads controller differently with the CORS filter for AJAX requests / API calls
$routes->post('collect_leads/save', 'Collect_leads::save', ['filter' => 'cors']);
$routes->options('collect_leads/save', 'Collect_leads::save', ['filter' => 'cors']);

$routes->get('locations/calendar', 'Locations::calendar');
$routes->get('locations/report', 'Locations::report');
$routes->get('locations/add', 'Locations::add');
$routes->get('locations/list_data', 'Locations::list_data');
$routes->post('locations/list_data', 'Locations::list_data');
$routes->get('locations/modal_form', 'Locations::modal_form');
$routes->post('locations/modal_form', 'Locations::modal_form');
$routes->post('locations/save', 'Locations::save');
$routes->post('locations/delete', 'Locations::delete');
$routes->get('locations/check_availability', 'Locations::check_availability');
$routes->post('locations/check_availability', 'Locations::check_availability');
$routes->post('locations/change_status/(:segment)', 'Locations::change_status/$1');
$routes->get('locations/download_pdf/(:segment)', 'Locations::download_pdf/$1');
$routes->get('locations/duplicate/(:segment)', 'Locations::duplicate/$1');
$routes->get('locations/export_csv', 'Locations::export_csv');
$routes->get('locations/get_calendar_data', 'Locations::get_calendar_data');

// Routes avec paramètres (moins spécifiques)
$routes->get('locations/view/(:segment)', 'Locations::view/$1');
$routes->get('locations/edit/(:segment)', 'Locations::edit/$1');

// Route générale (la plus générale à la fin)
$routes->get('locations', 'Locations::index');
$routes->get('locations/(:any)', 'Locations::$1');



// Routes spécifiques pour chauffeurs (avant les routes génériques)
$routes->get('chauffeurs/list_data', 'Chauffeurs::list_data');
$routes->post('chauffeurs/list_data', 'Chauffeurs::list_data');
$routes->get('chauffeurs/modal_form', 'Chauffeurs::modal_form');
$routes->post('chauffeurs/modal_form', 'Chauffeurs::modal_form');
$routes->get('chauffeurs/modal_form/(:segment)', 'Chauffeurs::modal_form/$1');
$routes->post('chauffeurs/save', 'Chauffeurs::save');
$routes->post('chauffeurs/delete', 'Chauffeurs::delete');
$routes->post('chauffeurs/change_status', 'Chauffeurs::change_status');
$routes->get('chauffeurs/view/(:segment)', 'Chauffeurs::view/$1');
$routes->get('chauffeurs/search_api', 'Chauffeurs::search_api');
$routes->get('chauffeurs/available_api', 'Chauffeurs::available_api');
$routes->get('chauffeurs/statistics', 'Chauffeurs::statistics');
$routes->get('chauffeurs/expiring_licenses', 'Chauffeurs::expiring_licenses');
$routes->get('chauffeurs/export_csv', 'Chauffeurs::export_csv');

// Route générale chauffeurs (à la fin)
$routes->get('chauffeurs', 'Chauffeurs::index');
$routes->get('chauffeurs/(:any)', 'Chauffeurs::$1');
// =================================================================
// FIN DE LA CORRECTION
// =================================================================
foreach ($controller_dropdown as $controller) {
    $routes->get(strtolower($controller), "$controller::index");
    $routes->get(strtolower($controller) . '/(:any)', "$controller::$1");
    $routes->post(strtolower($controller) . '/(:any)', "$controller::$1");
}

//add uppercase links

$routes->get("Updates", "Updates::index");
$routes->get("Updates/(:any)", "Updates::$1");
$routes->post("Updates/(:any)", "Updates::$1");

/*
 * --------------------------------------------------------------------
 * Additional Routing
 * --------------------------------------------------------------------
 *
 * There will often be times that you need additional routing and you
 * need it to be able to override any defaults in this file. Environment
 * based routes is one such time. require() additional route files here
 * to make that happen.
 *
 * You will have access to the $routes object within that file without
 * needing to reload it.
 */
if (is_file(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php')) {
    require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}
