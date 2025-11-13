package com.referentiel.service;

import com.referentiel.entity.Produit;
import com.referentiel.repository.ProduitRepository;
import lombok.RequiredArgsConstructor;
import org.springframework.stereotype.Service;
import org.springframework.transaction.annotation.Transactional;

import java.util.List;
import java.util.Optional;

@Service
@RequiredArgsConstructor
@Transactional
public class ProduitService {

    private final ProduitRepository produitRepository;

    public List<Produit> getAllProduits() {
        return produitRepository.findAll();
    }

    public Optional<Produit> getProduitById(Long id) {
        return produitRepository.findById(id);
    }

    public Produit createProduit(Produit produit) {
        return produitRepository.save(produit);
    }

    public Produit updateProduit(Long id, Produit produitDetails) {
        Produit produit = produitRepository.findById(id)
                .orElseThrow(() -> new RuntimeException("Produit non trouvé avec l'id: " + id));

        produit.setNom(produitDetails.getNom());
        produit.setReference(produitDetails.getReference());
        produit.setDescription(produitDetails.getDescription());
        produit.setPrix(produitDetails.getPrix());
        produit.setQuantiteStock(produitDetails.getQuantiteStock());
        produit.setCategorie(produitDetails.getCategorie());
        produit.setUnite(produitDetails.getUnite());
        produit.setFournisseur(produitDetails.getFournisseur());
        produit.setActif(produitDetails.getActif());

        return produitRepository.save(produit);
    }

    public void deleteProduit(Long id) {
        Produit produit = produitRepository.findById(id)
                .orElseThrow(() -> new RuntimeException("Produit non trouvé avec l'id: " + id));
        produitRepository.delete(produit);
    }

    public List<Produit> searchProduits(String keyword) {
        return produitRepository.searchProduits(keyword);
    }

    public List<Produit> getProduitsByCategorie(String categorie) {
        return produitRepository.findByCategorie(categorie);
    }

    public List<Produit> getProduitsByActif(Boolean actif) {
        return produitRepository.findByActif(actif);
    }

    public List<Produit> getProduitsByFournisseur(Long fournisseurId) {
        return produitRepository.findByFournisseurId(fournisseurId);
    }
}
