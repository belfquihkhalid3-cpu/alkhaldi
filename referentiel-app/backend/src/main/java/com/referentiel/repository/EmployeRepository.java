package com.referentiel.repository;

import com.referentiel.entity.Employe;
import org.springframework.data.jpa.repository.JpaRepository;
import org.springframework.data.jpa.repository.Query;
import org.springframework.data.repository.query.Param;
import org.springframework.stereotype.Repository;

import java.util.List;
import java.util.Optional;

@Repository
public interface EmployeRepository extends JpaRepository<Employe, Long> {

    Optional<Employe> findByMatricule(String matricule);

    List<Employe> findByDepartement(String departement);

    List<Employe> findByPoste(String poste);

    List<Employe> findByActif(Boolean actif);

    @Query("SELECT e FROM Employe e WHERE " +
           "LOWER(e.nom) LIKE LOWER(CONCAT('%', :keyword, '%')) OR " +
           "LOWER(e.prenom) LIKE LOWER(CONCAT('%', :keyword, '%')) OR " +
           "LOWER(e.email) LIKE LOWER(CONCAT('%', :keyword, '%')) OR " +
           "LOWER(e.matricule) LIKE LOWER(CONCAT('%', :keyword, '%')) OR " +
           "LOWER(e.poste) LIKE LOWER(CONCAT('%', :keyword, '%')) OR " +
           "LOWER(e.departement) LIKE LOWER(CONCAT('%', :keyword, '%'))")
    List<Employe> searchEmployes(@Param("keyword") String keyword);
}
