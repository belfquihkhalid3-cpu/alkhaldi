<?php

namespace App\Controllers;

use App\Models\Contrats_model;
use App\Models\Clients_contrat_model;
use App\Models\Voitures_contrat_model;
use Dompdf\Dompdf; // Ajout pour la génération de PDF

class Contrats extends Security_Controller
{
    protected $Contrats_model;
    protected $Clients_contrat_model;
    public $Voitures_contrat_model;

    public function __construct()
    {
        parent::__construct();
        $this->init_permission_checker("contrat");
        $this->Contrats_model = new Contrats_model();
        $this->Clients_contrat_model = new Clients_contrat_model();
        $this->Voitures_contrat_model = new Voitures_contrat_model();
    }

    public function index()
    {
        $this->access_only_allowed_members();
        return $this->template->rander("contrats/index");
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
            $view_data['model_info'] = $this->Contrats_model->get_details(['id' => $id])->getRow();
        } else {
            $view_data['model_info'] = new \stdClass();
            $view_data['model_info']->numero_contrat = "CONTRAT-" . time();
        }

        $view_data['clients_dropdown'] = ["" => "-"] + array_column($this->Clients_contrat_model->get_details()->getResult(), 'title', 'id');

        $voiture_options = ['statut' => 'disponible'];
        if($id && isset($view_data['model_info']->voiture_id)) {
            $voiture_options['or_where_id'] = $view_data['model_info']->voiture_id;
        }
        $voitures = $this->Voitures_contrat_model->get_details($voiture_options)->getResult();
        $view_data['voitures_dropdown'] = ["" => "-"] + array_column($voitures, 'title', 'id');

        return $this->template->view('contrats/modal_form', $view_data);
    }

    public function save()
    {
        $this->access_only_allowed_members();
        $id = $this->request->getPost('id');

        $rules = [
            'client_id' => 'required|numeric',
            'voiture_id' => 'required|numeric',
            'date_depart' => 'required',
            'date_retour_prevue' => 'required',
            'prix_jour' => 'required|numeric',
            'km_depart' => 'required|numeric',
        ];

        if ($id) {
            $rules['id'] = 'required|numeric';
        } else {
            $rules['numero_contrat'] = 'required|is_unique[contrats.numero_contrat]';
        }

        if (!$this->validate($rules)) {
            $errors = $this->validator->getErrors();
            echo json_encode(["success" => false, 'message' => reset($errors)]);
            return;
        }

        $data = [
            'client_id' => $this->request->getPost('client_id'),
            'voiture_id' => $this->request->getPost('voiture_id'),
            'numero_contrat' => $this->request->getPost('numero_contrat'),
            'date_depart' => $this->request->getPost('date_depart'),
            'date_retour_prevue' => $this->request->getPost('date_retour_prevue'),
            'lieu_depart' => $this->request->getPost('lieu_depart'),
            'lieu_retour' => $this->request->getPost('lieu_retour'),
            'km_depart' => $this->request->getPost('km_depart'),
            'carburant_depart' => $this->request->getPost('carburant_depart'),
            'prix_jour' => $this->request->getPost('prix_jour'),
            'nombre_jours' => $this->request->getPost('nombre_jours'),
            'total_final' => $this->request->getPost('total_final'),
            'caution' => $this->request->getPost('caution'),
            'avance' => $this->request->getPost('avance'),
            'mode_paiement' => $this->request->getPost('mode_paiement'),
            'notes_etat_vehicule_depart' => $this->request->getPost('notes_etat_vehicule_depart'),
            'statut' => 'en_cours',
            // CORRECTION : Ajout des champs pour le deuxième conducteur
            'deuxieme_conducteur_nom' => $this->request->getPost('deuxieme_conducteur_nom'),
            'deuxieme_conducteur_permis_numero' => $this->request->getPost('deuxieme_conducteur_permis_numero'),
            'deuxieme_conducteur_permis_delivre_le' => $this->request->getPost('deuxieme_conducteur_permis_delivre_le'),
        ];
        
        if ($id) {
            $data["id"] = $id;
        }

        if ($this->Contrats_model->save($data)) {
            $saved_id = $id ? $id : $this->Contrats_model->getInsertID();

            if (!$id) {
                $this->Voitures_contrat_model->save(['id' => $data['voiture_id'], 'statut' => 'en_location']);
            }

            $item_info = $this->Contrats_model->get_details(["id" => $saved_id])->getRow();
            echo json_encode(["success" => true, "data" => $this->_make_row($item_info), 'id' => $saved_id, 'message' => lang('record_saved')]);
        } else {
            $errors = $this->Contrats_model->errors();
            $message = $errors ? reset($errors) : lang('error_occurred');
            echo json_encode(["success" => false, 'message' => $message]);
        }
    }

    public function delete()
    {
        $this->access_only_allowed_members();
        $id = $this->request->getPost('id');
        $this->validate(['id' => 'required|numeric']);

        $contrat_info = $this->Contrats_model->get_details(['id' => $id])->getRow();

        if ($this->Contrats_model->delete($id)) {
            if ($contrat_info) {
                $this->Voitures_contrat_model->save(['id' => $contrat_info->voiture_id, 'statut' => 'disponible']);
            }
            echo json_encode(["success" => true, 'message' => lang('record_deleted')]);
        } else {
            echo json_encode(["success" => false, 'message' => lang('record_cannot_be_deleted')]);
        }
    }

    public function list_data()
    {
        $this->access_only_allowed_members();
        $list_data = $this->Contrats_model->get_details()->getResult();
        $result = [];
        foreach ($list_data as $data) {
            $result[] = $this->_make_row($data);
        }
        echo json_encode(["data" => $result]);
    }

    private function _make_row($data)
    {
        $edit_icon = "<i data-feather='edit' class='icon-16'></i>";
        $delete_icon = "<i data-feather='x' class='icon-16'></i>";
        $download_icon = "<i data-feather='download' class='icon-16'></i>";

        $options = modal_anchor(get_uri("contrats/modal_form"), $edit_icon, ["class" => "edit", "title" => "Modifier le contrat", "data-post-id" => $data->id])
            . js_anchor($delete_icon, ['title' => "Supprimer le contrat", "class" => "delete", "data-id" => $data->id, "data-action-url" => get_uri("contrats/delete"), "data-action" => "delete-confirmation"])
            . anchor(get_uri("contrats/download_pdf/" . $data->id), $download_icon, ["class" => "btn btn-default btn-xs", "title" => "Télécharger PDF", "target" => "_blank"]);

        $client_display = $data->client_prenom . " " . $data->client_nom;
        $voiture_display = $data->voiture_marque . " " . $data->voiture_modele . " (" . $data->voiture_immatriculation . ")";

        return [
            $data->id,
            $data->numero_contrat,
            $client_display,
            $voiture_display,
            format_to_date($data->date_depart, false),
            format_to_date($data->date_retour_prevue, false),
            to_currency($data->total_final),
            $data->statut,
            $options
        ];
    }
    
    public function download_pdf($id = 0)
    {
        $this->access_only_allowed_members();

        if (!$id) {
            show_404();
        }

        $contrat_info = $this->Contrats_model->get_details(["id" => $id])->getRow();
        if (!$contrat_info) {
            show_404();
        }
        
        $view_data['contrat_info'] = $contrat_info;

        ob_end_clean();

        $dompdf = new Dompdf();
        $html = $this->template->view('contrats/pdf_template', $view_data);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        
        $dompdf->stream("Contrat-" . $contrat_info->numero_contrat . ".pdf", array("Attachment" => 0));
        
        exit();
    }
}
