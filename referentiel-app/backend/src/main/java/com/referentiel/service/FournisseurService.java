package com.referentiel.service;

import com.referentiel.entity.Fournisseur;
import com.referentiel.repository.FournisseurRepository;
import lombok.RequiredArgsConstructor;
import org.springframework.stereotype.Service;
import org.springframework.transaction.annotation.Transactional;

import java.util.List;
import java.util.Optional;

@Service
@RequiredArgsConstructor
@Transactional
public class FournisseurService {

    private final FournisseurRepository fournisseurRepository;

    public List<Fournisseur> getAllFournisseurs() {
        return fournisseurRepository.findAll();
    }

    public Optional<Fournisseur> getFournisseurById(Long id) {
        return fournisseurRepository.findById(id);
    }

    public Fournisseur createFournisseur(Fournisseur fournisseur) {
        return fournisseurRepository.save(fournisseur);
    }

    public Fournisseur updateFournisseur(Long id, Fournisseur fournisseurDetails) {
        Fournisseur fournisseur = fournisseurRepository.findById(id)
                .orElseThrow(() -> new RuntimeException("Fournisseur non trouvé avec l'id: " + id));

        fournisseur.setNomEntreprise(fournisseurDetails.getNomEntreprise());
        fournisseur.setContact(fournisseurDetails.getContact());
        fournisseur.setEmail(fournisseurDetails.getEmail());
        fournisseur.setTelephone(fournisseurDetails.getTelephone());
        fournisseur.setFax(fournisseurDetails.getFax());
        fournisseur.setAdresse(fournisseurDetails.getAdresse());
        fournisseur.setVille(fournisseurDetails.getVille());
        fournisseur.setCodePostal(fournisseurDetails.getCodePostal());
        fournisseur.setPays(fournisseurDetails.getPays());
        fournisseur.setSiteWeb(fournisseurDetails.getSiteWeb());
        fournisseur.setNumeroSiret(fournisseurDetails.getNumeroSiret());
        fournisseur.setNumeroTVA(fournisseurDetails.getNumeroTVA());
        fournisseur.setCategoriesProduits(fournisseurDetails.getCategoriesProduits());
        fournisseur.setConditionsPaiement(fournisseurDetails.getConditionsPaiement());
        fournisseur.setDelaiLivraison(fournisseurDetails.getDelaiLivraison());
        fournisseur.setActif(fournisseurDetails.getActif());
        fournisseur.setNotes(fournisseurDetails.getNotes());

        return fournisseurRepository.save(fournisseur);
    }

    public void deleteFournisseur(Long id) {
        Fournisseur fournisseur = fournisseurRepository.findById(id)
                .orElseThrow(() -> new RuntimeException("Fournisseur non trouvé avec l'id: " + id));
        fournisseurRepository.delete(fournisseur);
    }

    public List<Fournisseur> searchFournisseurs(String keyword) {
        return fournisseurRepository.searchFournisseurs(keyword);
    }

    public List<Fournisseur> getFournisseursByActif(Boolean actif) {
        return fournisseurRepository.findByActif(actif);
    }

    public List<Fournisseur> getFournisseursByVille(String ville) {
        return fournisseurRepository.findByVille(ville);
    }
}
