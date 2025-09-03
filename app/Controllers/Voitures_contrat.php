<?php

namespace App\Controllers;

use App\Models\Voitures_contrat_model;

class Voitures_contrat extends Security_Controller
{
    public $Voitures_contrat_model;

    public function __construct()
    {
        parent::__construct();
        $this->init_permission_checker("contrat_voitures");
        $this->Voitures_contrat_model = new Voitures_contrat_model();
    }

    public function index()
    {
        $this->access_only_allowed_members();
        return $this->template->rander("voitures_contrat/index");
    }

    public function modal_form()
    {
        $this->access_only_allowed_members();
        $id = $this->request->getPost('id');
        
        if ($id) {
            $this->validate(['id' => 'numeric']);
        }

        $view_data = [];
        if ($id) {
            $view_data['model_info'] = $this->Voitures_contrat_model->get_details(['id' => $id])->getRow();
        } else {
            $view_data['model_info'] = new \stdClass();
        }

        return $this->template->view('voitures_contrat/modal_form', $view_data);
    }

    public function save()
    {
        $this->access_only_allowed_members();
        $id = $this->request->getPost('id');

        $rules = [
            'marque' => 'required',
            'immatriculation' => 'required'
        ];

        if ($id) {
            $rules['id'] = 'required|numeric';
            $rules['immatriculation'] .= "|is_unique[voitures_contrat.immatriculation,id,$id]";
        } else {
            $rules['immatriculation'] .= '|is_unique[voitures_contrat.immatriculation]';
        }

        if (!$this->validate($rules)) {
            $errors = $this->validator->getErrors();
            echo json_encode(["success" => false, 'message' => reset($errors)]);
            return;
        }

        $data = [
            "marque" => $this->request->getPost('marque'),
            "modele" => $this->request->getPost('modele'),
            "immatriculation" => $this->request->getPost('immatriculation'),
            "statut" => $this->request->getPost('statut'),
        ];
        
        if ($id) {
            $data["id"] = $id;
        }

        if ($this->Voitures_contrat_model->save($data)) {
            $saved_id = $id ? $id : $this->Voitures_contrat_model->getInsertID();
            $item_info = $this->Voitures_contrat_model->get_details(["id" => $saved_id])->getRow();
            echo json_encode(["success" => true, "data" => $this->_make_row($item_info), 'id' => $saved_id, 'message' => lang('record_saved')]);
        } else {
            $errors = $this->Voitures_contrat_model->errors();
            $message = $errors ? reset($errors) : lang('error_occurred');
            echo json_encode(["success" => false, 'message' => $message]);
        }
    }

    public function delete()
    {
        $this->access_only_allowed_members();
        $id = $this->request->getPost('id');
        $this->validate(['id' => 'required|numeric']);

        if ($this->Voitures_contrat_model->delete($id)) {
            echo json_encode(["success" => true, 'message' => lang('record_deleted')]);
        } else {
            echo json_encode(["success" => false, 'message' => lang('record_cannot_be_deleted')]);
        }
    }

    public function list_data()
    {
        $this->access_only_allowed_members();
        $list_data = $this->Voitures_contrat_model->get_details()->getResult();
        $result = [];
        foreach ($list_data as $data) {
            $result[] = $this->_make_row($data);
        }
        echo json_encode(["data" => $result]);
    }

    private function _make_row($data)
    {
        // CORRECTION: Utilisation des icônes Feather
        $edit_icon = "<i data-feather='edit' class='icon-16'></i>";
        $delete_icon = "<i data-feather='x' class='icon-16'></i>";

        $options = modal_anchor(get_uri("voitures_contrat/modal_form"), $edit_icon, ["class" => "edit", "title" => "Modifier la voiture", "data-post-id" => $data->id])
            . js_anchor($delete_icon, ['title' => "Supprimer la voiture", "class" => "delete", "data-id" => $data->id, "data-action-url" => get_uri("voitures_contrat/delete"), "data-action" => "delete-confirmation"]);

        return [
            $data->id,
            $data->marque,
            $data->modele,
            $data->immatriculation,
            $this->_get_statut_badge($data->statut),
            $options
        ];
    }

    private function _get_statut_badge($statut)
    {
        $badge_class = "bg-primary";
        if ($statut === "en_location") {
            $badge_class = "bg-warning";
        } else if ($statut === "en_maintenance") {
            $badge_class = "bg-danger";
        }
        return "<span class='badge $badge_class'>" . ucfirst(lang($statut) ?? $statut) . "</span>";
    }
}
