package com.referentiel.repository;

import com.referentiel.entity.Produit;
import org.springframework.data.jpa.repository.JpaRepository;
import org.springframework.data.jpa.repository.Query;
import org.springframework.data.repository.query.Param;
import org.springframework.stereotype.Repository;

import java.util.List;
import java.util.Optional;

@Repository
public interface ProduitRepository extends JpaRepository<Produit, Long> {

    Optional<Produit> findByReference(String reference);

    List<Produit> findByCategorie(String categorie);

    List<Produit> findByActif(Boolean actif);

    List<Produit> findByFournisseurId(Long fournisseurId);

    @Query("SELECT p FROM Produit p WHERE " +
           "LOWER(p.nom) LIKE LOWER(CONCAT('%', :keyword, '%')) OR " +
           "LOWER(p.reference) LIKE LOWER(CONCAT('%', :keyword, '%')) OR " +
           "LOWER(p.description) LIKE LOWER(CONCAT('%', :keyword, '%')) OR " +
           "LOWER(p.categorie) LIKE LOWER(CONCAT('%', :keyword, '%'))")
    List<Produit> searchProduits(@Param("keyword") String keyword);
}
