<?php

namespace App\Controllers;

class Chauffeur_documents extends Security_Controller
{
    protected $upload_path;

    public function __construct()
    {
        parent::__construct();
        // Définit le chemin où les fichiers seront stockés
        $this->upload_path = get_general_file_path("chauffeur_documents", 0);
    }
 public function index()
    {
        $this->access_only_allowed_members();
        return $this->template->rander("chauffeur_documents/index");
    }
    /**
     * Gère l'upload d'un fichier document.
     */
    public function upload_file()
    {
        $chauffeur_id = $this->request->getPost("chauffeur_id");
        $this->access_only_allowed_members(); // A adapter si des non-admins peuvent uploader

        // Valide que le chauffeur_id est présent
        if (!$chauffeur_id) {
            return $this->response->setJSON(["success" => false, 'message' => "Chauffeur ID manquant."]);
        }

        // Valide le fichier uploadé
        $validationRule = [
            'file' => [
                'label' => 'Fichier',
                'rules' => 'uploaded[file]|max_size[file,3072]|ext_in[file,pdf,doc,docx,jpg,jpeg,png,gif]',
            ],
        ];
        if (!$this->validate($validationRule)) {
            return $this->response->setJSON(["success" => false, 'message' => $this->validator->getErrors()['file']]);
        }

        $file = $this->request->getFile('file');
        if (!$file->isValid()) {
            return $this->response->setJSON(["success" => false, 'message' => $file->getErrorString() . '(' . $file->getError() . ')']);
        }
        
        // Crée un sous-dossier par chauffeur pour une meilleure organisation
        $target_path = get_general_file_path("chauffeur_documents", $chauffeur_id);
        if (!is_dir($target_path)) {
            if (!mkdir($target_path, 0755, true)) {
                return $this->response->setJSON(["success" => false, 'message' => "Erreur lors de la création du dossier de destination."]);
            }
        }
        
        // Génère un nouveau nom de fichier pour éviter les conflits
        $new_filename = "_" . $file->getRandomName();
        $file->move($target_path, $new_filename);

        // Sauvegarde les informations du fichier en base de données
        $data = [
            "chauffeur_id" => $chauffeur_id,
            "type_document" => $this->request->getPost("type_document"),
            "nom_fichier" => $file->getClientName(),
            "chemin_fichier" => $new_filename,
        ];

        $saved_id = $this->Chauffeur_documents_model->save($data);
        if ($saved_id) {
            $file_info = $this->Chauffeur_documents_model->get_details(["id" => $saved_id])->getRow();
            return $this->response->setJSON(["success" => true, "data" => $this->_make_list_row($file_info), 'message' => lang('record_saved')]);
        } else {
            return $this->response->setJSON(["success" => false, 'message' => lang('error_occurred')]);
        }
    }

    /**
     * Liste les documents d'un chauffeur spécifique (pour DataTables).
     */
    public function list_data($chauffeur_id = 0)
    {
        $this->access_only_allowed_members();
        $list_data = $this->Chauffeur_documents_model->get_details(["chauffeur_id" => $chauffeur_id])->getResult();
        $result = [];
        foreach ($list_data as $data) {
            $result[] = $this->_make_list_row($data);
        }
        return $this->response->setJSON(["data" => $result]);
    }

    /**
     * Prépare une ligne pour la liste des documents.
     */
     private function _make_list_row($data)
    {
        $file_path = get_general_file_path("chauffeur_documents", $data->chauffeur_id) . "/" . $data->chemin_fichier;
        
        $download_link = anchor(get_uri("chauffeur_documents/download_file/" . $data->id), $data->nom_fichier, ["title" => "Télécharger"]);

        return [
            $data->id, // ID pour le data-id de la ligne
            $download_link,
            // On ajoute le nom du chauffeur qui est un lien vers sa fiche
            anchor(get_uri("chauffeurs/view/" . $data->chauffeur_id), $data->chauffeur_name),
            lang($data->type_document) ?? $data->type_document,
            format_to_datetime($data->date_upload),
            js_anchor("<i data-feather='x' class='icon-16'></i>", ['title' => "Supprimer le document", "class" => "delete", "data-id" => $data->id, "data-action-url" => get_uri("chauffeur_documents/delete_file"), "data-action" => "delete-confirmation"])
        ];
    }
    
    /**
     * Gère la suppression d'un fichier.
     */
    public function delete_file()
    {
        $this->access_only_allowed_members();
        $id = $this->request->getPost('id');
        $file_info = $this->Chauffeur_documents_model->find($id);

        if ($file_info) {
            // 1. Supprimer le fichier physique
            $file_path = get_general_file_path("chauffeur_documents", $file_info->chauffeur_id) . "/" . $file_info->chemin_fichier;
            if (file_exists($file_path)) {
                unlink($file_path);
            }
            
            // 2. Supprimer l'enregistrement en base de données
            if ($this->Chauffeur_documents_model->delete($id)) {
                return $this->response->setJSON(["success" => true, 'message' => lang('record_deleted')]);
            }
        }
        return $this->response->setJSON(["success" => false, 'message' => lang('record_cannot_be_deleted')]);
    }

    /**
     * Gère le téléchargement d'un fichier.
     */
    public function download_file($id = 0)
    {
        if (!$id) return;
        $file_info = $this->Chauffeur_documents_model->find($id);
        $this->access_only_allowed_members();
        
        $file_path = get_general_file_path("chauffeur_documents", $file_info->chauffeur_id) . "/" . $file_info->chemin_fichier;
        
        // Force le téléchargement
        return $this->response->download($file_path, null)->setFileName($file_info->nom_fichier);
    }
}