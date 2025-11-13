package com.referentiel.controller;

import com.referentiel.entity.Employe;
import com.referentiel.service.EmployeService;
import jakarta.validation.Valid;
import lombok.RequiredArgsConstructor;
import org.springframework.http.HttpStatus;
import org.springframework.http.ResponseEntity;
import org.springframework.web.bind.annotation.*;

import java.util.List;

@RestController
@RequestMapping("/api/employes")
@CrossOrigin(origins = "*")
@RequiredArgsConstructor
public class EmployeController {

    private final EmployeService employeService;

    @GetMapping
    public ResponseEntity<List<Employe>> getAllEmployes() {
        return ResponseEntity.ok(employeService.getAllEmployes());
    }

    @GetMapping("/{id}")
    public ResponseEntity<Employe> getEmployeById(@PathVariable Long id) {
        return employeService.getEmployeById(id)
                .map(ResponseEntity::ok)
                .orElse(ResponseEntity.notFound().build());
    }

    @PostMapping
    public ResponseEntity<Employe> createEmploye(@Valid @RequestBody Employe employe) {
        Employe createdEmploye = employeService.createEmploye(employe);
        return ResponseEntity.status(HttpStatus.CREATED).body(createdEmploye);
    }

    @PutMapping("/{id}")
    public ResponseEntity<Employe> updateEmploye(
            @PathVariable Long id,
            @Valid @RequestBody Employe employe) {
        try {
            Employe updatedEmploye = employeService.updateEmploye(id, employe);
            return ResponseEntity.ok(updatedEmploye);
        } catch (RuntimeException e) {
            return ResponseEntity.notFound().build();
        }
    }

    @DeleteMapping("/{id}")
    public ResponseEntity<Void> deleteEmploye(@PathVariable Long id) {
        try {
            employeService.deleteEmploye(id);
            return ResponseEntity.noContent().build();
        } catch (RuntimeException e) {
            return ResponseEntity.notFound().build();
        }
    }

    @GetMapping("/search")
    public ResponseEntity<List<Employe>> searchEmployes(@RequestParam String keyword) {
        return ResponseEntity.ok(employeService.searchEmployes(keyword));
    }

    @GetMapping("/departement/{departement}")
    public ResponseEntity<List<Employe>> getEmployesByDepartement(@PathVariable String departement) {
        return ResponseEntity.ok(employeService.getEmployesByDepartement(departement));
    }

    @GetMapping("/poste/{poste}")
    public ResponseEntity<List<Employe>> getEmployesByPoste(@PathVariable String poste) {
        return ResponseEntity.ok(employeService.getEmployesByPoste(poste));
    }

    @GetMapping("/actif/{actif}")
    public ResponseEntity<List<Employe>> getEmployesByActif(@PathVariable Boolean actif) {
        return ResponseEntity.ok(employeService.getEmployesByActif(actif));
    }
}
