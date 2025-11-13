package com.referentiel.service;

import com.referentiel.entity.Employe;
import com.referentiel.repository.EmployeRepository;
import lombok.RequiredArgsConstructor;
import org.springframework.stereotype.Service;
import org.springframework.transaction.annotation.Transactional;

import java.util.List;
import java.util.Optional;

@Service
@RequiredArgsConstructor
@Transactional
public class EmployeService {

    private final EmployeRepository employeRepository;

    public List<Employe> getAllEmployes() {
        return employeRepository.findAll();
    }

    public Optional<Employe> getEmployeById(Long id) {
        return employeRepository.findById(id);
    }

    public Employe createEmploye(Employe employe) {
        return employeRepository.save(employe);
    }

    public Employe updateEmploye(Long id, Employe employeDetails) {
        Employe employe = employeRepository.findById(id)
                .orElseThrow(() -> new RuntimeException("Employé non trouvé avec l'id: " + id));

        employe.setNom(employeDetails.getNom());
        employe.setPrenom(employeDetails.getPrenom());
        employe.setEmail(employeDetails.getEmail());
        employe.setTelephone(employeDetails.getTelephone());
        employe.setMatricule(employeDetails.getMatricule());
        employe.setPoste(employeDetails.getPoste());
        employe.setDepartement(employeDetails.getDepartement());
        employe.setDateEmbauche(employeDetails.getDateEmbauche());
        employe.setSalaire(employeDetails.getSalaire());
        employe.setAdresse(employeDetails.getAdresse());
        employe.setVille(employeDetails.getVille());
        employe.setCodePostal(employeDetails.getCodePostal());
        employe.setPays(employeDetails.getPays());
        employe.setActif(employeDetails.getActif());
        employe.setNotes(employeDetails.getNotes());

        return employeRepository.save(employe);
    }

    public void deleteEmploye(Long id) {
        Employe employe = employeRepository.findById(id)
                .orElseThrow(() -> new RuntimeException("Employé non trouvé avec l'id: " + id));
        employeRepository.delete(employe);
    }

    public List<Employe> searchEmployes(String keyword) {
        return employeRepository.searchEmployes(keyword);
    }

    public List<Employe> getEmployesByDepartement(String departement) {
        return employeRepository.findByDepartement(departement);
    }

    public List<Employe> getEmployesByPoste(String poste) {
        return employeRepository.findByPoste(poste);
    }

    public List<Employe> getEmployesByActif(Boolean actif) {
        return employeRepository.findByActif(actif);
    }
}
