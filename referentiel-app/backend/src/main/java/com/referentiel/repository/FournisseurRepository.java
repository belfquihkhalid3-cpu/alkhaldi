package com.referentiel.repository;

import com.referentiel.entity.Fournisseur;
import org.springframework.data.jpa.repository.JpaRepository;
import org.springframework.data.jpa.repository.Query;
import org.springframework.data.repository.query.Param;
import org.springframework.stereotype.Repository;

import java.util.List;
import java.util.Optional;

@Repository
public interface FournisseurRepository extends JpaRepository<Fournisseur, Long> {

    Optional<Fournisseur> findByNumeroSiret(String numeroSiret);

    List<Fournisseur> findByActif(Boolean actif);

    List<Fournisseur> findByVille(String ville);

    @Query("SELECT f FROM Fournisseur f WHERE " +
           "LOWER(f.nomEntreprise) LIKE LOWER(CONCAT('%', :keyword, '%')) OR " +
           "LOWER(f.contact) LIKE LOWER(CONCAT('%', :keyword, '%')) OR " +
           "LOWER(f.email) LIKE LOWER(CONCAT('%', :keyword, '%')) OR " +
           "LOWER(f.numeroSiret) LIKE LOWER(CONCAT('%', :keyword, '%')) OR " +
           "LOWER(f.categoriesProduits) LIKE LOWER(CONCAT('%', :keyword, '%'))")
    List<Fournisseur> searchFournisseurs(@Param("keyword") String keyword);
}
