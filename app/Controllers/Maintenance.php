<?php

namespace App\Controllers;

class Maintenance extends Security_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->Maintenance_model = model('App\Models\Maintenance_model');
        $this->check_module_availability("module_maintenance");
        
        // C'EST LA PREMIÈRE CORRECTION IMPORTANTE
        $this->init_permission_checker("maintenance");
    }

    /**
     * Affiche la vue principale avec la liste des maintenances.
     */
    public function index()
    {
        // CORRECTION : Ajout de la vérification de permission
        $this->access_only_allowed_members();

        return $this->template->rander("maintenance/index");
    }
// Dans app/Controllers/Maintenance.php

    /**
     * Affiche la vue détaillée d'une maintenance spécifique.
     */
    public function view($id = 0)
    {
        $this->access_only_allowed_members();

        if (!$id || !is_numeric($id)) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $view_data = [];
        // Utilise notre super méthode get_details pour récupérer toutes les infos
        $maintenance_info = $this->Maintenance_model->get_details(["id" => $id])->getRow();

        if (!$maintenance_info) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $view_data['model_info'] = $maintenance_info;

        return $this->template->rander("maintenance/view", $view_data);
    }
    /**
     * Affiche le formulaire modal pour ajouter ou modifier une maintenance.
     */
// Dans app/Controllers/Maintenance.php

/**
 * Affiche le formulaire modal pour ajouter ou modifier une maintenance.
 */
public function modal_form()
{
    $this->access_only_allowed_members();

    $this->validate_submitted_data([
        "id" => "numeric"
    ]);

    $view_data = [];
    $id = $this->request->getPost('id');
    
    // CORRECTION : On retire ->getRow() car le modèle le fait déjà pour nous quand un ID est passé.
  // Ligne à changer
$view_data['model_info'] = $this->Maintenance_model->get_details(['id' => $id])->getRow();

    // -- Préparation du dropdown pour les véhicules --
    $all_vehicles = $this->Vehicles_model->get_details()->getResult();
    $vehicles_dropdown = [];
    foreach ($all_vehicles as $vehicle) {
        $vehicle_name = $vehicle->marque . " " . $vehicle->modele . " (" . $vehicle->numero_matricule . ")";
        $vehicles_dropdown[$vehicle->id] = $vehicle_name;
    }
    $view_data['vehicles_dropdown'] = $vehicles_dropdown;
    // -- Fin de la préparation du dropdown --

    return $this->template->view('maintenance/modal_form', $view_data);
}

    /**
     * Sauvegarde (ajoute ou modifie) une maintenance.
     */
  // Dans app/Controllers/Maintenance.php

    /**
     * Sauvegarde (ajoute ou modifie) une maintenance.
     */
    public function save()
    {
        $this->access_only_allowed_members();

        $this->validate_submitted_data([
            "id" => "numeric",
            "vehicle_id" => "required|numeric",
            "date_maintenance" => "required",
            "km_maintenance" => "required|numeric"
        ]);

        $id = $this->request->getPost('id');
        $data = [
            "vehicle_id" => $this->request->getPost('vehicle_id'),
            "type_maintenance" => $this->request->getPost('type_maintenance'),
            "date_maintenance" => $this->request->getPost('date_maintenance'),
            "km_maintenance" => $this->request->getPost('km_maintenance'),
            "cout" => $this->request->getPost('cout'),
            "garage" => $this->request->getPost('garage'),
            "description" => $this->request->getPost('description'),
            "prochaine_maintenance_date" => $this->request->getPost('prochaine_maintenance_date'),
            "prochaine_maintenance_km" => $this->request->getPost('prochaine_maintenance_km')
        ];

        if ($id) {
            $data["id"] = $id;
        }

        $save_result = $this->Maintenance_model->save($data);

        if ($save_result) {
            
            // ▼▼▼ DÉBUT DE LA CORRECTION D'ID ▼▼▼

            // On détermine le VRAI ID numérique de l'enregistrement.
            // Si c'était une modification ($id existe), on utilise $id.
            // Si c'était un ajout, on utilise le résultat de la sauvegarde (qui sera le nouvel ID).
            $actual_id = $id ? $id : $save_result;

            // On récupère les données mises à jour en utilisant cet ID fiable.
            $options = ["id" => $actual_id];
            $data = $this->Maintenance_model->get_details($options)->getRow();
            
            // On renvoie le VRAI ID numérique dans la réponse JSON.
            echo json_encode(["success" => true, "data" => $this->_make_row($data), 'id' => $actual_id, 'message' => lang('record_saved')]);
            
            // ▲▲▲ FIN DE LA CORRECTION D'ID ▲▲▲

        } else {
            echo json_encode(["success" => false, 'message' => lang('error_occurred')]);
        }
    }

    /**
     * Supprime (soft delete) un enregistrement de maintenance.
     */
    public function delete()
    {
        // CORRECTION : Ajout de la vérification de permission
        $this->access_only_allowed_members();

        $this->validate_submitted_data([
            "id" => "required|numeric"
        ]);

        $id = $this->request->getPost('id');
        if ($this->Maintenance_model->delete($id)) {
            echo json_encode(["success" => true, 'message' => lang('record_deleted')]);
        } else {
            echo json_encode(["success" => false, 'message' => lang('record_cannot_be_deleted')]);
        }
    }

    /**
     * Prépare les données pour la liste (DataTables).
     */
    public function list_data()
    {
        // CORRECTION : Ajout de la vérification de permission
        $this->access_only_allowed_members();

       // Ligne à changer
$list_data = $this->Maintenance_model->get_details()->getResult();
        $result = [];
        foreach ($list_data as $data) {
            $result[] = $this->_make_row($data);
        }
        echo json_encode(["data" => $result]);
    }

    /**
     * Prépare une ligne de données pour l'affichage dans la table.
     */
    // Dans app/Controllers/Maintenance.php

private function _make_row($data)
{
    // Gère le cas où un véhicule aurait été supprimé de la base
    $vehicle_name = $data->vehicle_marque ? ($data->vehicle_marque . " " . $data->vehicle_modele . " (" . $data->vehicle_matricule . ")") : "Véhicule supprimé";
    $vehicle_link = $data->vehicle_id ? anchor(get_uri("vehicles/view/" . $data->vehicle_id), $vehicle_name) : $vehicle_name;

    // â¼â¼â¼ DÉBUT DE LA CORRECTION DES ICÔNES â¼â¼â¼

    // L'icône "Modifier" de Feather s'appelle 'edit'
    $edit_button = "<i data-feather='edit' class='icon-16'></i>";
    
    // L'icône "Supprimer" de Feather s'appelle 'x'
    $delete_button = "<i data-feather='x' class='icon-16'></i>";

    // On utilise les nouvelles icônes dans nos helpers
    $options = modal_anchor(get_uri("maintenance/modal_form"), $edit_button, ["class" => "edit", "title" => lang('edit_maintenance'), "data-post-id" => $data->id]);
    $options .= js_anchor($delete_button, ['title' => lang('delete_maintenance'), "class" => "delete", "data-id" => $data->id, "data-action-url" => get_uri("maintenance/delete"), "data-action" => "delete-confirmation"]);

    // â²â²â² FIN DE LA CORRECTION DES ICÔNES â²â²â²

    return [
        $data->id,
        anchor(get_uri("maintenance/view/" . $data->id), format_to_date($data->date_maintenance, false)),
        $vehicle_link,
        lang($data->type_maintenance) ? lang($data->type_maintenance) : $data->type_maintenance,
        $data->km_maintenance,
        to_currency($data->cout),
        $data->garage,
        $options
    ];
}
}