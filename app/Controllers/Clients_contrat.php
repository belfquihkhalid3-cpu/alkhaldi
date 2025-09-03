<?php

namespace App\Controllers;

use App\Models\Clients_contrat_model;

class Clients_contrat extends Security_Controller
{
    protected $Clients_contrat_model;

    public function __construct()
    {
        parent::__construct();
        $this->init_permission_checker("contrat_clients");
        $this->Clients_contrat_model = new Clients_contrat_model();
    }

    public function index()
    {
        $this->access_only_allowed_members();
        return $this->template->rander("clients_contrat/index");
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
            $view_data['model_info'] = $this->Clients_contrat_model->get_details(['id' => $id])->getRow();
        } else {
            $view_data['model_info'] = new \stdClass();
        }

        return $this->template->view('clients_contrat/modal_form', $view_data);
    }

    public function save()
    {
        $this->access_only_allowed_members();
        $id = $this->request->getPost('id');

        $rules = [
            'nom' => 'required',
            'prenom' => 'required',
            'cin_numero' => 'permit_empty|is_unique[clients_contrat.cin_numero,id,' . $id . ']',
            'passeport_numero' => 'permit_empty|is_unique[clients_contrat.passeport_numero,id,' . $id . ']',
        ];

        if ($id) {
            $rules['id'] = 'required|numeric';
        }

        if (!$this->validate($rules)) {
            $errors = $this->validator->getErrors();
            echo json_encode(["success" => false, 'message' => reset($errors)]);
            return;
        }

        $data = [
            'nom' => $this->request->getPost('nom'),
            'prenom' => $this->request->getPost('prenom'),
            'cin_numero' => $this->request->getPost('cin_numero'),
            'cin_delivre_le' => $this->request->getPost('cin_delivre_le'),
            'passeport_numero' => $this->request->getPost('passeport_numero'),
            'passeport_delivre_le' => $this->request->getPost('passeport_delivre_le'),
            'permis_numero' => $this->request->getPost('permis_numero'),
            'permis_delivre_le' => $this->request->getPost('permis_delivre_le'),
            'nationalite' => $this->request->getPost('nationalite'),
            'adresse_maroc' => $this->request->getPost('adresse_maroc'),
            'adresse_etranger' => $this->request->getPost('adresse_etranger'),
            'telephone' => $this->request->getPost('telephone'),
        ];
        
        if ($id) {
            $data["id"] = $id;
        }

        if ($this->Clients_contrat_model->save($data)) {
            $saved_id = $id ? $id : $this->Clients_contrat_model->getInsertID();
            $item_info = $this->Clients_contrat_model->get_details(["id" => $saved_id])->getRow();
            echo json_encode(["success" => true, "data" => $this->_make_row($item_info), 'id' => $saved_id, 'message' => lang('record_saved')]);
        } else {
            $errors = $this->Clients_contrat_model->errors();
            $message = $errors ? reset($errors) : lang('error_occurred');
            echo json_encode(["success" => false, 'message' => $message]);
        }
    }

    public function delete()
    {
        $this->access_only_allowed_members();
        $id = $this->request->getPost('id');
        $this->validate(['id' => 'required|numeric']);

        if ($this->Clients_contrat_model->delete($id)) {
            echo json_encode(["success" => true, 'message' => lang('record_deleted')]);
        } else {
            echo json_encode(["success" => false, 'message' => lang('record_cannot_be_deleted')]);
        }
    }

    public function list_data()
    {
        $this->access_only_allowed_members();
        $list_data = $this->Clients_contrat_model->get_details()->getResult();
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

        $options = modal_anchor(get_uri("clients_contrat/modal_form"), $edit_icon, ["class" => "edit", "title" => "Modifier le client", "data-post-id" => $data->id])
            . js_anchor($delete_icon, ['title' => "Supprimer le client", "class" => "delete", "data-id" => $data->id, "data-action-url" => get_uri("clients_contrat/delete"), "data-action" => "delete-confirmation"]);

        return [
            $data->id,
            $data->prenom,
            $data->nom,
            $data->telephone,
            $data->cin_numero ? "CIN: " . $data->cin_numero : "Passeport: " . $data->passeport_numero,
            $options
        ];
    }
}
